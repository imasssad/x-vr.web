<?php
/**
 * Reading content.
 *
 * Every string on the page goes through tsa_text(). It returns what the site
 * owner saved in the Customizer, or the shipped default when they have not
 * touched it. That is why the theme looks finished the moment it is activated.
 */
defined('ABSPATH') || exit;

/** 'hero.primaryCta.label' -> 'tsa_hero_primarycta_label' */
function tsa_key($path) {
	return 'tsa_' . strtolower(str_replace('.', '_', $path));
}

/**
 * A file that ships with the theme. The stored paths look like
 * "/assets/images/x.webp", which is already relative to the theme folder.
 */
function tsa_asset($path) {
	return get_template_directory_uri() . '/' . ltrim($path, '/');
}

/**
 * A srcset whose candidates are shipped theme paths.
 *
 * The stored value looks like "/assets/images/x-800.webp 800w, ...", which is
 * relative to the THEME, not the site root — WordPress serves the theme from
 * /wp-content/themes/<name>/, so each candidate has to be resolved the same
 * way a plain src is. Left raw, the browser prefers the srcset and every
 * candidate 404s.
 */
function tsa_srcset($raw) {
	if (!is_string($raw) || trim($raw) === '') return '';
	$out = array();
	foreach (explode(',', $raw) as $candidate) {
		$candidate = trim($candidate);
		if ($candidate === '') continue;
		$bits = preg_split('/\s+/', $candidate, 2);
		$url  = $bits[0];
		if (strpos($url, '/assets/') === 0) $url = tsa_asset($url);
		$out[] = isset($bits[1]) ? $url . ' ' . $bits[1] : $url;
	}
	return implode(', ', $out);
}

/** Is that shipped file actually present? */
function tsa_asset_exists($path) {
	return file_exists(get_template_directory() . '/' . ltrim($path, '/'));
}

/**
 * Links in the content are written as "#section" anchors. On the front page
 * that is exactly right; on any other page a bare fragment is a dead link,
 * so it is rooted to the home page where the sections live.
 */
function tsa_href($href) {
	return (is_string($href) && isset($href[0]) && $href[0] === '#')
		? home_url('/') . $href
		: $href;
}

/**
 * A page-unique id SUFFIX. Sections can now be placed on any page more than
 * once (Elementor widgets), and ids must never collide. The first instance
 * gets no suffix, so the home page's markup stays exactly as it always was;
 * later instances get '-2', '-3', ...
 */
function tsa_uid($prefix) {
	$n = &tsa_uid_store();
	$n[$prefix] = isset($n[$prefix]) ? $n[$prefix] + 1 : 1;
	return $n[$prefix] === 1 ? '' : '-' . $n[$prefix];
}

/** The counters behind tsa_uid; a function so tests can reset them. */
function &tsa_uid_store() {
	static $n = array();
	return $n;
}

function tsa_uid_reset() {
	$n = &tsa_uid_store();
	$n = array();
}

/** The shipped value at a dot path, or null. */
function tsa_default($path) {
	$node = tsa_defaults();
	foreach (explode('.', $path) as $part) {
		if (is_array($node) && array_key_exists($part, $node)) {
			$node = $node[$part];
		} elseif (is_array($node) && ctype_digit($part) && isset($node[(int) $part])) {
			$node = $node[(int) $part];
		} else {
			return null;
		}
	}
	return $node;
}

/**
 * Values set for the duration of one render, used when a section is placed on
 * a page by a page builder and given its own wording there. Empty otherwise.
 */
function &tsa_overrides() {
	static $o = array();
	return $o;
}

/** Replace the whole override set; returns what was there before. */
function tsa_set_overrides($values) {
	$o = &tsa_overrides();
	$was = $o;
	$o = is_array($values) ? array_filter($values, function ($v) { return $v !== '' && $v !== null; }) : array();
	return $was;
}

/** A single editable string, escaped for text content. */
function tsa_text($path) {
	$o = &tsa_overrides();
	if (isset($o[$path])) return (string) $o[$path];
	$v = get_theme_mod(tsa_key($path), tsa_default($path));
	return is_string($v) ? $v : '';
}

/** Same, echoed and escaped. */
function tsa_e($path) { echo esc_html(tsa_text($path)); }

/**
 * A list of short strings. Stored in the Customizer as one per line, so the
 * owner adds or removes bullets by pressing Enter.
 */
function tsa_lines($path) {
	$o = &tsa_overrides();
	$saved = isset($o[$path]) ? $o[$path] : get_theme_mod(tsa_key($path), null);
	if (is_string($saved) && trim($saved) !== '') {
		$out = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $saved)), 'strlen'));
		if ($out) return $out;
	}
	$d = tsa_default($path);
	return is_array($d) ? $d : array();
}

/** An image: returns array('src' => url, 'alt' => text). src is '' when cleared. */
function tsa_image($path) {
	$o = &tsa_overrides();
	$src = isset($o[$path . '.src']) ? $o[$path . '.src'] : get_theme_mod(tsa_key($path . '.src'), null);
	if ($src === null) {
		$d = tsa_default($path . '.src');
		// defaults are paths from the old build; map them onto the theme folder
		$src = $d ? tsa_asset($d) : '';
	}
	if (is_numeric($src)) {                       // an attachment chosen in the Customizer
		$url = wp_get_attachment_image_url((int) $src, 'full');
		$src = $url ? $url : '';
	}
	if (!is_string($src)) $src = '';              // never let a stray array fatal the page
	return array(
		'src' => $src,
		'alt' => tsa_text($path . '.alt'),
	);
}

