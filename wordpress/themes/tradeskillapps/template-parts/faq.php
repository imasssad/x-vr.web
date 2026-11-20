<?php defined('ABSPATH') || exit; $items = tsa_items('tsa_faq'); ?>
<section class="band band--white" id="faq" aria-labelledby="faq-h">
	<div class="wrap">
		<div class="sec-head">
			<div>
				<p class="eyebrow rv"><?php tsa_e('faq.eyebrow'); ?></p>
				<?php tsa_heading('faq', 'faq-h'); ?>
			</div>
		</div>
		<?php tsa_accordion($items, 'faq'); ?>
	</div>
</section>
