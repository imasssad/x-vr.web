<?php
/**
 * Which sections go where.
 *
 * The client's reference page is six sections. Everything else this theme
 * can draw is still here - it moved onto the inner pages below rather than
 * being deleted. This file is the single list both the front page and the
 * Elementor page builder read, so the two cannot drift apart.
 */
defined('ABSPATH') || exit;

/** The front page, in order: the client's reference page. */
function tsa_front_sections() {
	return array('hero', 'multiplayer', 'knowledge', 'trades', 'licensing', 'partners');
}

/**
 * The inner pages. slug => title, the sections each one carries, and any
 * per-page field overrides (the trades explorer, for instance, shows every
 * trade with its tabs here, while the front page shows four plain cards).
 */
function tsa_inner_pages() {
	return array(
		'about-xvr' => array(
			'title'     => __('About XVR', 'tradeskillapps'),
			'sections'  => array('purpose', 'mission', 'closing'),
			'overrides' => array(),
		),
		'how-we-teach' => array(
			'title'     => __('How we teach', 'tradeskillapps'),
			'sections'  => array('approach', 'cycle', 'levels', 'videos'),
			'overrides' => array(),
		),
		'curriculum' => array(
			'title'     => __('Curriculum and pathways', 'tradeskillapps'),
			'sections'  => array('curriculum', 'trades', 'pathways'),
			'overrides' => array(
				// the full explorer, every trade, as the theme shipped it
				'tradesSection.layout'   => 'tabs',
				'tradesSection.limit'    => '0',
				'tradesSection.eyebrow'  => '02 / Explore the Possibilities',
				'tradesSection.headline' => 'Find the work',
				'tradesSection.headlineEm' => 'that moves you.',
				'tradesSection.lede'     => 'Explore the trade areas shaping our educational vision. Select a field to see its foundations and a sample immersive learning experience.',
			),
		),
		'for-educators' => array(
			'title'     => __('For educators', 'tradeskillapps'),
			'sections'  => array('educators', 'access'),
			'overrides' => array(),
		),
		'plan-your-pathway' => array(
			'title'     => __('Plan your pathway', 'tradeskillapps'),
			'sections'  => array('planner', 'glossary'),
			'overrides' => array(),
		),
		'questions-contact' => array(
			'title'     => __('Questions and contact', 'tradeskillapps'),
			'sections'  => array('faq', 'contact'),
			'overrides' => array(),
		),
	);
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
