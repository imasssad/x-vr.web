<?php defined('ABSPATH') || exit; $items = tsa_items('tsa_term'); ?>
<section class="band band--white" id="language" aria-labelledby="language-h">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('glossary.eyebrow'); ?></p>
				<?php tsa_heading('glossary', 'language-h'); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('glossary.lede'); ?></p>
		</div>
		<?php tsa_accordion($items, 'gloss'); ?>
	</div>
</section>
