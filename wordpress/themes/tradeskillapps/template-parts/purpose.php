<?php defined('ABSPATH') || exit; ?>
<section class="band" id="purpose" aria-labelledby="purpose-h">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('purpose.eyebrow'); ?></p>
				<?php tsa_heading('purpose', 'purpose-h'); ?>
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
