<?php
/**
 * Elementor support.
 *
 * Three separate things, in rising order of usefulness:
 *
 *  1. The plumbing Elementor expects of a theme, so it can edit pages without
 *     complaining: a declared content width, a plain the_content() call, and
 *     full-width page templates.
 *  2. Theme Builder locations, so Elementor Pro can replace the header,
 *     footer, single or archive. Without Pro these do nothing and the theme's
 *     own templates are used.
 *  3. The site's own sections as draggable widgets, under a TradeSkillApps
 *     heading in the widget panel. This is the part that makes the design
 *     reusable: a page can be built from the same blocks the home page uses.
 *
 * Everything here is skipped entirely when Elementor is not installed.
 */
defined('ABSPATH') || exit;

/** Elementor reads this to size its default content area. */
if (!isset($GLOBALS['content_width'])) {
	$GLOBALS['content_width'] = 1216;
}

function tsa_elementor_active() {
	return did_action('elementor/loaded') > 0;
}

/** Is this page one someone built in Elementor rather than the editor? */
function tsa_built_with_elementor($post_id = null) {
	if (!tsa_elementor_active() || !class_exists('\Elementor\Plugin')) return false;
	$post_id = $post_id ? $post_id : get_the_ID();
	if (!$post_id) return false;
	$docs = \Elementor\Plugin::$instance->documents;
	if (!$docs) return false;
	$doc = $docs->get($post_id);
	return $doc && $doc->is_built_with_elementor();
}

/**
 * Is Elementor's editor or its preview iframe loading this page right now?
 *
 * Read from the request, deliberately: Elementor's internal preview/editor
 * classes have changed between versions and calling them from a template has
 * been reported to throw and drop the editor into safe mode. These two
 * parameters have been stable for years.
 *
 * This is what makes taking over the home page possible at all: until the
 * page has Elementor content, is_built_with_elementor() is false, so without
 * this the editor would keep previewing the theme's own sections and there
 * would be nowhere to drop a widget.
 */
function tsa_elementor_editing($post_id = 0) {
	if (isset($_GET['elementor-preview'])) {
		$previewing = (int) $_GET['elementor-preview'];
		if ($previewing && (!$post_id || $previewing === (int) $post_id)) return true;
	}
	if (isset($_GET['action']) && $_GET['action'] === 'elementor') return true;
	return false;
}

/**
 * Lets Elementor Pro take over a region. Returns true when it did, so the
 * caller can skip its own markup. Always false without Pro.
 */
function tsa_do_location($location) {
	return function_exists('elementor_theme_do_location') && elementor_theme_do_location($location);
}

/* ------------------------------------------------------------------ *
 * Registration
 * ------------------------------------------------------------------ */

add_action('elementor/theme/register_locations', function ($manager) {
	$manager->register_all_core_location();   // header, footer, single, archive
});

add_action('elementor/elements/categories_registered', function ($manager) {
	$manager->add_category('tradeskillapps', array(
		'title' => __('TradeSkillApps', 'tradeskillapps'),
		'icon'  => 'eicon-parallax',
	));
});

/**
 * The sections offered as widgets.
 * slug => [ template part, label, icon, content group, which fields to expose ]
 *
 * The content group is where each widget's default wording comes from, so a
 * freshly dropped widget already reads correctly and only needs editing if
 * the page wants to say something different.
 */
function tsa_elementor_sections() {
	return array(
		// slug => [ label, icon, content group, text fields, pictures, posts-menu hint ]
		'hero'      => array('Hero', 'eicon-slides', 'hero',
			array('eyebrow','headline','headlineEm','lede'),
			array(array('Background picture', 'hero.image')), ''),
		'ticker'    => array('Scrolling words', 'eicon-animation-text', 'ticker',
			array(), array(), ''),
		'purpose'   => array('Why this exists', 'eicon-info-box', 'purpose',
			array('eyebrow','headline','headlineEm','lede','body'), array(), ''),
		'trades'    => array('Trades', 'eicon-tabs', 'tradesSection',
			array('eyebrow','headline','headlineEm','lede','note'), array(), 'Trades'),
		'approach'  => array('Our approach', 'eicon-text', 'approach',
			array('eyebrow','headline','headlineEm','lede','note'),
			array(array('Large picture', 'approach.heroImage'),
			      array('Second picture', 'approach.practiceImage')), ''),
		'cycle'     => array('Learning cycle', 'eicon-sync', 'learningCycle',
			array('eyebrow','headline','headlineEm'), array(), ''),
		'multiplayer' => array('Learning together (multiplayer)', 'eicon-users', 'multiplayer',
			array('eyebrow','headline','headlineEm','lede','body'), array(), ''),
		'videos'    => array('Videos', 'eicon-youtube', 'videos',
			array('eyebrow','headline','headlineEm','lede','note'), array(), 'Videos'),
		'pathways'  => array('Career pathways', 'eicon-time-line', 'pathways',
			array('eyebrow','headline','headlineEm','lede','note'),
			array(array('Picture', 'pathways.image')), ''),
		'educators' => array('For educators', 'eicon-person', 'educators',
			array('eyebrow','headline','headlineEm','lede','body'),
			array(array('Step 1 picture', 'educators.steps.0.image'),
			      array('Step 2 picture', 'educators.steps.1.image'),
			      array('Step 3 picture', 'educators.steps.2.image'),
			      array('Step 4 picture', 'educators.steps.3.image')), ''),
		'levels'    => array('Schools and colleges', 'eicon-gallery-grid', 'levels',
			array('eyebrow','headline','headlineEm','lede','note'),
			array(array('High schools picture', 'levels.highSchool.image')), ''),
		'mission'   => array('Mission', 'eicon-blockquote', 'mission',
			array('eyebrow','quote','quoteEm'),
			array(array('Picture', 'mission.image')), ''),
		'glossary'  => array('Glossary', 'eicon-book-open', 'glossary',
			array('eyebrow','headline','headlineEm','lede'), array(), 'Glossary'),
		'planner'   => array('Starter planner', 'eicon-form-horizontal', 'planner',
			array('eyebrow','headline','headlineEm','lede'), array(), ''),
		'access'    => array('Access and equipment', 'eicon-lock-user', 'access',
			array('eyebrow','headline','headlineEm','lede','body'),
			array(array('Picture', 'access.image')), ''),
		'licensing' => array('Licensing and price', 'eicon-price-table', 'licensing',
			array('eyebrow','headline','headlineEm','lede','note'), array(), ''),
		'faq'       => array('FAQ', 'eicon-help-o', 'faq',
			array('eyebrow','headline','headlineEm'), array(), 'FAQ'),
		'contact'   => array('Contact form', 'eicon-mail', 'contact',
			array('eyebrow','headline','headlineEm','lede'), array(), ''),
		'closing'   => array('Closing', 'eicon-call-to-action', 'closing',
			array('headline','headlineEm'),
			array(array('Picture', 'closing.image')), ''),
	);
}

