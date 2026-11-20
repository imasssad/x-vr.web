<?php defined('ABSPATH') || exit; ?>
<section class="band" id="pathways" aria-labelledby="pathways-h">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('pathways.eyebrow'); ?></p>
				<?php tsa_heading('pathways', 'pathways-h'); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('pathways.lede'); ?></p>
		</div>
		<?php tsa_figure('pathways.image', '16/7', null, 'margin:0 0 clamp(2.25rem,5vw,3.25rem)'); ?>
		<ol class="timeline">
			<?php for ($i = 0; $i < tsa_count('pathways.stages'); $i++) : $p = 'pathways.stages.' . $i; ?>
				<li class="tl <?php echo esc_attr(tsa_rv($i)); ?>">
					<span class="tl__node" aria-hidden="true"><?php tsa_e($p . '.n'); ?></span>
					<span class="tl__stage"><?php tsa_e($p . '.stage'); ?></span>
					<h3><?php tsa_e($p . '.title'); ?></h3>
					<p><?php tsa_e($p . '.body'); ?></p>
					<?php tsa_ticks($p . '.points'); ?>
				</li>
			<?php endfor; ?>
		</ol>
		<?php tsa_note('pathways.note', 'max-width:82ch'); ?>
	</div>
</section>
