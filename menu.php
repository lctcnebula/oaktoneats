<?php
$page = 'menu';
$title = 'Menu';
$description = 'The Oakton Eats menu: coffee all day, breakfast sandwiches, refreshers and house signature smash burgers, crispy chicken and fries.';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span>Opening <?= OPENING_DATE !== '' ? e(OPENING_DATE) : 'soon' ?></span><?= address_link() ?></div>
            <h1>The menu, <em>four ways.</em></h1>
        </div>
        <div>
            <p>Coffee all day, breakfast sandwiches, refreshers, and our house signatures for lunch and dinner.</p>
            <div class="actions">
                <?= order_button('btn btn-gold') ?>
            </div>
        </div>
    </div>
    <div class="wrap page-strip">
        <img src="<?= photo('cold-brew.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('croissant-egg-cheese.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('lemonade.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('hero-burger.jpg') ?>" alt="" loading="lazy">
    </div>
</section>

<div class="menu-bar">
    <div class="wrap menu-bar-inner">
        <div class="part-filter" role="group" aria-label="Filter the menu">
            <button type="button" data-filter="all" aria-pressed="true">Everything</button>
            <?php foreach ($GROUPS as $key => $g): ?>
                <button type="button" data-filter="<?= e($key) ?>" aria-pressed="false"><?= e($g['title'] === 'Breakfast Sandwiches' ? 'Breakfast' : $g['title']) ?></button>
            <?php endforeach; ?>
        </div>
        <nav class="cat-nav" aria-label="Menu categories">
            <?php foreach ($GROUPS as $key => $g): foreach (categories_for($key) as $cat): ?>
                <a href="#<?= e($cat['slug']) ?>" data-group="<?= e($key) ?>"><?= e($cat['name']) ?></a>
            <?php endforeach; endforeach; ?>
        </nav>
        <label class="menu-search">
            <?= icon('search') ?>
            <span class="sr-only">Search the menu</span>
            <input type="search" id="menu-search" placeholder="Search the menu" autocomplete="off">
        </label>
    </div>
</div>

<div class="wrap">
    <?php foreach ($GROUPS as $key => $g): ?>
        <section class="daypart" id="<?= e($key) ?>" data-group="<?= e($key) ?>">
            <div class="daypart-head">
                <div>
                    <span class="kicker"><?= icon($g['icon']) ?> <?= e($g['hours']) ?></span>
                    <h2><?= e($g['title']) ?></h2>
                </div>
                <div class="meta">
                    <span class="time-pill"><?= icon('clock') ?> <?= e($g['hours']) ?></span>
                </div>
            </div>
            <?php foreach (categories_for($key) as $cat): ?>
                <div class="menu-cat" id="<?= e($cat['slug']) ?>">
                    <div class="menu-cat-info">
                        <span class="count"><?= count($cat['items']) ?> items</span>
                        <h3><?= e($cat['name']) ?></h3>
                        <p><?= e($cat['blurb']) ?></p>
                    </div>
                    <div class="items">
                        <?php foreach ($cat['items'] as $item) echo menu_item($item); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>

    <p class="no-results" hidden>Nothing matches that. Try "latte", "egg" or "lemonade".</p>
</div>

<section class="section light" style="margin-top:clamp(70px,8vw,110px)">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Add-ons &amp; combos</span><span class="line"></span></div>
        <div class="addons">
            <?php foreach ($COMBOS as $c): ?>
                <div class="addon reveal">
                    <h3><?= e($c['name']) ?></h3>
                    <?php if (SHOW_PRICES): ?><span class="price">+ <?= money($c['price']) ?></span><?php endif; ?>
                    <p><?= e($c['desc']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <p class="fine"><?= SHOW_PRICES ? 'Prices and availability may change.' : 'Prices will be posted when we open.' ?> Please tell us about any allergies when you order.</p>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
