<?php
// Saves "Get notified" signups to the site database (see /admin) and emails NOTIFY_EMAIL if set.
require __DIR__ . '/includes/functions.php';

$json = stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
function done(bool $ok, string $msg, bool $json): void {
    if ($json) {
        header('Content-Type: application/json');
        http_response_code($ok ? 200 : 400);
        echo json_encode(['ok' => $ok, 'message' => $msg]);
    } else {
        header('Location: /?subscribed=' . ($ok ? '1' : '0'));
    }
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') done(false, 'Please use the signup form.', $json);

// Bots fill the hidden field; pretend it worked.
if (trim($_POST['website'] ?? '') !== '') done(true, "You're on the list!", $json);

$clean = fn($v, $n) => mb_substr(trim(preg_replace('/[\r\n\t]+/', ' ', (string) $v)), 0, $n);
$name  = $clean($_POST['name'] ?? '', 80);
$email = $clean($_POST['email'] ?? '', 120);
$phone = $clean($_POST['phone'] ?? '', 30);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) done(false, 'Please enter a valid email address.', $json);

if (already_signed_up($email)) done(true, "You're already on the list. See you at the opening!", $json);

if (save_submission('signup', $name, $email, $phone)) {
    notify_owner('New Oakton Eats signup', "Name: $name\nEmail: $email\nPhone: $phone\n", $email);
}

done(true, "You're on the list! Watch your inbox for opening news and your bonus points.", $json);
