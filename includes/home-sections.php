<?php // Everything below the homepage hero. ?>

<section class="section light t2-intro">
    <div class="wrap">
        <p class="t2-statement reveal">An espresso bar and a burger griddle, opening at <?= e(ADDRESS_LINE1) ?> in Skokie. Come in for a latte in the morning or a <em>double smash</em> at night.</p>
        <?php include __DIR__ . '/perks.php'; ?>
    </div>
</section>

<section class="section t2-index-sec" id="menus">
    <div class="wrap">
        <div class="t2-head reveal">
            <h2>On the <em>menu</em></h2>
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
    </div>
</section>

<section class="t2-split">
    <a class="t2-panel reveal" href="<?= e(menu_href('coffee')) ?>">
        <img src="<?= photo('cold-brew.jpg') ?>" alt="A glass of cold brew over ice" loading="lazy">
        <div class="t2-panel-copy">
            <span class="kicker">Coffee · All day</span>
            <h2>Espresso until <em>we close</em></h2>
            <p>Lattes, cold brew and iced coffee, hot or over ice. Start with the Spanish latte.</p>
            <span class="link">See all coffee <?= icon('arrow') ?></span>
        </div>
    </a>
    <a class="t2-panel reveal" href="<?= e(menu_href('breakfast')) ?>">
        <img src="<?= photo('scenes/croissants.jpg') ?>" alt="Fresh croissants and egg and cheese croissant sandwiches" loading="lazy">
        <div class="t2-panel-copy">
            <span class="kicker">Breakfast</span>
            <h2>Croissant <em>sandwiches</em></h2>
            <p>Egg and melted cheese on a warm croissant or toasted brioche. Burritos and wraps too.</p>
            <span class="link">Breakfast menu <?= icon('arrow') ?></span>
        </div>
    </a>
</section>

<section class="t2-band">
    <div class="wrap t2-band-grid">
        <div class="reveal">
            <h2>Lemonade &amp; <em>refreshers</em></h2>
            <p>Fresh lemonade, fruit refreshers and iced tea, all over ice.</p>
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
        <h2>Burgers, chicken <em>&amp; shakes</em></h2>
        <p>Smash burgers with crisp edges, hot honey chicken and loaded fries, cooked when you order.</p>
        <a class="link" href="<?= e(menu_href('signature')) ?>">See the signatures <?= icon('arrow') ?></a>
    </div>
</section>

<?php include __DIR__ . '/gallery.php'; ?>

<section class="section t2-join">
    <div class="wrap t2-join-inner reveal">
        <h2>Get the <em>opening date</em></h2>
        <p>Leave your email and we'll tell you when the doors open. You'll also get <?= e(SIGNUP_PERK) ?> and an invite to the grand opening.</p>
        <?= order_button('btn btn-black') ?>
    </div>
</section>

<section class="section light" id="visit">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Find us</span><span class="line"></span></div>
        <div class="head reveal">
            <h2><?= e(ADDRESS_LINE1) ?>, <em>Skokie</em></h2>
        </div>
        <figure class="visit-photo reveal"><img src="<?= photo('scenes/storefront.jpg') ?>" alt="A warm cafe front window at dusk" loading="lazy"></figure>
        <?php include __DIR__ . '/visit.php'; ?>
    </div>
</section>
