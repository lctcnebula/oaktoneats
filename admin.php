<?php
// Admin at /admin: dashboard, every form submission (signups, contact, careers), resumes and password.
// The first visit asks you to create the admin password. It's stored hashed in the site database.
require_once __DIR__ . '/includes/functions.php';

header('X-Robots-Tag: noindex, nofollow');
header('X-Frame-Options: DENY');
header('Cache-Control: no-store');

// Sign-in lives in a signed cookie (no server sessions, so it works on Vercel too).
// The signing key comes from the password hash plus a random secret kept in the database,
// so changing the password signs everyone else out.
function admin_key(string $hash): string {
    $secret = setting('app_secret');
    if (!$secret) { $secret = bin2hex(random_bytes(32)); set_setting('app_secret', $secret); }
    return hash('sha256', $secret . '|' . $hash);
}
function set_cookie(string $name, string $value, int $expires): void {
    setcookie($name, $value, ['expires' => $expires, 'path' => '/admin', 'httponly' => true, 'samesite' => 'Strict', 'secure' => is_https()]);
    $_COOKIE[$name] = $value;
}
function is_https(): bool { return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'; }
function login_as(string $hash): void {
    $exp = time() + 60 * 60 * 12;
    set_cookie('oe_admin', $exp . '.' . hash_hmac('sha256', 'admin|' . $exp, admin_key($hash)), $exp);
}
function signed_in(?string $hash): bool {
    [$exp, $sig] = array_pad(explode('.', $_COOKIE['oe_admin'] ?? '', 2), 2, '');
    return $hash && ctype_digit($exp) && (int) $exp > time() && hash_equals(hash_hmac('sha256', 'admin|' . $exp, admin_key($hash)), $sig);
}

$p = preg_replace('/[^a-z]/', '', $_GET['p'] ?? 'dashboard');
$hash = setting('admin_password');
$authed = signed_in($hash);
$flash = $_COOKIE['oe_flash'] ?? '';
if ($flash !== '') set_cookie('oe_flash', '', 1);
if (empty($_COOKIE['oe_csrf']) || !ctype_xdigit($_COOKIE['oe_csrf'])) set_cookie('oe_csrf', bin2hex(random_bytes(16)), 0);

function a_url(array $q = []): string { return '/admin' . ($q ? '?' . http_build_query($q) : ''); }
function a_go(array $q = [], string $flash = ''): never { if ($flash) set_cookie('oe_flash', $flash, time() + 60); header('Location: ' . a_url($q)); exit; }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e($_COOKIE['oe_csrf']) . '">'; }
function csrf_ok(): bool { return hash_equals($_COOKIE['oe_csrf'] ?? '', (string) ($_POST['csrf'] ?? '')); }

$post = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
$error = '';

// ---------- Not signed in: create a password on first run, otherwise sign in ----------
if (!$authed) {
    $fails = json_decode(setting('login_fails', '{}'), true) ?: [];
    $ip = client_ip();
    $locked = isset($fails[$ip]) && $fails[$ip]['n'] >= 8 && time() - $fails[$ip]['t'] < 900;

    if ($post && csrf_ok()) {
        $pw = (string) ($_POST['password'] ?? '');
        if (!$hash) {
            if (strlen($pw) < 8) $error = 'Use at least 8 characters.';
            elseif ($pw !== ($_POST['confirm'] ?? '')) $error = "The two passwords don't match.";
            else { $hash = password_hash($pw, PASSWORD_DEFAULT); set_setting('admin_password', $hash); login_as($hash); a_go([], 'Password created. Welcome to your admin.'); }
        } elseif ($locked) {
            $error = 'Too many tries. Wait 15 minutes and try again.';
        } elseif (password_verify($pw, $hash)) {
            unset($fails[$ip]); set_setting('login_fails', json_encode($fails));
            login_as($hash); a_go();
        } else {
            sleep(1);
            $fails[$ip] = ['n' => ($fails[$ip]['n'] ?? 0) + 1, 't' => time()];
            set_setting('login_fails', json_encode($fails));
            $error = 'That password is not right.';
        }
    }
    admin_head('Sign in');
    ?>
    <main class="a-login">
        <form class="a-card a-login-card" method="post">
            <?= logo_mark('a-login-logo') ?>
            <h1><?= $hash ? 'Admin sign in' : 'Create your admin password' ?></h1>
            <?php if (!$hash): ?><p class="a-muted">First time here. Pick a password you'll use to sign in.</p><?php endif; ?>
            <?php if ($error): ?><p class="a-error"><?= e($error) ?></p><?php endif; ?>
            <?= csrf_field() ?>
            <label>Password<input type="password" name="password" required autofocus autocomplete="<?= $hash ? 'current-password' : 'new-password' ?>"></label>
            <?php if (!$hash): ?><label>Confirm password<input type="password" name="confirm" required autocomplete="new-password"></label><?php endif; ?>
            <button class="a-btn a-btn-gold" type="submit"><?= $hash ? 'Sign in' : 'Create password' ?></button>
        </form>
    </main>
    <?php
    admin_foot();
    exit;
}

