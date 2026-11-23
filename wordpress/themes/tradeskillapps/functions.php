<?php
/**
 * TradeSkillApps theme.
 *
 * The page is assembled from template-parts/, its words come from the
 * Customizer (with the shipped copy as defaults), and its lists come from
 * posts. Nothing here needs editing to run the site.
 */
defined('ABSPATH') || exit;

define('TSA_VERSION', '1.0.0');

require_once get_template_directory() . '/inc/defaults.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/icons.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/contact.php';
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

function tsa_assets() {
	wp_enqueue_style('tradeskillapps', get_stylesheet_uri(), array(), TSA_VERSION);

	wp_enqueue_script(
		'tradeskillapps',
		get_template_directory_uri() . '/assets/js/site.js',
		array(), TSA_VERSION, true
	);
	wp_localize_script('tradeskillapps', 'TSA', array(
		'rest'    => esc_url_raw(rest_url('tsa/v1/contact')),
		'nonce'   => wp_create_nonce('wp_rest'),
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
	$img = get_template_directory_uri() . '/assets/images/og-image.jpg';
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
		printf('<li><a href="%s">%s</a></li>', esc_url($href), esc_html($label));
	}
	echo '</ul>';
}
