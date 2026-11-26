/**
 * The film popup.
 *
 * The reference page plays the weld film inline. The client asked for a
 * Discover button that opens it instead, so this is the only script the
 * site has: open the dialog, play, and stop the film again on close so
 * it is not still running behind a closed box.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') fn();
		else document.addEventListener('DOMContentLoaded', fn);
	}

	ready(function () {
		var box = document.getElementById('tsa-filmbox');
		if (!box) return;
		var video = box.querySelector('video');
		var opener = document.querySelector('[data-tsa-discover]');
		if (!opener) return;

		function stop() {
			if (!video) return;
			try { video.pause(); video.currentTime = 0; } catch (e) {}
		}

		function close() {
			stop();
			if (typeof box.close === 'function' && box.open) box.close();
			else box.removeAttribute('open');
			try { opener.focus(); } catch (e) {}
		}

		opener.addEventListener('click', function (e) {
			e.preventDefault();
			// <dialog> gives us the focus trap, the backdrop and Escape for
			// free. Where it is missing the button is still a link to the
			// film, so the browser handles it instead.
			if (typeof box.showModal !== 'function') return;
			e.stopPropagation();
			box.showModal();
			if (video) { try { video.play(); } catch (err) {} }
		});

		box.addEventListener('close', stop);
		box.addEventListener('cancel', stop);

		box.querySelectorAll('[data-tsa-close]').forEach(function (b) {
			b.addEventListener('click', close);
		});

		// clicking the backdrop, which is the dialog itself outside its panel
		box.addEventListener('click', function (e) {
			if (e.target === box) close();
		});
	});
})();
