<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-videos'); $videos = tsa_items('tsa_video'); ?>
<section class="band band--white" id="videos<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="videos-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('videos.eyebrow'); ?></p>
				<?php tsa_heading('videos', 'videos-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('videos.lede'); ?></p>
		</div>

		<div class="videos">
			<?php foreach ($videos as $i => $v) :
				$embed = trim((string) get_post_meta($v->ID, '_tsa_embed', true));
				$file  = trim((string) get_post_meta($v->ID, '_tsa_file', true));
				$ready = ($embed !== '' || $file !== '');
				$poster = has_post_thumbnail($v->ID) ? get_the_post_thumbnail_url($v->ID, 'full') : '';
			?>
				<article class="vcard <?php echo esc_attr(tsa_rv($i)); ?>"
					data-embed="<?php echo esc_attr($embed); ?>" data-file="<?php echo esc_url($file); ?>"
					data-poster="<?php echo esc_url($poster); ?>">
					<div class="vcard__media">
						<button type="button" class="vcard__play"<?php echo $ready ? '' : ' disabled'; ?>
							aria-label="<?php echo esc_attr($ready
								? sprintf(__('Play: %s', 'tradeskillapps'), $v->post_title)
								: sprintf(__('%s - film coming soon', 'tradeskillapps'), $v->post_title)); ?>">
							<?php if ($poster) : ?>
								<img src="<?php echo esc_url($poster); ?>" alt="" aria-hidden="true" loading="lazy" />
							<?php endif; ?>
							<span class="vcard__icon" aria-hidden="true"><svg><use href="#i-play" /></svg></span>
							<?php if (!$ready) : ?>
								<span class="vcard__pending"><?php esc_html_e('Film coming soon', 'tradeskillapps'); ?></span>
							<?php endif; ?>
						</button>
					</div>
					<div class="vcard__body">
						<h3><?php echo esc_html($v->post_title); ?></h3>
						<p><?php echo esc_html(get_post_meta($v->ID, '_tsa_blurb', true)); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php tsa_note('videos.note', 'max-width:80ch'); ?>
	</div>
</section>
