<?php
// Homepage, template 2: a different layout with the same content (full-bleed hero, menu index, split panels).
// Preview at /home-2. To make it the homepage, swap its two includes into index.php.
$page = 'home';
$noindex = true;
$hideAnnouncement = true;
require __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/home2-hero.php';
include __DIR__ . '/includes/home2-sections.php';
require __DIR__ . '/includes/footer.php';
