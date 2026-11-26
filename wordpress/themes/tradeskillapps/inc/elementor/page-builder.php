<?php
/**
 * "Build my home page in Elementor".
 *
 * Creates a real Page containing all seventeen sections already placed, sets
 * it as the site's front page, and hands it to Elementor. The point is that
 * the owner opens the builder and sees their finished site - not an empty
 * canvas they have to assemble first.
 *
 * Nothing is destroyed: the page is new, and switching Settings > Reading
 * back to "Your latest posts" restores the original PHP home page exactly.
 */
defined('ABSPATH') || exit;

// The list of sections the home page carries. Required here as well as
// from functions.php so this file does not depend on load order.
require_once get_template_directory() . '/inc/pages.php';

/**
 * The page's Elementor document: one full-width section per website
 * section, each holding one of the theme's widgets.
 *
 * Sections and columns carry zero padding because each of the theme's
 * sections brings its own spacing and runs edge to edge.
 */
function tsa_elementor_home_elements() {
	$zero = array('unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true);
	$out  = array();

	// The home page the builder offers is the front page, section for
	// section - not every section the theme can draw. The rest live on the
	// inner pages and are still available as widgets in the panel.
	$sections = tsa_elementor_sections();
	foreach (tsa_front_sections() as $slug) {
		if (!isset($sections[$slug])) continue;
		$spec = $sections[$slug];
		$out[] = array(
			'id'       => substr(md5('tsa-section-' . $slug), 0, 7),
			'elType'   => 'section',
			'settings' => array(
				'layout'                => 'full_width',
				'gap'                   => 'no',
				'content_position'      => 'top',
				'padding'               => $zero,
				'margin'                => $zero,
				'structure'             => '10',
			),
			'elements' => array(array(
				'id'       => substr(md5('tsa-column-' . $slug), 0, 7),
				'elType'   => 'column',
				'settings' => array(
					'_column_size' => 100,
					'_inline_size' => null,
					'padding'      => $zero,
					'margin'       => $zero,
				),
				'elements' => array(array(
					'id'         => substr(md5('tsa-widget-' . $slug), 0, 7),
					'elType'     => 'widget',
					'widgetType' => 'tsa-' . $slug,
					'settings'   => new stdClass(),
				)),
			)),
		);
	}

	return $out;
}

/** The page this tool made last time, if it is still there. */
function tsa_elementor_home_page() {
	$id = (int) get_option('tsa_elementor_home_id');
	if (!$id) return 0;
	$post = get_post($id);
	return ($post && $post->post_type === 'page' && $post->post_status !== 'trash') ? $id : 0;
}

/**
 * Create (or rebuild) the page and point the site at it.
 * Returns the page id, or a WP_Error.
 */
function tsa_build_elementor_home() {
	if (!tsa_elementor_active()) {
		return new WP_Error('no_elementor', __('Elementor is not active.', 'tradeskillapps'));
	}

	$id = tsa_elementor_home_page();
	if (!$id) {
		$id = wp_insert_post(array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => __('Home', 'tradeskillapps'),
			'post_content' => '',
		), true);
		if (is_wp_error($id)) return $id;
	}

	// Elementor reads this as a JSON string and WordPress strips slashes on
	// the way in, so it has to go in slashed.
	update_post_meta($id, '_elementor_data', wp_slash(wp_json_encode(tsa_elementor_home_elements())));
	update_post_meta($id, '_elementor_edit_mode', 'builder');
	update_post_meta($id, '_elementor_template_type', 'wp-page');
	update_post_meta($id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
	// Keeps the theme's header and footer, drops the content column.
	update_post_meta($id, '_wp_page_template', 'elementor_header_footer');
	// Written straight to the database, so Elementor has no cached CSS for
	// it yet; clearing the meta makes it regenerate on the next view.
	delete_post_meta($id, '_elementor_css');

	update_option('show_on_front', 'page');
	update_option('page_on_front', $id);
	update_option('tsa_elementor_home_id', $id);

	if (class_exists('\Elementor\Plugin') && !empty(\Elementor\Plugin::$instance->files_manager)) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	return $id;
}

/** The TSA sections a built page already contains, as slugs. */
function tsa_elementor_home_present($post_id) {
	$raw = get_post_meta($post_id, '_elementor_data', true);
	if (!$raw) return array();
	$data = is_string($raw) ? json_decode($raw, true) : $raw;
	if (!is_array($data)) return array();

	$found = array();
	$walk = function ($nodes) use (&$walk, &$found) {
		foreach ((array) $nodes as $n) {
			if (!is_array($n)) continue;
			if (isset($n['widgetType']) && strpos($n['widgetType'], 'tsa-') === 0) {
				$found[] = substr($n['widgetType'], 4);
			}
			if (!empty($n['elements'])) $walk($n['elements']);
		}
	};
	$walk($data);
	return array_values(array_unique($found));
}

/** Sections the theme offers that the built page has not got. */
function tsa_elementor_home_missing($post_id) {
	$present = tsa_elementor_home_present($post_id);
	return array_values(array_diff(tsa_front_sections(), $present));
}

/**
 * Sections on the built page that are no longer part of the home page -
 * the ones that moved onto the inner pages. A page built against an older
 * version of this theme is full of them, which is why installing the new
 * theme can appear to change nothing: front-page.php hands over to this
 * page, and this page still holds the old layout.
 */
function tsa_elementor_home_extra($post_id) {
	$present = tsa_elementor_home_present($post_id);
	return array_values(array_diff($present, tsa_front_sections()));
}

/**
 * Add the sections a built page is missing, without disturbing it.
 *
 * A page built from these widgets freezes at whatever sections existed that
 * day: adding one to the theme does not add it to a page already made. This
 * walks the theme's section order and keeps every element the page already
 * has - settings, styling, edits and all - inserting only the ones that are
 * absent, each in its proper place. Anything the owner added themselves is
 * kept and moved to the end rather than discarded.
 */
function tsa_update_elementor_home($post_id, $drop_extra = false) {
	$raw  = get_post_meta($post_id, '_elementor_data', true);
	$data = $raw ? json_decode(is_string($raw) ? $raw : wp_json_encode($raw), true) : array();
	if (!is_array($data)) $data = array();

	// index the page's top-level blocks by the TSA section they carry
	$byslug = array();
	$theirs = array();
	foreach ($data as $node) {
		$slug = null;
		$probe = function ($n) use (&$probe, &$slug) {
			if ($slug !== null || !is_array($n)) return;
			if (isset($n['widgetType']) && strpos($n['widgetType'], 'tsa-') === 0) { $slug = substr($n['widgetType'], 4); return; }
			foreach ((array) (isset($n['elements']) ? $n['elements'] : array()) as $c) $probe($c);
		};
		$probe($node);
		if ($slug !== null && !isset($byslug[$slug])) $byslug[$slug] = $node;
		elseif ($slug === null) $theirs[] = $node;     // the owner's own block
	}

	$fresh = tsa_elementor_home_elements();
	$freshby = array();
	foreach ($fresh as $node) {
		$t = $node['elements'][0]['elements'][0]['widgetType'];
		$freshby[substr($t, 4)] = $node;
	}

	$out = array();
	$added = array();
	foreach (tsa_front_sections() as $slug) {
		if (isset($byslug[$slug])) { $out[] = $byslug[$slug]; }
		elseif (isset($freshby[$slug])) { $out[] = $freshby[$slug]; $added[] = $slug; }
	}

	// Sections that are no longer part of the home page. By default they
	// are KEPT, because this function's button promises that nothing on
	// the page is touched. They are only removed when the owner has asked
	// for the home page to be rebuilt as the client's page, and even then
	// the content itself is not lost - it is on the inner pages.
	$removed = array();
	foreach (tsa_front_sections() as $slug) unset($byslug[$slug]);
	foreach ($byslug as $slug => $node) {
		if ($drop_extra) { $removed[] = $slug; continue; }
		$out[] = $node;
	}

	foreach ($theirs as $node) $out[] = $node;        // keep what they built

	update_post_meta($post_id, '_elementor_data', wp_slash(wp_json_encode($out)));
	delete_post_meta($post_id, '_elementor_css');
	if (class_exists('\Elementor\Plugin') && !empty(\Elementor\Plugin::$instance->files_manager)) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
	return $drop_extra ? array('added' => $added, 'removed' => $removed) : $added;
}

/** Say so in the admin when the home page has fallen behind the theme. */
add_action('admin_notices', function () {
	if (!current_user_can('edit_theme_options') || !tsa_elementor_active()) return;
	$id = tsa_elementor_home_page();
	if (!$id) return;
	$missing = tsa_elementor_home_missing($id);
	$extra   = tsa_elementor_home_extra($id);

	// The common case after upgrading: the page still holds the old layout,
	// so the site looks unchanged even though the theme is new.
	if ($extra) {
		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s</p><p>%s</p><p><a class="button button-primary" href="%s">%s</a></p></div>',
			esc_html__('Your home page is still the old layout.', 'tradeskillapps'),
			esc_html(sprintf(
				/* translators: %d is a number of sections */
				_n('It was built in Elementor before this theme was rebuilt, and still carries %d section that has moved to one of the inner pages. Because the page wins over the theme, the site can look unchanged after updating.',
				   'It was built in Elementor before this theme was rebuilt, and still carries %d sections that have moved to the inner pages. Because the page wins over the theme, the site can look unchanged after updating.',
				   count($extra), 'tradeskillapps'),
				count($extra)
			)),
			esc_html__('You can snap it back to the six sections of the home page in one click. Nothing is lost - those sections are on the inner pages and in their own admin menus.', 'tradeskillapps'),
			esc_url(admin_url('themes.php?page=tsa-elementor-home')),
			esc_html__('Fix the home page', 'tradeskillapps')
		);
	}

	if (!$missing) return;
	printf(
		'<div class="notice notice-warning"><p><strong>%s</strong> %s</p><p>%s</p><p><a class="button button-primary" href="%s">%s</a></p></div>',
		esc_html__('Your home page is missing sections.', 'tradeskillapps'),
		esc_html(sprintf(
			/* translators: %s is a list of section names */
			__('The theme now provides these, but the page was built before they existed: %s.', 'tradeskillapps'),
			implode(', ', array_map(function ($s) {
				$all = tsa_elementor_sections();
				return isset($all[$s]) ? $all[$s][0] : $s;
			}, $missing))
		)),
		esc_html__('Adding them keeps everything already on the page exactly as it is.', 'tradeskillapps'),
		esc_url(admin_url('themes.php?page=tsa-elementor-home')),
		esc_html__('Add the missing sections', 'tradeskillapps')
	);
});

