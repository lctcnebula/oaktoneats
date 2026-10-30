<?php
// Every form on the site (signups, contact messages, job applications) saves here, read by the admin at /admin.
// On Vercel (or anywhere DATABASE_URL / POSTGRES_URL is set) it uses that Postgres database.
// Otherwise it uses one SQLite file at data/oakton.sqlite, which works on regular PHP hosting with no setup.

const DB_FILE = __DIR__ . '/../data/oakton.sqlite';
const SUBMISSION_TYPES = ['signup' => 'Signup', 'contact' => 'Contact', 'catering' => 'Catering', 'career' => 'Careers'];
const SUBMISSION_STATUSES = ['new' => 'New', 'read' => 'Read', 'done' => 'Done', 'spam' => 'Spam'];
const RESUME_MAX_BYTES = 4 * 1024 * 1024; // Vercel caps a whole request at 4.5 MB

function db_url(): string {
    return (string) (getenv('DATABASE_URL') ?: getenv('POSTGRES_URL') ?: '');
}

function db_is_pg(): bool {
    return db_url() !== '';
}

function db(): PDO {
    static $pdo;
    if ($pdo) return $pdo;
    $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];

    if (db_is_pg()) {
        $u = parse_url(db_url());
        parse_str($u['query'] ?? '', $q);
        $dsn = 'pgsql:host=' . $u['host'] . ';port=' . ($u['port'] ?? 5432) . ';dbname=' . ltrim($u['path'] ?? '/postgres', '/')
             . ';sslmode=' . ($q['sslmode'] ?? 'require');
        // Neon routes by endpoint name; older Postgres client libraries need it passed explicitly.
        if (str_ends_with($u['host'], '.neon.tech')) $dsn .= ";options='endpoint=" . preg_replace('/-pooler$/', '', explode('.', $u['host'])[0]) . "'";
        $pdo = new PDO($dsn, urldecode($u['user'] ?? ''), urldecode($u['pass'] ?? ''), $opts);
        $id = 'SERIAL PRIMARY KEY';
        $blob = 'BYTEA';
    } else {
        $dir = dirname(DB_FILE);
        if (!is_dir($dir)) mkdir($dir, 0750, true);
        $pdo = new PDO('sqlite:' . DB_FILE, null, null, $opts);
        $pdo->exec('PRAGMA journal_mode = WAL');
        $id = 'INTEGER PRIMARY KEY AUTOINCREMENT';
        $blob = 'BLOB';
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS submissions (
        id $id,
        type TEXT NOT NULL,
        name TEXT NOT NULL DEFAULT '',
        email TEXT NOT NULL DEFAULT '',
        phone TEXT NOT NULL DEFAULT '',
        data TEXT NOT NULL DEFAULT '{}',
        status TEXT NOT NULL DEFAULT 'new',
        ip TEXT NOT NULL DEFAULT '',
        created_at TEXT NOT NULL
    )");
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_sub_type ON submissions (type, created_at)');
    $pdo->exec("CREATE TABLE IF NOT EXISTS files (id $id, name TEXT NOT NULL, mime TEXT NOT NULL, content $blob NOT NULL, created_at TEXT NOT NULL)");
    $pdo->exec('CREATE TABLE IF NOT EXISTS settings (key TEXT PRIMARY KEY, value TEXT NOT NULL)');
    return $pdo;
}

// Saves a form entry. Spammy entries are kept but marked 'spam' so they stay off the dashboard counts.
// Returns true for a real entry, false for spam (so the caller can skip the email).
function save_submission(string $type, string $name, string $email, string $phone, array $data = []): bool {
    $status = looks_like_spam($name . ' ' . implode(' ', array_map('strval', $data))) ? 'spam' : 'new';
    $stmt = db()->prepare('INSERT INTO submissions (type, name, email, phone, data, status, ip, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$type, $name, $email, $phone, json_encode($data, JSON_UNESCAPED_UNICODE), $status, client_ip(), date('Y-m-d H:i:s')]);
    return $status !== 'spam';
}

// Stores an uploaded file (a resume) in the database and returns its id.
function save_file(string $name, string $mime, string $content): int {
    $stmt = db()->prepare('INSERT INTO files (name, mime, content, created_at) VALUES (?, ?, ?, ?)' . (db_is_pg() ? ' RETURNING id' : ''));
    $stmt->bindValue(1, $name);
    $stmt->bindValue(2, $mime);
    $stmt->bindValue(3, $content, PDO::PARAM_LOB);
    $stmt->bindValue(4, date('Y-m-d H:i:s'));
    $stmt->execute();
    return (int) (db_is_pg() ? $stmt->fetchColumn() : db()->lastInsertId());
}

function find_file(int $id): ?array {
    $stmt = db()->prepare('SELECT * FROM files WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) return null;
    if (is_resource($row['content'])) $row['content'] = stream_get_contents($row['content']);
    return $row;
}

function delete_file(int $id): void {
    db()->prepare('DELETE FROM files WHERE id = ?')->execute([$id]);
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

function client_ip(): string {
    $fwd = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    return trim($fwd !== '' ? explode(',', $fwd)[0] : ($_SERVER['REMOTE_ADDR'] ?? ''));
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

// Emails NOTIFY_EMAIL about a new entry, if it's set. (Vercel has no mail server, so this only sends on regular PHP hosting.)
function notify_owner(string $subject, string $body, string $replyTo = ''): void {
    if (NOTIFY_EMAIL === '' || !function_exists('mail')) return;
    $headers = 'From: no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($replyTo ? "\r\nReply-To: " . $replyTo : '');
    @mail(NOTIFY_EMAIL, $subject, $body, $headers);
}