// The "build my home page in Elementor" screen under Appearance.
require_once get_template_directory() . '/inc/elementor/page-builder.php';

add_action('elementor/widgets/register', function ($widgets_manager) {
	require_once get_template_directory() . '/inc/elementor/class-section-widget.php';
	foreach (tsa_elementor_sections() as $slug => $spec) {
		$widgets_manager->register(new TSA_Section_Widget(array(), array(
			'tsa_slug'   => $slug,
			'tsa_label'  => $spec[0],
			'tsa_icon'   => $spec[1],
			'tsa_group'  => $spec[2],
			'tsa_fields' => $spec[3],
			'tsa_images' => isset($spec[4]) ? $spec[4] : array(),
			'tsa_posts'  => isset($spec[5]) ? $spec[5] : '',
		)));
	}
});

/* ------------------------------------------------------------------ *
 * Styling
 * ------------------------------------------------------------------ */

/** A small sheet that makes Elementor's own widgets look like the site. */
add_action('wp_enqueue_scripts', function () {
	if (!tsa_elementor_active()) return;
	wp_enqueue_style(
		'tradeskillapps-elementor',
		get_template_directory_uri() . '/assets/css/elementor.css',
		array('tradeskillapps'),
		TSA_VERSION
	);
}, 20);

/**
 * The theme's palette and typefaces, written into Elementor's active kit so
 * anything built alongside the sections matches them. Runs once: the owner's
 * later changes in Site Settings are theirs and are never overwritten.
 * (There is no filter for kit defaults - this goes through the kit document
 * itself, which is the supported way.)
 */
add_action('elementor/init', function () {
	if (get_option('tsa_kit_seeded')) return;
	if (!class_exists('\Elementor\Plugin')) return;
	$plugin = \Elementor\Plugin::$instance;
	if (empty($plugin->kits_manager)) return;
	$kit = $plugin->kits_manager->get_active_kit();
	if (!$kit || !method_exists($kit, 'update_settings')) return;

	$kit->update_settings(array(
		'system_colors' => array(
			array('_id' => 'primary',   'title' => __('Blue', 'tradeskillapps'),  'color' => '#2864E8'),
			array('_id' => 'secondary', 'title' => __('Navy', 'tradeskillapps'),  'color' => '#15283F'),
			array('_id' => 'text',      'title' => __('Slate', 'tradeskillapps'), 'color' => '#64748B'),
			array('_id' => 'accent',    'title' => __('Ice', 'tradeskillapps'),   'color' => '#B9D5F5'),
		),
		'custom_colors' => array(
			array('_id' => 'tsawash',   'title' => __('Page wash', 'tradeskillapps'), 'color' => '#F5F8FC'),
			array('_id' => 'tsawhite',  'title' => __('White', 'tradeskillapps'),     'color' => '#FFFFFF'),
			array('_id' => 'tsaborder', 'title' => __('Border', 'tradeskillapps'),    'color' => '#E2E8F0'),
		),
		'system_typography' => array(
			array('_id' => 'primary',   'title' => __('Headline', 'tradeskillapps'),
				'typography_typography' => 'custom', 'typography_font_family' => 'Barlow Condensed', 'typography_font_weight' => '700'),
			array('_id' => 'secondary', 'title' => __('Subheading', 'tradeskillapps'),
				'typography_typography' => 'custom', 'typography_font_family' => 'Barlow Condensed', 'typography_font_weight' => '600'),
			array('_id' => 'text',      'title' => __('Body', 'tradeskillapps'),
				'typography_typography' => 'custom', 'typography_font_family' => 'DM Sans', 'typography_font_weight' => '400'),
			array('_id' => 'accent',    'title' => __('Body emphasis', 'tradeskillapps'),
				'typography_typography' => 'custom', 'typography_font_family' => 'DM Sans', 'typography_font_weight' => '500'),
		),
	));
	update_option('tsa_kit_seeded', 1);
}, 20);

/**
 * The theme self-hosts Barlow Condensed and DM Sans. Telling Elementor they
 * are system fonts stops it fetching the same families from Google again.
 */
add_filter('elementor/fonts/additional_fonts', function ($fonts) {
	$fonts['Barlow Condensed'] = 'system';
	$fonts['DM Sans'] = 'system';
	return $fonts;
});
