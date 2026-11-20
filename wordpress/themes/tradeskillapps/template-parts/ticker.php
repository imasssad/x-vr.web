<?php defined('ABSPATH') || exit; $words = tsa_lines('ticker'); if (!$words) return; ?>
<div class="ticker" aria-hidden="true">
	<div class="ticker__track">
		<?php for ($k = 0; $k < 2; $k++) : ?>
			<span><?php foreach ($words as $t) : ?><span style="display:contents"><?php echo esc_html($t); ?> <i>&#9671;</i> </span><?php endforeach; ?></span>
		<?php endfor; ?>
	</div>
</div>