/** Echo an <img> for a path, or the "to be supplied" placeholder when cleared. */
function tsa_img($path, $class = '', $lazy = true) {
	$img = tsa_image($path);
	if ($img['src'] === '') {
		echo '<span class="frame__empty">' . esc_html__('Photograph to be supplied', 'tradeskillapps') . '</span>';
		return;
	}
	printf(
		'<img src="%s" alt="%s"%s decoding="async"%s />',
		esc_url($img['src']),
		esc_attr($img['alt']),
		$lazy ? ' loading="lazy"' : '',
		$class ? ' class="' . esc_attr($class) . '"' : ''
	);
}

/** A link pair, e.g. 'hero.primaryCta' -> array('label','href'). */
function tsa_link($path) {
	return array(
		'label' => tsa_text($path . '.label'),
		'href'  => tsa_text($path . '.href'),
	);
}

/** Heading with its highlighted second half, the two-tone pattern used throughout. */
function tsa_heading($base, $id = '', $class = '') {
	$a = tsa_text($base . '.headline');
	$b = tsa_text($base . '.headlineEm');
	printf('<h2%s class="rv %s">%s%s</h2>',
		$id ? ' id="' . esc_attr($id) . '"' : '',
		esc_attr($class),
		esc_html($a),
		$b !== '' ? ' <em>' . esc_html($b) . '</em>' : ''
	);
}

/** A framed picture, with the optional caption strip under it. */
function tsa_figure($path, $ratio, $caption_path = null, $style = '', $class = '') {
	$img    = tsa_image($path);
	$srcset = tsa_srcset(tsa_text($path . '.srcSet'));
	$styles = 'aspect-ratio:' . $ratio . ($style ? ';' . $style : '');
	printf('<figure class="frame frame--zoom rv %s" style="%s">', esc_attr($class), esc_attr($styles));

	if ($img['src'] !== '') {
		$shipped = strpos($img['src'], '/assets/') !== false;
		printf('<img src="%s"%s alt="%s" loading="lazy" decoding="async"%s />',
			esc_url($img['src']),
			($srcset && $shipped) ? ' srcset="' . esc_attr($srcset) . '"' : '',
			esc_attr($img['alt']),
			($srcset && $shipped) ? ' sizes="(min-width:1280px) 1216px, 100vw"' : ''
		);
	} else {
		echo '<span class="frame__empty">' . esc_html__('Photograph to be supplied', 'tradeskillapps') . '</span>';
	}

	if ($caption_path) {
		$lines = tsa_lines($caption_path);
		if ($lines) {
			echo '<figcaption class="frame__cap">';
			foreach ($lines as $i => $c) {
				echo $i === 1
					? '<span><strong>' . esc_html($c) . '</strong></span>'
					: '<span class="chip-label">' . esc_html($c) . '</span>';
			}
			echo '</figcaption>';
		}
	}
	echo '</figure>';
}

/** The ticked bullet list. */
function tsa_ticks($path) {
	$items = tsa_lines($path);
	if (!$items) return;
	echo '<ul class="ticks">';
	foreach ($items as $p) {
		echo '<li><svg aria-hidden="true"><use href="#i-check" /></svg><span>' . esc_html($p) . '</span></li>';
	}
	echo '</ul>';
}

/** 'rv rv-d2' - the staggered reveal class for the nth item. */
function tsa_rv($i) {
	return 'rv' . ($i ? ' rv-d' . $i : '');
}

/** How many items a repeated block currently has. */
function tsa_count($path) {
	$d = tsa_default($path);
	return is_array($d) ? count($d) : 0;
}

/** The accordion used by the glossary and the FAQ. First item opens by default. */
function tsa_accordion($rows, $prefix) {
	if (!$rows) return;
	$prefix .= tsa_uid('acc-' . $prefix);   // unique even when the section repeats on a page
	echo '<div class="acc rv">';
	foreach ($rows as $i => $row) {
		$open = ($i === 0);
		printf('<div class="acc__i">
			<h3><button class="acc__btn" type="button" id="%1$s-b%2$d" aria-expanded="%3$s" aria-controls="%1$s-p%2$d">
				<span>%4$s</span><span class="acc__pm" aria-hidden="true"></span>
			</button></h3>
			<div class="acc__panel" id="%1$s-p%2$d" role="region" aria-labelledby="%1$s-b%2$d" data-open="%3$s">
				<div><p>%5$s</p></div>
			</div>
		</div>',
			esc_attr($prefix), $i,
			$open ? 'true' : 'false',
			esc_html($row['q']),
			esc_html($row['a'])
		);
	}
	echo '</div>';
}

/** Answers are short; keep them as one paragraph rather than wrapping in <p>. */
function tsa_plain_text($content) {
	return trim(wp_strip_all_tags($content, false));
}
