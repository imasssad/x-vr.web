<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-faq'); $items = tsa_items('tsa_faq'); ?>
<section class="band band--white" id="faq<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="faq-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head">
			<div>
				<p class="eyebrow rv"><?php tsa_e('faq.eyebrow'); ?></p>
				<?php tsa_heading('faq', 'faq-h' . $sec_sfx); ?>
			</div>
		</div>
		<?php tsa_accordion($items, 'faq'); ?>
	</div>
</section>
