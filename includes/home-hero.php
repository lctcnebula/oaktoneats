<?php
// Homepage hero, template 2: full-bleed photo with centered type and the four menus along the bottom.
$soon = 'Opening ' . (OPENING_DATE !== '' ? OPENING_DATE : 'soon');
?>
<section class="t2-hero">
    <div class="t2-hero-bg" aria-hidden="true">
        <img src="<?= photo('iced-coffee.jpg') ?>" alt="">
    </div>
    <div class="wrap t2-hero-inner">
        <div class="soon-row"><span class="soon-pill"><span class="dot"></span><?= e($soon) ?></span><?= address_link() ?></div>
        <h1><span class="ln">Coffee all day.</span> <em class="ln">Breakfast, lunch &amp; dinner.</em></h1>
        <p class="t2-lede">Espresso and cold brew from open to close, buttery breakfast sandwiches, fresh refreshers and our house signature smash burgers.</p>
        <div class="t2-actions">
            <?= order_button('btn btn-gold') ?>
            <a class="btn btn-line" href="<?= e(menu_href()) ?>">See the Menu</a>
        </div>
    </div>
    <nav class="t2-tabs" aria-label="Our menus">
        <?php foreach ($GROUPS as $key => $g): ?>
            <a href="<?= e(menu_href($key)) ?>"<?= $key === serving_now() ? ' class="is-now"' : '' ?>>
                <span class="ico"><?= icon($g['icon']) ?></span>
                <span><b><?= e($g['title']) ?></b><small><?= $key === serving_now() ? 'Now · ' : '' ?><?= e($g['hours']) ?></small></span>
                <?= icon('arrow') ?>
            </a>
        <?php endforeach; ?>
    </nav>
</section>
