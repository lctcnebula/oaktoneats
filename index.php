<?php
// The site address (/). With HOME_CHOOSER on, it's a page to pick Home 1 or Home 2;
// whichever one is opened becomes "Home" for that visitor. With it off, it shows Home 1.
require_once __DIR__ . '/includes/functions.php';
if (!HOME_CHOOSER) { require __DIR__ . '/home-1.php'; return; }

$homes = [
    'home-1' => ['name' => 'Home 1', 'text' => 'Split hero with photo cards, menu cards, coffee and breakfast spotlights, and a bento grid.'],
    'home-2' => ['name' => 'Home 2', 'text' => 'Full-screen photo hero, an editorial menu list, side-by-side panels and a gold refreshers band.'],
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(SITE_NAME) ?> | Choose a homepage</title>
    <meta name="robots" content="noindex">
    <link rel="icon" href="/assets/img/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600&family=Marcellus&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?= filemtime(__DIR__ . '/assets/css/style.css') ?>">
</head>
<body class="chooser">
    <main class="wrap">
        <div class="chooser-head">
            <?= logo_mark('chooser-logo') ?>
            <span class="kicker">Oakton Eats</span>
            <h1>Pick a <em>homepage.</em></h1>
            <p>Two layouts, same menu and content. Open one and it becomes Home as you browse the site.</p>
        </div>
        <div class="chooser-grid">
            <?php foreach ($homes as $slug => $h): ?>
                <a class="chooser-card" href="/<?= e($slug) ?>">
                    <div class="chooser-shot"><img src="/assets/img/templates/<?= e($slug) ?>.jpg" alt="Preview of <?= e($h['name']) ?>"></div>
                    <div class="chooser-body">
                        <h2><?= e($h['name']) ?></h2>
                        <p><?= e($h['text']) ?></p>
                        <span class="btn btn-gold">Open <?= e($h['name']) ?> <?= icon('arrow-ur') ?></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
