<?php
/** The opening screen. A still photograph by default; a video fades in over it. */
defined('ABSPATH') || exit;

$img    = tsa_image('hero.image');
$srcset = tsa_text('hero.image.srcSet');
$strip  = tsa_lines('hero.strip');

// Only render the <video> when a file is actually named, so the browser is
// not sent chasing sources that do not exist.
$sources = array();
foreach ((array) tsa_default('hero.video.sources') as $i => $unused) {
	$src = tsa_text('hero.video.sources.' . $i . '.src');
	if ($src === '') continue;
	// A path into the theme only counts when the file is actually present;
	// anything else is a URL the owner supplied, so take it at face value.
	if (strpos($src, '/assets/') === 0) {
		if (!tsa_asset_exists($src)) continue;
		$src = tsa_asset($src);
	}
	$sources[] = array($src, tsa_text('hero.video.sources.' . $i . '.type'));
}
$poster = tsa_text('hero.video.poster');
if (strpos($poster, '/assets/') === 0) {
	$poster = tsa_asset_exists($poster) ? tsa_asset($poster) : '';
}
?>
<section class="hero" aria-labelledby="hero-h">
	<div class="hero__media">
		<?php if ($img['src'] !== '') : ?>
			<img id="heroPoster" src="<?php echo esc_url($img['src']); ?>"
				<?php if ($srcset && strpos($img['src'], '/assets/') !== false) : ?>
					srcset="<?php echo esc_attr($srcset); ?>" sizes="100vw"
				<?php endif; ?>
				alt="<?php echo esc_attr($img['alt']); ?>" decoding="async" fetchpriority="high" />
		<?php endif; ?>

		<?php if ($sources) : ?>
			<video id="heroVideo" autoplay muted loop playsinline preload="metadata"
				poster="<?php echo esc_url($poster); ?>"
				aria-label="<?php echo esc_attr(tsa_text('hero.video.label')); ?>"
				style="position:absolute;inset:0;opacity:0;transition:opacity .6s ease">
				<?php foreach ($sources as $s) : ?>
					<source src="<?php echo esc_url($s[0]); ?>" type="<?php echo esc_attr($s[1]); ?>" />
				<?php endforeach; ?>
			</video>
		<?php endif; ?>
	</div>
	<div class="hero__scrim" aria-hidden="true"></div>

	<?php if ($sources) : ?>
		<div class="vidctl" id="vidctl" hidden>
			<button type="button" data-act="play" aria-pressed="true">
				<svg aria-hidden="true"><use href="#i-pause" /></svg><span><?php esc_html_e('Pause', 'tradeskillapps'); ?></span>
			</button>
			<button type="button" data-act="sound" aria-pressed="false">
				<svg aria-hidden="true"><use href="#i-sound" /></svg><span><?php esc_html_e('Play with sound', 'tradeskillapps'); ?></span>
			</button>
		</div>
	<?php endif; ?>

	<div class="wrap hero__in">
		<p class="eyebrow rv"><?php tsa_e('hero.eyebrow'); ?></p>
		<h1 id="hero-h" class="rv rv-d1">
			<?php tsa_e('hero.headline'); ?> <em><?php tsa_e('hero.headlineEm'); ?></em>
		</h1>
		<p class="lede rv rv-d2"><?php tsa_e('hero.lede'); ?></p>
		<div class="hero__actions rv rv-d3">
			<?php $p = tsa_link('hero.primaryCta'); $s = tsa_link('hero.secondaryCta'); ?>
			<a class="btn btn--primary" href="<?php echo esc_url($p['href']); ?>">
				<?php echo esc_html($p['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
			</a>
			<a class="btn btn--ghost" href="<?php echo esc_url($s['href']); ?>">
				<?php echo esc_html($s['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
			</a>
		</div>
		<?php if ($strip) : ?>
			<div class="hero__strip rv rv-d3">
				<?php foreach ($strip as $i => $line) : ?>
					<?php if ($i) : ?><span class="dot" aria-hidden="true"></span><?php endif; ?>
					<p><?php echo esc_html($line); ?></p>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
