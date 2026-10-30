<?php
// Everything below the hero, template 1 (index.php).
$marquee = ['Espresso', 'Cold Brew', 'Croissant Sandwiches', 'Lemonades', 'Refreshers', 'Smash Burgers', 'Crispy Chicken', 'Shakes'];
?>

<div class="marquee" aria-hidden="true">
    <div class="marquee-track">
        <?php for ($i = 0; $i < 2; $i++): foreach ($marquee as $m): ?><span><?= e($m) ?></span><?php endforeach; endfor; ?>
    </div>
</div>

<section class="section light" id="menus">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">One counter, four menus</span><span class="line"></span></div>
        <div class="head reveal">
            <h2>Four menus. <em>One counter.</em></h2>
            <p>Coffee from open to close, breakfast sandwiches to start the day, refreshers when it warms up, and our house signatures for lunch and dinner.</p>
        </div>
        <div class="day-grid">
            <?php foreach ($GROUPS as $key => $g): ?>
                <a class="part reveal" href="<?= e(menu_href($key)) ?>">
                    <div class="part-img">
                        <img src="<?= photo($g['img']) ?>" alt="" loading="lazy">
                        <span class="part-time"><?= $key === serving_now() ? 'Now · ' : '' ?><?= e($g['hours']) ?></span>
                    </div>
                    <div class="part-body">
                        <h3><?= e($g['title']) ?></h3>
                        <p><?= e($g['copy']) ?></p>
                        <ul>
                            <?php foreach ($g['lines'] as $line): ?>
                                <li><span><?= e($line) ?></span></li>
                            <?php endforeach; ?>
                        </ul>
                        <span class="link">See the menu <?= icon('arrow') ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <?php if (OPENING_SOON): ?><a class="menu-soon reveal"<?= notify_attrs() ?>><span class="soon-tag">Opening soon</span>The full menu goes live when our doors open. Join the list for bonus points.<?= icon('arrow') ?></a><?php endif; ?>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">The coffee bar, all day</span><span class="line"></span></div>
        <div class="spot">
            <div class="spot-media reveal">
                <img src="<?= photo('iced-coffee.jpg') ?>" alt="Iced coffee being poured over milk and ice" loading="lazy">
                <img class="mini" src="<?= photo('scenes/latte-art.jpg') ?>" alt="Latte art being poured" loading="lazy">
                <div class="stamp"><b>All day</b>Espresso<br>&amp; Cold Brew</div>
            </div>
            <div class="spot-copy reveal">
                <h2>Pulled to order, <em>open to close.</em></h2>
                <p>Hot or iced, the coffee never clocks out. Our house Spanish latte is sweet, smooth and the one everyone comes back for.</p>
                <?= group_list('coffee') ?>
                <a class="link" href="<?= e(menu_href('coffee')) ?>">See all coffee <?= icon('arrow') ?></a>
            </div>
        </div>
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Breakfast sandwiches</span><span class="line"></span></div>
        <div class="spot flip">
            <div class="spot-media reveal">
                <img src="<?= photo('croissant-egg-cheese.jpg') ?>" alt="An egg and cheese croissant sandwich" loading="lazy">
                <img class="mini" src="<?= photo('scenes/croissants.jpg') ?>" alt="A tray of fresh croissants" loading="lazy">
                <div class="stamp"><b>Fresh</b>Made to<br>order</div>
            </div>
            <div class="spot-copy reveal">
                <h2>Start slow. <em>Start buttery.</em></h2>
                <p>Warm croissants and toasted brioche stacked with fluffy egg and melted cheese. Grab one with a latte on the way in.</p>
                <?= group_list('breakfast') ?>
                <a class="link" href="<?= e(menu_href('breakfast')) ?>">Full breakfast menu <?= icon('arrow') ?></a>
            </div>
        </div>
    </div>
</section>

<section class="section bento-sec">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Why Oakton</span><span class="line"></span></div>
        <div class="head reveal">
            <h2>Small menu. <em>Serious care.</em></h2>
            <p>We keep it tight so every cup and every plate gets made right, from the first espresso to the last order of fries.</p>
        </div>
        <div class="bento">
            <div class="b b-video reveal">
                <?php if ($c = clip(4)): ?>
                    <video muted loop playsinline preload="none" poster="<?= e($c['poster']) ?>" data-autoplay><source src="<?= e($c['video']) ?>" type="video/mp4"></video>
                <?php else: ?>
                    <img src="<?= photo('hero-burger.jpg') ?>" alt="" loading="lazy">
                <?php endif; ?>
                <div class="b-over">
                    <span class="kicker">House signatures</span>
                    <h3>Smashed to order, <em>never ahead.</em></h3>
                </div>
            </div>
            <a class="b b-gold reveal"<?= notify_attrs() ?>>
                <span class="big">Soon.</span>
                <p>We're opening soon. Join the list for bonus points and an invite to our grand opening. <span class="b-go">Get notified <?= icon('bell') ?></span></p>
            </a>
            <div class="b b-photo reveal">
                <img src="<?= photo('scenes/counter.jpg') ?>" alt="The espresso bar and pastry case" loading="lazy">
                <span class="b-cap">The espresso bar</span>
            </div>
            <div class="b b-photo reveal">
                <img src="<?= photo('scenes/refreshers.jpg') ?>" alt="Fresh, mango and strawberry lemonades" loading="lazy">
                <span class="b-cap">Refreshers, shaken cold</span>
            </div>
            <div class="b b-dark reveal">
                <span class="ico"><?= icon('flame') ?></span>
                <h3>Nothing under a heat lamp</h3>
                <p>Eggs, beef and fries are cooked when you order. You can taste the difference.</p>
            </div>
        </div>
    </div>
</section>

<?php $reels = reels(5); if ($reels): ?>
<section class="section alt">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Behind the counter</span><span class="line"></span></div>
        <div class="head reveal">
            <h2>Made fresh, <em>every order.</em></h2>
            <p>From the first espresso to the last basket of fries, here's what that looks like.</p>
        </div>
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

<?php include __DIR__ . '/gallery.php'; ?>

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
