<?php
defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-multiplayer');
?>
<section class="band band--wash" id="multiplayer<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="multiplayer-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('multiplayer.eyebrow'); ?></p>
				<?php tsa_heading('multiplayer', 'multiplayer-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('multiplayer.lede'); ?></p>
		</div>

		<p class="copy rv" style="margin-bottom:clamp(2.25rem,5vw,3.25rem)"><?php tsa_e('multiplayer.body'); ?></p>

		<div class="cycle">
			<?php for ($i = 0; $i < tsa_count('multiplayer.steps'); $i++) : $p = 'multiplayer.steps.' . $i; ?>
				<article class="cycle__i <?php echo esc_attr(tsa_rv($i)); ?>">
					<svg aria-hidden="true"><use href="#<?php echo esc_attr(tsa_text($p . '.icon')); ?>" /></svg>
					<span class="cycle__n"><?php tsa_e($p . '.n'); ?></span>
					<h4><?php tsa_e($p . '.title'); ?></h4>
					<p><?php tsa_e($p . '.body'); ?></p>
				</article>
			<?php endfor; ?>
		</div>
	</div>
</section>
