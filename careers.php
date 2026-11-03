<?php
// Careers page. Applications and their optional resume are saved to the site database (see /admin).
// NOTIFY_EMAIL gets an email for each one if set.
require_once __DIR__ . '/includes/functions.php';

$roles = [
    ['title' => 'Barista',             'type' => 'Full & part time', 'icon' => 'cup',       'text' => 'Pull espresso, steam milk and keep the coffee bar moving from open to close.'],
    ['title' => 'Line Cook',           'type' => 'Full & part time', 'icon' => 'flame',     'text' => 'Smash burgers, fry chicken and build breakfast sandwiches to order on the line.'],
    ['title' => 'Front Counter',       'type' => 'Part time',        'icon' => 'users',     'text' => 'Greet guests, take orders and make every visit quick and friendly.'],
    ['title' => 'Shift Lead',          'type' => 'Full time',        'icon' => 'check',     'text' => 'Run the floor, guide the team and keep quality high on every shift.'],
];
$times = ['Mornings', 'Afternoons', 'Evenings', 'Weekends'];
$resumeTypes = ['pdf' => 'application/pdf', 'doc' => 'application/msword', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

$sent = isset($_GET['sent']);
$error = '';
$old = ['name' => '', 'email' => '', 'phone' => '', 'role' => $roles[0]['title'], 'times' => [], 'about' => ''];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    // Bots fill the hidden field; pretend it worked.
    if (trim($_POST['website'] ?? '') !== '') { header('Location: /careers?sent=1#apply'); exit; }

    $clean = fn($v, $n) => mb_substr(trim(preg_replace('/[\r\t]+/', ' ', (string) $v)), 0, $n);
    foreach (['name' => 80, 'email' => 120, 'phone' => 30, 'role' => 40, 'about' => 2000] as $k => $n) $old[$k] = $clean($_POST[$k] ?? '', $n);
    $old['times'] = array_values(array_intersect($times, (array) ($_POST['times'] ?? [])));
    if (!in_array($old['role'], array_column($roles, 'title'), true)) $old['role'] = 'Any role';

    if ($old['name'] === '') $error = 'Please add your name.';
    elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $error = 'Please enter a valid email address.';
    elseif ($old['phone'] === '') $error = 'Please add a phone number so we can reach you.';

    // Optional resume: PDF or Word, up to 4 MB, kept in the database.
    $resume = 0;
    $file = $_FILES['resume'] ?? null;
    if (!$error && $file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $mime = $file['error'] === UPLOAD_ERR_OK ? (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']) : '';
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > RESUME_MAX_BYTES) $error = 'Your resume needs to be under 4 MB.';
        elseif (!isset($resumeTypes[$ext]) || !in_array($mime, [$resumeTypes[$ext], 'application/zip', 'application/octet-stream'], true)) $error = 'Please upload your resume as a PDF or Word file.';
        else $resume = save_file('resume.' . $ext, $resumeTypes[$ext], file_get_contents($file['tmp_name']));
    }

    if (!$error) {
        $data = ['role' => $old['role'], 'availability' => implode(', ', $old['times']), 'about' => $old['about'], 'resume' => $resume];
        if (save_submission('career', $old['name'], $old['email'], $old['phone'], $data)) {
            notify_owner('Oakton Eats job application: ' . $old['role'],
                "Name: {$old['name']}\nEmail: {$old['email']}\nPhone: {$old['phone']}\nRole: {$old['role']}\nAvailability: {$data['availability']}\nResume: " . ($resume ? 'attached in the admin' : 'none') . "\n\n{$old['about']}\n", $old['email']);
        }
        header('Location: /careers?sent=1#apply');
        exit;
    }
}

$page = 'careers';
$title = 'Careers';
$description = 'Join the Oakton Eats team in Skokie, IL. We are hiring baristas, line cooks, front counter and shift leads for our opening.';
require __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/views/careers.php';
require __DIR__ . '/includes/footer.php';
