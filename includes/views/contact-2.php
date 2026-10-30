<?php // Contact page body, Home 2 design. Variables come from contact.php. ?>

<section class="a2-hero c2-hero">
    <img src="<?= photo('scenes/seating.jpg') ?>" alt="Seating inside the cafe with warm lamps">
    <div class="wrap a2-hero-copy">
        <span class="kicker">Contact</span>
        <h1>Come say <em>hello.</em></h1>
        <p>Questions, catering, jobs or press. Pick the quickest way below, or send us a note.</p>
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="c2-ways">
            <a class="c2-way reveal"<?= MAPS_URL ? ' href="' . e(MAPS_URL) . '" target="_blank" rel="noopener"' : '' ?>>
                <span class="ico"><?= icon('pin') ?></span>
                <span class="kicker">Visit</span>
                <h3><?= e(ADDRESS_LINE1) ?></h3>
                <p><?= e(ADDRESS_LINE2) ?> · Opening soon</p>
                <span class="link">Directions <?= icon('arrow') ?></span>
            </a>
            <a class="c2-way reveal" href="/catering">
                <span class="ico"><?= icon('bag') ?></span>
                <span class="kicker">Catering</span>
                <h3>Feeding a crowd?</h3>
                <p>Coffee boxes, breakfast platters and burger bars for any event.</p>
                <span class="link">Get a quote <?= icon('arrow') ?></span>
            </a>
            <a class="c2-way reveal" href="/careers">
                <span class="ico"><?= icon('users') ?></span>
                <span class="kicker">Careers</span>
                <h3>Join the team</h3>
                <p>We're hiring baristas, cooks and counter crew for opening.</p>
                <span class="link">See roles <?= icon('arrow') ?></span>
            </a>
            <?php if (PHONE): ?>
                <a class="c2-way reveal" href="tel:<?= e(PHONE_TEL) ?>">
                    <span class="ico"><?= icon('phone') ?></span>
                    <span class="kicker">Call</span>
                    <h3><?= e(PHONE) ?></h3>
                    <p>Talk to us directly.</p>
                    <span class="link">Call now <?= icon('arrow') ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section" id="form">
    <div class="wrap c2-grid">
        <div class="c2-side reveal">
            <span class="kicker">Write to us</span>
            <h2>Send a <em>note.</em></h2>
            <p>We read every message and reply within a day or two. For catering, the <a href="/catering">quote form</a> gets you an answer faster.</p>
            <ul class="c2-list">
                <li><span>Hours</span>Coming soon. Coffee all day, breakfast, lunch &amp; dinner.</li>
                <?php if (EMAIL): ?><li><span>Email</span><a href="mailto:<?= e(EMAIL) ?>"><?= e(EMAIL) ?></a></li><?php endif; ?>
                <?php if (INSTAGRAM_URL): ?><li><span>Instagram</span><a href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener"><?= e('@' . basename(rtrim(INSTAGRAM_URL, '/'))) ?></a></li><?php endif; ?>
            </ul>
        </div>
        <div class="contact-card c2-card reveal">
            <?php include __DIR__ . '/contact-form.php'; ?>
        </div>
    </div>
</section>

<?php if (has_address()): ?>
<section class="c2-map">
    <iframe title="Map to <?= e(SITE_NAME) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= urlencode(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) ?>&output=embed"></iframe>
    <div class="c2-map-card">
        <?= logo_mark('c2-map-logo') ?>
        <div><b><?= e(SITE_NAME) ?></b><span><?= e(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) ?></span></div>
    </div>
</section>
<?php endif; ?>
