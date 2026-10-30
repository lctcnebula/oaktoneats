<?php
// Generates a photo for every menu item with OpenAI's image model and saves it to
// assets/img/photos/items/<item-name>.jpg. The menu page picks these up on its own.
// Items that already have a photo (their own 'img' or a saved file) are skipped,
// so it's safe to re-run. Delete a file to make a new one. Paid per image.
//
// Run from the project folder:
//   OPENAI_API_KEY=... php tools/make-photos.php
//   OPENAI_API_KEY=... php tools/make-photos.php "Mocha"     (just one item)
//   OPENAI_API_KEY=... php tools/make-photos.php --all       (redo items that have their own photo too)
//   OPENAI_API_KEY=... php tools/make-photos.php --scenes    (wide cafe scenes used around the site, in photos/scenes/)

if (PHP_SAPI !== 'cli') exit;
$key = getenv('OPENAI_API_KEY') ?: exit("Set OPENAI_API_KEY first.\n");
$root = dirname(__DIR__);
require $root . '/includes/config.php';
require $root . '/includes/menu-data.php';
require $root . '/includes/functions.php';

$only = null; $all = false; $scenes = false;
foreach (array_slice($argv, 1) as $a) { if ($a === '--all') $all = true; elseif ($a === '--scenes') $scenes = true; else $only = $a; }

// Asks the image model for one picture and saves it as a JPEG at the given width.
function generate(string $key, string $prompt, string $size, string $file, int $width): bool {
    $ch = curl_init('https://api.openai.com/v1/images/generations');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 240,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $key, 'Content-Type: application/json'],
        CURLOPT_POSTFIELDS => json_encode(['model' => 'gpt-image-2', 'prompt' => $prompt, 'size' => $size, 'quality' => 'medium']),
    ]);
    $res = json_decode((string) curl_exec($ch), true);
    $b64 = $res['data'][0]['b64_json'] ?? null;
    if (!$b64) { echo 'failed: ' . ($res['error']['message'] ?? 'no image returned') . "\n"; return false; }
    $src = imagecreatefromstring(base64_decode($b64));
    imagejpeg(imagescale($src, $width), $file, 82);
    echo "saved\n";
    return true;
}

if ($scenes) {
    $look = 'Editorial photograph for a modern cafe website. Warm golden light, black and gold tones, rich shadows, realistic, shallow depth of field. No text, no signs with words, no logos, no faces. No pork, ham or bacon.';
    $SCENES = [
        'counter'    => 'Inside a modern all-day cafe: a long black counter with a gold espresso machine, a glass case of croissants, warm pendant lights.',
        'latte-art'  => 'A barista\'s hands pouring a rosetta of latte art into a black ceramic cup, steam rising.',
        'croissants' => 'A tray of golden butter croissants fresh from the oven next to egg and cheese croissant sandwiches being assembled.',
        'refreshers' => 'Three tall glasses on a dark counter: fresh lemonade with mint, mango lemonade and strawberry lemonade, ice and condensation.',
        'griddle'    => 'A beef smash burger patty pressed onto a hot flat-top griddle with a steel spatula, crispy lacy edges, a slice of cheese melting.',
        'spread'     => 'Overhead view of a dark wood table with an iced latte, an egg and cheese croissant, a double smash burger, fries in a paper basket and a lemonade.',
        'shakes'     => 'Three thick milkshakes topped with whipped cream: vanilla, chocolate and strawberry, on a dark counter.',
        'chicken'    => 'A crispy fried chicken sandwich glazed with hot honey, pickles and slaw on a toasted bun, on parchment paper.',
        'storefront' => 'The glowing front window of a small cafe at dusk on a city street corner, warm gold light inside, people-free.',
        'to-go'      => 'A pickup order waiting on a dark counter: a kraft paper bag, an iced latte in a clear cup and a wrapped sandwich, ready to grab.',
        'seating'    => 'Cozy seating inside a modern cafe: dark wood tables, black chairs, warm brass lamps and plants by a big window, empty and inviting.',
        'fries'      => 'Loaded fries in a paper boat topped with cheese sauce, chopped beef, grilled onions and jalapenos, on a dark counter.',
        'matcha'     => 'An iced matcha latte in a tall clear glass, green matcha swirling into milk over ice, next to a whisk.',
    ];
    $dir = $root . '/assets/img/photos/scenes';
    @mkdir($dir, 0755, true);
    foreach ($SCENES as $name => $prompt) {
        if ($only !== null && $only !== $name) continue;
        $file = "$dir/$name.jpg";
        if ($only === null && is_file($file)) continue;
        echo "$name ... ";
        generate($key, "$prompt $look", '1536x1024', $file, 1400);
    }
    exit;
}

$style = 'Professional food photograph for a cafe menu. One serving, centered, close-up, shot from a slight angle. '
       . 'Dark moody background, warm golden light, shallow depth of field, appetizing and realistic. '
       . 'No text, no logos, no hands, no people. No pork, ham or bacon.';
$outDir = $root . '/assets/img/photos/items';
@mkdir($outDir, 0755, true);

foreach ($MENU as $cat) {
    foreach ($cat['items'] as $item) {
        if ($only !== null && strcasecmp($only, $item['name']) !== 0) continue;
        $file = $outDir . '/' . item_slug($item['name']) . '.jpg';
        if ($only === null && is_file($file)) continue;
        if ($only === null && !$all && !empty($item['img'])) continue;

        $prompt = $item['name'] . ' (' . $cat['name'] . ')'
            . (!empty($item['desc']) ? ': ' . $item['desc'] : '') . ' ' . $style;
        echo $item['name'] . ' ... ';

        generate($key, $prompt, '1024x1024', $file, 800);
    }
}
