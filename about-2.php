<?php
// About page for Home 2: full-photo hero, a day at Oakton, vision and mission, values with photos.
require_once __DIR__ . '/includes/functions.php';
remember_home('home-2');
$page = 'about';
$title = 'Our Story';
$description = 'The story of Oakton Eats in Skokie, IL: one counter for coffee all day, breakfast sandwiches, refreshers and smash burgers.';

$day = [
    ['when' => 'Morning',     'title' => 'The first pour',      'text' => 'Espresso, lattes and cold brew for the commute and the early risers.', 'img' => 'scenes/latte-art.jpg'],
    ['when' => 'Breakfast',   'title' => 'Out of the oven',     'text' => 'Butter croissants stacked with fluffy egg and melted cheese.',           'img' => 'croissant-egg-cheese.jpg'],
    ['when' => 'Afternoon',   'title' => 'Something cold',      'text' => 'Fresh lemonades and fruit refreshers, shaken over ice.',                 'img' => 'scenes/refreshers.jpg'],
    ['when' => 'Dinner',      'title' => 'On the griddle',      'text' => 'Smash burgers with crispy edges and hot honey chicken.',                 'img' => 'scenes/griddle.jpg'],
    ['when' => 'Late',        'title' => 'One more round',      'text' => 'A thick shake or one last coffee before you head home.',                 'img' => 'scenes/shakes.jpg'],
];
$values = [
    ['title' => 'Made fresh',         'text' => 'Nothing sits waiting. Coffee is pulled, eggs are cooked and beef is smashed when you order.', 'img' => 'scenes/griddle.jpg'],
    ['title' => 'Made by hand',       'text' => 'Chicken breaded in-house and sauces mixed in our kitchen.',                                   'img' => 'scenes/chicken.jpg'],
    ['title' => 'Quality first',      'text' => 'Good beans, real butter and fresh produce. A tight menu done right.',                         'img' => 'scenes/croissants.jpg'],
    ['title' => "Everyone's welcome", 'text' => "A seat for everyone, whether you're in for five minutes or an hour.",                         'img' => 'scenes/seating.jpg'],
];
require __DIR__ . '/includes/header.php';
?>

<section class="a2-hero">
    <img src="<?= photo('scenes/counter.jpg') ?>" alt="The Oakton Eats counter with the espresso machine and pastry case">
    <div class="wrap a2-hero-copy">
        <span class="kicker">Our story</span>
        <h1>Start with <em>coffee.</em><br>End with a <em>burger.</em></h1>
        <p>Oakton Eats is a neighborhood counter on Oakton St in Skokie, built for the whole day.</p>
    </div>
</section>

<section class="section light">
    <div class="wrap a2-intro">
        <p class="a2-big reveal">Most places pick a lane: a coffee shop or a burger joint. <em>We wanted both,</em> under one roof, done with the same care.</p>
        <div class="reveal">
            <p>So we built one counter for every hour. A proper latte when you wake up, a warm breakfast sandwich on your way out, a cold refresher in the afternoon and a smash burger with real crunch for dinner.</p>
            <p>Everything is made to order, the menu stays tight, and the espresso bar never closes before the kitchen does.</p>
            <?= order_button('btn btn-black') ?>
        </div>
    </div>
</section>

<section class="section a2-day-sec">
    <div class="wrap">
        <div class="t2-head reveal">
            <span class="kicker">A day at Oakton</span>
            <h2>One counter, <em>every hour.</em></h2>
        </div>
    </div>
    <div class="a2-day">
        <?php foreach ($day as $d): ?>
            <article class="a2-moment reveal">
                <img src="<?= photo($d['img']) ?>" alt="" loading="lazy">
                <div>
                    <span class="a2-when"><?= e($d['when']) ?></span>
                    <h3><?= e($d['title']) ?></h3>
                    <p><?= e($d['text']) ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="a2-vm reveal">
            <span class="kicker">Our vision</span>
            <p>To be the neighborhood's <em>favorite stop,</em> morning, noon and night: the place you grab your first coffee and come back to for dinner.</p>
        </div>
        <div class="a2-vm reveal">
            <span class="kicker">Our mission</span>
            <p>To serve <em>great coffee and food made with care,</em> fresh to order and fairly priced, with a welcome that makes every visit easy.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="t2-head reveal">
            <span class="kicker">What we stand for</span>
            <h2>Our core <em>values.</em></h2>
        </div>
        <div class="a2-values">
            <?php foreach ($values as $v): ?>
                <article class="a2-value reveal">
                    <img src="<?= photo($v['img']) ?>" alt="" loading="lazy">
                    <div><h3><?= e($v['title']) ?></h3><p><?= e($v['text']) ?></p></div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="a2-visit">
    <img src="<?= photo('scenes/storefront.jpg') ?>" alt="A warm cafe front window at dusk" loading="lazy">
    <div class="wrap a2-visit-copy reveal">
        <span class="kicker">Opening soon</span>
        <h2>Find us on <em>Oakton St.</em></h2>
        <p><?= e(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) ?></p>
        <div class="t2-actions">
            <?= order_button('btn btn-gold') ?>
            <?php if (MAPS_URL): ?><a class="btn btn-line" href="<?= e(MAPS_URL) ?>" target="_blank" rel="noopener"><?= icon('pin') ?> Directions</a><?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
