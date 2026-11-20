<?php defined('ABSPATH') || exit; ?>
<section class="band on-navy" id="learning" aria-labelledby="learning-h">
	<div class="wrap">
		<div class="sec-head">
			<div>
				<p class="eyebrow rv"><?php tsa_e('learningCycle.eyebrow'); ?></p>
				<?php tsa_heading('learningCycle', 'learning-h'); ?>
			</div>
		</div>
		<div class="cycle">
			<?php for ($i = 0; $i < tsa_count('learningCycle.items'); $i++) : ?>
				<article class="cycle__i <?php echo esc_attr(tsa_rv($i)); ?>">
					<svg aria-hidden="true"><use href="#<?php echo esc_attr(tsa_text('learningCycle.items.' . $i . '.icon')); ?>" /></svg>
					<span class="cycle__n"><?php tsa_e('learningCycle.items.' . $i . '.n'); ?></span>
					<h4><?php tsa_e('learningCycle.items.' . $i . '.title'); ?></h4>
					<p><?php tsa_e('learningCycle.items.' . $i . '.body'); ?></p>
				</article>
			<?php endfor; ?>
		</div>
	</div>
</section>
