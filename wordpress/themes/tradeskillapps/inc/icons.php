<?php
/**
 * One SVG sprite, printed once near the top of the page. Every icon on the
 * site is a <use> reference to a symbol in here, so an icon costs no request
 * and inherits the surrounding text colour.
 *
 * Generated from the original build - the shapes are unchanged.
 */
defined('ABSPATH') || exit;

function tsa_icon_sprite() {
	static $done = false;            // once per page, whatever template runs
	if ($done) return;
	$done = true;
?>
<svg xmlns="http://www.w3.org/2000/svg" width="0" height="0" aria-hidden="true" focusable="false" style="position:absolute">
<defs>
<symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 12.5 9.5 18 20 6"/></symbol>
<symbol id="i-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 5 17 12 9 19"/></symbol>
<symbol id="i-info" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.2"/><line x1="12" y1="11" x2="12" y2="16.5"/><circle cx="12" cy="7.6" r=".9" fill="currentColor" stroke="none"/></symbol>
<symbol id="i-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4.5" y="10.5" width="15" height="10.5" rx="2.2"/><path d="M8 10.5V7.4a4 4 0 0 1 8 0v3.1"/></symbol>
<symbol id="i-play" viewBox="0 0 24 24" fill="currentColor"><path d="M7.5 5.2v13.6a.8.8 0 0 0 1.22.68l11-6.8a.8.8 0 0 0 0-1.36l-11-6.8a.8.8 0 0 0-1.22.68z"/></symbol>
<symbol id="i-pause" viewBox="0 0 24 24" fill="currentColor"><rect x="6.4" y="5" width="4" height="14" rx="1.2"/><rect x="13.6" y="5" width="4" height="14" rx="1.2"/></symbol>
<symbol id="i-sound" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5h3.2L12.5 5v14L7.2 14.5H4z"/><path d="M16.2 9a4.6 4.6 0 0 1 0 6"/><path d="M18.8 6.2a8.2 8.2 0 0 1 0 11.6"/></symbol>
<symbol id="i-mute" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5h3.2L12.5 5v14L7.2 14.5H4z"/><line x1="16.5" y1="9.5" x2="21" y2="14.5"/><line x1="21" y1="9.5" x2="16.5" y2="14.5"/></symbol>
<symbol id="i-weld" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20.5h18"/><path d="M6.5 20.5 14 6.2"/><path d="M10.6 4.1l3.9 2.1-1.6 2.9-3.9-2.1z"/><path d="M17.4 12.8l1.3-2.4M19.9 15l2.1-1.1M16.4 16.6l.6-2.6"/></symbol>
<symbol id="i-hvac" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2.6" y="4.4" width="18.8" height="9.6" rx="2"/><path d="M6.4 8.1h11.2M6.4 10.6h11.2"/><path d="M7 17.4c0 1.3 1 2.3 2.3 2.3s2.3-1 2.3-2.3 1-2.3 2.3-2.3 2.3 1 2.3 2.3"/></symbol>
<symbol id="i-elec" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13.4 2.5 5.6 13.4h5.1l-1.3 8.1 8-11.2h-5.1z"/></symbol>
<symbol id="i-manu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.8 20.6V10l5.4 3.3V10l5.4 3.3V10l5.6 3.4v7.2z"/><path d="M2.8 20.6h18.4"/><path d="M6 6.4V3.6M10.4 6.4V3.6"/></symbol>
<symbol id="i-cons" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2.5 13.6h19v3.1h-19z"/><path d="M4.4 13.6c0-4.2 3.4-7.6 7.6-7.6s7.6 3.4 7.6 7.6"/><path d="M12 6V3.3M9.2 13.6V6.8M14.8 13.6V6.8"/><path d="M4 20.7h16"/></symbol>
<symbol id="i-tech" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2.4" y="7.8" width="19.2" height="8.8" rx="3.4"/><circle cx="7.9" cy="12.2" r="1.7"/><circle cx="16.1" cy="12.2" r="1.7"/><path d="M12 16.6v2.1M9.4 5.3 12 7.8l2.6-2.5"/></symbol>
<symbol id="i-learn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3.2 6.1 12 2.7l8.8 3.4L12 9.6z"/><path d="M6 8.2v5.4c0 1.9 2.7 3.4 6 3.4s6-1.5 6-3.4V8.2"/><path d="M12 17v4.3"/></symbol>
<symbol id="i-rehearse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.6 12a8.6 8.6 0 1 1-2.9-6.4"/><polyline points="20.9 3.6 20.9 8.4 16.1 8.4"/><path d="M12 8.3V12l2.6 1.8"/></symbol>
<symbol id="i-reflect" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 14.5a2.3 2.3 0 0 1-2.3 2.3H8.2L3.6 21V5.6a2.3 2.3 0 0 1 2.3-2.3h12.8A2.3 2.3 0 0 1 21 5.6z"/><path d="M8.6 8.4h7.6M8.6 11.8h4.6"/></symbol>
<symbol id="i-apply" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14.1 3.4 4.8 13.2l-1.4 6.4 6.4-1.4L19.6 8.9a3.2 3.2 0 0 0 0-4.5l-.9-1a3.2 3.2 0 0 0-4.6 0z"/><path d="M12.2 5.4 17.6 10.8"/></symbol>
<symbol id="i-reset" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.4 12a8.6 8.6 0 1 0 2.9-6.4"/><polyline points="3.1 3.6 3.1 8.4 7.9 8.4"/></symbol>
<symbol id="i-drone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="6" height="6" rx="1.4"/><path d="M9.4 9.4 6 6M14.6 9.4 18 6M9.4 14.6 6 18M14.6 14.6 18 18"/><circle cx="4.6" cy="4.6" r="2.1"/><circle cx="19.4" cy="4.6" r="2.1"/><circle cx="4.6" cy="19.4" r="2.1"/><circle cx="19.4" cy="19.4" r="2.1"/></symbol>
</defs>
</svg>
<?php }

