<?php
/**
 * XVR Trade Skills theme.
 *
 * The page is assembled from template-parts/, its words come from the
 * Customizer (with the shipped copy as defaults), and its lists come from
 * posts. Nothing here needs editing to run the site.
 */
defined('ABSPATH') || exit;

// Read from the stylesheet header rather than repeated here, because the two
// had already drifted: the header said 1.15.0 while this said 1.0.0. That
// number is the cache-buster on style.css and site.js, and it is what decides
// whether an existing install tops up its shipped content - so a stale copy
// means an updated theme ships no updated CSS and no new sections.
define('TSA_VERSION', wp_get_theme(get_template())->get('Version') ?: '1.0.0');

require_once get_template_directory() . '/inc/defaults.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/pages.php';
require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/elementor.php';

function tsa_setup() {
	load_theme_textdomain('tradeskillapps', get_template_directory() . '/languages');

	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('automatic-feed-links');
	add_theme_support('responsive-embeds');
	add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
	add_theme_support('custom-logo', array('height' => 64, 'width' => 64, 'flex-height' => true, 'flex-width' => true));

	register_nav_menus(array(
		'primary' => __('Main menu', 'tradeskillapps'),
		'footer'  => __('Footer links', 'tradeskillapps'),
	));
}
add_action('after_setup_theme', 'tsa_setup');

/* ------------------------------------------------------------------ *
 * Keeping the site out of search while it is being built
 * ------------------------------------------------------------------ */

/**
 * Is the owner asking for the site to be kept out of search?
 *
 * This is WordPress's own switch, Settings > Reading > "Discourage search
 * engines from indexing this site". The theme does not add a setting of
 * its own: a second switch is a second thing to forget on launch day.
 */
function tsa_hidden_from_search() {
	return '0' === (string) get_option('blog_public');
}

/**
 * WordPress prints a robots meta tag when that switch is on, which covers
 * the page. It sends no header, so nothing covers what is not a page -
 * the weld film, the photographs, the feeds, the REST responses. This
 * adds the header, which applies to all of them.
 *
 * Requests for files on disk never reach PHP, so the film still needs a
 * rule in the server config. That is in the readme.
 */
function tsa_robots_header_value() {
	return tsa_hidden_from_search()
		? 'noindex, nofollow, noarchive, nosnippet, noimageindex'
		: '';
}

function tsa_robots_header() {
	$value = tsa_robots_header_value();
	if ($value === '' || headers_sent()) return;
	header('X-Robots-Tag: ' . $value, true);
}
add_action('send_headers', 'tsa_robots_header');
add_action('rest_api_init', 'tsa_robots_header');

/**
 * Say so, loudly and on every screen, while the site is hidden.
 *
 * WordPress mentions it in one line of the dashboard. A site that goes
 * live still carrying noindex is invisible to Google for as long as it
 * takes someone to notice, so this is worth being blunt about.
 */
add_action('admin_notices', function () {
	if (!tsa_hidden_from_search() || !current_user_can('manage_options')) return;
	printf(
		'<div class="notice notice-warning"><p><strong>%s</strong> %s</p><p><a href="%s">%s</a></p></div>',
		esc_html__('This site is hidden from search engines.', 'tradeskillapps'),
		esc_html__('Google and the others are asked not to list it. That is right while the site is being built - and it must be turned off when it goes live, or the site will not appear in search at all.', 'tradeskillapps'),
		esc_url(admin_url('options-reading.php')),
		esc_html__('Settings > Reading, where this is switched', 'tradeskillapps')
	);
});

