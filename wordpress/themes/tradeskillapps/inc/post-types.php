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

	register_post_type('tsa_video', array_merge($common, array(
		'labels' => tsa_labels(__('Video', 'tradeskillapps'), __('Videos', 'tradeskillapps')),
		'menu_icon' => 'dashicons-video-alt3',
		'supports'  => array('title', 'thumbnail', 'page-attributes'),
	)));

	register_post_type('tsa_faq', array_merge($common, array(
		'labels' => tsa_labels(__('FAQ entry', 'tradeskillapps'), __('FAQ', 'tradeskillapps')),
		'menu_icon' => 'dashicons-editor-help',
		'supports'  => array('title', 'editor', 'page-attributes'),
	)));

	register_post_type('tsa_term', array_merge($common, array(
		'labels' => tsa_labels(__('Glossary term', 'tradeskillapps'), __('Glossary', 'tradeskillapps')),
		'menu_icon' => 'dashicons-book-alt',
		'supports'  => array('title', 'editor', 'page-attributes'),
	)));

	// Where contact-form enquiries are kept. Read-only in spirit: entries are
	// created by the form, not by hand, so "Add New" is switched off.
	register_post_type('tsa_msg', array_merge($common, array(
		'labels' => tsa_labels(__('Enquiry', 'tradeskillapps'), __('Enquiries', 'tradeskillapps')),
		'menu_icon' => 'dashicons-email-alt',
		'supports'  => array('title', 'editor'),
		'capabilities' => array('create_posts' => 'do_not_allow'),
		'map_meta_cap' => true,
	)));
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
	if ($type === 'tsa_video') {
		return array(
			'blurb' => array(__('Description', 'tradeskillapps'), 'textarea', ''),
			'embed' => array(__('YouTube video id', 'tradeskillapps'), 'text', __('The part after v= in a YouTube address. Easiest option - leave the box below empty.', 'tradeskillapps')),
			'file'  => array(__('Video file URL', 'tradeskillapps'), 'text', __('Only if the film is uploaded to this site instead.', 'tradeskillapps')),
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
	);
}

function tsa_add_meta_boxes() {
	foreach (array('tsa_trade', 'tsa_video') as $type) {
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
 * First run: create the entries the site ships with, so the theme looks
 * finished immediately. Runs once; never overwrites anything already there.
 */
function tsa_seed_content() {
	if (get_option('tsa_seeded')) return;

	$d = tsa_defaults();

	foreach ((isset($d['trades']) ? $d['trades'] : array()) as $i => $t) {
		if (get_page_by_path($t['slug'], OBJECT, 'tsa_trade')) continue;
		$id = wp_insert_post(array(
			'post_type' => 'tsa_trade', 'post_status' => 'publish',
			'post_title' => $t['name'], 'post_name' => $t['slug'], 'menu_order' => $i,
		));
		if (!$id || is_wp_error($id)) continue;
		update_post_meta($id, '_tsa_title',  $t['title']);
		update_post_meta($id, '_tsa_desc',   $t['desc']);
		update_post_meta($id, '_tsa_skills', implode("\n", $t['skills']));
		update_post_meta($id, '_tsa_goal',   $t['goal']);
		update_post_meta($id, '_tsa_sample', $t['sample']);
		update_post_meta($id, '_tsa_icon',   $t['icon']);
		if (!empty($t['image']['src'])) {
			update_post_meta($id, '_tsa_shipped_image', $t['image']['src']);
			update_post_meta($id, '_tsa_shipped_alt',   isset($t['image']['alt']) ? $t['image']['alt'] : '');
		}
	}

	foreach ((isset($d['videos']['items']) ? $d['videos']['items'] : array()) as $i => $v) {
		if (get_page_by_path($v['id'], OBJECT, 'tsa_video')) continue;
		$id = wp_insert_post(array(
			'post_type' => 'tsa_video', 'post_status' => 'publish',
			'post_title' => $v['title'], 'post_name' => $v['id'], 'menu_order' => $i,
		));
		if (!$id || is_wp_error($id)) continue;
		update_post_meta($id, '_tsa_blurb', $v['blurb']);
		update_post_meta($id, '_tsa_embed', $v['embed']);
		update_post_meta($id, '_tsa_file',  $v['file']);
	}

	foreach ((isset($d['faq']['items']) ? $d['faq']['items'] : array()) as $i => $f) {
		$slug = sanitize_title($f['q']);
		if (get_page_by_path($slug, OBJECT, 'tsa_faq')) continue;
		wp_insert_post(array(
			'post_type' => 'tsa_faq', 'post_status' => 'publish', 'post_name' => $slug,
			'post_title' => $f['q'], 'post_content' => $f['a'], 'menu_order' => $i,
		));
	}

	foreach ((isset($d['glossary']['items']) ? $d['glossary']['items'] : array()) as $i => $g) {
		$slug = sanitize_title($g['term']);
		if (get_page_by_path($slug, OBJECT, 'tsa_term')) continue;
		wp_insert_post(array(
			'post_type' => 'tsa_term', 'post_status' => 'publish', 'post_name' => $slug,
			'post_title' => $g['term'], 'post_content' => $g['def'], 'menu_order' => $i,
		));
	}

	update_option('tsa_seeded', 1);
}
add_action('after_switch_theme', 'tsa_seed_content');

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
