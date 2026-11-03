<?php
// Local dev only: mimics the .htaccess clean URLs for `php -S localhost:8123 router.php`.
// Not used on the live server.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path !== '/' && is_file(__DIR__ . $path)) {
    if (str_starts_with($path, '/includes/') || str_starts_with($path, '/data/')) { http_response_code(403); exit; }
    return false;
}

$slug = trim($path, '/');
$moved = ['home-1' => '/', 'home-2' => '/', 'about-2' => '/about'];
if (isset($moved[$slug])) { header('Location: ' . $moved[$slug], true, 301); exit; }
$slug = ['robots.txt' => 'robots', 'sitemap.xml' => 'sitemap'][$slug] ?? $slug;
$file = __DIR__ . '/' . ($slug === '' ? 'index' : $slug) . '.php';
if (preg_match('/^[a-z0-9-]*$/', $slug) && is_file($file) && $slug !== 'router') {
    require $file;
} else {
    require __DIR__ . '/404.php';
}
