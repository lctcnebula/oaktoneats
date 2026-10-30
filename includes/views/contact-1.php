<?php // Contact page body, Home 1 design. Variables come from contact.php. ?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span>Opening <?= OPENING_DATE !== '' ? e(OPENING_DATE) : 'soon' ?></span><?= address_link() ?></div>
            <h1>Say <em>hello.</em></h1>
        </div>
        <div>
            <p>Questions, catering, jobs or just want to know when we open? Send us a note and we'll get back to you.</p>
            <div class="actions"><?= order_button() ?></div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:20px">
    <div class="wrap contact-grid">
        <div class="contact-info reveal">
            <figure class="contact-photo">
                <img src="<?= photo('scenes/counter.jpg') ?>" alt="Our espresso bar and pastry case" loading="lazy">
            </figure>
            <ul class="visit-list">
                <li><span class="k">Address</span><span><?= has_address() ? (MAPS_URL ? '<a href="' . e(MAPS_URL) . '" target="_blank" rel="noopener">' : '') . e(ADDRESS_LINE1) . ', ' . e(ADDRESS_LINE2) . (MAPS_URL ? '</a>' : '') : 'Opening soon.' ?></span></li>
                <?php if (PHONE): ?><li><span class="k">Phone</span><a href="tel:<?= e(PHONE_TEL) ?>"><?= e(PHONE) ?></a></li><?php endif; ?>
                <?php if (EMAIL): ?><li><span class="k">Email</span><a href="mailto:<?= e(EMAIL) ?>"><?= e(EMAIL) ?></a></li><?php endif; ?>
                <li><span class="k">Hours</span><span>Coming soon. Coffee all day, breakfast, lunch &amp; dinner.</span></li>
                <?php if (INSTAGRAM_URL): ?><li><span class="k">Instagram</span><a href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener"><?= e('@' . basename(rtrim(INSTAGRAM_URL, '/'))) ?></a></li><?php endif; ?>
            </ul>
            <?php if (MAPS_URL): ?><a class="btn btn-line" href="<?= e(MAPS_URL) ?>" target="_blank" rel="noopener"><?= icon('pin') ?> Directions</a><?php endif; ?>
        </div>

        <div class="contact-card reveal" id="form">
            <?php include __DIR__ . '/contact-form.php'; ?>
        </div>
    </div>
</section>

<?php if (has_address()): ?>
<section class="contact-map">
    <iframe title="Map to <?= e(SITE_NAME) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= urlencode(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) ?>&output=embed"></iframe>
</section>
<?php endif; ?>

