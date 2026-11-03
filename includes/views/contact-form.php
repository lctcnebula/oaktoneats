<?php // Contact form (or its thank-you). Variables come from contact.php. ?>
<?php if ($sent): ?>
    <div class="contact-done">
        <span class="ico"><?= icon('check') ?></span>
        <h2>Thanks, <em>we got it</em></h2>
        <p>We'll reply by email. While you're here, join the opening list for <?= e(SIGNUP_PERK) ?>.</p>
        <?= order_button() ?>
    </div>
<?php else: ?>
    <?php if ($error): ?><p class="contact-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form class="contact-form" method="post" action="/contact#form">
        <div class="two">
            <label>Name<input type="text" name="name" value="<?= e($old['name']) ?>" autocomplete="name" required maxlength="80"></label>
            <label>Email<input type="email" name="email" value="<?= e($old['email']) ?>" autocomplete="email" required maxlength="120"></label>
        </div>
        <div class="two">
            <label>Phone (optional)<input type="tel" name="phone" value="<?= e($old['phone']) ?>" autocomplete="tel" maxlength="30"></label>
            <label>Topic
                <select name="topic">
                    <?php foreach ($topics as $t): ?><option<?= $t === $old['topic'] ? ' selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
                </select>
            </label>
        </div>
        <label>Message<textarea name="message" rows="6" required maxlength="2000"><?= e($old['message']) ?></textarea></label>
        <label class="hp" aria-hidden="true">Leave this empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        <button class="btn btn-gold" type="submit">Send message <?= icon('arrow-ur') ?></button>
    </form>
<?php endif; ?>