// Printed from the hook, not from header.php: Elementor's Canvas template
// skips the theme header entirely but still fires wp_body_open, and without
// the sprite every icon on such a page would silently vanish.
add_action('wp_body_open', 'tsa_icon_sprite');

/** Echo one icon. */
function tsa_icon($id, $class = '') {
	printf('<svg class="%s" aria-hidden="true" focusable="false"><use href="#%s" /></svg>',
		esc_attr($class), esc_attr($id));
}

/**
 * Stands in for a photograph that has not been supplied, and says so on its
 * face rather than pretending to be artwork. Ported from the original build.
 */
function tsa_placeholder($kind, $icon) {
	$motifs = array(
		'welding' => 'sparks', 'hvac' => 'flow', 'electrical' => 'circuit',
		'manufacturing' => 'grid', 'construction' => 'grid', 'emerging-technology' => 'circuit',
	);
	$m    = isset($motifs[$kind]) ? $motifs[$kind] : 'grid';
	$id   = 'ph-' . sanitize_html_class($kind) . tsa_uid('ph-' . $kind);
	$blue = '#2864E8'; $navy = '#15283F'; $ice = '#B9D5F5';

	$shapes = '';
	if ($m === 'sparks') {
		for ($i = 0; $i < 22; $i++) {
			$shapes .= sprintf('<circle cx="%s%%" cy="%s%%" r="%s" fill="%s" opacity="%.2f" />',
				6 + ($i * 37) % 88, 12 + ($i * 53) % 76, 0.6 + ($i % 4) * 0.5, $blue, 0.18 + ($i % 5) * 0.08);
		}
	} elseif ($m === 'flow') {
		for ($i = 0; $i < 6; $i++) {
			$oy = 18 + $i * 12;
			$shapes .= sprintf('<path d="M-5,%1$d C 25,%2$d 55,%3$d 105,%4$d" fill="none" stroke="%5$s" stroke-width="0.45" opacity="%6$.2f" />',
				$oy, $oy - 9, $oy + 9, $oy - 3, $blue, 0.42 - $i * 0.05);
		}
	} elseif ($m === 'circuit') {
		for ($i = 0; $i < 7; $i++) {
			$px = 10 + $i * 13; $py = 20 + ($i % 3) * 22;
			$shapes .= sprintf('<path d="M%1$d,96 L%1$d,%2$d L%3$d,%2$d" fill="none" stroke="%4$s" stroke-width="0.5" opacity="0.32" />',
				$px, $py, $px + 11, $blue);
			$shapes .= sprintf('<circle cx="%d" cy="%d" r="1.1" fill="%s" opacity="0.55" />', $px + 11, $py, $blue);
		}
	} else {
		$shapes .= sprintf('<path d="M0,78 L30,40 L58,62 L100,26" fill="none" stroke="%s" stroke-width="0.5" opacity="0.32" />', $blue);
	}
	?>
	<div class="art" aria-hidden="true">
		<svg viewBox="0 0 100 100" preserveAspectRatio="xMidYMid slice" width="100%" height="100%" focusable="false">
			<defs>
				<linearGradient id="<?php echo esc_attr($id); ?>bg" x1="0" y1="0" x2="1" y2="1">
					<stop offset="0" stop-color="#EDF3FD" /><stop offset="1" stop-color="#FFFFFF" />
				</linearGradient>
				<radialGradient id="<?php echo esc_attr($id); ?>gl" cx="0.78" cy="0.18" r="0.8">
					<stop offset="0" stop-color="<?php echo esc_attr($ice); ?>" stop-opacity="0.85" />
					<stop offset="1" stop-color="<?php echo esc_attr($ice); ?>" stop-opacity="0" />
				</radialGradient>
				<pattern id="<?php echo esc_attr($id); ?>p" width="7" height="7" patternUnits="userSpaceOnUse">
					<path d="M7,0 L0,7" stroke="<?php echo esc_attr($navy); ?>" stroke-opacity="0.05" stroke-width="0.4" />
				</pattern>
			</defs>
			<rect width="100" height="100" fill="url(#<?php echo esc_attr($id); ?>bg)" />
			<rect width="100" height="100" fill="url(#<?php echo esc_attr($id); ?>gl)" />
			<rect width="100" height="100" fill="url(#<?php echo esc_attr($id); ?>p)" />
			<?php echo $shapes; // built above from numbers only ?>
			<g opacity="0.14" transform="translate(50,46) scale(1.7) translate(-12,-12)">
				<use href="#<?php echo esc_attr($icon); ?>" width="24" height="24" color="<?php echo esc_attr($navy); ?>" />
			</g>
		</svg>
	</div>
	<?php
}

