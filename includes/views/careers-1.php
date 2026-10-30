<?php // Careers page body, Home 1 design. Variables come from careers.php. ?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span>Now hiring</span><?= address_link() ?></div>
            <h1>Join the <em>opening team.</em></h1>
        </div>
        <div>
            <p>We're building the crew that opens our doors: people who like good coffee, fresh food and a busy, friendly counter. No experience? We'll teach you.</p>
            <div class="actions"><a class="btn btn-gold" href="#apply">Apply now <?= icon('arrow') ?></a></div>
        </div>
    </div>
    <div class="wrap page-strip">
        <img src="<?= photo('scenes/latte-art.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/griddle.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/counter.jpg') ?>" alt="" loading="lazy">
        <img src="<?= photo('scenes/croissants.jpg') ?>" alt="" loading="lazy">
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Open roles</span><span class="line"></span></div>
        <div class="roles">
            <?php foreach ($roles as $r): ?>
                <a class="role reveal" href="#apply" data-role="<?= e($r['title']) ?>">
                    <span class="ico"><?= icon($r['icon']) ?></span>
                    <h3><?= e($r['title']) ?></h3>
                    <span class="role-type"><?= e($r['type']) ?></span>
                    <p><?= e($r['text']) ?></p>
                    <span class="link">Apply <?= icon('arrow') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <ul class="perks-list reveal">
            <li><?= icon('check') ?> Flexible shifts</li>
            <li><?= icon('check') ?> Free shift meal &amp; coffee</li>
            <li><?= icon('check') ?> Paid training</li>
            <li><?= icon('check') ?> Room to grow</li>
        </ul>
    </div>
</section>

<section class="section" id="apply">
    <div class="wrap contact-grid">
        <div class="reveal">
            <span class="kicker">Apply</span>
            <h2 class="apply-title">Tell us <em>about you.</em></h2>
            <p class="muted">It takes two minutes. Add a resume if you have one; if not, just tell us a little about yourself. We'll reach out to set up a quick chat.</p>
            <figure class="contact-photo"><img src="<?= photo('scenes/seating.jpg') ?>" alt="Inside the cafe" loading="lazy"></figure>
        </div>

        <div class="contact-card reveal">
            <?php include __DIR__ . '/careers-form.php'; ?>
        </div>
    </div>
</section>

<script>
    // Clicking a role card picks it in the form.
    document.querySelectorAll('[data-role]').forEach(function (a) {
        a.addEventListener('click', function () { var s = document.getElementById('role'); if (s) s.value = a.dataset.role; });
    });
</script>

