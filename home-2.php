<?php
// Homepage, template 2: a different layout with the same content (full-bleed hero, menu index, split panels).
$page = 'home';
require_once __DIR__ . '/includes/functions.php';
remember_home('home-2');
$hideAnnouncement = true;
require __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/home2-hero.php';
include __DIR__ . '/includes/home2-sections.php';
require __DIR__ . '/includes/footer.php';
