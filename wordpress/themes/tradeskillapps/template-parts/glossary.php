<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-language'); $items = tsa_items('tsa_term'); ?>
<section class="band band--white" id="language<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="language-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('glossary.eyebrow'); ?></p>
				<?php tsa_heading('glossary', 'language-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('glossary.lede'); ?></p>
		</div>
		<?php tsa_accordion($items, 'gloss'); ?>
	</div>
</section>
