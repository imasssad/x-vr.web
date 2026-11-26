<?php
/**
 * "Explore the trades." - the four programmes, each a <details> with its
 * name, its subtitle and a sentence, exactly as the reference page has them.
 * They stay editable as Trades in the admin menu.
 */
defined('ABSPATH') || exit;
$uid    = tsa_uid('sec-programs');
$trades = tsa_trade_items();
if (!$trades) return;

$limit = (int) tsa_text('tradesSection.limit');
if ($limit > 0) $trades = array_slice($trades, 0, $limit);
?>
<section id="programs<?php echo esc_attr($uid); ?>" class="programs">
	<div class="wrap">
		<p class="eyebrow"><?php tsa_e('tradesSection.eyebrow'); ?></p>
		<h2><?php tsa_e('tradesSection.headline'); ?></h2>
		<div class="program-grid">
			<?php foreach ($trades as $t) : ?>
				<details>
					<summary><span><?php echo esc_html($t['name']); ?></span><small><?php echo esc_html($t['title']); ?></small></summary>
					<p><?php echo esc_html($t['desc']); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
