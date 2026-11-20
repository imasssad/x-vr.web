<?php defined('ABSPATH') || exit; $cta = tsa_link('access.cta'); ?>
<section class="band band--wash" id="access" aria-labelledby="access-h">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('access.eyebrow'); ?></p>
				<?php tsa_heading('access', 'access-h'); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('access.lede'); ?></p>
		</div>
		<p class="copy rv"><?php tsa_e('access.body'); ?></p>
		<?php tsa_figure('access.image', '16/7', null, 'margin:clamp(1.75rem,4vw,2.5rem) 0 0'); ?>
		<div class="access__fig">
			<?php for ($i = 0; $i < tsa_count('access.cells'); $i++) : ?>
				<div class="access__cell <?php echo esc_attr(tsa_rv($i)); ?>">
					<h4><?php tsa_e('access.cells.' . $i . '.title'); ?></h4>
					<p><?php tsa_e('access.cells.' . $i . '.body'); ?></p>
				</div>
			<?php endfor; ?>
		</div>
		<p class="rv" style="margin-top:clamp(1.75rem,4vw,2.5rem)">
			<a class="btn btn--primary" href="<?php echo esc_url($cta['href']); ?>">
				<?php echo esc_html($cta['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
			</a>
		</p>
	</div>
</section>
