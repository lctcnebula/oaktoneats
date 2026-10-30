<?php
// Every form on the site (signups, contact messages, job applications) saves here:
// one SQLite file at data/oakton.sqlite, read by the admin at /admin.

const DB_FILE = __DIR__ . '/../data/oakton.sqlite';
const SUBMISSION_TYPES = ['signup' => 'Signup', 'contact' => 'Contact', 'career' => 'Careers'];
const SUBMISSION_STATUSES = ['new' => 'New', 'read' => 'Read', 'done' => 'Done', 'spam' => 'Spam'];

function db(): PDO {
    static $pdo;
    if ($pdo) return $pdo;
    $dir = dirname(DB_FILE);
    if (!is_dir($dir)) mkdir($dir, 0750, true);
    $pdo = new PDO('sqlite:' . DB_FILE, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('CREATE TABLE IF NOT EXISTS submissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        type TEXT NOT NULL,
        name TEXT NOT NULL DEFAULT \'\',
        email TEXT NOT NULL DEFAULT \'\',
        phone TEXT NOT NULL DEFAULT \'\',
        data TEXT NOT NULL DEFAULT \'{}\',
        status TEXT NOT NULL DEFAULT \'new\',
        ip TEXT NOT NULL DEFAULT \'\',
        created_at TEXT NOT NULL
    )');
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_sub_type ON submissions (type, created_at)');
    $pdo->exec('CREATE TABLE IF NOT EXISTS settings (key TEXT PRIMARY KEY, value TEXT NOT NULL)');
    return $pdo;
}

// Saves a form entry. Spammy entries are kept but marked 'spam' so they stay off the dashboard counts.
// Returns true for a real entry, false for spam (so the caller can skip the email).
function save_submission(string $type, string $name, string $email, string $phone, array $data = []): bool {
    $status = looks_like_spam($name . ' ' . implode(' ', array_map('strval', $data))) ? 'spam' : 'new';
    $stmt = db()->prepare('INSERT INTO submissions (type, name, email, phone, data, status, ip, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$type, $name, $email, $phone, json_encode($data, JSON_UNESCAPED_UNICODE), $status, $_SERVER['REMOTE_ADDR'] ?? '', date('Y-m-d H:i:s')]);
    return $status !== 'spam';
}

function already_signed_up(string $email): bool {
    $stmt = db()->prepare("SELECT 1 FROM submissions WHERE type = 'signup' AND lower(email) = lower(?) LIMIT 1");
    $stmt->execute([$email]);
    return (bool) $stmt->fetchColumn();
}

// Catches the usual bot junk: links in a name field, piles of links, or Cyrillic money-transfer spam.
function looks_like_spam(string $text): bool {
    if (preg_match_all('~https?://|www\.|tinyurl|bit\.ly~i', $text) >= 2) return true;
    if (preg_match('~https?://|tinyurl|bit\.ly~i', $text) && preg_match('/\p{Cyrillic}/u', $text)) return true;
    return false;
}

function setting(string $key, ?string $default = null): ?string {
    $stmt = db()->prepare('SELECT value FROM settings WHERE key = ?');
    $stmt->execute([$key]);
    $v = $stmt->fetchColumn();
    return $v === false ? $default : $v;
}

function set_setting(string $key, string $value): void {
    db()->prepare('INSERT INTO settings (key, value) VALUES (?, ?) ON CONFLICT(key) DO UPDATE SET value = excluded.value')->execute([$key, $value]);
}

// Emails NOTIFY_EMAIL about a new entry, if it's set.
function notify_owner(string $subject, string $body, string $replyTo = ''): void {
    if (NOTIFY_EMAIL === '') return;
    $headers = 'From: no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($replyTo ? "\r\nReply-To: " . $replyTo : '');
    @mail(NOTIFY_EMAIL, $subject, $body, $headers);
}
