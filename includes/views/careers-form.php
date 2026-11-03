<?php // Careers form (or its thank-you). Variables come from careers.php. ?>
<?php if ($sent): ?>
    <div class="contact-done">
        <span class="ico"><?= icon('check') ?></span>
        <h2>Thanks, <em>it's in</em></h2>
        <p>If it's a fit, we'll call or email you to set up a chat.</p>
        <a class="btn btn-gold" href="/">Back to home</a>
    </div>
<?php else: ?>
    <?php if ($error): ?><p class="contact-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form class="contact-form" method="post" action="/careers#apply" enctype="multipart/form-data">
        <div class="two">
            <label>Name<input type="text" name="name" value="<?= e($old['name']) ?>" autocomplete="name" required maxlength="80"></label>
            <label>Email<input type="email" name="email" value="<?= e($old['email']) ?>" autocomplete="email" required maxlength="120"></label>
        </div>
        <div class="two">
            <label>Phone<input type="tel" name="phone" value="<?= e($old['phone']) ?>" autocomplete="tel" required maxlength="30"></label>
            <label>Role
                <select name="role" id="role">
                    <?php foreach (array_merge(array_column($roles, 'title'), ['Any role']) as $t): ?><option<?= $t === $old['role'] ? ' selected' : '' ?>><?= e($t) ?></option><?php endforeach; ?>
                </select>
            </label>
        </div>
        <fieldset class="checks">
            <legend>When can you work?</legend>
            <?php foreach ($times as $t): ?><label><input type="checkbox" name="times[]" value="<?= e($t) ?>"<?= in_array($t, $old['times'], true) ? ' checked' : '' ?>> <?= e($t) ?></label><?php endforeach; ?>
        </fieldset>
        <label>About you <span class="opt">(optional)</span><textarea name="about" rows="5" maxlength="2000" placeholder="Past jobs, what you enjoy, anything we should know"><?= e($old['about']) ?></textarea></label>
        <label>Resume <span class="opt">(optional, PDF or Word, up to 4 MB)</span><input type="file" name="resume" accept=".pdf,.doc,.docx"></label>
        <label class="hp" aria-hidden="true">Leave this empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        <button class="btn btn-gold" type="submit">Send application <?= icon('arrow-ur') ?></button>
    </form>
<?php endif; ?>