// ---------- Actions ----------
if ($p === 'logout') { set_cookie('oe_admin', '', 1); header('Location: /admin'); exit; }

if ($p === 'resume') {
    $row = find_submission((int) ($_GET['id'] ?? 0));
    $file = $row ? find_file((int) (json_decode($row['data'], true)['resume'] ?? 0)) : null;
    if (!$file) { http_response_code(404); exit('Resume not found.'); }
    $name = preg_replace('/[^A-Za-z0-9]+/', '-', $row['name']) . '-resume.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    header('Content-Type: ' . $file['mime']);
    header('Content-Disposition: attachment; filename="' . $name . '"');
    header('Content-Length: ' . strlen($file['content']));
    echo $file['content'];
    exit;
}

if ($post) {
    if (!csrf_ok()) a_go([], 'Your session expired. Please try again.');
    $id = (int) ($_POST['id'] ?? 0);
    if ($p === 'status' && isset(SUBMISSION_STATUSES[$_POST['status'] ?? ''])) {
        db()->prepare('UPDATE submissions SET status = ? WHERE id = ?')->execute([$_POST['status'], $id]);
        a_go(['p' => 'view', 'id' => $id], 'Marked as ' . SUBMISSION_STATUSES[$_POST['status']] . '.');
    }
    if ($p === 'delete' && ($row = find_submission($id))) {
        if ($resume = (int) (json_decode($row['data'], true)['resume'] ?? 0)) delete_file($resume);
        db()->prepare('DELETE FROM submissions WHERE id = ?')->execute([$id]);
        a_go(['p' => 'submissions'], "Deleted #$id.");
    }
    if ($p === 'password') {
        $cur = (string) ($_POST['current'] ?? ''); $new = (string) ($_POST['new'] ?? '');
        if (!password_verify($cur, $hash)) $error = 'Your current password is not right.';
        elseif (strlen($new) < 8) $error = 'Use at least 8 characters for the new password.';
        elseif ($new !== ($_POST['confirm'] ?? '')) $error = "The new passwords don't match.";
        else { $hash = password_hash($new, PASSWORD_DEFAULT); set_setting('admin_password', $hash); login_as($hash); a_go(['p' => 'password'], 'Password changed.'); }
    }
}

// ---------- Filters shared by the dashboard and submissions list ----------
function range_filter(): array {
    $r = $_GET['range'] ?? 'today';
    $today = date('Y-m-d');
    [$from, $to] = match ($r) {
        '7' => [date('Y-m-d', strtotime('-6 days')), $today],
        '30' => [date('Y-m-d', strtotime('-29 days')), $today],
        'all' => ['', ''],
        'custom' => [preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['from'] ?? '') ? $_GET['from'] : '', preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['to'] ?? '') ? $_GET['to'] : ''],
        default => [$today, $today],
    };
    return [$r, $from, $to];
}

