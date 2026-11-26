<?php
/**
 * The parts of the page that are a list the owner grows or shrinks.
 *
 * These are posts rather than Customizer fields, because adding a seventh
 * trade or a fourth video should be "Add New", not a developer job.
 */
defined('ABSPATH') || exit;

function tsa_register_post_types() {
	$common = array(
		'public'              => false,   // no stray single pages on the front end
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'publicly_queryable'  => false,
		'has_archive'         => false,
		'hierarchical'        => false,
		'supports'            => array('title', 'page-attributes'),
		'menu_position'       => 21,
	);

	register_post_type('tsa_trade', array_merge($common, array(
		'labels' => tsa_labels(__('Trade', 'tradeskillapps'), __('Trades', 'tradeskillapps')),
		'menu_icon' => 'dashicons-hammer',
		'supports'  => array('title', 'thumbnail', 'page-attributes'),
	)));




	// Where contact-form enquiries are kept. Read-only in spirit: entries are
	// created by the form, not by hand, so "Add New" is switched off.
}
add_action('init', 'tsa_register_post_types');

function tsa_labels($single, $plural) {
	return array(
		'name' => $plural, 'singular_name' => $single,
		'add_new_item' => sprintf(__('Add %s', 'tradeskillapps'), $single),
		'edit_item'    => sprintf(__('Edit %s', 'tradeskillapps'), $single),
		'new_item'     => sprintf(__('New %s', 'tradeskillapps'), $single),
		'all_items'    => $plural,
		'menu_name'    => $plural,
		'not_found'    => sprintf(__('No %s yet.', 'tradeskillapps'), strtolower($plural)),
	);
}

/** Which extra fields each type carries. */
function tsa_meta_fields($type) {
	if ($type === 'tsa_trade') {
		return array(
			'title'  => array(__('Panel heading', 'tradeskillapps'), 'text', __('The large heading shown when this trade is selected.', 'tradeskillapps')),
			'desc'   => array(__('Description', 'tradeskillapps'), 'textarea', ''),
			'skills' => array(__('Skills practised', 'tradeskillapps'), 'textarea', __('One per line.', 'tradeskillapps')),
			'goal'   => array(__('Learning goal', 'tradeskillapps'), 'textarea', ''),
			'sample' => array(__('Sample activity', 'tradeskillapps'), 'textarea', ''),
			'icon'   => array(__('Icon', 'tradeskillapps'), 'icon', __('Drawn to match the set - pick the closest.', 'tradeskillapps')),
		);
	}
	return array();
}

function tsa_icon_choices() {
	return array(
		'i-weld' => __('Welding', 'tradeskillapps'),
		'i-hvac' => __('HVAC', 'tradeskillapps'),
		'i-elec' => __('Electrical', 'tradeskillapps'),
		'i-manu' => __('Manufacturing', 'tradeskillapps'),
		'i-cons' => __('Construction', 'tradeskillapps'),
		'i-tech' => __('Emerging technology', 'tradeskillapps'),
		'i-drone' => __('Drone technology', 'tradeskillapps'),
	);
}

function tsa_add_meta_boxes() {
	foreach (array('tsa_trade') as $type) {
		add_meta_box('tsa_fields', __('Details', 'tradeskillapps'), 'tsa_render_meta_box', $type, 'normal', 'high');
	}
}
add_action('add_meta_boxes', 'tsa_add_meta_boxes');

