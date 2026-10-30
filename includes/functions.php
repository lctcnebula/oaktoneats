<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/menu-data.php';
require_once __DIR__ . '/media.php';
require_once __DIR__ . '/db.php';

date_default_timezone_set(TIMEZONE);

function e(?string $s): string {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

// Cache-busted asset URL, so updated CSS/JS reaches visitors right away.
function asset(string $path): string {
    $file = dirname(__DIR__) . '/assets/' . $path;
    $v = is_file($file) ? filemtime($file) : '1';
    return '/assets/' . $path . '?v=' . $v;
}

function site_url(): string {
    if (SITE_URL !== '') return SITE_URL;
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
}

function money(float $n): string {
    return '$' . number_format($n, 2);
}

// '' while prices are switched off (SHOW_PRICES in config.php).
function price_label($price): string {
    if (!SHOW_PRICES) return '';
    return is_array($price) ? 'from ' . money(min($price)) : money($price);
}

// Where "Order" buttons go: online ordering if set, otherwise the order page.
function order_href(): string {
    return ORDER_URL !== '' ? ORDER_URL : '/order';
}
function order_attrs(): string {
    return ORDER_URL !== '' ? ' target="_blank" rel="noopener"' : '';
}

function has_address(): bool { return ADDRESS_LINE1 !== ''; }

// A link to the menu (or one of the four menus on it): MENU_URL when set, otherwise /menu.
function menu_href(string $group = ''): string {
    return MENU_URL !== '' ? MENU_URL : '/menu' . ($group !== '' ? '#' . $group : '');
}

// The address as a small map link with a pin, for the heroes. Empty until ADDRESS_LINE1 is set.
function address_link(): string {
    if (!has_address()) return '';
    $text = icon('pin') . '<span>' . e(ADDRESS_LINE1 . ', ' . ADDRESS_LINE2) . '</span>';
    return MAPS_URL ? '<a class="addr" href="' . e(MAPS_URL) . '" target="_blank" rel="noopener">' . $text . '</a>' : '<span class="addr">' . $text . '</span>';
}

// 'breakfast' or 'signature', by the restaurant's clock. Add ?at=8 or ?at=18 to a URL to preview either one.
function serving_now(): string {
    $hour = isset($_GET['at']) && ctype_digit($_GET['at']) ? (int) $_GET['at'] : (int) (new DateTime('now', new DateTimeZone(TIMEZONE)))->format('G');
    return $hour < LUNCH_FROM ? 'breakfast' : 'signature';
}

function photo(string $file): string {
    return '/assets/img/photos/' . $file;
}

function find_item(string $name): ?array {
    global $MENU;
    foreach ($MENU as $cat) {
        foreach ($cat['items'] as $item) {
            if (strcasecmp($item['name'], $name) === 0) {
                return $item + ['category' => $cat['name'], 'group' => $cat['group']];
            }
        }
    }
    return null;
}

function menu_count(): int {
    global $MENU;
    return array_sum(array_map(fn($c) => count($c['items']), $MENU));
}

function categories_for(string $group): array {
    global $MENU;
    return array_values(array_filter($MENU, fn($c) => $c['group'] === $group));
}

function icon(string $name): string {
    $paths = [
        'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'arrow-ur'  => '<path d="M7 17L17 7M8 7h9v9"/>',
        'pin'       => '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
        'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'bag'       => '<path d="M6 7h12l1 13H5L6 7z"/><path d="M9 7a3 3 0 0 1 6 0"/>',
        'search'    => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'check'     => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'sun'       => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'cup'       => '<path d="M4 9h13v4a6 6 0 0 1-6 6h-1a6 6 0 0 1-6-6V9z"/><path d="M17 11h1.5a2.5 2.5 0 0 1 0 5H17M8 3c-.5 1 .5 2 0 3M12 3c-.5 1 .5 2 0 3"/>',
        'burger'    => '<path d="M4 11a8 6 0 0 1 16 0z"/><path d="M3.5 14.5h17"/><path d="M4 17.5h16a0 0 0 0 1 0 0 2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/>',
        'croissant' => '<path d="M4 15c2-6 6-9 8-9s6 3 8 9c-2 1-4 1-5-1-1 2-5 2-6 0-1 2-3 2-5 1z"/><path d="M9.5 8.5l1.5 5M14.5 8.5l-1.5 5"/>',
        'flame'     => '<path d="M12 3c1 4 5 5.5 5 10a5 5 0 0 1-10 0c0-2 1-3.5 2-4.5.3 1.6 1.2 2.5 2 2.5-1-3 0-6 1-8z"/>',
        'hand'      => '<path d="M8 13V6a1.5 1.5 0 0 1 3 0v5M11 11V4.5a1.5 1.5 0 0 1 3 0V11M14 11V6a1.5 1.5 0 0 1 3 0v7a7 7 0 0 1-7 7h-.5A5.5 5.5 0 0 1 5 16l-1.4-3.2a1.5 1.5 0 0 1 2.6-1.4L8 13"/>',
        'users'     => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6 6 0 0 1 3.5 6"/>',
        'car'       => '<path d="M5 16V11l2-5h10l2 5v5M5 16h14M5 16v2M19 16v2"/><circle cx="8" cy="13" r="1"/><circle cx="16" cy="13" r="1"/>',
        'spark'     => '<path d="M12 2v20M2 12h20M5 5l14 14M19 5L5 19"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>',
        'facebook'  => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v2H7v4h3v7h4v-7h3l1-4h-4V8z"/>',
        'tiktok'    => '<path d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5M14 3c.5 2.5 2.5 4.5 5 4.5"/>',
        'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'glass'     => '<path d="M6 4h12l-1.5 15a2 2 0 0 1-2 1.8h-5a2 2 0 0 1-2-1.8L6 4z"/><path d="M6.6 10h10.8"/><path d="M14 4l2-2.5"/>',
        'bell'      => '<path d="M6 16V11a6 6 0 0 1 12 0v5l1.5 2h-15L6 16z"/><path d="M10 20a2 2 0 0 0 4 0"/>',
        'gift'      => '<rect x="3.5" y="8" width="17" height="4" rx="1"/><path d="M5 12v8h14v-8M12 8v12"/><path d="M12 8c-1.5-3-5-3.5-5-1.2C7 8 9 8 12 8zM12 8c1.5-3 5-3.5 5-1.2C17 8 15 8 12 8z"/>',
        'close'     => '<path d="M6 6l12 12M18 6L6 18"/>',
    ];
    return '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">'
        . ($paths[$name] ?? '') . '</svg>';
}

// Brand logo: the gold icon plus the OAKTON / EATS wordmark.
function logo_mark(string $class = 'logo-mark'): string {
    return '<img class="' . e($class) . '" src="/assets/img/logo-icon.png" alt="" width="270" height="372">';
}
// Remembers which homepage template the visitor opened, so "Home" and the logo lead back to it.
function remember_home(string $slug): void {
    if (($_COOKIE['oe_home'] ?? '') !== $slug && !headers_sent()) {
        setcookie('oe_home', $slug, ['expires' => time() + 31536000, 'path' => '/', 'samesite' => 'Lax']);
    }
    $_COOKIE['oe_home'] = $slug;
}

function is_home2(): bool {
    return HOME_CHOOSER && ($_COOKIE['oe_home'] ?? '') === 'home-2';
}

// Home 2 has its own page set: a different About, plus Gallery and Catering.
function site_nav(): array {
    global $NAV;
    $nav = $NAV;
    $nav['home']['href'] = home_href();
    if (!is_home2()) return $nav;
    $nav['about']['href'] = '/about-2';
    $out = [];
    foreach ($nav as $k => $item) {
        $out[$k] = $item;
        if ($k === 'menu') {
            $out['gallery']  = ['label' => 'Gallery',  'href' => '/gallery'];
            $out['catering'] = ['label' => 'Catering', 'href' => '/catering'];
        }
    }
    return $out;
}

function home_href(): string {
    if (!HOME_CHOOSER) return '/';
    $home = $_COOKIE['oe_home'] ?? '';
    return in_array($home, ['home-1', 'home-2'], true) ? '/' . $home : '/';
}

function logo(): string {
    return '<a class="logo" href="' . e(home_href()) . '" aria-label="' . e(SITE_NAME) . ' home">' . logo_mark()
        . '<span class="logo-word"><b>Oakton</b><span>Eats</span></span></a>';
}

// Attributes for any link that opens the opening-soon popup.
function notify_attrs(): string {
    return ' href="#notify" data-notify';
}

// Every main button: "Order Now" to online ordering once ORDER_URL is set; until then "Get Notified", which opens the opening-soon popup.
function order_button(string $class = 'btn btn-gold'): string {
    $attrs = ORDER_URL !== '' ? ' href="' . e(ORDER_URL) . '"' . order_attrs() : notify_attrs();
    return '<a class="' . e($class) . '"' . $attrs . '>' . (ORDER_URL !== '' ? 'Order Now ' . icon('arrow-ur') : 'Get Notified ' . icon('bell')) . '</a>';
}

// A row in the menu page's two-column list.
// An item's photo: its own 'img', or a generated one in photos/items/<name>.jpg (see tools/make-photos.php).
function item_img(array $item): string {
    if (!empty($item['img'])) return $item['img'];
    $file = 'items/' . item_slug($item['name']) . '.jpg';
    return is_file(dirname(__DIR__) . '/assets/img/photos/' . $file) ? $file : '';
}

function item_slug(string $name): string {
    return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower(str_replace(['&', 'é', 'ñ'], ['and', 'e', 'n'], $name))), '-');
}

