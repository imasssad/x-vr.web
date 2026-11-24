/**
 * Everything the page does once it has loaded.
 *
 * All of it is an enhancement: the HTML the server sends is complete and
 * readable on its own, so if this file never runs the content is still there.
 *
 * Structure: every behaviour is an init(root) function that binds only
 * inside `root` and marks what it binds (data-tsa-bound-*), so running it
 * twice is safe. That matters twice over: a section can appear more than
 * once on a page (Elementor widgets), and Elementor's editor re-renders a
 * widget's HTML after every edit and we re-initialise just that fragment.
 */
(function () {
	'use strict';

	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var $  = function (s, c) { return (c || document).querySelector(s); };
	var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

	/** Mark an element as bound for one behaviour; true if it already was. */
	function bound(el, key) {
		var k = 'tsaBound' + key;
		if (el.dataset[k]) return true;
		el.dataset[k] = '1';
		return false;
	}

	/* ---------------------------------------------------------------- nav */
	function initNav() {
		var nav = $('#nav');
		if (nav && !bound(nav, 'Stuck')) {
			var onScroll = function () { nav.classList.toggle('is-stuck', window.scrollY > 20); };
			onScroll();
			window.addEventListener('scroll', onScroll, { passive: true });
		}

		var burger = $('.burger');
		var drawer = $('#drawer');
		if (burger && drawer && !bound(burger, 'Drawer')) {
			var setOpen = function (open) {
				drawer.classList.toggle('is-open', open);
				burger.setAttribute('aria-expanded', open ? 'true' : 'false');
				burger.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
			};
			burger.addEventListener('click', function () {
				setOpen(burger.getAttribute('aria-expanded') !== 'true');
			});
			$$('a', drawer).forEach(function (a) { a.addEventListener('click', function () { setOpen(false); }); });
			document.addEventListener('keydown', function (e) {
				if (e.key === 'Escape' && drawer.classList.contains('is-open')) { setOpen(false); burger.focus(); }
			});
		}
	}

	/* ------------------------------------------------- active section mark */
	function initSpy() {
		// Menu links may be absolute ("https://site/#trades") on subpages, so
		// read the fragment from a.hash and only spy on links for THIS page.
		var links = $$('.nav__links a').filter(function (a) {
			return a.hash && a.pathname.replace(/\/$/, '') === location.pathname.replace(/\/$/, '');
		});
		if (!links.length || !('IntersectionObserver' in window)) return;

		var byId = {};
		var targets = [];
		links.forEach(function (a) {
			if (bound(a, 'Spy')) return;
			var id = a.hash.slice(1);
			var el = document.getElementById(id);
			if (el) { byId[id] = a; targets.push(el); }
		});
		if (!targets.length) return;

		var ratios = {};
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				ratios[en.target.id] = en.isIntersecting ? en.intersectionRatio : 0;
			});
			var best = null, top = 0;
			Object.keys(byId).forEach(function (id) {
				var r = ratios[id] || 0;
				if (r > top) { top = r; best = id; }
			});
			links.forEach(function (a) { a.removeAttribute('aria-current'); });
			if (best && byId[best]) byId[best].setAttribute('aria-current', 'true');
		}, { rootMargin: '-45% 0px -45% 0px', threshold: [0, 0.25, 0.5, 1] });
		targets.forEach(function (t) { io.observe(t); });
	}

	/* ------------------------------------------------------ scroll reveal */
	var revealObserver = null;
	function initReveal(root) {
		if (reduce || !('IntersectionObserver' in window)) return;
		if (!revealObserver) {
			revealObserver = new IntersectionObserver(function (entries) {
				entries.forEach(function (en) {
					if (en.isIntersecting) { en.target.dataset.reveal = 'in'; revealObserver.unobserve(en.target); }
				});
			}, { rootMargin: '0px 0px -6% 0px', threshold: 0.1 });
		}
		$$('.rv', root).forEach(function (el) {
			if (bound(el, 'Rv')) return;
			// anything already on screen stays visible; the rest start hidden
			if (el.getBoundingClientRect().top >= window.innerHeight) {
				el.dataset.reveal = 'pending';
				revealObserver.observe(el);
			}
		});
	}

	/* ------------------------------------------------------------- trades */
	function initTrades(root) {
		$$('.tradelist', root).forEach(function (tablist) {
			if (bound(tablist, 'Tabs')) return;
			var tabs   = $$('[role="tab"]', tablist);
			var panels = tabs.map(function (t) { return document.getElementById(t.getAttribute('aria-controls')); });

			var select = function (i, focus) {
				tabs.forEach(function (t, n) {
					var on = n === i;
					t.setAttribute('aria-selected', on ? 'true' : 'false');
					t.tabIndex = on ? 0 : -1;
					if (panels[n]) panels[n].hidden = !on;
				});
				if (focus && tabs[i]) tabs[i].focus();
			};

			tabs.forEach(function (t, i) {
				t.addEventListener('click', function () { select(i); });
				t.addEventListener('keydown', function (e) {
					var last = tabs.length - 1, next = null;
					if (e.key === 'ArrowDown' || e.key === 'ArrowRight') next = i === last ? 0 : i + 1;
					else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') next = i === 0 ? last : i - 1;
					else if (e.key === 'Home') next = 0;
					else if (e.key === 'End') next = last;
					if (next !== null) { e.preventDefault(); select(next, true); }
				});
			});
		});
	}

	/* ---------------------------------------------------------- accordion */
	function initAccordions(root) {
		$$('.acc__btn', root).forEach(function (btn) {
			if (bound(btn, 'Acc')) return;
			btn.addEventListener('click', function () {
				var open  = btn.getAttribute('aria-expanded') === 'true';
				var panel = document.getElementById(btn.getAttribute('aria-controls'));
				btn.setAttribute('aria-expanded', open ? 'false' : 'true');
				if (panel) panel.dataset.open = open ? 'false' : 'true';
			});
		});
	}

	/* ------------------------------------------------------------- videos */
	function initVideos(root) {
		$$('.vcard', root).forEach(function (card) {
			var btn = $('.vcard__play', card);
			if (!btn || btn.disabled || bound(btn, 'Play')) return;
			btn.addEventListener('click', function () {
				var media = $('.vcard__media', card);
				var embed = card.dataset.embed;
				var file  = card.dataset.file;
				var node;
				if (embed) {
					node = document.createElement('iframe');
					node.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(embed) + '?autoplay=1';
					node.title = ($('h3', card) || {}).textContent || 'Video';
					node.allow = 'autoplay; encrypted-media; picture-in-picture';
					node.allowFullscreen = true;
				} else if (file) {
					node = document.createElement('video');
					node.src = file; node.controls = true; node.autoplay = true; node.playsInline = true;
					if (card.dataset.poster) node.poster = card.dataset.poster;
				} else { return; }
				media.innerHTML = '';
				media.appendChild(node);
				// the focused play button was just destroyed; keep keyboard
				// users where they were instead of dropping them to the top
				try { node.setAttribute('tabindex', '-1'); node.focus(); } catch (e) {}
			});
		});
	}

	/* --------------------------------------------------------- hero video */
	function initHero(root) {
		$$('.hero', root).forEach(function (hero) {
			var hv  = $('.hero__video', hero) || $('#heroVideo', hero);
			var ctl = $('.vidctl', hero);
			if (!hv || !ctl || bound(hv, 'Hero')) return;

			var playBtn  = $('[data-act="play"]', ctl);
			var soundBtn = $('[data-act="sound"]', ctl);

			var sync = function () {
				var playing = !hv.paused;
				playBtn.setAttribute('aria-pressed', playing ? 'true' : 'false');
				$('use', playBtn).setAttribute('href', playing ? '#i-pause' : '#i-play');
				$('span', playBtn).textContent = playing ? 'Pause' : 'Play';
				soundBtn.setAttribute('aria-pressed', hv.muted ? 'false' : 'true');
				$('use', soundBtn).setAttribute('href', hv.muted ? '#i-sound' : '#i-mute');
				$('span', soundBtn).textContent = hv.muted ? 'Play with sound' : 'Mute';
			};

			hv.addEventListener('canplay', function () {
				hv.style.opacity = '1';
				ctl.hidden = false;
				ctl.classList.add('is-on');
				if (reduce) { hv.pause(); sync(); }
			}, { once: true });

			playBtn.addEventListener('click', function () {
				if (hv.paused) { hv.play().catch(function () {}); } else { hv.pause(); }
				sync();
			});
			soundBtn.addEventListener('click', function () {
				hv.muted = !hv.muted;
				if (!hv.muted) hv.play().catch(function () {});
				sync();
			});
		});
	}

	/* ------------------------------------------------------------ planner */
	function initPlanners(root) {
		if (!window.TSA || !TSA.planner) return;
		$$('[data-tsa="planner"]', root).forEach(function (planner) {
			if (bound(planner, 'Plan')) return;
			var form   = $('form', planner);
			var out    = $('.plan-out', planner);
			var status = $('[role="status"]', planner);
			var P = TSA.planner, S = TSA.steps;

			// The owner can rename roles and stages in the Customizer, so
			// nothing here matches on wording: position is what counts.
			var learnerSets = Object.keys(S.learner).map(function (k) { return S.learner[k]; });

			var checkedIndex = function (name, values) {
				var el = $('input[name="' + name + '"]:checked', form);
				return el ? values.indexOf(el.value) : -1;
			};

			form.addEventListener('submit', function (e) {
				e.preventDefault();
				var input = $('input[name="trade"]', form);
				var raw   = (input && input.value || '').trim();
				var trade = raw || P.fallbackTrade;

				var isLearner = checkedIndex('role', P.roles) <= 0;
				var si = checkedIndex('stage', P.stages);
				var steps = isLearner ? (learnerSets[si] || learnerSets[0]) : S.instructor;
				if (!steps || !steps.length) return;

				var ol = document.createElement('ol');
				steps.concat([S.closing]).forEach(function (pair) {
					var li = document.createElement('li');
					var b  = document.createElement('strong');
					b.textContent = pair[0] + '.';
					li.appendChild(b);
					li.appendChild(document.createTextNode(' ' + String(pair[1]).split('TRADE').join(trade)));
					ol.appendChild(li);
				});

				var box = document.createElement('div');
				box.className = 'plan-out__box';
				var h = document.createElement('h3');
				h.textContent = (isLearner ? P.learnerTitle : P.instructorTitle) + (raw || P.fallbackTradeArea);
				box.appendChild(h);
				box.appendChild(ol);

				out.innerHTML = '';
				out.appendChild(box);
				out.hidden = false;
				if (status) status.textContent = 'Your starter plan is ready, below the form.';
			});

			var resetBtn = $('[data-act="reset"]', form);
			if (resetBtn) {
				resetBtn.addEventListener('click', function () {
					form.reset();
					out.innerHTML = '';
					out.hidden = true;
					if (status) status.textContent = '';
					var input = $('input[name="trade"]', form);
					if (input) input.focus();
				});
			}
		});
	}

	/* ------------------------------------------------------- contact form */
	function initContacts(root) {
		if (!window.TSA || !TSA.rest) return;
		$$('form.tsa-contact', root).forEach(function (cf) {
			if (bound(cf, 'Contact')) return;
			var msg = $('[role="status"]', cf);
			var btn = $('button[type="submit"]', cf);
			var label = btn ? btn.innerHTML : '';

			cf.addEventListener('submit', function (e) {
				e.preventDefault();
				if (btn) { btn.disabled = true; btn.textContent = btn.dataset.sending || 'Sending…'; }
				if (msg) msg.textContent = '';

				var body = {};
				new FormData(cf).forEach(function (v, k) { body[k] = v; });

				fetch(TSA.rest, {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body: JSON.stringify(body)
				})
					.then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
					.then(function (res) {
						if (msg) msg.textContent = res.d && res.d.message ? res.d.message : '';
						if (res.ok && res.d && res.d.ok) cf.reset();
					})
					.catch(function () {
						if (msg) msg.textContent = 'Could not reach the server. Please try again.';
					})
					.then(function () {
						if (btn) { btn.disabled = false; btn.innerHTML = label; }
					});
			});
		});
	}

	/* ---------------------------------------------------------------- go */
	function init(root) {
		root = root || document;
		initNav();
		initSpy();
		initReveal(root);
		initTrades(root);
		initAccordions(root);
		initVideos(root);
		initHero(root);
		initPlanners(root);
		initContacts(root);
	}

	init(document);

	// Inside Elementor's editor the preview re-renders a widget's HTML after
	// every edit; re-initialise just that fragment so it keeps behaving.
	window.addEventListener('elementor/frontend/init', function () {
		if (window.elementorFrontend && elementorFrontend.hooks) {
			elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
				init($scope && $scope[0] ? $scope[0] : document);
			});
		}
	});
})();
