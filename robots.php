<?php
// /robots.txt: keep the admin out of search and point crawlers at the sitemap.
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: text/plain; charset=utf-8');
echo "User-agent: *\nDisallow: /admin\n\nSitemap: " . site_url() . "/sitemap.xml\n";
