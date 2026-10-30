<?php
// Gallery (Home 2 page set): every scene and menu photo, filterable by menu, with a lightbox.
require_once __DIR__ . '/includes/functions.php';
remember_home('home-2');
$page = 'gallery';
$title = 'Gallery';
$description = 'Photos from Oakton Eats: coffee, breakfast sandwiches, refreshers, smash burgers and more.';

$shots = [
    ['img' => 'scenes/spread.jpg',     'group' => '',           'cap' => 'The whole menu, one table', 'wide' => true],
    ['img' => 'scenes/latte-art.jpg',  'group' => 'coffee',     'cap' => 'Latte art, poured to order'],
    ['img' => 'scenes/counter.jpg',    'group' => 'coffee',     'cap' => 'The espresso bar'],
    ['img' => 'iced-coffee.jpg',       'group' => 'coffee',     'cap' => 'Iced Spanish Latte'],
    ['img' => 'scenes/croissants.jpg', 'group' => 'breakfast',  'cap' => 'Croissants out of the oven', 'wide' => true],
    ['img' => 'croissant-egg-cheese.jpg', 'group' => 'breakfast', 'cap' => 'Egg & Cheese Croissant'],
    ['img' => 'scenes/refreshers.jpg', 'group' => 'refreshers', 'cap' => 'Lemonades, shaken cold', 'wide' => true],
    ['img' => 'scenes/matcha.jpg',     'group' => 'coffee',     'cap' => 'Iced matcha'],
    ['img' => 'scenes/griddle.jpg',    'group' => 'signature',  'cap' => 'Smashed on the griddle'],
    ['img' => 'hero-burger.jpg',       'group' => 'signature',  'cap' => 'The Oakton'],
    ['img' => 'scenes/chicken.jpg',    'group' => 'signature',  'cap' => 'Hot honey chicken'],
    ['img' => 'scenes/fries.jpg',      'group' => 'signature',  'cap' => 'Loaded Oakton Fries'],
    ['img' => 'scenes/shakes.jpg',     'group' => 'signature',  'cap' => 'Hand-spun shakes', 'wide' => true],
    ['img' => 'scenes/seating.jpg',    'group' => '',           'cap' => 'Pull up a chair'],
    ['img' => 'scenes/storefront.jpg', 'group' => '',           'cap' => 'Opening soon on Oakton St', 'wide' => true],
];
// Then every menu item that has its own photo.
$seen = array_column($shots, 'img');
foreach ($MENU as $cat) {
    foreach ($cat['items'] as $item) {
        $img = item_img($item);
        if ($img === '' || in_array($img, $seen, true)) continue;
        $shots[] = ['img' => $img, 'group' => $cat['group'], 'cap' => $item['name']];
        $seen[] = $img;
    }
}
$shots = array_values(array_filter($shots, fn($s) => is_file(__DIR__ . '/assets/img/photos/' . $s['img'])));
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span>Opening soon</span><?= address_link() ?></div>
            <h1>A look <em>inside.</em></h1>
        </div>
        <div>
            <p>Coffee, croissants, refreshers and smash burgers: a taste of what's coming to Oakton St.</p>
            <div class="actions"><?= order_button() ?></div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:10px">
    <div class="wrap">
        <div class="part-filter gal-filters" role="group" aria-label="Filter photos">
            <button type="button" data-gal="" aria-pressed="true">Everything</button>
            <?php foreach ($GROUPS as $key => $g): ?><button type="button" data-gal="<?= e($key) ?>" aria-pressed="false"><?= e($key === 'breakfast' ? 'Breakfast' : $g['title']) ?></button><?php endforeach; ?>
        </div>
        <div class="gal">
            <?php foreach ($shots as $i => $s): ?>
                <button class="gal-item<?= !empty($s['wide']) ? ' wide' : '' ?>" data-group="<?= e($s['group']) ?>" data-full="<?= e(photo($s['img'])) ?>" data-cap="<?= e($s['cap']) ?>">
                    <img src="<?= photo($s['img']) ?>" alt="<?= e($s['cap']) ?>" loading="<?= $i < 6 ? 'eager' : 'lazy' ?>">
                    <span><?= e($s['cap']) ?></span>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<div class="lightbox" id="lightbox" hidden>
    <button class="lb-close" aria-label="Close"><?= icon('close') ?></button>
    <figure><img alt=""><figcaption></figcaption></figure>
</div>

<script>
(function () {
    var items = [].slice.call(document.querySelectorAll('.gal-item'));
    document.querySelectorAll('[data-gal]').forEach(function (b) {
        b.addEventListener('click', function () {
            document.querySelectorAll('[data-gal]').forEach(function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
            items.forEach(function (it) { it.hidden = b.dataset.gal !== '' && it.dataset.group !== b.dataset.gal; });
        });
    });
    var lb = document.getElementById('lightbox'), img = lb.querySelector('img'), cap = lb.querySelector('figcaption');
    function close() { lb.hidden = true; document.body.classList.remove('modal-open'); }
    items.forEach(function (it) {
        it.addEventListener('click', function () {
            img.src = it.dataset.full; img.alt = it.dataset.cap; cap.textContent = it.dataset.cap;
            lb.hidden = false; document.body.classList.add('modal-open');
        });
    });
    lb.addEventListener('click', function (e) { if (e.target === lb || e.target.closest('.lb-close')) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) close(); });
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
