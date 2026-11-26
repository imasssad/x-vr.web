<?php
/** The reference page's hero: photograph right, words and the weld film left. */
defined('ABSPATH') || exit;
$uid   = tsa_uid('hero');
$img   = tsa_image('hero.image');
$film  = tsa_text('hero.film.src');
if (strpos($film, '/assets/') === 0) $film = tsa_asset_exists($film) ? tsa_asset($film) : '';
$post  = tsa_text('hero.film.poster');
if (strpos($post, '/assets/') === 0) $post = tsa_asset_exists($post) ? tsa_asset($post) : '';
$cap   = tsa_text('hero.videoHeading');
$em    = tsa_text('hero.headlineEm');
?>
<section class="hero" id="hero<?php echo esc_attr($uid); ?>">
	<?php if ($img['src'] !== '') : ?>
		<div class="hero-art">
			<img class="hero-photo" src="<?php echo esc_url($img['src']); ?>"
				alt="<?php echo esc_attr($img['alt']); ?>" width="1536" height="1024" fetchpriority="high" />
			<a class="screen-link" href="#programs"
			   aria-label="<?php esc_attr_e('Explore the XVR trades programs', 'tradeskillapps'); ?>"></a>
		</div>
	<?php endif; ?>
	<div class="wrap hero-inner">
		<h1><?php
			// His headline carries a hard line break - "Train for the<br>work
			// ahead" - so it reads as two lines at every width, not only where
			// it happens to wrap. One line per line in the Customizer box.
			$lines = tsa_lines('hero.headline');
			echo implode('<br />', array_map('esc_html', $lines));
			if ($em !== '') echo '<span>' . esc_html($em) . '</span>';
		?></h1>
		<p class="lead"><?php tsa_e('hero.lede'); ?></p>
		<?php if ($film !== '') : ?>
			<figure class="hero-video">
				<?php if ($cap !== '') : ?>
					<figcaption id="weld-video-title<?php echo esc_attr($uid); ?>"><?php echo esc_html($cap); ?></figcaption>
				<?php endif; ?>
				<video controls playsinline preload="metadata" width="512" height="910"
					<?php if ($cap !== '') : ?>aria-labelledby="weld-video-title<?php echo esc_attr($uid); ?>"<?php endif; ?>
					poster="<?php echo esc_url($post); ?>" src="<?php echo esc_url($film); ?>">
					<?php esc_html_e('Your browser does not support embedded video.', 'tradeskillapps'); ?>
				</video>
			</figure>
		<?php endif; ?>
	</div>
</section>
