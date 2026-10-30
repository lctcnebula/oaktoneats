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
?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span>Opening <?= OPENING_DATE !== '' ? e(OPENING_DATE) : 'soon' ?></span><?= address_link() ?></div>
            <h1>Say <em>hello.</em></h1>
        </div>
        <div>
            <p>Questions, catering, jobs or just want to know when we open? Send us a note and we'll get back to you.</p>
            <div class="actions"><?= order_button() ?></div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:20px">
    <div class="wrap contact-grid">
        <div class="contact-info reveal">
            <figure class="contact-photo">
                <img src="<?= photo('scenes/counter.jpg') ?>" alt="Our espresso bar and pastry case" loading="lazy">
            </figure>
            <ul class="visit-list">
                <li><span class="k">Address</span><span><?= has_address() ? (MAPS_URL ? '<a href="' . e(MAPS_URL) . '" target="_blank" rel="noopener">' : '') . e(ADDRESS_LINE1) . ', ' . e(ADDRESS_LINE2) . (MAPS_URL ? '</a>' : '') : 'Opening soon.' ?></span></li>
                <?php if (PHONE): ?><li><span class="k">Phone</span><a href="tel:<?= e(PHONE_TEL) ?>"><?= e(PHONE) ?></a></li><?php endif; ?>
                <?php if (EMAIL): ?><li><span class="k">Email</span><a href="mailto:<?= e(EMAIL) ?>"><?= e(EMAIL) ?></a></li><?php endif; ?>
                <li><span class="k">Hours</span><span>Coming soon. Coffee all day, breakfast, lunch &amp; dinner.</span></li>
                <?php if (INSTAGRAM_URL): ?><li><span class="k">Instagram</span><a href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener"><?= e('@' . basename(rtrim(INSTAGRAM_URL, '/'))) ?></a></li><?php endif; ?>
            </ul>
            <?php if (MAPS_URL): ?><a class="btn btn-line" href="<?= e(MAPS_URL) ?>" target="_blank" rel="noopener"><?= icon('pin') ?> Directions</a><?php endif; ?>
        </div>

        <div class="contact-card reveal" id="form">
            <?php if ($sent): ?>
                <div class="contact-done">
                    <span class="ico"><?= icon('check') ?></span>
                    <h2>Thanks, <em>we got it.</em></h2>
                    <p>We'll get back to you soon. In the meantime, join the list for bonus points on your first order.</p>
                    <?= order_button() ?>
                </div>
            <?php else: ?>
                <h2>Send a <em>message.</em></h2>
                <?php if ($error): ?><p class="contact-error" role="alert"><?= e($error) ?></p><?php endif; ?>
                <form class="contact-form" method="post" action="/contact#form">
                    <div class="two">
                        <label>Name<input type="text" name="name" value="<?= e($old['name']) ?>" autocomplete="name" required maxlength="80"></label>
                        <label>Email<input type="email" name="email" value="<?= e($old['email']) ?>" autocomplete="email" required maxlength="120"></label>
                    </div>
                    <div class="two">
                        <label>Phone (optional)<input type="tel" name="phone" value="<?= e($old['phone']) ?>" autocomplete="tel" maxlength="30"></label>
                        <label>Topic
                            <select name="topic">
                                <?php foreach ($topics as $t): ?><option<?= $t === $old['topic'] ? ' selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
                            </select>
                        </label>
                    </div>
                    <label>Message<textarea name="message" rows="6" required maxlength="2000"><?= e($old['message']) ?></textarea></label>
                    <label class="hp" aria-hidden="true">Leave this empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <button class="btn btn-gold" type="submit">Send message <?= icon('arrow-ur') ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (has_address()): ?>
<section class="contact-map">
    <iframe title="Map to <?= e(SITE_NAME) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= urlencode(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) ?>&output=embed"></iframe>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
