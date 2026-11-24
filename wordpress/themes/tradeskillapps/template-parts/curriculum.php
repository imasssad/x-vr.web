<?php
defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-curriculum');
?>
<section class="band band--white" id="curriculum<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="curriculum-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('curriculum.eyebrow'); ?></p>
				<?php tsa_heading('curriculum', 'curriculum-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('curriculum.lede'); ?></p>
		</div>

		<?php if (tsa_count('curriculum.stats')) : ?>
			<ul class="stats rv">
				<?php for ($i = 0; $i < tsa_count('curriculum.stats'); $i++) :
					$v = tsa_text('curriculum.stats.' . $i . '.value');
					$l = tsa_text('curriculum.stats.' . $i . '.label');
					if ($v === '' && $l === '') continue; ?>
					<li class="stats__i<?php echo $v === '' ? ' stats__i--plain' : ''; ?>">
						<?php if ($v !== '') : ?><span class="stats__n"><?php echo esc_html($v); ?></span><?php endif; ?>
						<span class="stats__l"><?php echo esc_html($l); ?></span>
					</li>
				<?php endfor; ?>
			</ul>
		<?php endif; ?>

		<p class="copy rv" style="margin:clamp(2rem,4.5vw,3rem) 0 clamp(2rem,4.5vw,3rem)"><?php tsa_e('curriculum.body'); ?></p>

		<ol class="curric">
			<?php for ($i = 0; $i < tsa_count('curriculum.courses'); $i++) : $p = 'curriculum.courses.' . $i; ?>
				<li class="curric__i <?php echo esc_attr(tsa_rv(min($i, 3))); ?>">
					<span class="curric__codes"><?php
						// Break between codes, never inside one: "MAT 005" split
						// across two lines reads as two different things.
						$codes = array_filter(array_map('trim', explode('·', tsa_text($p . '.codes'))), 'strlen');
						if (!$codes) $codes = array(tsa_text($p . '.codes'));
						echo implode('<span class="curric__sep" aria-hidden="true"> · </span>',
							array_map(function ($c) { return '<span class="curric__code">' . esc_html($c) . '</span>'; }, $codes));
					?></span>
					<div class="curric__body">
						<h3><?php tsa_e($p . '.title'); ?></h3>
						<p><?php tsa_e($p . '.body'); ?></p>
					</div>
				</li>
			<?php endfor; ?>
		</ol>

		<div class="curric__practice on-navy rv">
			<p class="eyebrow"><?php tsa_e('curriculum.practiceEyebrow'); ?></p>
			<h3>
				<?php tsa_e('curriculum.practiceHeadline'); ?>
				<em><?php tsa_e('curriculum.practiceHeadlineEm'); ?></em>
			</h3>
			<p><?php tsa_e('curriculum.practiceBody'); ?></p>
		</div>

		<?php tsa_note('curriculum.note', 'max-width:84ch'); ?>
	</div>
</section>
