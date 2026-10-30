<?php
// Photos and video clips used around the site, in one place.
// Clips live in /assets/video as clip-N.mp4 with a matching clip-N.jpg poster.

const PHOTO_HERO  = '/assets/img/photos/hero-burger.jpg';   // double smash, dark background
const PHOTO_ABOUT = '/assets/img/photos/hero-burger.jpg';   // smash burger on black

// "Behind the counter" reels, in display order: a clip number or a photo, with a caption.
// Mixed across all four menus so it isn't only burgers.
$REELS = [
    ['img' => 'scenes/latte-art.jpg',  'label' => 'Espresso Bar'],
    ['clip' => 4,                'label' => 'On the Griddle'],
    ['img' => 'scenes/croissants.jpg', 'label' => 'Morning Croissants'],
    ['img' => 'scenes/refreshers.jpg', 'label' => 'Shaken Cold'],
    ['clip' => 5,                'label' => 'Burger & Fries'],
];

function clip(int $n): ?array {
    $base = dirname(__DIR__) . '/assets/video/clip-' . $n;
    if (!is_file($base . '.mp4')) return null;
    return ['video' => '/assets/video/clip-' . $n . '.mp4', 'poster' => '/assets/video/clip-' . $n . '.jpg'];
}

function reels(int $max = 5): array {
    global $REELS;
    $out = [];
    foreach ($REELS as $r) {
        if (isset($r['clip'])) {
            if ($c = clip($r['clip'])) $out[] = $c + ['label' => $r['label']];
        } elseif (is_file(dirname(__DIR__) . '/assets/img/photos/' . $r['img'])) {
            $out[] = ['img' => photo($r['img']), 'label' => $r['label']];
        }
        if (count($out) >= $max) break;
    }
    return $out;
}

// A reel's video, or its photo.
function reel_media(array $r): string {
    if (isset($r['video'])) {
        return '<video muted loop playsinline preload="none" poster="' . e($r['poster']) . '" data-autoplay><source src="' . e($r['video']) . '" type="video/mp4"></video>';
    }
    return '<img src="' . e($r['img']) . '" alt="" loading="lazy">';
}
