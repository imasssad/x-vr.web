<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-purpose'); ?>
<section class="band" id="purpose<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="purpose-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('purpose.eyebrow'); ?></p>
				<?php tsa_heading('purpose', 'purpose-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('purpose.lede'); ?></p>
		</div>
		<p class="copy rv" style="margin-bottom:clamp(2.25rem,5vw,3.5rem)"><?php tsa_e('purpose.body'); ?></p>
		<div class="grid grid--3">
			<?php for ($i = 0; $i < tsa_count('purpose.pillars'); $i++) : ?>
				<article class="card <?php echo esc_attr(tsa_rv($i)); ?>">
					<span class="pillar__n"><?php tsa_e('purpose.pillars.' . $i . '.n'); ?></span>
					<h3><?php tsa_e('purpose.pillars.' . $i . '.title'); ?></h3>
					<p><?php tsa_e('purpose.pillars.' . $i . '.body'); ?></p>
				</article>
			<?php endfor; ?>
		</div>
	</div>
</section>
