</main>

<footer class="site-footer">
    <div class="wrap footer-big">
        <h2>Coffee all day.<br><em>Breakfast, lunch &amp; dinner.</em></h2>
        <div class="footer-cta"><?php if (OPENING_SOON): ?><span class="soon-pill"><span class="dot"></span>Opening <?= e(OPENING_DATE !== '' ? OPENING_DATE : 'soon') ?></span><?php endif; ?><?= order_button('btn btn-gold') ?></div>
    </div>

    <div class="wrap footer-grid">
        <div>
            <?= logo() ?>
            <p>Your all-day neighborhood spot<?= OPENING_SOON ? ', opening soon' : '' ?>. Coffee from open to close, with breakfast, lunch and dinner from one counter.</p>
            <?php if (INSTAGRAM_URL || TIKTOK_URL || FACEBOOK_URL): ?>
                <div class="socials">
                    <?php if (INSTAGRAM_URL): ?><a href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram') ?></a><?php endif; ?>
                    <?php if (TIKTOK_URL): ?><a href="<?= e(TIKTOK_URL) ?>" target="_blank" rel="noopener" aria-label="TikTok"><?= icon('tiktok') ?></a><?php endif; ?>
                    <?php if (FACEBOOK_URL): ?><a href="<?= e(FACEBOOK_URL) ?>" target="_blank" rel="noopener" aria-label="Facebook"><?= icon('facebook') ?></a><?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <h4>Explore</h4>
            <ul><?php foreach ($NAV as $item): ?><li><a href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li><?php endforeach; ?></ul>
        </div>
        <div>
            <h4>Our Menus</h4>
            <ul>
                <?php foreach ($GROUPS as $key => $g): ?>
                    <li><a href="<?= e(menu_href($key)) ?>"><?= e($g['title']) ?></a><br><small><?= e($g['hours']) ?></small></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div>
            <h4>Visit</h4>
            <ul>
                <li><?= has_address() ? e(ADDRESS_LINE1) . '<br>' . e(ADDRESS_LINE2) : 'Location coming soon' ?></li>
                <?php if (PHONE): ?><li><a href="tel:<?= e(PHONE_TEL) ?>"><?= e(PHONE) ?></a></li><?php endif; ?>
                <?php if (EMAIL): ?><li><a href="mailto:<?= e(EMAIL) ?>"><?= e(EMAIL) ?></a></li><?php endif; ?>
            </ul>
            <?php include __DIR__ . '/hours.php'; ?>
        </div>
    </div>

    <div class="wrap footer-bottom">
        <span>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</span>
        <a href="#top">Back to top &uarr;</a>
    </div>
</footer>

<?php include __DIR__ . '/notify.php'; ?>

<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>
