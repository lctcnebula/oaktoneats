<?php
// Vercel entry point: every page request comes here and is handed to the matching page file,
// the same clean URLs .htaccess gives on regular hosting (/about -> about.php).
ini_set('display_errors', '0');
header('Content-Type: text/html; charset=utf-8');
$root = dirname(__DIR__);
chdir($root);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$slug = trim($path, '/');
$slug = ['robots.txt' => 'robots', 'sitemap.xml' => 'sitemap'][$slug] ?? $slug;
$file = $root . '/' . ($slug === '' ? 'index' : $slug) . '.php';

if (preg_match('/^[a-z0-9-]*$/', $slug) && is_file($file) && !in_array($slug, ['router', '404'], true)) {
    require $file;
} else {
    http_response_code(404);
    require $root . '/404.php';
}