function tsa_render_meta_box($post) {
	wp_nonce_field('tsa_save_meta', 'tsa_meta_nonce');
	echo '<style>.tsa-f{margin:0 0 18px}.tsa-f label{display:block;font-weight:600;margin:0 0 4px}
	.tsa-f input[type=text],.tsa-f textarea,.tsa-f select{width:100%}.tsa-f p{margin:4px 0 0;color:#646970}</style>';
	foreach (tsa_meta_fields($post->post_type) as $key => $f) {
		list($label, $type, $hint) = $f;
		$id    = 'tsa_' . $key;
		$value = get_post_meta($post->ID, '_' . $id, true);
		echo '<div class="tsa-f"><label for="' . esc_attr($id) . '">' . esc_html($label) . '</label>';
		if ($type === 'textarea') {
			printf('<textarea id="%s" name="%s" rows="3">%s</textarea>', esc_attr($id), esc_attr($id), esc_textarea($value));
		} elseif ($type === 'icon') {
			printf('<select id="%s" name="%s">', esc_attr($id), esc_attr($id));
			foreach (tsa_icon_choices() as $v => $l) {
				printf('<option value="%s"%s>%s</option>', esc_attr($v), selected($value, $v, false), esc_html($l));
			}
			echo '</select>';
		} else {
			printf('<input type="text" id="%s" name="%s" value="%s" />', esc_attr($id), esc_attr($id), esc_attr($value));
		}
		if ($hint) echo '<p>' . esc_html($hint) . '</p>';
		echo '</div>';
	}
}

function tsa_save_meta($post_id, $post) {
	if (!isset($_POST['tsa_meta_nonce']) || !wp_verify_nonce($_POST['tsa_meta_nonce'], 'tsa_save_meta')) return;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	foreach (tsa_meta_fields($post->post_type) as $key => $f) {
		$id = 'tsa_' . $key;
		if (!isset($_POST[$id])) continue;
		$raw = wp_unslash($_POST[$id]);
		if (!is_string($raw)) continue;   // an array here would fatal below and abort the save
		if ($f[1] === 'textarea') {
			$clean = sanitize_textarea_field($raw);
		} elseif ($f[1] === 'icon') {
			$clean = array_key_exists($raw, tsa_icon_choices()) ? $raw : 'i-weld';
		} else {
			$clean = sanitize_text_field($raw);
		}
		update_post_meta($post_id, '_' . $id, $clean);
	}
}
add_action('save_post', 'tsa_save_meta', 10, 2);

/** Fetch a type's entries in the owner's chosen order. */
function tsa_items($type, $limit = 50) {
	return get_posts(array(
		'post_type'      => $type,
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array('menu_order' => 'ASC', 'date' => 'ASC'),
	));
}

/**
 * Keep the shipped entries in step with the theme.
 *
 * This used to run once and never again, which meant a site activated last
 * month never received anything added since - a new trade stayed invisible,
 * and a film added to the defaults never reached the video that was already
 * there. It now runs whenever the theme version changes, and does two
 * careful things:
 *
 *   1. Creates any shipped entry whose slug is missing.
 *   2. Fills a field on an existing entry ONLY where that field is currently
 *      empty and the theme now ships something for it.
 *
 * It never replaces a value the owner has written. Anything they typed, or
 * deliberately cleared and then typed over, stays exactly as it is; a field
 * they cleared and left empty may be refilled, which is the one trade-off
 * and the reason the rule is "empty only".
 */
function tsa_sync_shipped_content() {
	$d = tsa_defaults();

	/* ---- trades ---- */
	foreach ((isset($d['trades']) ? $d['trades'] : array()) as $i => $t) {
		$meta = array(
			'_tsa_title'  => $t['title'],
			'_tsa_desc'   => $t['desc'],
			'_tsa_skills' => implode("\n", $t['skills']),
			'_tsa_goal'   => $t['goal'],
			'_tsa_sample' => $t['sample'],
			'_tsa_icon'   => $t['icon'],
		);
		if (!empty($t['image']['src'])) {
			$meta['_tsa_shipped_image'] = $t['image']['src'];
			$meta['_tsa_shipped_alt']   = isset($t['image']['alt']) ? $t['image']['alt'] : '';
		}
		tsa_upsert_shipped('tsa_trade', $t['slug'], $t['name'], '', $i, $meta);
	}

	/* ---- videos ---- */


	update_option('tsa_seeded', 1);
	update_option('tsa_shipped_version', TSA_VERSION);
}

function tsa_upsert_shipped($type, $slug, $title, $content, $order, $meta) {
	$existing = $slug ? get_page_by_path($slug, OBJECT, $type) : null;

	if (!$existing) {
		$id = wp_insert_post(array(
			'post_type'    => $type,
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'menu_order'   => $order,
		));
		if (!$id || is_wp_error($id)) return 0;
		foreach ($meta as $k => $v) {
			if ($v !== '') update_post_meta($id, $k, $v);
		}
		return $id;
	}

	// Already there: top up blanks, touch nothing else.
	foreach ($meta as $k => $v) {
		if ($v === '') continue;
		$current = get_post_meta($existing->ID, $k, true);
		if ($current === '' || $current === null) update_post_meta($existing->ID, $k, $v);
	}
	return $existing->ID;
}

add_action('after_switch_theme', 'tsa_sync_shipped_content');

/**
 * Also run after an update, since updating a theme does not switch to it.
 * Comparing the stored version means this costs one option read per request
 * and does nothing at all until the theme actually changes.
 */
function tsa_maybe_sync_shipped_content() {
	if (!is_admin()) return;
	if (get_option('tsa_shipped_version') === TSA_VERSION) return;
	tsa_sync_shipped_content();
}
add_action('admin_init', 'tsa_maybe_sync_shipped_content');

/** A trade's picture: the owner's featured image, else the one it shipped with. */
function tsa_post_image($post_id) {
	if (has_post_thumbnail($post_id)) {
		return array(
			'src' => get_the_post_thumbnail_url($post_id, 'full'),
			'alt' => get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true),
		);
	}
	$shipped = get_post_meta($post_id, '_tsa_shipped_image', true);
	if ($shipped) {
		return array(
			'src' => tsa_asset($shipped),
			'alt' => get_post_meta($post_id, '_tsa_shipped_alt', true),
		);
	}
	return array('src' => '', 'alt' => '');
}

