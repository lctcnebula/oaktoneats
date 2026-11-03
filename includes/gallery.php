<?php // Photo mosaic of the four menus on the homepage. ?>
<section class="section gallery-sec">
    <div class="wrap">
        <div class="head reveal">
            <h2>From the <em>counter</em></h2>
        </div>
        <div class="gallery">
            <a class="g g-big reveal" href="<?= e(menu_href()) ?>"><img src="<?= photo('scenes/spread.jpg') ?>" alt="An iced latte, egg and cheese croissant, smash burger, fries and lemonade" loading="lazy"><span>Iced latte, croissant, burger &amp; fries</span></a>
            <a class="g reveal" href="<?= e(menu_href('signature')) ?>"><img src="<?= photo('scenes/griddle.jpg') ?>" alt="A smash burger patty on the griddle" loading="lazy"><span>On the griddle</span></a>
            <a class="g reveal" href="<?= e(menu_href('signature')) ?>"><img src="<?= photo('scenes/chicken.jpg') ?>" alt="A hot honey crispy chicken sandwich" loading="lazy"><span>Hot honey chicken</span></a>
            <a class="g reveal" href="<?= e(menu_href('signature')) ?>"><img src="<?= photo('scenes/shakes.jpg') ?>" alt="Vanilla, chocolate and strawberry shakes" loading="lazy"><span>Vanilla, chocolate &amp; strawberry shakes</span></a>
            <a class="g reveal" href="<?= e(menu_href('coffee')) ?>"><img src="<?= photo('scenes/latte-art.jpg') ?>" alt="Latte art being poured" loading="lazy"><span>Latte art</span></a>
        </div>
    </div>
</section>
