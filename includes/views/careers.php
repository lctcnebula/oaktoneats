<?php // Careers page body. Variables come from careers.php. ?>

<section class="a2-hero k2-hero">
    <img src="<?= photo('scenes/latte-art.jpg') ?>" alt="A barista pouring latte art">
    <div class="wrap a2-hero-copy">
        <span class="kicker">Now hiring · <?= e(ADDRESS_LINE1) ?></span>
        <h1>Make the <em>first pour</em><br>with us.</h1>
        <p>We're building the crew that opens our doors. No experience? We'll teach you.</p>
        <div class="t2-actions"><a class="btn btn-gold" href="#apply">Apply now <?= icon('arrow') ?></a></div>
    </div>
</section>

<section class="section">
    <div class="wrap">
        <div class="t2-head reveal">
            <span class="kicker">Open roles</span>
            <h2>Find your <em>spot.</em></h2>
        </div>
        <div class="t2-index">
            <?php foreach ($roles as $r): ?>
                <a class="t2-row k2-row reveal" href="#apply" data-role="<?= e($r['title']) ?>">
                    <span class="k2-ico"><?= icon($r['icon']) ?></span>
                    <div class="t2-row-title">
                        <h3><?= e($r['title']) ?></h3>
                        <span><?= e($r['type']) ?></span>
                    </div>
                    <p class="k2-text"><?= e($r['text']) ?></p>
                    <span class="t2-go"><?= icon('arrow-ur') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="t2-band k2-band">
    <div class="wrap">
        <div class="k2-perks">
            <div class="reveal"><span class="ico"><?= icon('clock') ?></span><h3>Flexible shifts</h3><p>Mornings, evenings or weekends. Work around school and life.</p></div>
            <div class="reveal"><span class="ico"><?= icon('cup') ?></span><h3>Free shift meal</h3><p>Coffee and a meal on us every shift.</p></div>
            <div class="reveal"><span class="ico"><?= icon('check') ?></span><h3>Paid training</h3><p>Learn espresso, the griddle and the counter from day one.</p></div>
            <div class="reveal"><span class="ico"><?= icon('spark') ?></span><h3>Room to grow</h3><p>Opening crew members are first in line for shift lead.</p></div>
        </div>
    </div>
</section>

<section class="section light" id="apply">
    <div class="wrap contact-grid">
        <div class="reveal">
            <span class="kicker">Apply</span>
            <h2 class="apply-title cat-title">Tell us <em>about you.</em></h2>
            <p>It takes two minutes. Add a resume if you have one; if not, just tell us a little about yourself.</p>
            <figure class="contact-photo"><img src="<?= photo('scenes/griddle.jpg') ?>" alt="A smash burger on the griddle" loading="lazy"></figure>
        </div>
        <div class="contact-card cat-card reveal">
            <?php include __DIR__ . '/careers-form.php'; ?>
        </div>
    </div>
</section>

<script>
    // Clicking a role picks it in the form.
    document.querySelectorAll('[data-role]').forEach(function (a) {
        a.addEventListener('click', function () { var s = document.getElementById('role'); if (s) s.value = a.dataset.role; });
    });
</script>
