(function () {
    'use strict';

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var hasIO = 'IntersectionObserver' in window;

    // Mobile nav
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.getElementById('mobile-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var open = nav.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open);
            toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && nav.classList.contains('open')) toggle.click();
        });
    }

    // Reveal on scroll
    var reveals = document.querySelectorAll('.reveal');
    if (hasIO) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
            });
        }, { rootMargin: '0px 0px -8% 0px' });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('in'); });
    }

    // Videos marked data-autoplay only play while on screen
    if (!reduceMotion && hasIO) {
        var vio = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                var v = en.target;
                if (en.isIntersecting) { var p = v.play(); if (p && p.catch) p.catch(function () {}); }
                else v.pause();
            });
        }, { threshold: 0.25 });
        document.querySelectorAll('video[data-autoplay]').forEach(function (v) { vio.observe(v); });
    }

    // ---------- "Get notified" popup ----------
    var modal = document.getElementById('notify');
    if (modal) {
        var lastFocus = null;
        var store = function (k, v) { try { return v === undefined ? localStorage.getItem(k) : localStorage.setItem(k, v); } catch (e) { return null; } };
        var openModal = function () {
            lastFocus = document.activeElement;
            modal.hidden = false;
            requestAnimationFrame(function () { modal.classList.add('open'); });
            document.body.classList.add('modal-open');
            var first = modal.querySelector('input[name="name"]');
            if (first) setTimeout(function () { first.focus(); }, 250);
            store('oe-notify-seen', '1');
        };
        var closeModal = function () {
            modal.classList.remove('open');
            document.body.classList.remove('modal-open');
            setTimeout(function () { modal.hidden = true; }, 250);
            if (lastFocus) lastFocus.focus();
        };
        document.addEventListener('click', function (e) {
            var t = e.target.closest('[data-notify]');
            if (t) { e.preventDefault(); if (nav && nav.classList.contains('open')) toggle.click(); openModal(); return; }
            if (e.target.closest('[data-notify-close]')) closeModal();
        });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) closeModal(); });

        // Show it once on its own for first-time visitors.
        if (!store('oe-notify-seen') && !/subscribed=/.test(location.search)) {
            setTimeout(function () { if (modal.hidden) openModal(); }, 6000);
        }

        var form = modal.querySelector('[data-notify-form]');
        var msg = modal.querySelector('.notify-msg');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            msg.className = 'notify-msg';
            fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    msg.textContent = res.message;
                    msg.classList.add(res.ok ? 'ok' : 'err');
                    if (res.ok) { form.classList.add('done'); store('oe-subscribed', '1'); }
                })
                .catch(function () { msg.textContent = 'Something went wrong. Please try again.'; msg.classList.add('err'); })
                .then(function () { btn.disabled = false; });
        });
    }

    // Hero collage: layers drift a little with the mouse
    var stage = document.querySelector('[data-parallax]');
    if (stage && !reduceMotion && window.matchMedia('(hover: hover)').matches) {
        var layers = stage.querySelectorAll('[data-depth]');
        var raf = 0;
        document.querySelector('.hero').addEventListener('mousemove', function (e) {
            var x = e.clientX / innerWidth - .5, y = e.clientY / innerHeight - .5;
            cancelAnimationFrame(raf);
            raf = requestAnimationFrame(function () {
                layers.forEach(function (l) {
                    var d = +l.dataset.depth;
                    l.style.transform = 'translate(' + (-x * d).toFixed(1) + 'px,' + (-y * d).toFixed(1) + 'px)';
                });
            });
        });
    }

    // ---------- Homepage-style tabs ----------
    document.querySelectorAll('[role="tablist"]').forEach(function (list) {
        var tabs = list.querySelectorAll('[role="tab"]');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (x) {
                    var sel = x === tab;
                    x.setAttribute('aria-selected', sel);
                    document.getElementById(x.getAttribute('aria-controls')).hidden = !sel;
                });
            });
        });
    });

    // ---------- Menu page ----------
    var catNav = document.querySelector('.cat-nav');
    if (catNav) {
        var links = catNav.querySelectorAll('a');
        var cats = document.querySelectorAll('.menu-cat');
        var groups = document.querySelectorAll('.daypart');
        var filterBtns = document.querySelectorAll('[data-filter]');
        var search = document.getElementById('menu-search');
        var empty = document.querySelector('.no-results');
        var filter = 'all';

        var setActive = function (id) {
            links.forEach(function (a) {
                var on = a.getAttribute('href') === '#' + id;
                a.classList.toggle('active', on);
                if (on) catNav.scrollTo({ left: a.offsetLeft - 20, behavior: 'smooth' });
            });
        };
        if (hasIO) {
            var spy = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) { if (en.isIntersecting) setActive(en.target.id); });
            }, { rootMargin: '-35% 0px -60% 0px' });
            cats.forEach(function (c) { spy.observe(c); });
        }

        var apply = function () {
            var q = search ? search.value.trim().toLowerCase() : '';
            var any = false;
            groups.forEach(function (dp) {
                var partOn = filter === 'all' || dp.dataset.group === filter;
                var shownInPart = 0;
                dp.querySelectorAll('.menu-cat').forEach(function (cat) {
                    var shown = 0;
                    cat.querySelectorAll('.item').forEach(function (it) {
                        var match = partOn && (!q || it.dataset.name.indexOf(q) !== -1);
                        it.hidden = !match;
                        if (match) shown++;
                    });
                    cat.hidden = shown === 0;
                    shownInPart += shown;
                });
                dp.hidden = shownInPart === 0;
                if (shownInPart) any = true;
            });
            links.forEach(function (a) { a.hidden = filter !== 'all' && a.dataset.group !== filter; });
            if (empty) empty.hidden = any;
        };

        filterBtns.forEach(function (b) {
            b.addEventListener('click', function () {
                filter = b.dataset.filter;
                filterBtns.forEach(function (x) { x.setAttribute('aria-pressed', x === b); });
                apply();
            });
        });
        if (search) search.addEventListener('input', apply);

        // /menu#coffee etc. opens with that filter selected
        var hash = location.hash.replace('#', '');
        var hashBtn = hash && document.querySelector('[data-filter="' + hash + '"]');
        if (hashBtn) hashBtn.click();
    }
})();
