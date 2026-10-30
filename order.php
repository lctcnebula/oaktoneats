<?php
$page = 'order';
$title = 'Order';
$description = 'Ordering at Oakton Eats: pickup and delivery open with our doors. Join the list for bonus points and a grand opening invite.';
require __DIR__ . '/includes/header.php';

// Ordering options. Each shows only once its link is set in config.php.
$options = [];
if (ORDER_URL)    $options[] = ['primary' => true,  'icon' => 'bag',   'title' => 'Order Pickup', 'text' => 'Order direct, skip the line and grab it fresh.', 'href' => ORDER_URL, 'go' => 'Start order'];
if (DOORDASH_URL) $options[] = ['primary' => false, 'icon' => 'car',   'title' => 'DoorDash',     'text' => 'Delivered to your door.', 'href' => DOORDASH_URL, 'go' => 'Order on DoorDash'];
if (UBEREATS_URL) $options[] = ['primary' => false, 'icon' => 'car',   'title' => 'Uber Eats',    'text' => 'Delivered to your door.', 'href' => UBEREATS_URL, 'go' => 'Order on Uber Eats'];
if (PHONE)        $options[] = ['primary' => !ORDER_URL, 'icon' => 'phone', 'title' => 'Call it in', 'text' => "Call ahead and we'll have it ready.", 'href' => 'tel:' . PHONE_TEL, 'go' => PHONE];

// One favorite from each menu, in menu order.
$picks = [
    ['Iced Spanish Latte', 'Coffee', ['img' => photo('iced-coffee.jpg')]],
    ['Egg & Cheese Croissant', 'Breakfast', ['img' => photo('croissant-egg-cheese.jpg')]],
    ['Fresh Lemonade', 'Refresher', ['img' => photo('lemonade.jpg')]],
    ['The Oakton', 'House signature', ['img' => photo('hero-burger.jpg')]],
];
?>

<section class="page-hero">
    <div class="wrap">
        <div>
            <div class="soon-row"><span class="soon-pill"><span class="dot"></span>Opening <?= OPENING_DATE !== '' ? e(OPENING_DATE) : 'soon' ?></span><?= address_link() ?></div>
            <h1>Order <em><?= ORDER_URL ? 'ahead.' : 'soon.' ?></em></h1>
        </div>
        <div>
            <p><?= ORDER_URL ? "Coffee, a breakfast sandwich or a double smash. Order ahead and it'll be fresh and ready when you walk in." : 'Pickup and delivery open with our doors. Join the list and we\'ll tell you the moment ordering goes live, plus send your bonus points.' ?></p>
            <div class="actions"><?= order_button() ?></div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:20px">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Ways to order</span><span class="line"></span></div>
        <div class="options">
            <?php if ($options): foreach ($options as $o): ?>
                <a class="option<?= $o['primary'] ? ' primary' : '' ?> reveal" href="<?= e($o['href']) ?>"<?= strpos($o['href'], 'http') === 0 ? ' target="_blank" rel="noopener"' : '' ?>>
                    <span class="ico"><?= icon($o['icon']) ?></span>
                    <h3><?= e($o['title']) ?></h3>
                    <p><?= e($o['text']) ?></p>
                    <span class="go"><?= e($o['go']) ?> <?= icon('arrow-ur') ?></span>
                </a>
            <?php endforeach; else: ?>
                <a class="option primary reveal"<?= notify_attrs() ?>>
                    <span class="ico"><?= icon('bag') ?></span>
                    <h3>Online ordering soon</h3>
                    <p>Pickup and delivery links will be right here as soon as we open.</p>
                    <span class="go">Notify me <?= icon('arrow-ur') ?></span>
                </a>
                <div class="option reveal">
                    <span class="ico"><?= icon('pin') ?></span>
                    <h3>Walk in</h3>
                    <p>Stop by the counter and order fresh once we're open. Details below.</p>
                </div>
                <div class="option reveal">
                    <span class="ico"><?= icon('users') ?></span>
                    <h3>Big order?</h3>
                    <p>Office coffee, team lunches, parties. Reach out ahead and we'll have it ready.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section alt">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">House favorites</span><span class="line"></span></div>
        <div class="head reveal">
            <h2>One from <em>every menu.</em></h2>
            <p>Coffee, breakfast, a refresher and the burger with our name on it.</p>
        </div>
        <div class="cards">
            <?php foreach ($picks as [$name, $badge, $media]) { $it = find_item($name); if ($it) echo photo_card($it, $media, $badge); } ?>
        </div>
    </div>
</section>

<section class="section light">
    <div class="wrap">
        <div class="sec-label"><span class="kicker">Pickup spot</span><span class="line"></span></div>
        <?php include __DIR__ . '/includes/visit.php'; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
