# Oakton Eats website

Plain PHP, no database, no build step. Runs on Namecheap shared hosting (PHP 7.4+).

## Pages

| URL      | File        |
|----------|-------------|
| `/`      | `index.php` |
| `/about` | `about.php` |
| `/menu`  | `menu.php`  |
| `/order` | `order.php` |
| `/home-2` | `home-2.php` (homepage template 2, hidden from search) |

The shared header, footer and visit block live in `includes/`. Clean URLs come from `.htaccess`.

## Before launch: fill these in

Open `includes/config.php`. Anything left blank is hidden on the site automatically.

- `ORDER_URL`: online ordering link (Toast, Square, Clover…). Once it's set, every "Order Now" button goes there instead of the popup.
- `DOORDASH_URL`, `UBEREATS_URL`: optional delivery links.
- `PHONE`, `PHONE_TEL`, `EMAIL`
- `ADDRESS_LINE1`, `ADDRESS_LINE2`, `MAPS_URL`. The map appears once the address is set.
- `$HOURS`
- `INSTAGRAM_URL`, `TIKTOK_URL`, `FACEBOOK_URL`
- `SITE_URL`: the final domain.

Menu items and prices are in `includes/menu-data.php`. **The prices are a draft. Confirm them with the owner.**

## Two homepage templates

- `index.php`: template 1, with the gold announcement bar at the top.
- `home-2.php`: template 2, the same page without the announcement bar. Preview it at `/home-2`.
- Both share `includes/home-hero.php` and `includes/home-sections.php`.
- To go with template 2, set `ANNOUNCEMENT` to `''` in `includes/config.php`. That hides the bar on every page.

## Opening soon and signups

- `OPENING_SOON` and `OPENING_DATE` in `includes/config.php` control the "Opening soon" badges.
- Every "Order Now" button opens the opening-soon signup popup until `ORDER_URL` is set. After that, the buttons go straight to online ordering. The popup also opens once on its own for first-time visitors.
- Signups are saved to `data/subscribers.csv` on the server. Download it from cPanel File Manager. The folder is blocked from the web.
- Set `NOTIFY_EMAIL` to also get an email for each signup.
- `SIGNUP_PERK` is the bonus-points wording in the popup.

## Menus and prices

- The site has four menus, in this order: Coffee, Breakfast Sandwiches, Refreshers, House Signatures. They are set in `$GROUPS` in `includes/config.php`.
- Each category in `includes/menu-data.php` has a `'group'` key that puts it in one of the four menus.
- Prices are hidden everywhere. Set `SHOW_PRICES` to `true` to show them.

## Photos and videos

- Logo: `assets/img/logo-icon.png` (gold icon, transparent background). Favicons: `favicon.png`, `apple-touch-icon.png`.
- Photos: `assets/img/photos/`. Signature cards and page heroes are set in `index.php`, `order.php` and `includes/media.php`.
- Video clips: `assets/video/clip-N.mp4`, each with a `clip-N.jpg` poster frame. Add a clip to `$REELS` in `includes/media.php` to show it in the "Watch It Cook" row.
- Keep clips short (about 10 s), muted, 720p and under 1 MB, so pages stay fast on shared hosting:

```
ffmpeg -i input.mp4 -t 10 -an -vf "scale=1280:-2,fps=24" -c:v libx264 -crf 30 -pix_fmt yuv420p -movflags +faststart clip-N.mp4
```

## Deploy to Namecheap

1. cPanel → File Manager → `public_html` (or the domain's folder).
2. Upload everything **except** `router.php`, `README.md`, `oakton-eats-tour.mp4` and `.claude/`.
   Include the `data` folder with its `.htaccess`. If signups don't save, set the folder's permissions to 755.
3. Turn on "Show Hidden Files" and check that `.htaccess` was uploaded.
4. Once SSL is active, uncomment the HTTPS lines in `.htaccess`.

## Run locally

```
php -S localhost:8123 router.php
```
