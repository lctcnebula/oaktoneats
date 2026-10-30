<?php
// Catering (Home 2 page set): what we cater, how it works, and an inquiry form saved to the admin as "Catering".
require_once __DIR__ . '/includes/functions.php';
remember_home('home-2');

$packages = [
    ['title' => 'Coffee Bar',        'img' => 'scenes/latte-art.jpg',  'text' => 'Boxes of hot coffee and cold brew, iced lattes and teas, with cups, milk and sweeteners.'],
    ['title' => 'Breakfast Platter', 'img' => 'scenes/croissants.jpg', 'text' => 'Egg & cheese croissants, breakfast burritos and wraps, cut and ready to share.'],
    ['title' => 'Burger Bar',        'img' => 'scenes/spread.jpg',     'text' => 'Smash burgers, crispy chicken sandwiches and trays of fries and sides.'],
    ['title' => 'Refreshers & Shakes', 'img' => 'scenes/refreshers.jpg', 'text' => 'Lemonades and fruit refreshers by the gallon, plus hand-spun shakes.'],
];
$needs = ['Coffee & drinks', 'Breakfast', 'Burgers & chicken', 'Refreshers', 'Shakes & desserts'];

$sent = isset($_GET['sent']);
$error = '';
$old = ['name' => '', 'email' => '', 'phone' => '', 'company' => '', 'date' => '', 'guests' => '', 'service' => 'Pickup', 'needs' => [], 'notes' => ''];

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (trim($_POST['website'] ?? '') !== '') { header('Location: /catering?sent=1#quote'); exit; }

    $clean = fn($v, $n) => mb_substr(trim(preg_replace('/[\r\t]+/', ' ', (string) $v)), 0, $n);
    foreach (['name' => 80, 'email' => 120, 'phone' => 30, 'company' => 80, 'date' => 10, 'guests' => 5, 'service' => 20, 'notes' => 2000] as $k => $n) $old[$k] = $clean($_POST[$k] ?? '', $n);
    $old['needs'] = array_values(array_intersect($needs, (array) ($_POST['needs'] ?? [])));
    if (!in_array($old['service'], ['Pickup', 'Delivery'], true)) $old['service'] = 'Pickup';

    if ($old['name'] === '') $error = 'Please add your name.';
    elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $error = 'Please enter a valid email address.';
    elseif ($old['phone'] === '') $error = 'Please add a phone number.';
    elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $old['date']) || $old['date'] < date('Y-m-d')) $error = 'Please pick an event date from today on.';
    elseif (!ctype_digit($old['guests']) || (int) $old['guests'] < 1) $error = 'Please tell us roughly how many guests.';

    if (!$error) {
        $data = ['company' => $old['company'], 'event_date' => $old['date'], 'guests' => $old['guests'], 'service' => $old['service'], 'needs' => implode(', ', $old['needs']), 'message' => $old['notes']];
        if (save_submission('catering', $old['name'], $old['email'], $old['phone'], $data)) {
            notify_owner('Oakton Eats catering: ' . $old['guests'] . ' guests on ' . $old['date'],
                "Name: {$old['name']}\nEmail: {$old['email']}\nPhone: {$old['phone']}\nCompany: {$old['company']}\nDate: {$old['date']}\nGuests: {$old['guests']}\n{$old['service']}\nNeeds: {$data['needs']}\n\n{$old['notes']}\n", $old['email']);
        }
        header('Location: /catering?sent=1#quote');
        exit;
    }
}

$page = 'catering';
$title = 'Catering';
$description = 'Catering from Oakton Eats in Skokie, IL: coffee boxes, breakfast platters, smash burger bars and refreshers for offices, parties and events.';
require __DIR__ . '/includes/header.php';
?>