function where(array $f, array &$args): string {
    $w = [];
    if ($f['from'] !== '') { $w[] = 'created_at >= ?'; $args[] = $f['from'] . ' 00:00:00'; }
    if ($f['to'] !== '') { $w[] = 'created_at <= ?'; $args[] = $f['to'] . ' 23:59:59'; }
    if (!empty($f['type'])) { $w[] = 'type = ?'; $args[] = $f['type']; }
    if (!empty($f['status'])) { $w[] = 'status = ?'; $args[] = $f['status']; }
    elseif (empty($f['with_spam'])) { $w[] = "status != 'spam'"; }
    if (!empty($f['q'])) { $w[] = '(lower(name) LIKE lower(?) OR lower(email) LIKE lower(?) OR phone LIKE ? OR lower(data) LIKE lower(?))'; array_push($args, ...array_fill(0, 4, '%' . $f['q'] . '%')); }
    return $w ? ' WHERE ' . implode(' AND ', $w) : '';
}

function count_where(array $f): int {
    $args = [];
    $stmt = db()->prepare('SELECT COUNT(*) FROM submissions' . where($f, $args));
    $stmt->execute($args);
    return (int) $stmt->fetchColumn();
}

function find_submission(int $id): ?array {
    $stmt = db()->prepare('SELECT * FROM submissions WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function sub_rows(array $f, int $limit, int $offset = 0): array {
    $args = [];
    $stmt = db()->prepare('SELECT * FROM submissions' . where($f, $args) . ' ORDER BY id DESC LIMIT ' . $limit . ' OFFSET ' . $offset);
    $stmt->execute($args);
    return $stmt->fetchAll();
}

function sub_summary(array $row): string {
    $d = json_decode($row['data'], true) ?: [];
    return match ($row['type']) {
        'contact' => ($d['topic'] ?? '') . ': ' . mb_strimwidth($d['message'] ?? '', 0, 70, '…'),
        'career' => ($d['role'] ?? '') . ($d['availability'] ?? '' ? ' · ' . $d['availability'] : '') . (!empty($d['resume']) ? ' · Resume' : ''),
        default => 'Get notified signup',
    };
}

function status_badge(string $s): string { return '<span class="a-badge s-' . e($s) . '">' . e(SUBMISSION_STATUSES[$s] ?? $s) . '</span>'; }

function sub_table(array $rows): void { ?>
    <div class="a-table-wrap"><table class="a-table">
        <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Email</th><th>Details</th><th>Date</th><th>Status</th></tr></thead>
        <tbody>
        <?php if (!$rows): ?><tr><td colspan="7" class="a-empty">Nothing here yet.</td></tr><?php endif; ?>
        <?php foreach ($rows as $r): $link = a_url(['p' => 'view', 'id' => $r['id']]); ?>
            <tr onclick="location.href='<?= e($link) ?>'">
                <td><a href="<?= e($link) ?>">#<?= (int) $r['id'] ?></a></td>
                <td><?= e($r['name'] ?: '—') ?></td>
                <td><?= e(SUBMISSION_TYPES[$r['type']] ?? $r['type']) ?></td>
                <td><?= e($r['email']) ?></td>
                <td class="a-muted"><?= e(sub_summary($r)) ?></td>
                <td class="a-nowrap"><?= e(date('M j, Y g:i A', strtotime($r['created_at']))) ?></td>
                <td><?= status_badge($r['status']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
<?php }

function range_bar(string $p, string $r, string $from, string $to, array $keep = []): void { ?>
    <form class="a-range" method="get" action="/admin">
        <input type="hidden" name="p" value="<?= e($p) ?>">
        <?php foreach ($keep as $k => $v): if ($v !== '' && $v !== null): ?><input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>"><?php endif; endforeach; ?>
        <?php foreach (['today' => 'Today', '7' => 'Last 7 Days', '30' => 'Last 30 Days', 'all' => 'All Time'] as $k => $l): ?>
            <a class="a-pill<?= $r === $k ? ' on' : '' ?>" href="<?= e(a_url(['p' => $p, 'range' => $k] + array_filter($keep))) ?>"><?= $l ?></a>
        <?php endforeach; ?>
        <input type="date" name="from" value="<?= e($r === 'custom' ? $from : '') ?>"> <span>to</span> <input type="date" name="to" value="<?= e($r === 'custom' ? $to : '') ?>">
        <button class="a-pill" name="range" value="custom">Apply</button>
    </form>
<?php }

// ---------- CSV export ----------
if ($p === 'export') {
    [$r, $from, $to] = range_filter();
    $f = ['from' => $from, 'to' => $to, 'type' => $_GET['type'] ?? '', 'status' => $_GET['status'] ?? '', 'q' => trim($_GET['q'] ?? '')];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="oakton-eats-' . ($f['type'] ?: 'submissions') . '-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    $cell = fn($v) => preg_match('/^[=+\-@]/', (string) $v) ? "'" . $v : $v;
    fputcsv($out, escape: '', fields: ['id', 'date', 'type', 'status', 'name', 'email', 'phone', 'topic / role', 'message / about', 'availability']);
    foreach (sub_rows($f, 100000) as $row) {
        $d = json_decode($row['data'], true) ?: [];
        fputcsv($out, escape: '', fields: array_map($cell, [$row['id'], $row['created_at'], $row['type'], $row['status'], $row['name'], $row['email'], $row['phone'], $d['topic'] ?? $d['role'] ?? '', $d['message'] ?? $d['about'] ?? '', $d['availability'] ?? '']));
    }
    exit;
}

// ---------- Pages ----------
$titles = ['dashboard' => 'Dashboard', 'submissions' => 'Submissions', 'careers' => 'Careers', 'view' => 'Submission', 'password' => 'Change Password'];
if (!isset($titles[$p])) $p = 'dashboard';
admin_head($titles[$p]);
admin_nav($p);
?>
<main class="a-main">
    <?php if ($flash): ?><p class="a-flash"><?= e($flash) ?></p><?php endif; ?>

<?php if ($p === 'dashboard'):
    [$r, $from, $to] = range_filter();
    $inRange = ['from' => $from, 'to' => $to];
    $all = ['from' => '', 'to' => ''];
    $cards = [
        ['In range', count_where($inRange), a_url(['p' => 'submissions', 'range' => $r, 'from' => $from, 'to' => $to])],
        ['Total submissions', count_where($all), a_url(['p' => 'submissions', 'range' => 'all'])],
        ['New / unread', count_where($all + ['status' => 'new']), a_url(['p' => 'submissions', 'range' => 'all', 'status' => 'new'])],
        ['Signups', count_where($inRange + ['type' => 'signup']), a_url(['p' => 'submissions', 'range' => $r, 'type' => 'signup', 'from' => $from, 'to' => $to])],
        ['Contact', count_where($inRange + ['type' => 'contact']), a_url(['p' => 'submissions', 'range' => $r, 'type' => 'contact', 'from' => $from, 'to' => $to])],
        ['Careers', count_where($inRange + ['type' => 'career']), a_url(['p' => 'careers', 'range' => $r, 'from' => $from, 'to' => $to])],
        ['Total signups', count_where($all + ['type' => 'signup']), a_url(['p' => 'submissions', 'range' => 'all', 'type' => 'signup'])],
        ['Spam blocked', count_where($all + ['status' => 'spam']), a_url(['p' => 'submissions', 'range' => 'all', 'status' => 'spam']), 'warn'],
    ];
    range_bar('dashboard', $r, $from, $to); ?>
    <div class="a-stats">
        <?php foreach ($cards as $c): ?>
            <a class="a-card a-stat<?= isset($c[3]) ? ' ' . $c[3] : '' ?>" href="<?= e($c[2]) ?>"><b><?= number_format($c[1]) ?></b><span><?= e($c[0]) ?></span></a>
        <?php endforeach; ?>
    </div>
    <h2 class="a-h2">Recent submissions</h2>
    <?php sub_table(sub_rows($all, 10)); ?>

<?php elseif ($p === 'submissions' || $p === 'careers'):
    [$r, $from, $to] = range_filter();
    if (!isset($_GET['range'])) { $r = 'all'; $from = $to = ''; }
    $type = $p === 'careers' ? 'career' : (isset(SUBMISSION_TYPES[$_GET['type'] ?? '']) ? $_GET['type'] : '');
    $status = isset(SUBMISSION_STATUSES[$_GET['status'] ?? '']) ? $_GET['status'] : '';
    $q = mb_substr(trim($_GET['q'] ?? ''), 0, 80);
    $f = ['from' => $from, 'to' => $to, 'type' => $type, 'status' => $status, 'q' => $q];
    $total = count_where($f);
    $per = 50; $pg = max(1, (int) ($_GET['page'] ?? 1)); $pages = max(1, (int) ceil($total / $per));
    $keep = ['type' => $p === 'careers' ? '' : $type, 'status' => $status, 'q' => $q]; ?>
    <div class="a-toolbar">
        <h1 class="a-h1"><?= $p === 'careers' ? 'Job applications' : 'Submissions' ?> <span class="a-count"><?= number_format($total) ?></span></h1>
        <a class="a-btn" href="<?= e(a_url(['p' => 'export', 'range' => $r, 'from' => $from, 'to' => $to, 'type' => $type, 'status' => $status, 'q' => $q])) ?>">Export CSV</a>
    </div>
    <?php range_bar($p, $r, $from, $to, $keep); ?>
    <form class="a-filters" method="get" action="/admin">
        <input type="hidden" name="p" value="<?= e($p) ?>"><input type="hidden" name="range" value="<?= e($r) ?>">
        <?php if ($r === 'custom'): ?><input type="hidden" name="from" value="<?= e($from) ?>"><input type="hidden" name="to" value="<?= e($to) ?>"><?php endif; ?>
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search name, email, phone, message">
        <?php if ($p !== 'careers'): ?>
            <select name="type"><option value="">All types</option><?php foreach (SUBMISSION_TYPES as $k => $l): ?><option value="<?= $k ?>"<?= $type === $k ? ' selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select>
        <?php endif; ?>
        <select name="status"><option value="">All (no spam)</option><?php foreach (SUBMISSION_STATUSES as $k => $l): ?><option value="<?= $k ?>"<?= $status === $k ? ' selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select>
        <button class="a-btn a-btn-gold">Filter</button>
    </form>
    <?php sub_table(sub_rows($f, $per, ($pg - 1) * $per)); ?>
    <?php if ($pages > 1): ?>
        <nav class="a-pages"><?php for ($i = 1; $i <= $pages; $i++): ?><a class="a-pill<?= $i === $pg ? ' on' : '' ?>" href="<?= e(a_url(['p' => $p, 'range' => $r, 'from' => $from, 'to' => $to, 'page' => $i] + array_filter($keep))) ?>"><?= $i ?></a><?php endfor; ?></nav>
    <?php endif; ?>

<?php elseif ($p === 'view'):
    $row = find_submission((int) ($_GET['id'] ?? 0));
    if (!$row): ?><p class="a-error">That submission doesn't exist.</p><?php else:
    if ($row['status'] === 'new') { db()->prepare("UPDATE submissions SET status = 'read' WHERE id = ?")->execute([$row['id']]); $row['status'] = 'read'; }
    $d = json_decode($row['data'], true) ?: [];
    $fields = ['Name' => $row['name'], 'Email' => $row['email'], 'Phone' => $row['phone'], 'Topic' => $d['topic'] ?? '', 'Role' => $d['role'] ?? '', 'Availability' => $d['availability'] ?? '', 'Message' => $d['message'] ?? '', 'About' => $d['about'] ?? '', 'Received' => date('l, M j, Y \a\t g:i A', strtotime($row['created_at'])), 'IP address' => $row['ip']]; ?>
    <a class="a-back" href="<?= e(a_url(['p' => $row['type'] === 'career' ? 'careers' : 'submissions'])) ?>">← Back</a>
    <div class="a-toolbar">
        <h1 class="a-h1">#<?= (int) $row['id'] ?> · <?= e(SUBMISSION_TYPES[$row['type']] ?? $row['type']) ?> <?= status_badge($row['status']) ?></h1>
        <div class="a-actions">
            <?php if ($row['email']): ?><a class="a-btn a-btn-gold" href="mailto:<?= e($row['email']) ?>">Reply by email</a><?php endif; ?>
            <?php if ($row['phone']): ?><a class="a-btn" href="tel:<?= e(preg_replace('/[^0-9+]/', '', $row['phone'])) ?>">Call</a><?php endif; ?>
            <?php if (!empty($d['resume'])): ?><a class="a-btn" href="<?= e(a_url(['p' => 'resume', 'id' => $row['id']])) ?>">Download resume</a><?php endif; ?>
        </div>
    </div>
    <div class="a-card a-detail">
        <?php foreach ($fields as $k => $v): if ($v === '' || $v === null) continue; ?>
            <div class="a-row"><span><?= e($k) ?></span><div><?= nl2br(e($v)) ?></div></div>
        <?php endforeach; ?>
    </div>
    <div class="a-actions a-status">
        <?php foreach (SUBMISSION_STATUSES as $k => $l): if ($k === $row['status']) continue; ?>
            <form method="post" action="<?= e(a_url(['p' => 'status'])) ?>"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button class="a-btn" name="status" value="<?= $k ?>">Mark as <?= $l ?></button></form>
        <?php endforeach; ?>
        <form method="post" action="<?= e(a_url(['p' => 'delete'])) ?>" onsubmit="return confirm('Delete this submission for good?')"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button class="a-btn a-btn-danger">Delete</button></form>
    </div>
    <?php endif; ?>

<?php elseif ($p === 'password'): ?>
    <h1 class="a-h1">Change password</h1>
    <form class="a-card a-form" method="post" action="<?= e(a_url(['p' => 'password'])) ?>">
        <?php if ($error): ?><p class="a-error"><?= e($error) ?></p><?php endif; ?>
        <?= csrf_field() ?>
        <label>Current password<input type="password" name="current" required autocomplete="current-password"></label>
        <label>New password<input type="password" name="new" required minlength="8" autocomplete="new-password"></label>
        <label>Confirm new password<input type="password" name="confirm" required minlength="8" autocomplete="new-password"></label>
        <button class="a-btn a-btn-gold">Save password</button>
    </form>
<?php endif; ?>
</main>
<?php
admin_foot();

// ---------- Layout ----------
function admin_head(string $title): void { ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($title) ?> | <?= e(SITE_NAME) ?> Admin</title>
    <link rel="icon" href="/assets/img/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Marcellus&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin.css?v=<?= filemtime(__DIR__ . '/assets/css/admin.css') ?>">
</head>
<body>
<?php }

function admin_nav(string $p): void {
    $links = ['dashboard' => 'Dashboard', 'submissions' => 'Submissions', 'careers' => 'Careers', 'password' => 'Change Password']; ?>
    <header class="a-header">
        <a class="a-brand" href="/admin"><?= logo_mark('a-logo') ?><span><b>Oakton</b> Eats</span><em>Admin</em></a>
        <nav>
            <?php foreach ($links as $k => $l): ?><a href="<?= e(a_url(['p' => $k])) ?>"<?= $p === $k || ($p === 'view' && $k === 'submissions') ? ' class="on"' : '' ?>><?= $l ?></a><?php endforeach; ?>
            <a href="/" target="_blank" rel="noopener">View site</a>
            <a href="<?= e(a_url(['p' => 'logout'])) ?>">Logout</a>
        </nav>
    </header>
<?php }

function admin_foot(): void { ?>
</body>
</html>
<?php }
