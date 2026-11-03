<div class="visit">
    <div class="reveal">
        <ul class="visit-list">
            <li><span class="k">Address</span><span><?= has_address() ? (MAPS_URL ? '<a href="' . e(MAPS_URL) . '" target="_blank" rel="noopener">' : '') . e(ADDRESS_LINE1) . ', ' . e(ADDRESS_LINE2) . (MAPS_URL ? '</a>' : '') : 'Opening soon. Join the list for opening day.' ?></span></li>
            <?php if (PHONE): ?><li><span class="k">Phone</span><a href="tel:<?= e(PHONE_TEL) ?>"><?= e(PHONE) ?></a></li><?php endif; ?>
            <?php if (EMAIL): ?><li><span class="k">Email</span><a href="mailto:<?= e(EMAIL) ?>"><?= e(EMAIL) ?></a></li><?php endif; ?>
            <?php if (INSTAGRAM_URL): ?><li><span class="k">Instagram</span><a href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener"><?= e('@' . basename(rtrim(INSTAGRAM_URL, '/'))) ?></a></li><?php endif; ?>
            <li><span class="k">Ordering</span><span><?= ORDER_URL ? 'Order online for pickup, or walk in.' : 'Walk in and order at the counter. Online ordering is coming soon.' ?></span></li>
        </ul>
        <div class="visit-actions">
            <?php if (ORDER_URL): ?><a class="btn btn-black" href="<?= e(ORDER_URL) ?>" target="_blank" rel="noopener">Order Online <?= icon('arrow-ur') ?></a><?php endif; ?>
            <?php if (!ORDER_URL): ?><?= order_button('btn btn-black') ?><?php endif; ?>
            <?php if (MAPS_URL): ?><a class="btn btn-line" href="<?= e(MAPS_URL) ?>" target="_blank" rel="noopener"><?= icon('pin') ?> Directions</a><?php endif; ?>
            <?php if (PHONE): ?><a class="btn btn-line" href="tel:<?= e(PHONE_TEL) ?>"><?= icon('phone') ?> Call</a><?php endif; ?>
            <?php if (!MAPS_URL && !PHONE): ?><a class="btn btn-line" href="<?= e(menu_href()) ?>">See the Menu</a><?php endif; ?>
        </div>
        <?php if (has_address()): ?>
            <div class="map"><iframe title="Map to <?= e(SITE_NAME) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= urlencode(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) ?>&output=embed"></iframe></div>
        <?php endif; ?>
    </div>

    <div class="sched reveal">
        <?php if (empty($visitHoursOnly)): ?>
        <h3>What we <em>serve</em></h3>
        <?php foreach ($GROUPS as $key => $g): ?>
            <a class="sched-row" href="<?= e(menu_href($key)) ?>">
                <span class="ico"><?= icon($g['icon']) ?></span>
                <div><b><?= e($g['title']) ?></b></div>
                <span class="when"><?= e($g['hours']) ?></span>
            </a>
        <?php endforeach; ?>
        <?php include __DIR__ . '/hours.php'; ?>
        <?php else: ?>
        <h3>Opening <em>hours</em></h3>
        <?php if ($HOURS): include __DIR__ . '/hours.php'; else: ?>
            <p class="hours-soon">We'll post our hours before opening day. <a<?= notify_attrs() ?>>Join the list</a> and you'll hear first.</p>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
