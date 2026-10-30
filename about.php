<?php
$page = 'about';
$title = 'About Us';
$description = 'Oakton Eats is an all-day neighborhood spot opening soon: coffee from open to close, with breakfast, lunch and dinner from one counter.';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <span class="kicker">About Oakton Eats</span>
            <h1>One counter, <em>open all day.</em></h1>
        </div>
        <div>
            <p>A neighborhood spot for the whole day. Coffee when you wake up, a warm breakfast sandwich on your way out, a refresher in the afternoon and a smash burger for dinner.</p>
        </div>
    </div>
    <div class="wrap page-strip">
        <img src="<?= photo('scenes/latte-art.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/croissants.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('hero-burger.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/refreshers.jpg') ?>" alt="" loading="lazy">
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Our story</span><span class="line"></span></div>
        <div class="spot">
            <div class="spot-copy reveal">
                <h2>We didn't want to <em>pick a lane.</em></h2>
                <p>Most places are a coffee shop or a burger joint. We wanted both: somewhere you can start the morning with a proper latte and come back at lunch for a burger with real crunch on the edges.</p>
                <p>So Oakton Eats does both. Breakfast, lunch and dinner from one counter: breakfast sandwiches, fresh refreshers and our house signature smash burgers. The espresso bar never stops.</p>
                <a class="link" href="<?= e(menu_href()) ?>">See the menu <?= icon('arrow') ?></a>
            </div>
            <div class="spot-media reveal">
                <img src="<?= photo('iced-coffee.jpg') ?>" alt="Iced coffee being poured" loading="lazy">
                <img class="mini" src="<?= photo('croissant-egg-cheese.jpg') ?>" alt="An egg and cheese croissant sandwich" loading="lazy">
                <img class="mini mini-2" src="<?= photo('hero-burger.jpg') ?>" alt="A double smash burger" loading="lazy">
                <div class="stamp stamp-low"><b>All day</b>Menu</div>
            </div>
        </div>
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">What we stand for</span><span class="line"></span></div>
        <div class="vm">
            <div class="vm-card reveal">
                <span class="vm-ico"><?= icon('sun') ?></span>
                <span class="kicker">Our vision</span>
                <p>To be the neighborhood's <em>favorite stop</em>, morning, noon and night: the place you grab your first coffee and come back to for dinner.</p>
            </div>
            <div class="vm-card dark reveal">
                <span class="vm-ico"><?= icon('flame') ?></span>
                <span class="kicker">Our mission</span>
                <p>To serve <em>great coffee and food made with care</em>, fresh to order and fairly priced, with a welcome that makes every visit easy.</p>
            </div>
        </div>

        <div class="core-head reveal">
            <h2>Our core <em>values.</em></h2>
        </div>
        <div class="core">
            <div class="core-item reveal"><span class="ico"><?= icon('flame') ?></span><h3>Made fresh</h3><p>Espresso pulled to order, eggs cooked when you order, beef smashed on the spot. Nothing sits waiting.</p></div>
            <div class="core-item reveal"><span class="ico"><?= icon('hand') ?></span><h3>Made by hand</h3><p>Chicken breaded in-house and sauces mixed in our kitchen. Small details you can taste.</p></div>
            <div class="core-item reveal"><span class="ico"><?= icon('check') ?></span><h3>Quality first</h3><p>Good beans, real butter and fresh produce. We'd rather keep the menu tight than cut corners.</p></div>
            <div class="core-item reveal"><span class="ico"><?= icon('users') ?></span><h3>Everyone's welcome</h3><p>Friendly faces and a seat for everyone, whether you're in for five minutes or an hour.</p></div>
        </div>
    </div>
</section>

<?php $reels = reels(5); if ($reels): ?>
<section class="section alt">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Behind the counter</span><span class="line"></span></div>
        <div class="head reveal"><h2>Watch it <em>come together.</em></h2></div>
        <div class="reels">
            <?php foreach ($reels as $r): ?>
                <div class="reel reveal">
                    <?= reel_media($r) ?>
                    <span><?= e($r['label']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
