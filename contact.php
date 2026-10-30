<?php
// Contact page. Messages are saved to the site database (see /admin) and emailed to NOTIFY_EMAIL if set.
require_once __DIR__ . '/includes/functions.php';

$sent = isset($_GET['sent']);
$error = '';
$old = ['name' => '', 'email' => '', 'phone' => '', 'topic' => 'General question', 'message' => ''];
$topics = ['General question', 'Catering & large orders', 'Jobs', 'Press', 'Something else'];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    // Bots fill the hidden field; pretend it worked.
    if (trim($_POST['website'] ?? '') !== '') { header('Location: /contact?sent=1#form'); exit; }

    $clean = fn($v, $n) => mb_substr(trim(preg_replace('/[\r\t]+/', ' ', (string) $v)), 0, $n);
    foreach (['name' => 80, 'email' => 120, 'phone' => 30, 'topic' => 40, 'message' => 2000] as $k => $n) $old[$k] = $clean($_POST[$k] ?? '', $n);
    if (!in_array($old['topic'], $topics, true)) $old['topic'] = 'Something else';

    if ($old['name'] === '') $error = 'Please add your name.';
    elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $error = 'Please enter a valid email address.';
    elseif (mb_strlen($old['message']) < 5) $error = 'Please write a short message.';

    if (!$error) {
        if (save_submission('contact', $old['name'], $old['email'], $old['phone'], ['topic' => $old['topic'], 'message' => $old['message']])) {
            notify_owner('Oakton Eats contact: ' . $old['topic'], "Name: {$old['name']}\nEmail: {$old['email']}\nPhone: {$old['phone']}\n\n{$old['message']}\n", $old['email']);
        }
        header('Location: /contact?sent=1#form');
        exit;
    }
}

$page = 'contact';
$title = 'Contact';
$description = 'Contact Oakton Eats at 4949 Oakton St, Skokie, IL. Questions, catering, jobs or press: send us a message.';
require __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/views/contact-' . (is_home2() ? '2' : '1') . '.php';
require __DIR__ . '/includes/footer.php';
