<?php
// Homepage, template 1: split hero with photo cards, menu cards, spotlights and bento grid.
$page = 'home';
require_once __DIR__ . '/includes/functions.php';
remember_home('home-1');
require __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/home-hero.php';
include __DIR__ . '/includes/home-sections.php';
require __DIR__ . '/includes/footer.php';
