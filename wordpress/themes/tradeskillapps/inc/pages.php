<?php
/**
 * Which sections go where.
 *
 * The client supplied two reference pages and asked for both merged. This
 * file is the single list that front-page.php and the Elementor page
 * builder both read, so the page and the builder cannot drift apart.
 */
defined('ABSPATH') || exit;

/**
 * The front page, in order.
 *
 * Both of the client's reference pages, merged - not a third design.
 *
 *   The OLDER page (x-vr-skilled-trades) is the full narrative: purpose,
 *   trades, curriculum, approach, learning cycle, pathways, educators,
 *   youth, mission, glossary, planner, access, FAQ, closing. It is the
 *   site this theme was first built from, and it carries the photography.
 *
 *   The NEWER page (xvr-skilled-trades) brought the rebrand: the "Train
 *   for the work ahead" hero with the weld film, the multiplayer section,
 *   the trade-knowledge band, published pricing, and the partners close.
 *
 * The order below follows the older page, because that is the flow the
 * client approved, with the newer page's sections placed where they read
 * naturally. Everything from both is here; nothing was invented.
 */
function tsa_front_sections() {
	return array(
		'hero',          // newer: Train for the work ahead
		'purpose',       // older: 01 / Our purpose
		'multiplayer',   // newer: Learn together inside the virtual shop
		'trades',        // both:  Explore the trades
		'videos',        // ours:  the films the client supplied
		'curriculum',    // older: MAT-VR curriculum
		'approach',      // older: A headset opens the door
		'cycle',         // older: 03 / More than seeing. Learning by doing
		'knowledge',     // newer: Build expert trade knowledge
		'pathways',      // older: 04 / A starting point. A path forward
		'educators',     // older: 05 / Great teaching
		'levels',        // older: Start with curiosity. Grow into a craft
		'mission',       // older: See yourself building what comes next
		'licensing',     // newer: What does XVR cost for schools?
		'glossary',      // older: 06 / Know the language
		'planner',       // older: 07 / Make your next move
		'access',        // older: Big possibilities. A very affordable way in
		'faq',           // older: Questions, answered
		'partners',      // newer: Bring XVR to your training program
		'contact',       // ours:  the enquiry form
		'closing',       // older: The future takes skill. Let's build it
	);
}

/**
 * No inner pages: both references are a single page, and every section is
 * now on it. The machinery below still works, so a page can be added here
 * whenever something genuinely does not belong on the home page. Pages
 * created by an earlier version are left alone - they are not deleted,
 * only no longer created or linked.
 */
function tsa_inner_pages() {
	return array();
}

/** Every section this theme can draw, front page and inner pages together. */
function tsa_all_placed_sections() {
	$all = tsa_front_sections();
	foreach (tsa_inner_pages() as $p) {
		foreach ($p['sections'] as $s) {
			if (!in_array($s, $all, true)) $all[] = $s;
		}
	}
	return $all;
}

/**
 * The permalink of one of our inner pages, by slug. Falls back to an
 * anchor so a link never points at nothing while the pages are being
 * created, and so the theme still works if the owner deletes one.
 */
function tsa_page_url($slug, $fallback = '#') {
	$page = get_page_by_path($slug);
	if ($page && $page->post_status === 'publish') return get_permalink($page);
	return $fallback;
}

/** The inner page whose slug this is, or null. */
function tsa_inner_page_for($slug) {
	$pages = tsa_inner_pages();
	return isset($pages[$slug]) ? $pages[$slug] : null;
}