add_action('admin_post_tsa_update_home', function () {
	if (!current_user_can('edit_theme_options')) {
		wp_die(esc_html__('You do not have permission to do that.', 'tradeskillapps'), '', array('response' => 403));
	}
	check_admin_referer('tsa_update_home', 'tsa_update_home_nonce');
	$id = tsa_elementor_home_page();
	$added = $id ? tsa_update_elementor_home($id) : array();
	wp_safe_redirect(add_query_arg(array('page' => 'tsa-elementor-home', 'added' => count($added)), admin_url('themes.php')));
	exit;
});

/**
 * Rebuild the home page as the client's page: the six sections, in order,
 * and nothing else of ours. Sections that moved to the inner pages are
 * taken off THIS page only - their content lives on those pages and in
 * their own admin menus, so nothing is lost. Blocks the owner added
 * themselves are kept.
 */
add_action('admin_post_tsa_reset_home', function () {
	if (!current_user_can('edit_theme_options')) {
		wp_die(esc_html__('You do not have permission to do that.', 'tradeskillapps'), '', array('response' => 403));
	}
	check_admin_referer('tsa_reset_home', 'tsa_reset_home_nonce');
	$id = tsa_elementor_home_page();
	$r  = $id ? tsa_update_elementor_home($id, true) : array('added' => array(), 'removed' => array());
	wp_safe_redirect(add_query_arg(array(
		'page'    => 'tsa-elementor-home',
		'reset'   => count($r['added']),
		'removed' => count($r['removed']),
	), admin_url('themes.php')));
	exit;
});

