<?php
/**
 * The opening screen.
 *
 * Two layouts, chosen in the Customizer (Hero > Layout) or on the widget:
 *
 *   split    - the client's reference layout. A dark band: the words on the
 *              left, the photograph bleeding off the right edge, and the
 *              weld film offered underneath the copy.
 *   overlay  - the original. One full-bleed photograph with the words over
 *              it, and the film playing silently behind them.
 */
defined('ABSPATH') || exit;

$layout = tsa_text('hero.layout') === 'overlay' ? 'overlay' : 'split';
$uid    = tsa_uid('hero');
$img    = tsa_image('hero.image');
$srcset = tsa_srcset(tsa_text('hero.image.srcSet'));
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

// Built once and placed differently by each layout: inside the text column
// for the overlay (where it sat before), beneath both columns for the split.
$strip_html = '';
if ($strip) {
	ob_start(); ?>
	<div class="hero__strip rv rv-d3">
		<?php foreach ($strip as $i => $line) : ?>
			<?php if ($i) : ?><span class="dot" aria-hidden="true"></span><?php endif; ?>
			<p><?php echo esc_html($line); ?></p>
		<?php endforeach; ?>
	</div>
	<?php $strip_html = ob_get_clean();
}

// The film offered under the copy in the split layout. Its own fields,
// because hero.video above is the silent background film of the overlay
// layout and its files were never supplied.
$film = array('src' => '', 'poster' => '');
$fsrc = tsa_text('hero.film.src');
if ($fsrc !== '') {
	if (strpos($fsrc, '/assets/') === 0) {
		$fsrc = tsa_asset_exists($fsrc) ? tsa_asset($fsrc) : '';
	}
	if ($fsrc !== '') {
		$fposter = tsa_text('hero.film.poster');
		if (strpos($fposter, '/assets/') === 0) {
			$fposter = tsa_asset_exists($fposter) ? tsa_asset($fposter) : '';
		}
		$film = array(
			'src'    => $fsrc,
			'type'   => tsa_text('hero.film.type'),
			'poster' => $fposter,
			'label'  => tsa_text('hero.film.label'),
		);
	}
}

$vheading = tsa_text('hero.videoHeading');
$p = tsa_link('hero.primaryCta');
$s = tsa_link('hero.secondaryCta');
?>
<section class="hero hero--<?php echo esc_attr($layout); ?><?php echo $layout === 'split' ? ' on-navy' : ''; ?>" id="hero<?php echo esc_attr($uid); ?>" aria-labelledby="hero-h<?php echo esc_attr($uid); ?>">

<?php if ($layout === 'overlay') : ?>
	<div class="hero__media">
		<?php if ($img['src'] !== '') : ?>
			<img id="heroPoster" src="<?php echo esc_url($img['src']); ?>"
				<?php if ($srcset && strpos($img['src'], '/assets/') !== false) : ?>
					srcset="<?php echo esc_attr($srcset); ?>" sizes="100vw"
				<?php endif; ?>
				alt="<?php echo esc_attr($img['alt']); ?>" decoding="async" fetchpriority="high" />
		<?php endif; ?>

		<?php if ($sources) : ?>
			<video id="heroVideo<?php echo esc_attr(tsa_uid('herov')); ?>" class="hero__video" autoplay muted loop playsinline preload="metadata"
				poster="<?php echo esc_url($poster); ?>"
				aria-label="<?php echo esc_attr(tsa_text('hero.video.label')); ?>"
				style="position:absolute;inset:0;opacity:0;transition:opacity .6s ease">
				<?php foreach ($sources as $src) : ?>
					<source src="<?php echo esc_url($src[0]); ?>" type="<?php echo esc_attr($src[1]); ?>" />
				<?php endforeach; ?>
			</video>
		<?php endif; ?>
	</div>
	<div class="hero__scrim" aria-hidden="true"></div>

	<?php if ($sources) : ?>
		<div class="vidctl" hidden>
			<button type="button" data-act="play" aria-pressed="true">
				<svg aria-hidden="true"><use href="#i-pause" /></svg><span><?php esc_html_e('Pause', 'tradeskillapps'); ?></span>
			</button>
			<button type="button" data-act="sound" aria-pressed="false">
				<svg aria-hidden="true"><use href="#i-sound" /></svg><span><?php esc_html_e('Play with sound', 'tradeskillapps'); ?></span>
			</button>
		</div>
	<?php endif; ?>
