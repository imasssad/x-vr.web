<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-levels'); $hs = tsa_image('levels.highSchool.image'); ?>
<section class="band" id="levels<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="levels-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('levels.eyebrow'); ?></p>
				<?php tsa_heading('levels', 'levels-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('levels.lede'); ?></p>
		</div>
		<div class="bento">
			<article class="bento__card bento__card--light rv">
				<figure class="frame bento__media frame--zoom">
					<?php if ($hs['src'] !== '') : ?>
						<img src="<?php echo esc_url($hs['src']); ?>" alt="<?php echo esc_attr($hs['alt']); ?>" loading="lazy" decoding="async" />
					<?php else : ?>
						<span class="frame__empty"><?php esc_html_e('Photograph to be supplied', 'tradeskillapps'); ?></span>
					<?php endif; ?>
				</figure>
				<div class="bento__body">
					<span class="bento__eye"><?php tsa_e('levels.highSchool.eye'); ?></span>
					<h3><?php tsa_e('levels.highSchool.title'); ?></h3>
					<p><?php tsa_e('levels.highSchool.body'); ?></p>
					<?php tsa_ticks('levels.highSchool.points'); ?>
				</div>
			</article>
			<article class="bento__card bento__card--navy on-navy rv rv-d1">
				<div class="bento__body">
					<span class="bento__eye"><?php tsa_e('levels.tradeSchool.eye'); ?></span>
					<h3><?php tsa_e('levels.tradeSchool.title'); ?></h3>
					<p><?php tsa_e('levels.tradeSchool.body'); ?></p>
					<?php tsa_ticks('levels.tradeSchool.points'); ?>
				</div>
			</article>
		</div>
		<?php tsa_note('levels.note', 'max-width:82ch'); ?>
	</div>
</section>
