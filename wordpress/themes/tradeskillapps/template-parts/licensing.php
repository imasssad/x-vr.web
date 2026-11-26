<?php
/** "What does XVR cost for schools?" - the two plans on the right. */
defined('ABSPATH') || exit;
$uid = tsa_uid('sec-pricing');
$cta = tsa_link('licensing.site.cta');
?>
<section id="pricing<?php echo esc_attr($uid); ?>" class="pricing">
	<div class="wrap split">
		<div>
			<p class="eyebrow"><?php tsa_e('licensing.eyebrow'); ?></p>
			<h2><?php tsa_e('licensing.headline'); ?></h2>
			<p><?php tsa_e('licensing.lede'); ?></p>
		</div>
		<div class="plans">
			<div>
				<h3><?php tsa_e('licensing.individual.label'); ?></h3>
				<p class="price"><?php tsa_e('licensing.individual.price'); ?></p>
				<p><?php tsa_e('licensing.individual.unit'); ?></p>
			</div>
			<div>
				<h3><?php tsa_e('licensing.site.label'); ?></h3>
				<p><?php tsa_e('licensing.site.body'); ?></p>
				<?php if ($cta['label'] !== '') : ?>
					<p><a class="button small" href="<?php echo esc_url(tsa_href($cta['href'])); ?>"><?php echo esc_html($cta['label']); ?></a></p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
