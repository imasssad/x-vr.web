<?php
/**
 * The sections the home page is built from, in order.
 *
 * The one list that front-page.php and the Elementor page builder both
 * read, so the page and the builder cannot drift apart. It is the
 * reference page's six sections and nothing else.
 */
defined('ABSPATH') || exit;

function tsa_front_sections() {
	return array('hero', 'multiplayer', 'knowledge', 'trades', 'licensing', 'partners');
}