<?php endif; ?>

	<div class="wrap hero__in">
		<div class="hero__text">
			<?php if (tsa_text('hero.eyebrow') !== '') : ?>
				<p class="eyebrow rv"><?php tsa_e('hero.eyebrow'); ?></p>
			<?php endif; ?>
			<?php $h_em = tsa_text('hero.headlineEm'); ?>
			<h1 id="hero-h<?php echo esc_attr($uid); ?>" class="rv rv-d1"><?php
				tsa_e('hero.headline');
				if ($h_em !== '') {
					echo tsa_em_gap($h_em) . '<em>' . esc_html($h_em) . '</em>';
				}
			?></h1>
			<p class="lede rv rv-d2"><?php tsa_e('hero.lede'); ?></p>
			<?php if ($p['label'] !== '' || $s['label'] !== '') : ?>
				<div class="hero__actions rv rv-d3">
					<?php if ($p['label'] !== '') : ?>
						<a class="btn btn--primary" href="<?php echo esc_url($p['href']); ?>">
							<?php echo esc_html($p['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
						</a>
					<?php endif; ?>
					<?php if ($s['label'] !== '') : ?>
						<a class="btn btn--ghost" href="<?php echo esc_url($s['href']); ?>">
							<?php echo esc_html($s['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ($layout === 'split' && $film['src'] !== '') : ?>
				<div class="hero__film rv rv-d3">
					<?php if ($vheading !== '') : ?>
						<p class="hero__film-h" id="hero-film-h<?php echo esc_attr($uid); ?>"><?php echo esc_html($vheading); ?></p>
					<?php endif; ?>
					<?php // the same card the films section uses, so the existing
					      // play-on-click behaviour applies with no new script ?>
					<article class="vcard vcard--hero" data-embed=""
						data-file="<?php echo esc_url($film['src']); ?>"
						data-poster="<?php echo esc_url($film['poster']); ?>">
						<div class="vcard__media">
							<button type="button" class="vcard__play"
								aria-label="<?php echo esc_attr(sprintf(
									/* translators: %s is the name of a film */
									__('Play: %s', 'tradeskillapps'),
									$vheading !== '' ? $vheading : $film['label']
								)); ?>">
								<?php if ($film['poster'] !== '') : ?>
									<img src="<?php echo esc_url($film['poster']); ?>" alt="" aria-hidden="true" loading="lazy" />
								<?php endif; ?>
								<span class="vcard__icon" aria-hidden="true"><svg><use href="#i-play" /></svg></span>
							</button>
						</div>
					</article>
				</div>
			<?php endif; ?>

			<?php if ($layout === 'overlay') echo $strip_html; ?>
		</div>

		<?php if ($layout === 'split' && $img['src'] !== '') : ?>
			<div class="hero__shot rv rv-d2">
				<img src="<?php echo esc_url($img['src']); ?>"
					<?php if ($srcset && strpos($img['src'], '/assets/') !== false) : ?>
						srcset="<?php echo esc_attr($srcset); ?>" sizes="(max-width:900px) 100vw, 55vw"
					<?php endif; ?>
					alt="<?php echo esc_attr($img['alt']); ?>" decoding="async" fetchpriority="high" />
			</div>
		<?php endif; ?>
	</div>

	<?php if ($layout === 'split' && $strip_html !== '') : ?>
		<div class="wrap hero__strip-wrap"><?php echo $strip_html; ?></div>
	<?php endif; ?>
</section>
