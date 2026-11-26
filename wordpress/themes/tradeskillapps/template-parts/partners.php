<?php
/** "Bring XVR to your training program." - the closing band. */
defined('ABSPATH') || exit;
$uid = tsa_uid('sec-partners');
$cta = tsa_link('partners.cta');
?>
<section id="partners<?php echo esc_attr($uid); ?>" class="partners">
	<div class="wrap split">
		<div>
			<p class="eyebrow"><?php tsa_e('partners.eyebrow'); ?></p>
			<h2><?php tsa_e('partners.headline'); ?></h2>
		</div>
		<div>
			<p><?php tsa_e('partners.body'); ?></p>
			<?php if ($cta['label'] !== '') : ?>
				<a class="button" href="<?php echo esc_url(tsa_href($cta['href'])); ?>"><?php echo esc_html($cta['label']); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