/** The disclaimer note. **bold** in the text becomes <strong>. */
function tsa_note($path, $style = '') {
	$text  = tsa_text($path);
	if ($text === '') return;
	$parts = preg_split('/(\*\*[^*]+\*\*)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	printf('<div class="note rv"%s>', $style ? ' style="' . esc_attr($style) . '"' : '');
	echo '<svg aria-hidden="true"><use href="#i-info" /></svg><p>';
	foreach ($parts as $p) {
		if (strpos($p, '**') === 0) echo '<strong>' . esc_html(substr($p, 2, -2)) . '</strong>';
		else echo '<span>' . esc_html($p) . '</span>';
	}
	echo '</p></div>';
}

/**
 * The brand lockup.
 *
 * The supplied logo carries the name inside it, so no separate wordmark is
 * printed beside it - that would say "XVR" twice. A logo uploaded under
 * Appearance > Customize > Site Identity replaces it.
 */
function tsa_brand($class = 'brand', $variant = 'nav') {
	$home = home_url('/');
	$name = tsa_text('site.name');
	?>
	<a class="<?php echo esc_attr($class); ?> brand--logo brand--<?php echo esc_attr($variant); ?>"
	   href="<?php echo esc_url($home); ?>#top"
	   aria-label="<?php echo esc_attr(sprintf(__('%s - home', 'tradeskillapps'), $name)); ?>">
		<?php if (has_custom_logo()) {
			$id = get_theme_mod('custom_logo');
			echo wp_get_attachment_image($id, 'full', false, array(
				'class' => 'brand__logo',
				'alt'   => $name,
				// a ceiling that survives a stale stylesheet
				'style' => 'height:' . ($variant === 'full' ? 64 : 44) . 'px;width:auto',
			));
		} else {
			$base = get_template_directory_uri() . '/assets/brand';
			// The navigation uses the lockup without the strapline: at the
			// height a header allows, the strapline is a few pixels tall and
			// reads as a smudge. The footer has room for the whole thing.
			$full = ($variant === 'full');
			$file = $full ? 'xvr-logo' : 'xvr-logo-nav';
			// Real width and height attributes, at the size it is meant to
			// appear. Without them the browser lays the file out at its own
			// width, which is what a stale cached stylesheet produced: a
			// logo several hundred pixels wide sprawling over the hero. With
			// them the worst a missing stylesheet can do is nothing at all.
			$h = $full ? 64 : 44;
			$w = (int) round($h * ($full ? 1.931 : 2.143));
			printf(
				'<img class="brand__logo" src="%1$s/%2$s.png" srcset="%1$s/%2$s.png 1x, %1$s/%2$s@2x.png 2x" width="%4$d" height="%5$d" alt="%3$s" decoding="async" />',
				esc_url($base),
				esc_attr($file),
				esc_attr($full
					? __('XVR Trades - Skilled Labor Trades to Apprenticeships', 'tradeskillapps')
					: __('XVR Trades', 'tradeskillapps')),
				$w, $h
			);
		} ?>
	</a>
	<?php
}
