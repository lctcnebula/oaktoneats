<?php
// Homepage, template 1. (Template 2, the same page without the top announcement bar, is home-2.php.)
$page = 'home';
require __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/home-hero.php';
include __DIR__ . '/includes/home-sections.php';
require __DIR__ . '/includes/footer.php';
