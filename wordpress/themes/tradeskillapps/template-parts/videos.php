<?php
defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-videos');
$items   = tsa_video_items();

// The first film leads at full width unless the owner asks for an even grid.
$feature = (tsa_text('videos.layout') !== 'grid' && $items) ? array_shift($items) : null;

/** One card. $lead makes it the wide feature. */
if (!function_exists('tsa_video_card')) {
	function tsa_video_card($v, $i, $lead = false) {
		$ready = ($v['embed'] !== '' || $v['file'] !== '');
		?>
		<article class="vcard<?php echo $lead ? ' vcard--feature' : ''; ?> <?php echo esc_attr(tsa_rv($i)); ?>"
			data-embed="<?php echo esc_attr($v['embed']); ?>"
			data-file="<?php echo esc_url($v['file']); ?>"
			data-poster="<?php echo esc_url($v['poster']); ?>">
			<div class="vcard__media">
				<button type="button" class="vcard__play"<?php echo $ready ? '' : ' disabled'; ?>
					aria-label="<?php echo esc_attr($ready
						? sprintf(__('Play: %s', 'tradeskillapps'), $v['title'])
						: sprintf(__('%s - film coming soon', 'tradeskillapps'), $v['title'])); ?>">
					<?php if ($v['poster']) : ?>
						<img src="<?php echo esc_url($v['poster']); ?>" alt="" aria-hidden="true"
							<?php echo $lead ? '' : 'loading="lazy"'; ?> />
					<?php endif; ?>
					<span class="vcard__icon" aria-hidden="true"><svg><use href="#i-play" /></svg></span>
					<?php if (!$ready) : ?>
						<span class="vcard__pending"><?php esc_html_e('Film coming soon', 'tradeskillapps'); ?></span>
					<?php endif; ?>
				</button>
			</div>
			<div class="vcard__body">
				<h3><?php echo esc_html($v['title']); ?></h3>
				<p><?php echo esc_html($v['blurb']); ?></p>
			</div>
		</article>
		<?php
	}
}
?>
<section class="band band--white" id="videos<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="videos-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('videos.eyebrow'); ?></p>
				<?php tsa_heading('videos', 'videos-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('videos.lede'); ?></p>
		</div>

		<?php if ($feature) : ?>
			<div class="videos__feature">
				<?php tsa_video_card($feature, 0, true); ?>
			</div>
		<?php endif; ?>

		<?php if ($items) : ?>
			<div class="videos<?php echo $feature ? ' videos--rest' : ''; ?>">
				<?php foreach ($items as $i => $v) tsa_video_card($v, $i); ?>
			</div>
		<?php endif; ?>

		<?php tsa_note('videos.note', 'max-width:80ch'); ?>
	</div>
</section>
