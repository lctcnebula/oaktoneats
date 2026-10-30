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
?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span>Now hiring</span><?= address_link() ?></div>
            <h1>Join the <em>opening team.</em></h1>
        </div>
        <div>
            <p>We're building the crew that opens our doors: people who like good coffee, fresh food and a busy, friendly counter. No experience? We'll teach you.</p>
            <div class="actions"><a class="btn btn-gold" href="#apply">Apply now <?= icon('arrow') ?></a></div>
        </div>
    </div>
    <div class="wrap page-strip">
        <img src="<?= photo('scenes/latte-art.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/griddle.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/counter.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/croissants.jpg') ?>" alt="" loading="lazy">
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Open roles</span><span class="line"></span></div>
        <div class="roles">
            <?php foreach ($roles as $r): ?>
                <a class="role reveal" href="#apply" data-role="<?= e($r['title']) ?>">
                    <span class="ico"><?= icon($r['icon']) ?></span>
                    <h3><?= e($r['title']) ?></h3>
                    <span class="role-type"><?= e($r['type']) ?></span>
                    <p><?= e($r['text']) ?></p>
                    <span class="link">Apply <?= icon('arrow') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <ul class="perks-list reveal">
            <li><?= icon('check') ?> Flexible shifts</li>
            <li><?= icon('check') ?> Free shift meal &amp; coffee</li>
            <li><?= icon('check') ?> Paid training</li>
            <li><?= icon('check') ?> Room to grow</li>
        </ul>
    </div>
</section>

<section class="section" id="apply">
    <div class="wrap contact-grid">
        <div class="reveal">
            <span class="kicker">Apply</span>
            <h2 class="apply-title">Tell us <em>about you.</em></h2>
            <p class="muted">It takes two minutes. Add a resume if you have one; if not, just tell us a little about yourself. We'll reach out to set up a quick chat.</p>
            <figure class="contact-photo"><img src="<?= photo('scenes/seating.jpg') ?>" alt="Inside the cafe" loading="lazy"></figure>
        </div>

        <div class="contact-card reveal">
            <?php if ($sent): ?>
                <div class="contact-done">
                    <span class="ico"><?= icon('check') ?></span>
                    <h2>Thanks, <em>application in.</em></h2>
                    <p>We read every one. If it's a fit, we'll call or email you to set up a chat.</p>
                    <a class="btn btn-gold" href="/">Back to home</a>
                </div>
            <?php else: ?>
                <h2>Job <em>application.</em></h2>
                <?php if ($error): ?><p class="contact-error" role="alert"><?= e($error) ?></p><?php endif; ?>
                <form class="contact-form" method="post" action="/careers#apply" enctype="multipart/form-data">
                    <div class="two">
                        <label>Name<input type="text" name="name" value="<?= e($old['name']) ?>" autocomplete="name" required maxlength="80"></label>
                        <label>Email<input type="email" name="email" value="<?= e($old['email']) ?>" autocomplete="email" required maxlength="120"></label>
                    </div>
                    <div class="two">
                        <label>Phone<input type="tel" name="phone" value="<?= e($old['phone']) ?>" autocomplete="tel" required maxlength="30"></label>
                        <label>Role
                            <select name="role" id="role">
                                <?php foreach (array_merge(array_column($roles, 'title'), ['Any role']) as $t): ?><option<?= $t === $old['role'] ? ' selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                    <fieldset class="checks">
                        <legend>When can you work?</legend>
                        <?php foreach ($times as $t): ?><label><input type="checkbox" name="times[]" value="<?= e($t) ?>"<?= in_array($t, $old['times'], true) ? ' checked' : '' ?>> <?= e($t) ?></label><?php endforeach; ?>
                    </fieldset>
                    <label>About you <span class="opt">(optional)</span><textarea name="about" rows="5" maxlength="2000" placeholder="Past jobs, what you enjoy, anything we should know"><?= e($old['about']) ?></textarea></label>
                    <label>Resume <span class="opt">(optional, PDF or Word, up to 4 MB)</span><input type="file" name="resume" accept=".pdf,.doc,.docx"></label>
                    <label class="hp" aria-hidden="true">Leave this empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <button class="btn btn-gold" type="submit">Send application <?= icon('arrow-ur') ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Clicking a role card picks it in the form.
    document.querySelectorAll('[data-role]').forEach(function (a) {
        a.addEventListener('click', function () { var s = document.getElementById('role'); if (s) s.value = a.dataset.role; });
    });
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
