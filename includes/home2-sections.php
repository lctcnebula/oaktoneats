<?php // Everything below the hero, template 2. ?>

<section class="section light t2-intro">
    <div class="wrap">
        <?= logo_mark('t2-intro-mark') ?>
        <p class="t2-statement reveal">Start your day with a <em>coffee.</em> End it with a <em>burger.</em> One neighborhood counter, from the first espresso of the morning to the last smash of the night.</p>
        <?php include __DIR__ . '/perks.php'; ?>
    </div>
</section>

<section class="section t2-index-sec" id="menus">
    <div class="wrap">
        <div class="t2-head reveal">
            <span class="kicker">The menus</span>
            <h2>Four menus. <em>One counter.</em></h2>
        </div>
        <div class="t2-index">
            <?php foreach ($GROUPS as $key => $g): ?>
                <a class="t2-row reveal" href="<?= e(menu_href($key)) ?>">
                    <img src="<?= photo($g['img']) ?>" alt="" loading="lazy">
                    <div class="t2-row-title">
                        <h3><?= e($g['title']) ?></h3>
                        <span><?= e($g['hours']) ?></span>
                    </div>
                    <ul>
                        <?php foreach ($g['lines'] as $line): ?><li><?= e($line) ?></li><?php endforeach; ?>
                    </ul>
                    <span class="t2-go"><?= icon('arrow-ur') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php if (OPENING_SOON): ?><a class="menu-soon reveal"<?= notify_attrs() ?>><span class="soon-tag">Opening soon</span>The full menu goes live when our doors open. Join the list for bonus points.<?= icon('arrow') ?></a><?php endif; ?>
    </div>
</section>

<section class="t2-split">
    <a class="t2-panel reveal" href="<?= e(menu_href('coffee')) ?>">
        <img src="<?= photo('cold-brew.jpg') ?>" alt="A glass of cold brew over ice" loading="lazy">
        <div class="t2-panel-copy">
            <span class="kicker">All day</span>
            <h2>Pulled to order, <em>open to close.</em></h2>
            <p>Hot or iced, the coffee never clocks out. Try the house Spanish latte.</p>
            <span class="link">See all coffee <?= icon('arrow') ?></span>
        </div>
    </a>
    <a class="t2-panel reveal" href="<?= e(menu_href('breakfast')) ?>">
        <img src="<?= photo('scenes/croissants.jpg') ?>" alt="Fresh croissants and egg and cheese croissant sandwiches" loading="lazy">
        <div class="t2-panel-copy">
            <span class="kicker">Breakfast</span>
            <h2>Start slow. <em>Start buttery.</em></h2>
            <p>Warm croissants and brioche stacked with fluffy egg and melted cheese.</p>
            <span class="link">Breakfast menu <?= icon('arrow') ?></span>
        </div>
    </a>
</section>

<section class="t2-band">
    <div class="wrap t2-band-grid">
        <div class="reveal">
            <span class="kicker">Refreshers</span>
            <h2>Shaken cold, <em>poured bright.</em></h2>
            <p>Fresh lemonades, fruit refreshers and iced teas for when the day warms up.</p>
            <?= group_list('refreshers') ?>
        </div>
        <div class="t2-band-img reveal">
            <img src="<?= photo('scenes/refreshers.jpg') ?>" alt="Fresh, mango and strawberry lemonades" loading="lazy">
        </div>
    </div>
</section>

<section class="t2-banner">
    <?php if ($c = clip(4)): ?>
        <video muted loop playsinline preload="none" poster="<?= e($c['poster']) ?>" data-autoplay><source src="<?= e($c['video']) ?>" type="video/mp4"></video>
    <?php else: ?>
        <img src="<?= photo('hero-burger.jpg') ?>" alt="" loading="lazy">
    <?php endif; ?>
    <div class="wrap t2-banner-copy reveal">
        <span class="kicker">House signatures · Lunch &amp; dinner</span>
        <h2>Smashed to order, <em>never ahead.</em></h2>
        <p>Smash burgers, hot honey chicken and loaded fries, cooked the moment you order.</p>
        <a class="link" href="<?= e(menu_href('signature')) ?>">See the signatures <?= icon('arrow') ?></a>
    </div>
</section>

<?php include __DIR__ . '/gallery.php'; ?>

<section class="section t2-join">
    <div class="wrap t2-join-inner reveal">
        <span class="kicker">Opening soon</span>
        <h2>Be first <em>in line.</em></h2>
        <p>Join the list for bonus points on your first order and an invite to our grand opening.</p>
        <?= order_button('btn btn-black') ?>
    </div>
</section>

<section class="section light" id="visit">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Find us</span><span class="line"></span></div>
        <div class="head reveal">
            <h2>Opening soon, <em>right here.</em></h2>
        </div>
        <figure class="visit-photo reveal"><img src="<?= photo('scenes/storefront.jpg') ?>" alt="A warm cafe front window at dusk" loading="lazy"><figcaption><?= icon('pin') ?> <?= e(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) ?></figcaption></figure>
        <?php include __DIR__ . '/visit.php'; ?>
    </div>
</section>
