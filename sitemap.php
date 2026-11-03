<?php
// /sitemap.xml: the public pages, for search engines.
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/xml; charset=utf-8');
$paths = ['/', '/about', MENU_URL === '' ? '/menu' : null, '/order', '/careers', '/contact'];
echo '<?xml version="1.0" encoding="UTF-8"?>', "\n", '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";
foreach (array_filter($paths) as $path) echo '  <url><loc>', e(site_url() . $path), "</loc></url>\n";
echo "</urlset>\n";