<section class="a2-hero cat-hero">
    <img src="<?= photo('scenes/spread.jpg') ?>" alt="Coffee, a croissant sandwich, a burger, fries and lemonade on a table">
    <div class="wrap a2-hero-copy">
        <span class="kicker">Catering &amp; large orders</span>
        <h1>Feed the <em>whole crew.</em></h1>
        <p>Coffee for the morning meeting, breakfast for the office, a burger bar for the party. Tell us what you need and we'll put together a quote.</p>
        <div class="t2-actions"><a class="btn btn-gold" href="#quote">Get a quote <?= icon('arrow') ?></a></div>
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="t2-head reveal">
            <span class="kicker">What we cater</span>
            <h2>Built for <em>sharing.</em></h2>
        </div>
        <div class="cat-packs">
            <?php foreach ($packages as $pk): ?>
                <article class="cat-pack reveal">
                    <img src="<?= photo($pk['img']) ?>" alt="" loading="lazy">
                    <div><h3><?= e($pk['title']) ?></h3><p><?= e($pk['text']) ?></p></div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="t2-head reveal">
            <span class="kicker">How it works</span>
            <h2>Easy as <em>ordering lunch.</em></h2>
        </div>
        <div class="cat-steps">
            <div class="cat-step reveal"><span class="ico"><?= icon('mail') ?></span><h3>Tell us the plan</h3><p>Date, headcount and what you'd like. Send the form below.</p></div>
            <div class="cat-step reveal"><span class="ico"><?= icon('check') ?></span><h3>Get a quote</h3><p>We'll reply with a menu and price, and tweak it until it's right.</p></div>
            <div class="cat-step reveal"><span class="ico"><?= icon('bag') ?></span><h3>Pick up or delivered</h3><p>Fresh, packed and labelled, ready when you need it.</p></div>
        </div>
    </div>
</section>

<section class="section light" id="quote">
    <div class="wrap contact-grid">
        <div class="reveal">
            <span class="kicker">Get a quote</span>
            <h2 class="apply-title cat-title">Tell us about <em>your event.</em></h2>
            <p>We ask for a few days' notice for large orders. Not sure what you need yet? Send what you have and we'll help plan it.</p>
            <figure class="contact-photo"><img src="<?= photo('scenes/to-go.jpg') ?>" alt="A packed pickup order on the counter" loading="lazy"></figure>
        </div>

        <div class="contact-card cat-card reveal">
            <?php if ($sent): ?>
                <div class="contact-done">
                    <span class="ico"><?= icon('check') ?></span>
                    <h2>Thanks, <em>request in.</em></h2>
                    <p>We'll get back to you with a quote soon.</p>
                    <?= order_button() ?>
                </div>
            <?php else: ?>
                <h2>Catering <em>request.</em></h2>
                <?php if ($error): ?><p class="contact-error" role="alert"><?= e($error) ?></p><?php endif; ?>
                <form class="contact-form" method="post" action="/catering#quote">
                    <div class="two">
                        <label>Name<input type="text" name="name" value="<?= e($old['name']) ?>" autocomplete="name" required maxlength="80"></label>
                        <label>Email<input type="email" name="email" value="<?= e($old['email']) ?>" autocomplete="email" required maxlength="120"></label>
                    </div>
                    <div class="two">
                        <label>Phone<input type="tel" name="phone" value="<?= e($old['phone']) ?>" autocomplete="tel" required maxlength="30"></label>
                        <label>Company <span class="opt">(optional)</span><input type="text" name="company" value="<?= e($old['company']) ?>" autocomplete="organization" maxlength="80"></label>
                    </div>
                    <div class="two">
                        <label>Event date<input type="date" name="date" value="<?= e($old['date']) ?>" min="<?= date('Y-m-d') ?>" required></label>
                        <label>Guests<input type="number" name="guests" value="<?= e($old['guests']) ?>" min="1" max="5000" required></label>
                    </div>
                    <fieldset class="checks">
                        <legend>What do you need?</legend>
                        <?php foreach ($needs as $n): ?><label><input type="checkbox" name="needs[]" value="<?= e($n) ?>"<?= in_array($n, $old['needs'], true) ? ' checked' : '' ?>> <?= e($n) ?></label><?php endforeach; ?>
                    </fieldset>
                    <fieldset class="checks">
                        <legend>Pickup or delivery?</legend>
                        <?php foreach (['Pickup', 'Delivery'] as $sv): ?><label><input type="radio" name="service" value="<?= $sv ?>"<?= $old['service'] === $sv ? ' checked' : '' ?>> <?= $sv ?></label><?php endforeach; ?>
                    </fieldset>
                    <label>Anything else? <span class="opt">(optional)</span><textarea name="notes" rows="4" maxlength="2000" placeholder="Dietary needs, budget, delivery address, timing"><?= e($old['notes']) ?></textarea></label>
                    <label class="hp" aria-hidden="true">Leave this empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    <button class="btn btn-gold" type="submit">Send request <?= icon('arrow-ur') ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
