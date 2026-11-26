<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-approach'); ?>
<section class="band" id="approach<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="approach-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('approach.eyebrow'); ?></p>
				<?php tsa_heading('approach', 'approach-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('approach.lede'); ?></p>
		</div>

		<?php tsa_figure('approach.heroImage', '16/7', 'approach.heroImage.caption', 'margin:0 0 clamp(2rem,5vw,3.25rem)', '', true); ?>

		<?php foreach (tsa_lines('approach.body') as $i => $b) : ?>
			<p class="copy rv" style="margin-bottom:<?php echo $i === 0 ? '1rem' : 'clamp(2.25rem,5vw,3.25rem)'; ?>"><?php echo esc_html($b); ?></p>
		<?php endforeach; ?>

		<p class="eyebrow rv"><?php tsa_e('approach.lessonEyebrow'); ?></p>
		<h3 class="rv" style="font-size:var(--t-sub);margin-bottom:clamp(1.75rem,4vw,2.5rem);text-transform:uppercase">
			<?php tsa_e('approach.lessonHeadline'); ?> <em><?php tsa_e('approach.lessonHeadlineEm'); ?></em>
		</h3>

		<ol class="process rv">
			<?php for ($i = 0; $i < tsa_count('approach.steps'); $i++) : ?>
				<li>
					<h4><?php tsa_e('approach.steps.' . $i . '.title'); ?></h4>
					<p><?php tsa_e('approach.steps.' . $i . '.body'); ?></p>
				</li>
			<?php endfor; ?>
		</ol>

		<?php tsa_figure('approach.practiceImage', '16/7', 'approach.practiceImage.caption', 'margin:clamp(2rem,5vw,3rem) 0 0', '', true); ?>
		<?php tsa_note('approach.note', 'max-width:80ch'); ?>
	</div>
</section>