function tsa_lines_of($raw) {
	if (is_array($raw)) return array_values(array_filter(array_map('trim', $raw), 'strlen'));
	return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $raw)), 'strlen'));
}

/**
 * The trade cards, from a page builder's own list if one was given for this
 * placement, otherwise from Trades in the admin menu.
 * Each row: slug, name, icon, title, desc, skills[], goal, sample, image.
 */
function tsa_trade_items() {
	$o = &tsa_overrides();
	$rows = array();

	if (isset($o['trades.items']) && is_array($o['trades.items'])) {
		foreach ($o['trades.items'] as $i => $row) {
			if (!is_array($row)) continue;
			$name = isset($row['name']) ? (string) $row['name'] : '';
			$rows[] = array(
				'slug'   => sanitize_title($name !== '' ? $name : 'trade-' . ($i + 1)),
				'name'   => $name,
				'icon'   => (isset($row['icon']) && array_key_exists($row['icon'], tsa_icon_choices())) ? $row['icon'] : 'i-weld',
				'title'  => isset($row['title']) ? (string) $row['title'] : '',
				'desc'   => isset($row['desc']) ? (string) $row['desc'] : '',
				'skills' => tsa_lines_of(isset($row['skills']) ? $row['skills'] : ''),
				'goal'   => isset($row['goal']) ? (string) $row['goal'] : '',
				'sample' => isset($row['sample']) ? (string) $row['sample'] : '',
				'image'  => array(
					'src' => isset($row['image']) ? (string) $row['image'] : '',
					'alt' => isset($row['alt']) ? (string) $row['alt'] : '',
				),
			);
		}
		return $rows;
	}

	foreach (tsa_items('tsa_trade') as $t) {
		$rows[] = array(
			'slug'   => $t->post_name,
			'name'   => $t->post_title,
			'icon'   => get_post_meta($t->ID, '_tsa_icon', true) ? get_post_meta($t->ID, '_tsa_icon', true) : 'i-weld',
			'title'  => (string) get_post_meta($t->ID, '_tsa_title', true),
			'desc'   => (string) get_post_meta($t->ID, '_tsa_desc', true),
			'skills' => tsa_lines_of(get_post_meta($t->ID, '_tsa_skills', true)),
			'goal'   => (string) get_post_meta($t->ID, '_tsa_goal', true),
			'sample' => (string) get_post_meta($t->ID, '_tsa_sample', true),
			'image'  => tsa_post_image($t->ID),
		);
	}
	return $rows;
}

function tsa_seed_content() { tsa_sync_shipped_content(); }
