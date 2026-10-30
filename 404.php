<?php
http_response_code(404);
$page = '';
$title = 'Page Not Found';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero" style="padding-block:clamp(110px,14vw,180px)">
    <div class="wrap">
        <div>
            <span class="kicker">404</span>
            <h1>This page got <em>eaten.</em></h1>
        </div>
        <div>
            <p>Whatever was here is gone. The coffee and burgers aren't, though.</p>
            <div class="actions">
                <a class="btn btn-gold" href="/">Back home</a>
                <a class="btn btn-line" href="<?= e(menu_href()) ?>">See the menu</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
