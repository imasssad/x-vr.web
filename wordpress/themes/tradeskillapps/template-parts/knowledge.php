<?php
/**
 * A short statement band, between the "how it works" section and the
 * trades. One heading and one paragraph on a deep band - the client's
 * reference page has exactly this and the theme had no equivalent.
 */
defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-knowledge');
?>
<section class="band band--tight knowledge on-navy" id="knowledge<?php echo esc_attr($sec_sfx); ?>"
	aria-labelledby="knowledge-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap knowledge__in">
		<?php if (tsa_text('knowledge.eyebrow') !== '') : ?>
			<p class="eyebrow rv"><?php tsa_e('knowledge.eyebrow'); ?></p>
		<?php endif; ?>
		<h2 id="knowledge-h<?php echo esc_attr($sec_sfx); ?>" class="rv">
<?php tsa_e('knowledge.headline');
			$em = tsa_text('knowledge.headlineEm');
			if ($em !== '') echo tsa_em_gap($em) . '<em>' . esc_html($em) . '</em>'; ?>
		</h2>
		<?php foreach (tsa_lines('knowledge.body') as $i => $b) : ?>
			<p class="copy rv rv-d1"><?php echo esc_html($b); ?></p>
		<?php endforeach; ?>
	</div>
</section>
