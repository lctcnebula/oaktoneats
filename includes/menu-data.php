<?php
// Full menu. Every page reads from this file.
// group: which of the four menus a category belongs to ($GROUPS in config.php).
//        Categories show in the order they're listed here.
// price: a number, or ['Option' => price, ...] for sizes/singles/doubles.
//        Hidden on the site while SHOW_PRICES is false.
// img:   optional photo in /assets/img/photos/ for the item.
// tag:   optional label on the card.
// NOTE: prices are a starting draft. Confirm with the owner before turning them on.

$MENU = [
    [
        'slug' => 'hot-coffee', 'name' => 'Hot Coffee & Tea', 'group' => 'coffee',
        'blurb' => 'Espresso pulled to order, morning to close.',
        'items' => [
            ['name' => 'Drip Coffee', 'price' => ['Regular' => 2.75, 'Large' => 3.25]],
            ['name' => 'Espresso', 'price' => ['Single' => 2.95, 'Double' => 3.45]],
            ['name' => 'Americano', 'price' => ['Regular' => 3.75, 'Large' => 4.50]],
            ['name' => 'Cappuccino', 'price' => ['Regular' => 4.75, 'Large' => 5.50], 'img' => 'latte-top.jpg'],
            ['name' => 'Latte', 'price' => ['Regular' => 4.95, 'Large' => 5.75], 'img' => 'latte-cup.jpg'],
            ['name' => 'Spanish Latte', 'price' => ['Regular' => 5.75, 'Large' => 6.50], 'tag' => 'House',
             'desc' => 'Espresso with sweetened condensed milk. Smooth, rich and a little sweet.'],
            ['name' => 'Honey Cinnamon Latte', 'price' => ['Regular' => 5.75, 'Large' => 6.50]],
            ['name' => 'Caramel Macchiato', 'price' => ['Regular' => 5.75, 'Large' => 6.50]],
            ['name' => 'Mocha', 'price' => ['Regular' => 5.50, 'Large' => 6.25]],
            ['name' => 'Chai Latte', 'price' => ['Regular' => 5.25, 'Large' => 6.00]],
            ['name' => 'Matcha Latte', 'price' => ['Regular' => 5.75, 'Large' => 6.50]],
            ['name' => 'Hot Chocolate', 'price' => ['Regular' => 3.95, 'Large' => 4.75]],
            ['name' => 'Hot Tea', 'price' => 2.75, 'desc' => 'Black, green or chamomile.'],
        ],
    ],
    [
        'slug' => 'iced', 'name' => 'Iced & Cold Brew', 'group' => 'coffee',
        'blurb' => 'Cold coffee over ice, steeped slow and poured fresh.',
        'items' => [
            ['name' => 'Iced Spanish Latte', 'price' => ['Regular' => 6.00, 'Large' => 6.75], 'tag' => 'Favorite', 'img' => 'iced-coffee.jpg',
             'desc' => 'Our house Spanish latte, poured over ice.'],
            ['name' => 'Iced Latte', 'price' => ['Regular' => 5.25, 'Large' => 6.00]],
            ['name' => 'Cold Brew', 'price' => ['Regular' => 4.75, 'Large' => 5.50], 'img' => 'cold-brew.jpg',
             'desc' => 'Steeped for hours for a smooth, low-acid cup.'],
            ['name' => 'Vanilla Sweet Cream Cold Brew', 'price' => ['Regular' => 5.50, 'Large' => 6.25]],
            ['name' => 'Iced Caramel Macchiato', 'price' => ['Regular' => 6.00, 'Large' => 6.75]],
            ['name' => 'Iced Mocha', 'price' => ['Regular' => 5.75, 'Large' => 6.50]],
            ['name' => 'Iced Matcha Latte', 'price' => ['Regular' => 6.00, 'Large' => 6.75]],
        ],
    ],
    [
        'slug' => 'sandwiches', 'name' => 'Breakfast Sandwiches', 'group' => 'breakfast',
        'blurb' => 'Breakfast sandwiches on buttery croissants and toasted brioche, made to order.',
        'items' => [
            ['name' => 'Egg & Cheese Croissant', 'price' => 6.49, 'tag' => 'Classic', 'img' => 'croissant-egg-cheese.jpg',
             'desc' => 'Fluffy folded egg and melted American cheese on a warm butter croissant.'],
            ['name' => 'Sausage, Egg & Cheese', 'price' => 7.49,
             'desc' => 'Savory sausage patty, egg and American cheese on toasted brioche.'],
            ['name' => 'Herb Egg Croissant', 'price' => 6.99, 'img' => 'croissant-egg.jpg', 'tag' => 'Veggie',
             'desc' => 'Soft scrambled eggs with chives and a touch of garlic aioli.'],
            ['name' => 'Turkey & Swiss Croissant', 'price' => 7.99,
             'desc' => 'Sliced turkey, Swiss, lettuce, tomato and honey mustard.'],
            ['name' => 'Breakfast Burrito', 'price' => 8.49,
             'desc' => 'Scrambled eggs, hash browns, cheddar and salsa in a warm tortilla.'],
            ['name' => 'Veggie Egg White Wrap', 'price' => 6.99,
             'desc' => 'Egg whites, spinach, tomato and feta in a spinach tortilla.'],
        ],
    ],
    [
        'slug' => 'lemonades', 'name' => 'Lemonades & Refreshers', 'group' => 'refreshers',
        'blurb' => 'Fresh lemonades, fruit refreshers and iced teas, shaken over ice.',
        'items' => [
            ['name' => 'Fresh Lemonade', 'price' => 3.49, 'img' => 'lemonade.jpg', 'tag' => 'House',
             'desc' => 'Squeezed lemons, a little sugar and fresh mint.'],
            ['name' => 'Mango Lemonade', 'price' => 3.99],
            ['name' => 'Strawberry Lemonade', 'price' => 3.99],
            ['name' => 'Passion Fruit Refresher', 'price' => 4.49, 'desc' => 'Passion fruit and green tea, shaken with ice.'],
            ['name' => 'Peach Iced Tea', 'price' => 3.49],
            ['name' => 'Iced Tea', 'price' => 2.99],
            ['name' => 'Fountain Drink', 'price' => ['Regular' => 2.49, 'Large' => 2.99]],
            ['name' => 'Bottled Water', 'price' => 1.99],
        ],
    ],
    [
        'slug' => 'burgers', 'name' => 'Oakton Smash Burgers', 'group' => 'signature',
        'blurb' => 'Fresh beef balls smashed thin on a hot griddle for crispy, lacy edges. Served on a toasted potato bun.',
        'items' => [
            ['name' => 'The Oakton', 'price' => ['Single' => 8.99, 'Double' => 11.49], 'tag' => 'Signature', 'img' => 'hero-burger.jpg',
             'desc' => 'American cheese, grilled onions, pickles and Oakton sauce. Where everyone should start.'],
            ['name' => 'Cheese Lover', 'price' => ['Single' => 8.49, 'Double' => 10.99],
             'desc' => 'Two slices of American cheese, pickles, ketchup and mustard. Simple and classic.'],
            ['name' => 'Jalapeño Heat', 'price' => ['Single' => 9.49, 'Double' => 11.99], 'tag' => 'Spicy',
             'desc' => 'Pepper jack, fresh and pickled jalapeños, chipotle mayo, crispy onions.'],
            ['name' => 'Mushroom Swiss', 'price' => ['Single' => 9.49, 'Double' => 11.99],
             'desc' => 'Sautéed mushrooms, Swiss cheese, garlic aioli, caramelized onions.'],
            ['name' => 'BBQ Crunch', 'price' => ['Single' => 9.49, 'Double' => 11.99],
             'desc' => 'Cheddar, smoky BBQ sauce, crispy onion strings, pickles.'],
            ['name' => 'Triple Stack', 'price' => 13.99, 'tag' => 'Big Appetite',
             'desc' => 'Three smashed patties, three slices of cheese, grilled onions, pickles, Oakton sauce.'],
            ['name' => 'Garden Smash', 'price' => 9.99, 'tag' => 'Veggie',
             'desc' => 'Crispy veggie patty, American cheese, lettuce, tomato, red onion, Oakton sauce.'],
        ],
    ],
    [
        'slug' => 'chicken', 'name' => 'Crispy Chicken', 'group' => 'signature',
        'blurb' => 'Buttermilk-marinated, hand-breaded and fried to order. Pick your heat.',
        'items' => [
            ['name' => 'Crispy Chicken Sandwich', 'price' => 9.99,
             'desc' => 'Fried chicken breast, pickles, shredded lettuce, garlic mayo, toasted bun.'],
            ['name' => 'Hot Honey Chicken', 'price' => 10.99, 'tag' => 'Favorite',
             'desc' => 'Crispy chicken glazed in hot honey with pickles and creamy slaw.'],
            ['name' => 'Nashville Hot Sandwich', 'price' => 10.99, 'tag' => 'Spicy',
             'desc' => 'Dipped in spicy chili oil with pickles, slaw and comeback sauce. Choose mild, hot or extra hot.'],
            ['name' => 'Chicken Tenders', 'price' => ['3 pc' => 8.99, '5 pc' => 12.99],
             'desc' => 'Hand-breaded tenders with fries and your choice of two sauces.'],
            ['name' => 'Chicken Wrap', 'price' => 9.49,
             'desc' => 'Chopped tenders, lettuce, tomato, cheddar and ranch in a warm tortilla.'],
        ],
    ],
    [
        'slug' => 'sides', 'name' => 'Fries & Sides', 'group' => 'signature',
        'blurb' => 'Crispy, salty, and made to share (or not).',
        'items' => [
            ['name' => 'Seasoned Fries', 'price' => ['Regular' => 3.99, 'Large' => 5.49]],
            ['name' => 'Cheese Fries', 'price' => 5.99, 'desc' => 'Seasoned fries under warm cheese sauce.'],
            ['name' => 'Loaded Oakton Fries', 'price' => 8.99, 'tag' => 'Must Try',
             'desc' => 'Fries topped with chopped smash patty, cheese sauce, grilled onions, jalapeños and Oakton sauce.'],
            ['name' => 'Cajun Fries', 'price' => ['Regular' => 4.49, 'Large' => 5.99]],
            ['name' => 'Sweet Potato Fries', 'price' => 4.99],
            ['name' => 'Onion Rings', 'price' => 5.49],
            ['name' => 'Mac & Cheese Bites', 'price' => 6.49, 'desc' => 'Golden fried bites with a gooey center.'],
            ['name' => 'Mozzarella Sticks', 'price' => 6.49, 'desc' => 'Served with marinara.'],
        ],
    ],
    [
        'slug' => 'shakes', 'name' => 'Shakes', 'group' => 'signature',
        'blurb' => 'Thick, hand-spun shakes topped with whipped cream.',
        'items' => [
            ['name' => 'Vanilla Shake', 'price' => 5.99],
            ['name' => 'Chocolate Shake', 'price' => 5.99],
            ['name' => 'Strawberry Shake', 'price' => 5.99],
            ['name' => 'Oreo Shake', 'price' => 6.49, 'tag' => 'Favorite'],
            ['name' => 'Biscoff Shake', 'price' => 6.99, 'desc' => 'Vanilla shake blended with Biscoff spread and cookie crumble.'],
            ['name' => 'Nutella Shake', 'price' => 6.99],
        ],
    ],
    [
        'slug' => 'sauces', 'name' => 'Sauces', 'group' => 'signature',
        'blurb' => 'Made in-house. Extra cups for your fries and tenders.',
        'items' => [
            ['name' => 'Oakton Sauce', 'price' => 0.75, 'tag' => 'House'],
            ['name' => 'Garlic Aioli', 'price' => 0.75],
            ['name' => 'Chipotle Mayo', 'price' => 0.75],
            ['name' => 'Comeback Sauce', 'price' => 0.75],
            ['name' => 'Hot Honey', 'price' => 0.75],
            ['name' => 'Ranch', 'price' => 0.75],
            ['name' => 'BBQ', 'price' => 0.75],
        ],
    ],
];

// Add-ons shown on the menu page.
$COMBOS = [
    ['name' => 'Breakfast Combo', 'price' => 2.99, 'desc' => 'Add a regular drip coffee or hot tea to any breakfast sandwich.'],
    ['name' => 'Make it a Combo', 'price' => 4.49, 'desc' => 'Add regular fries and a fountain drink to any burger or sandwich.'],
    ['name' => 'Upgrade to Loaded', 'price' => 3.99, 'desc' => 'Swap your combo fries for Loaded Oakton Fries.'],
    ['name' => 'Add a Shake', 'price' => 3.99, 'desc' => 'Swap your combo drink for any classic shake or refresher.'],
];
