<?php // Opening-soon popup. Opened by every "Get Notified" button (until ORDER_URL is set), and once on its own for new visitors. ?>
<div class="notify" id="notify" role="dialog" aria-modal="true" aria-labelledby="notify-title" hidden>
    <div class="notify-backdrop" data-notify-close></div>
    <div class="notify-box">
        <button class="notify-x" type="button" aria-label="Close" data-notify-close><?= icon('close') ?></button>
        <div class="notify-art" aria-hidden="true">
            <?= logo_mark('notify-logo') ?>
            <span class="soon-pill"><span class="dot"></span>Opening <?= OPENING_DATE !== '' ? e(OPENING_DATE) : 'soon' ?></span>
        </div>
        <div class="notify-body">
            <h2 id="notify-title">Get the <em>opening date</em></h2>
            <p class="notify-lede">Online ordering starts the day we open. Leave your email and we'll let you know.</p>
            <ul class="perks">
                <li><span class="ico"><?= icon('gift') ?></span><div><b>Bonus points</b><span>Sign up now and get <?= e(SIGNUP_PERK) ?>.</span></div></li>
                <li><span class="ico"><?= icon('bell') ?></span><div><b>Grand opening invite</b><span>You'll hear the date before we announce it anywhere else.</span></div></li>
            </ul>
            <form class="notify-form" action="/subscribe" method="post" data-notify-form>
                <label><span class="sr-only">Your name</span><input type="text" name="name" placeholder="Your name" autocomplete="name" maxlength="80"></label>
                <label><span class="sr-only">Email</span><input type="email" name="email" placeholder="Email address" autocomplete="email" required maxlength="120"></label>
                <label><span class="sr-only">Phone (optional)</span><input type="tel" name="phone" placeholder="Phone (optional, for text alerts)" autocomplete="tel" maxlength="30"></label>
                <label class="hp" aria-hidden="true">Leave this empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                <button class="btn btn-gold" type="submit">Notify me <?= icon('arrow-ur') ?></button>
                <p class="notify-msg" role="status" aria-live="polite"></p>
                <p class="notify-fine">We'll only email about the opening and your points.</p>
            </form>
        </div>
    </div>
</div>