function menu_item(array $item): string {
    $sizes = is_array($item['price']) ? $item['price'] : null;
    $img = item_img($item);
    $thumb = $img !== '';
    ob_start(); ?>
    <div class="item<?= $thumb ? ' has-thumb' : '' ?>" data-name="<?= e(strtolower($item['name'] . ' ' . ($item['tag'] ?? '') . ' ' . ($item['desc'] ?? ''))) ?>">
        <?php if ($thumb): ?><img class="thumb" src="<?= e(photo($img)) ?>" alt="" loading="lazy" width="64" height="64"><?php endif; ?>
        <div>
            <div class="row">
                <h4><?= e($item['name']) ?><?php if (!empty($item['tag'])): ?><span class="tag"><?= e($item['tag']) ?></span><?php endif; ?></h4>
                <?php if (!$sizes && SHOW_PRICES): ?><span class="dots"></span><span class="price"><?= money($item['price']) ?></span><?php endif; ?>
            </div>
            <?php if (!empty($item['desc'])): ?><p><?= e($item['desc']) ?></p><?php endif; ?>
            <?php if ($sizes): ?>
                <div class="sizes"><?php foreach ($sizes as $s => $p): ?><div><span><?= e($s) ?></span><?php if (SHOW_PRICES): ?><b><?= money($p) ?></b><?php endif; ?></div><?php endforeach; ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php return ob_get_clean();
}

