<?php
// Expects: $page (nav key), $title, $description.
require_once __DIR__ . '/functions.php';
$page        = $page ?? '';
$NAV = site_nav();
if (is_home2()) $hideAnnouncement = true; // Home 2 and its pages have no top bar
$fullTitle   = isset($title) ? $title . ' | ' . SITE_NAME : SITE_NAME . ' | Coffee All Day, Breakfast, Lunch & Dinner';
$description = $description ?? 'Oakton Eats, opening soon: coffee all day, breakfast sandwiches, refreshers and our house signature smash burgers for lunch and dinner.';
$canonical   = site_url() . ($NAV[$page]['href'] ?? (strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/'));

$schema = [
    '@context' => 'https://schema.org',
    '@type' => ['Restaurant', 'CafeOrCoffeeShop'],
    'name' => SITE_NAME,
    'url' => site_url(),
    'image' => site_url() . '/assets/img/photos/hero-burger.jpg',
    'servesCuisine' => ['Coffee', 'Breakfast', 'Burgers', 'American'],
    'priceRange' => '$',
    'hasMenu' => MENU_URL !== '' ? MENU_URL : site_url() . '/menu',
];
if (PHONE !== '') $schema['telephone'] = PHONE;
if (has_address()) $schema['address'] = ['@type' => 'PostalAddress', 'streetAddress' => ADDRESS_LINE1, 'addressCountry' => 'US'] + (preg_match('/^(.+), ([A-Z]{2}) (\d{5})$/', ADDRESS_LINE2, $m) ? ['addressLocality' => $m[1], 'addressRegion' => $m[2], 'postalCode' => $m[3]] : ['addressLocality' => ADDRESS_LINE2]);
$social = array_values(array_filter([INSTAGRAM_URL, TIKTOK_URL, FACEBOOK_URL]));
if ($social) $schema['sameAs'] = $social;

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($fullTitle) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta name="theme-color" content="#0b0a08">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
    <meta property="og:title" content="<?= e($fullTitle) ?>">
    <meta property="og:description" content="<?= e($description) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e(site_url()) ?>/assets/img/photos/hero-burger.jpg">

    <link rel="icon" type="image/png" href="/assets/img/favicon.png">
    <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Marcellus&family=Instrument+Serif:ital@0;1&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">

<?php if (!empty($noindex)): ?>
    <meta name="robots" content="noindex">
<?php endif; ?>
    <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body class="page-<?= e($page ?: 'other') ?>">
<a class="skip" href="#main">Skip to content</a>

<?php if (ANNOUNCEMENT !== '' && empty($hideAnnouncement)): ?>
    <div class="announce"><?= e(ANNOUNCEMENT) ?></div>
<?php endif; ?>

<header class="site-header" id="top">
    <div class="wrap header-inner">
        <?= logo() ?>

        <nav class="main-nav" aria-label="Main">
            <?php foreach ($NAV as $k => $item): ?>
                <a href="<?= e($item['href']) ?>"<?= $k === $page ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="header-right">
            <?= order_button('btn btn-gold btn-sm') ?>
            <button class="nav-toggle" type="button" aria-controls="mobile-nav" aria-expanded="false" aria-label="Open menu"><?= icon('menu') ?></button>
        </div>
    </div>
    <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile">
        <?php foreach ($NAV as $k => $item): ?>
            <a href="<?= e($item['href']) ?>"<?= $k === $page ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a>
        <?php endforeach; ?>
        <?= order_button('btn btn-gold') ?>
    </nav>
</header>

<main id="main">
