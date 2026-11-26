<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-educators'); $cta = tsa_link('educators.cta'); ?>
<section class="band band--white" id="educators<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="educators-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('educators.eyebrow'); ?></p>
				<?php tsa_heading('educators', 'educators-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('educators.lede'); ?></p>
		</div>
		<p class="copy rv" style="margin-bottom:clamp(2.25rem,5vw,3.5rem)"><?php tsa_e('educators.body'); ?></p>
		<div class="alt">
			<?php for ($i = 0; $i < tsa_count('educators.steps'); $i++) : $p = 'educators.steps.' . $i; $img = tsa_image($p . '.image'); ?>
				<div class="alt__row<?php echo $img['src'] === '' ? ' alt__row--text' : ''; ?> rv">
					<?php if ($img['src'] !== '') : ?>
						<figure class="frame alt__media frame--zoom">
							<img src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy" decoding="async" />
						</figure>
					<?php endif; ?>
					<div class="alt__body">
						<span class="alt__n" aria-hidden="true"><?php tsa_e($p . '.n'); ?></span>
						<h3><?php tsa_e($p . '.title'); ?></h3>
						<p><?php tsa_e($p . '.body'); ?></p>
					</div>
				</div>
			<?php endfor; ?>
		</div>
		<p class="rv" style="margin-top:clamp(2rem,4.5vw,3rem)">
			<a class="btn btn--primary" href="<?php echo esc_url($cta['href']); ?>">
				<?php echo esc_html($cta['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
			</a>
		</p>
	</div>
</section>