// A menu's general lines ($GROUPS[...]['lines']), styled like item_list.
function group_list(string $group): string {
    global $GROUPS;
    $out = '<ul class="plist">';
    foreach ($GROUPS[$group]['lines'] as $line) {
        $out .= '<li><div class="row"><span class="name">' . e($line) . '</span></div></li>';
    }
    return $out . '</ul>';
}

// A short list of items for the homepage spotlights (prices only when SHOW_PRICES is on).
function item_list(array $names): string {
    $out = '<ul class="plist">';
    foreach ($names as $name) {
        $i = find_item($name);
        if (!$i) continue;
        $out .= '<li><div class="row"><span class="name">' . e($i['name'])
            . (!empty($i['tag']) ? '<span class="tag">' . e($i['tag']) . '</span>' : '')
            . '</span>' . (SHOW_PRICES ? '<span class="dots"></span><span class="price">' . e(price_label($i['price'])) . '</span>' : '') . '</div>'
            . (!empty($i['desc']) ? '<div class="desc">' . e($i['desc']) . '</div>' : '') . '</li>';
    }
    return $out . '</ul>';
}

// A photo card. $media: ['img' => url] or ['video' => url, 'poster' => url].
function photo_card(array $item, array $media, string $badge = ''): string {
    ob_start(); ?>
    <a class="card reveal" href="<?= e(menu_href($item['group'] ?? '')) ?>">
        <div class="card-img">
            <?php if (!empty($media['video'])): ?>
                <video muted loop playsinline preload="none" poster="<?= e($media['poster']) ?>" data-autoplay><source src="<?= e($media['video']) ?>" type="video/mp4"></video>
            <?php else: ?>
                <img src="<?= e($media['img']) ?>" alt="<?= e($item['name']) ?>" loading="lazy">
            <?php endif; ?>
            <?php if ($badge): ?><span class="badge"><?= e($badge) ?></span><?php endif; ?>
        </div>
        <div class="card-body">
            <span class="cat"><?= e($item['category'] ?? '') ?></span>
            <h3><?= e($item['name']) ?></h3>
            <?php if (!empty($item['desc'])): ?><p><?= e($item['desc']) ?></p><?php endif; ?>
            <div class="card-foot"><span class="price"><?= e(price_label($item['price'])) ?></span><span class="go">See menu <?= icon('arrow-ur') ?></span></div>
        </div>
    </a>
    <?php return ob_get_clean();
}
