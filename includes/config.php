<?php
// Site-wide settings. Every page reads from here.
// Anything left as '' is hidden on the site until it's filled in.

const SITE_NAME = 'Oakton Eats';
const SITE_TAGLINE = 'Coffee all day · Breakfast, lunch & dinner';
const SITE_URL  = '';            // e.g. 'https://oaktoneats.com' (no trailing slash)

// Thin bar above the header. Leave '' to hide it.
const ANNOUNCEMENT = 'Opening soon · Coffee all day · Breakfast, lunch & dinner';

// The restaurant's clock. The homepage shows breakfast in the morning and lunch & dinner after LUNCH_FROM.
// This uses the restaurant's time zone, not the visitor's computer or the server's.
const TIMEZONE = 'America/Chicago';   // Skokie, IL
const LUNCH_FROM = 11;               // hour (24h) when lunch & dinner take over from breakfast

// Where every "Menu" link goes. Leave '' to use this site's own /menu page.
const MENU_URL = 'https://www.oaktoneats.com/';

const ORDER_URL = '';            // online ordering link (Toast, Square, Clover, etc.)
const DOORDASH_URL = '';
const UBEREATS_URL = '';

const PHONE = '';                // display format, e.g. '(847) 555-1234'
const PHONE_TEL = '';            // dial format, e.g. '+18475551234'
const EMAIL = '';

const ADDRESS_LINE1 = '4949 Oakton St';
const ADDRESS_LINE2 = 'Skokie, IL 60077';
const MAPS_URL = 'https://www.google.com/maps/search/?api=1&query=4949+Oakton+St,+Skokie,+IL+60077';

const INSTAGRAM_URL = '';
const TIKTOK_URL = '';
const FACEBOOK_URL = '';

// Shown on the site exactly as written. Leave empty to show "Hours coming soon".
$HOURS = [
    // ['Mon – Thu', '11am – 10pm'],
    // ['Fri – Sat', '11am – 12am'],
    // ['Sun', '12pm – 9pm'],
];

// Opening soon. Set OPENING_SOON to false once the doors are open.
// OPENING_DATE is optional, e.g. 'Spring 2027' or 'March 14'. Leave '' to just say "Opening soon".
const OPENING_SOON = true;
const OPENING_DATE = '';

// The signup popup. Signups are saved to data/subscribers.csv on the server.
// NOTIFY_EMAIL: if set, each new signup is also emailed here.
const SIGNUP_PERK = 'bonus points on your first order';
const NOTIFY_EMAIL = '';

// Prices are hidden everywhere while this is false. Set to true to show them.
const SHOW_PRICES = false;

// The four menus, in the order they appear on the site.
// Each category in menu-data.php has a 'group' key pointing at one of these.
$GROUPS = [
    'coffee'     => ['title' => 'Coffee',               'hours' => 'All day',         'icon' => 'cup',       'img' => 'iced-coffee.jpg',       'copy' => 'Espresso, lattes, cold brew and iced coffee, pulled to order from open to close.', 'lines' => ['Espresso, lattes & cappuccinos', 'Iced coffee & cold brew', 'Matcha, chai & hot chocolate', 'Hot tea']],
    'breakfast'  => ['title' => 'Breakfast Sandwiches', 'hours' => 'Breakfast',       'icon' => 'croissant', 'img' => 'croissant-egg-cheese.jpg', 'copy' => 'Buttery croissants and toasted brioche stacked with egg and melted cheese.', 'lines' => ['Croissant sandwiches', 'Brioche sandwiches', 'Burritos & wraps']],
    'refreshers' => ['title' => 'Refreshers',           'hours' => 'All day',         'icon' => 'glass',     'img' => 'lemonade.jpg',          'copy' => 'Fresh lemonades, fruit refreshers and iced teas, shaken cold.', 'lines' => ['Fresh lemonades', 'Fruit refreshers', 'Iced teas', 'Sodas & water']],
    'signature'  => ['title' => 'House Signatures',     'hours' => 'Lunch & dinner',  'icon' => 'burger',    'img' => 'hero-burger.jpg',       'copy' => 'The Oakton smash burger, crispy chicken, loaded fries and thick shakes.', 'lines' => ['Smash burgers', 'Crispy chicken', 'Fries & sides', 'Shakes']],
];

$NAV = [
    'home'  => ['label' => 'Home',  'href' => '/'],
    'about' => ['label' => 'About', 'href' => '/about'],
    'menu'  => ['label' => 'Menu',  'href' => MENU_URL !== '' ? MENU_URL : '/menu'],
    'careers' => ['label' => 'Careers', 'href' => '/careers'],
    'contact' => ['label' => 'Contact', 'href' => '/contact'],
];
