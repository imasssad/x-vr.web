<?php
/**
 * The closing band: who the product is for, and one way to get in touch.
 * The last section on the client's reference page.
 */
defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-partners');
$cta     = tsa_link('partners.cta');
?>
<section class="band partners on-navy" id="partners<?php echo esc_attr($sec_sfx); ?>"
	aria-labelledby="partners-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap partners__in">
		<?php if (tsa_text('partners.eyebrow') !== '') : ?>
			<p class="eyebrow rv"><?php tsa_e('partners.eyebrow'); ?></p>
		<?php endif; ?>
		<h2 id="partners-h<?php echo esc_attr($sec_sfx); ?>" class="rv">
<?php tsa_e('partners.headline');
			$em = tsa_text('partners.headlineEm');
			if ($em !== '') echo tsa_em_gap($em) . '<em>' . esc_html($em) . '</em>'; ?>
		</h2>
		<?php foreach (tsa_lines('partners.body') as $b) : ?>
			<p class="copy rv rv-d1"><?php echo esc_html($b); ?></p>
		<?php endforeach; ?>
		<?php if ($cta['label'] !== '') : ?>
			<p class="rv rv-d2 partners__act">
				<a class="btn btn--primary" href="<?php echo esc_url($cta['href']); ?>">
					<?php echo esc_html($cta['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>
