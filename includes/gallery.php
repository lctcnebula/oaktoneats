<?php // Photo mosaic of the four menus, used on both homepages. ?>
<section class="section gallery-sec">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">From the counter</span><span class="line"></span></div>
        <div class="head reveal">
            <h2>Something for <em>every hour.</em></h2>
            <p>An iced latte at eight, a croissant sandwich at nine, a burger at noon and a shake after dinner.</p>
        </div>
        <div class="gallery">
            <a class="g g-big reveal" href="<?= e(menu_href()) ?>"><img src="<?= photo('scenes/spread.jpg') ?>" alt="An iced latte, egg and cheese croissant, smash burger, fries and lemonade" loading="lazy"><span>The whole menu, one table</span></a>
            <a class="g reveal" href="<?= e(menu_href('signature')) ?>"><img src="<?= photo('scenes/griddle.jpg') ?>" alt="A smash burger patty on the griddle" loading="lazy"><span>Smashed on the griddle</span></a>
            <a class="g reveal" href="<?= e(menu_href('signature')) ?>"><img src="<?= photo('scenes/chicken.jpg') ?>" alt="A hot honey crispy chicken sandwich" loading="lazy"><span>Hot honey chicken</span></a>
            <a class="g reveal" href="<?= e(menu_href('signature')) ?>"><img src="<?= photo('scenes/shakes.jpg') ?>" alt="Vanilla, chocolate and strawberry shakes" loading="lazy"><span>Hand-spun shakes</span></a>
            <a class="g reveal" href="<?= e(menu_href('coffee')) ?>"><img src="<?= photo('scenes/latte-art.jpg') ?>" alt="Latte art being poured" loading="lazy"><span>Poured with care</span></a>
        </div>
    </div>
</section>
