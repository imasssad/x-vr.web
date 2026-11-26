<?php
/** "Learn together inside the virtual shop." - eyebrow and heading left, the three steps right. */
defined('ABSPATH') || exit;
$uid = tsa_uid('sec-learning');
?>
<section class="intro" id="learning<?php echo esc_attr($uid); ?>">
	<div class="wrap split">
		<div>
			<p class="eyebrow"><?php tsa_e('multiplayer.eyebrow'); ?></p>
			<h2><?php tsa_e('multiplayer.headline'); ?></h2>
		</div>
		<div class="process">
			<p><?php tsa_e('multiplayer.lede'); ?></p>
			<ol>
				<?php for ($i = 0; $i < tsa_count('multiplayer.steps'); $i++) : $p = 'multiplayer.steps.' . $i; ?>
					<li><strong><?php tsa_e($p . '.title'); ?></strong><span><?php tsa_e($p . '.body'); ?></span></li>
				<?php endfor; ?>
			</ol>
		</div>
	</div>
</section>