function tsa_assets() {
	wp_enqueue_style('tradeskillapps', get_stylesheet_uri(), array(), TSA_VERSION);

	wp_enqueue_script(
		'tradeskillapps',
		get_template_directory_uri() . '/assets/js/site.js',
		array(), TSA_VERSION, true
	);
	wp_localize_script('tradeskillapps', 'TSA', array(
		'rest'    => esc_url_raw(rest_url('tsa/v1/contact')),
		// the planner builds its guidance in the browser, so it needs the text
		'planner' => array(
			'roles'              => tsa_lines('planner.roles'),
			'stages'             => tsa_lines('planner.stages'),
			'learnerTitle'       => tsa_text('planner.learnerTitle'),
			'instructorTitle'    => tsa_text('planner.instructorTitle'),
			'fallbackTrade'      => tsa_text('planner.fallbackTrade'),
			'fallbackTradeArea'  => tsa_text('planner.fallbackTradeArea'),
		),
		'steps'   => tsa_default('plannerSteps'),
	));
}
add_action('wp_enqueue_scripts', 'tsa_assets');

/** Favicon and touch icon ship with the theme. */
function tsa_head_icons() {
	$u = get_template_directory_uri() . '/assets/favicon';
	if (has_site_icon()) return;                 // the owner set their own
	printf('<link rel="icon" href="%s/favicon.svg" type="image/svg+xml" />' . "\n", esc_url($u));
	printf('<link rel="apple-touch-icon" href="%s/apple-touch-icon.png" />' . "\n", esc_url($u));
}
add_action('wp_head', 'tsa_head_icons', 5);

/** Social preview tags, using the Customizer values. */
function tsa_head_meta() {
	if (!is_front_page()) return;
	// The social image honours the Customizer field; a theme path, an
	// attachment id and a full URL all work. Falls back to the shipped file.
	$set = tsa_text('site.ogImage');
	if ($set !== '' && strpos($set, '/assets/') === 0) {
		$img = tsa_asset($set);
	} elseif (is_numeric($set) && wp_get_attachment_image_url((int) $set, 'full')) {
		$img = wp_get_attachment_image_url((int) $set, 'full');
	} elseif ($set !== '' && preg_match('#^https?://#', $set)) {
		$img = $set;
	} else {
		$img = get_template_directory_uri() . '/assets/images/og-image.jpg';
	}
	printf('<meta name="description" content="%s" />' . "\n", esc_attr(tsa_text('site.description')));
	printf('<meta property="og:title" content="%s" />' . "\n", esc_attr(tsa_text('site.title')));
	printf('<meta property="og:description" content="%s" />' . "\n", esc_attr(tsa_text('site.description')));
	printf('<meta property="og:image" content="%s" />' . "\n", esc_url($img));
	printf('<meta property="og:type" content="website" />' . "\n");
	printf('<meta name="twitter:card" content="summary_large_image" />' . "\n");
}
add_action('wp_head', 'tsa_head_meta', 6);

/** The front page is a single scroll, so the admin bar margin is unwanted there. */
function tsa_body_class($classes) {
	if (is_front_page()) $classes[] = 'tsa-front';
	return $classes;
}
add_filter('body_class', 'tsa_body_class');

/** Menu items are anchors on one page; mark the current one with JavaScript, not PHP. */
function tsa_nav_fallback() {
	echo '<ul class="nav__links">';
	foreach (tsa_defaults()['nav'] as $i => $item) {
		$label = tsa_text('nav.' . $i . '.label');
		$href  = tsa_text('nav.' . $i . '.href');
		if ($label === '') continue;
		printf('<li><a href="%s">%s</a></li>', esc_url(tsa_href($href)), esc_html($label));
	}
	echo '</ul>';
}

/** The extra drawer-only links, as <li> markup for items_wrap. */
function tsa_drawer_extra_items() {
	$out = '';
	foreach ((array) tsa_default('drawerExtra') as $i => $unused) {
		$label = tsa_text('drawerExtra.' . $i . '.label');
		$href  = tsa_text('drawerExtra.' . $i . '.href');
		if ($label === '') continue;
		$out .= '<li><a href="' . esc_url(tsa_href($href)) . '">' . esc_html($label) . '</a></li>';
	}
	return $out;
}
