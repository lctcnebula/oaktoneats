<?php
// Homepage hero, template 1 (index.php).
$soon = 'Opening ' . (OPENING_DATE !== '' ? OPENING_DATE : 'soon');
// The photo card changes with the restaurant's clock: breakfast in the morning, house signatures after.
$now = serving_now();
$nowCard = $now === 'breakfast'
    ? ['img' => 'croissant-egg-cheese.jpg', 'alt' => 'An egg and cheese croissant', 'name' => 'Egg &amp; Cheese Croissant']
    : ['img' => 'hero-burger.jpg', 'alt' => 'The Oakton smash burger', 'name' => 'The Oakton'];
?>
<section class="hero">
    <div class="hero-bg" aria-hidden="true"><span class="orb o1"></span><span class="orb o2"></span></div>
    <div class="wrap hero-grid">
        <div class="hero-copy">
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span><?= e($soon) ?></span><?= address_link() ?></div>
            <h1><span class="ln">Coffee</span> <span class="ln">all day.</span> <em class="ln">Breakfast, lunch &amp; dinner.</em></h1>
            <p class="hero-lede">Oakton Eats is opening soon. Espresso and cold brew from open to close, buttery breakfast sandwiches, fresh refreshers and our house signature smash burgers.</p>
            <div class="hero-actions">
                <?= order_button('btn btn-gold') ?>
                <a class="btn btn-line" href="<?= e(menu_href()) ?>">See the Menu</a>
            </div>
            <?php include __DIR__ . '/perks.php'; ?>
        </div>

        <div class="hero-visual" data-parallax>
            <svg class="spark s1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M12 2v20M2 12h20M5 5l14 14M19 5L5 19"/></svg>
            <div class="hero-photo" data-depth="10">
                <img class="on" src="<?= photo('iced-coffee.jpg') ?>" alt="Iced Spanish latte being poured">
                <span class="photo-tag">Iced Spanish Latte</span>
            </div>
            <figure class="polaroid" data-depth="22">
                <img src="<?= photo($nowCard['img']) ?>" alt="<?= e($nowCard['alt']) ?>">
                <figcaption><?= $nowCard['name'] ?> <span><?= e($GROUPS[$now]['hours']) ?></span></figcaption>
            </figure>
            <a class="toast t1" data-depth="16"<?= notify_attrs() ?>>
                <span class="ico ico-logo"><?= logo_mark('toast-logo') ?></span>
                <div><b><?= e($soon) ?></b><span>Join the list for bonus points</span></div>
            </a>
        </div>
    </div>
</section>
