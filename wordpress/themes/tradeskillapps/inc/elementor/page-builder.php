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

	foreach (tsa_elementor_sections() as $slug => $spec) {
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

		<?php if ($existing) : ?>
			<p>
				<a class="button button-primary" href="<?php echo esc_url(admin_url('post.php?post=' . $existing . '&action=elementor')); ?>">
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