/* ------------------------------------------------------------------ *
 * The screen
 * ------------------------------------------------------------------ */

add_action('admin_menu', function () {
	if (!tsa_elementor_active()) return;
	add_theme_page(
		__('Home page in Elementor', 'tradeskillapps'),
		__('Home in Elementor', 'tradeskillapps'),
		'edit_theme_options',
		'tsa-elementor-home',
		'tsa_elementor_home_screen'
	);
});

function tsa_elementor_home_screen() {
	if (!current_user_can('edit_theme_options')) return;

	$existing = tsa_elementor_home_page();
	$is_front = $existing && (int) get_option('page_on_front') === $existing
		&& 'page' === get_option('show_on_front');
	?>
	<div class="wrap">
		<h1><?php esc_html_e('Home page in Elementor', 'tradeskillapps'); ?></h1>

		<?php if (isset($_GET['built'])) : ?>
			<div class="notice notice-success"><p>
				<?php esc_html_e('Done. Your home page is now an Elementor page with every section in place.', 'tradeskillapps'); ?>
			</p></div>
		<?php endif; ?>

		<p style="max-width:46em">
			<?php esc_html_e('By default this site\'s home page is built in code: fast, but it does not appear under Pages and Elementor cannot open it.', 'tradeskillapps'); ?>
		</p>
		<p style="max-width:46em">
			<?php esc_html_e('This button creates a real page containing the same seventeen sections, already arranged in order, and makes it the home page. You can then open it in Elementor and change pictures, reorder sections or add anything else, with the site in front of you.', 'tradeskillapps'); ?>
		</p>

		<?php if (isset($_GET['added'])) : ?>
			<div class="notice notice-success"><p><?php
				$n = (int) $_GET['added'];
				echo esc_html($n
					? sprintf(_n('%d section added to the home page.', '%d sections added to the home page.', $n, 'tradeskillapps'), $n)
					: __('The home page was already up to date.', 'tradeskillapps'));
			?></p></div>
		<?php endif; ?>

		<?php if (isset($_GET['reset'])) : ?>
			<div class="notice notice-success"><p><?php
				$rm = isset($_GET['removed']) ? (int) $_GET['removed'] : 0;
				echo esc_html(sprintf(
					/* translators: %d is a number of sections */
					_n('Home page rebuilt. %d section that had moved to an inner page was taken off it.',
					   'Home page rebuilt. %d sections that had moved to the inner pages were taken off it.',
					   $rm, 'tradeskillapps'),
					$rm
				));
			?></p></div>
		<?php endif; ?>

		<?php if ($existing) : $extra = tsa_elementor_home_extra($existing); ?>
			<?php if ($extra) : ?>
				<div class="notice notice-warning inline"><p>
					<strong><?php esc_html_e('This page is still the old layout.', 'tradeskillapps'); ?></strong>
					<?php
					$all = tsa_elementor_sections();
					echo esc_html(sprintf(
						/* translators: %s is a list of section names */
						__('It carries sections that have moved to the inner pages: %s. While they are here, the home page does not match the design.', 'tradeskillapps'),
						implode(', ', array_map(function ($m) use ($all) {
							return isset($all[$m]) ? $all[$m][0] : $m;
						}, $extra))
					));
					?>
				</p></div>
				<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
					<input type="hidden" name="action" value="tsa_reset_home" />
					<?php wp_nonce_field('tsa_reset_home', 'tsa_reset_home_nonce'); ?>
					<p><button type="submit" class="button button-primary">
						<?php esc_html_e('Rebuild the home page to the six sections', 'tradeskillapps'); ?>
					</button>
					<span class="description"><?php esc_html_e('Those sections come off this page only. Their content stays on the inner pages and in their admin menus, and any block you added yourself is kept.', 'tradeskillapps'); ?></span></p>
				</form>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ($existing) : $missing = tsa_elementor_home_missing($existing); ?>
			<?php if ($missing) : ?>
				<div class="notice notice-warning inline"><p>
					<strong><?php esc_html_e('This page is missing sections the theme now provides:', 'tradeskillapps'); ?></strong>
					<?php
					$all = tsa_elementor_sections();
					echo esc_html(implode(', ', array_map(function ($m) use ($all) {
						return isset($all[$m]) ? $all[$m][0] : $m;
					}, $missing)));
					?>
				</p></div>
				<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
					<input type="hidden" name="action" value="tsa_update_home" />
					<?php wp_nonce_field('tsa_update_home', 'tsa_update_home_nonce'); ?>
					<p><button type="submit" class="button button-primary">
						<?php esc_html_e('Add the missing sections', 'tradeskillapps'); ?>
					</button>
					<span class="description"><?php esc_html_e('Everything already on the page stays exactly as it is.', 'tradeskillapps'); ?></span></p>
				</form>
			<?php endif; ?>
			<p>
				<a class="button" href="<?php echo esc_url(admin_url('post.php?post=' . $existing . '&action=elementor')); ?>">
					<?php esc_html_e('Edit the home page with Elementor', 'tradeskillapps'); ?>
				</a>
				<a class="button" href="<?php echo esc_url(get_permalink($existing)); ?>">
					<?php esc_html_e('View it', 'tradeskillapps'); ?>
				</a>
			</p>
			<?php if (!$is_front) : ?>
				<div class="notice notice-warning inline"><p>
					<?php esc_html_e('That page exists but is not currently set as the home page. Rebuilding below will point the site back at it.', 'tradeskillapps'); ?>
				</p></div>
			<?php endif; ?>
			<hr>
			<h2><?php esc_html_e('Start it again', 'tradeskillapps'); ?></h2>
			<p style="max-width:46em">
				<?php esc_html_e('Rebuilding replaces that page\'s layout with the seventeen sections again, in their original order. Anything you changed on it in Elementor is lost; your text, pictures, trades and videos are not affected.', 'tradeskillapps'); ?>
			</p>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
			<input type="hidden" name="action" value="tsa_build_home" />
			<?php wp_nonce_field('tsa_build_home', 'tsa_build_home_nonce'); ?>
			<p>
				<button type="submit" class="button <?php echo $existing ? '' : 'button-primary'; ?>"
					<?php if ($existing) : ?>onclick="return confirm('<?php echo esc_js(__('Rebuild the layout of that page? Anything you changed on it in Elementor will be lost.', 'tradeskillapps')); ?>')"<?php endif; ?>>
					<?php echo $existing
						? esc_html__('Rebuild the home page layout', 'tradeskillapps')
						: esc_html__('Build my home page in Elementor', 'tradeskillapps'); ?>
				</button>
			</p>
		</form>

		<hr>
		<h2><?php esc_html_e('Going back', 'tradeskillapps'); ?></h2>
		<p style="max-width:46em">
			<?php esc_html_e('Set Settings > Reading > Your homepage displays back to "Your latest posts" and the original coded home page returns, exactly as it was. Nothing is lost either way, and you can switch as often as you like.', 'tradeskillapps'); ?>
		</p>
	</div>
	<?php
}

add_action('admin_post_tsa_build_home', function () {
	if (!current_user_can('edit_theme_options')) {
		wp_die(esc_html__('You do not have permission to do that.', 'tradeskillapps'), '', array('response' => 403));
	}
	check_admin_referer('tsa_build_home', 'tsa_build_home_nonce');

	$id = tsa_build_elementor_home();
	$args = is_wp_error($id)
		? array('page' => 'tsa-elementor-home', 'failed' => 1)
		: array('page' => 'tsa-elementor-home', 'built' => $id);

	wp_safe_redirect(add_query_arg($args, admin_url('themes.php')));
	exit;
});
