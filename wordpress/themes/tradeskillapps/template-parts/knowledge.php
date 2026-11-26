<?php
/** "Build expert trade knowledge." - heading left, one bold paragraph right. */
defined('ABSPATH') || exit;
$uid = tsa_uid('sec-knowledge');
?>
<section class="knowledge" id="knowledge<?php echo esc_attr($uid); ?>">
	<div class="wrap split">
		<div><h2><?php tsa_e('knowledge.headline'); ?></h2></div>
		<div><p><strong><?php tsa_e('knowledge.body'); ?></strong></p></div>
	</div>
</section>
