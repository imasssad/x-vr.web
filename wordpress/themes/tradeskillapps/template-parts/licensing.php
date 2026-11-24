<?php
defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-licensing');
$cta = tsa_link('licensing.site.cta');
?>
<section class="band" id="licensing<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="licensing-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('licensing.eyebrow'); ?></p>
				<?php tsa_heading('licensing', 'licensing-h' . $sec_sfx); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('licensing.lede'); ?></p>
		</div>

		<div class="price">
			<article class="price__card rv">
				<span class="price__label"><?php tsa_e('licensing.individual.label'); ?></span>
				<p class="price__figure"><?php tsa_e('licensing.individual.price'); ?></p>
				<p class="price__unit"><?php tsa_e('licensing.individual.unit'); ?></p>
			</article>

			<article class="price__card price__card--quote on-navy rv rv-d1">
				<span class="price__label"><?php tsa_e('licensing.site.label'); ?></span>
				<p class="price__body"><?php tsa_e('licensing.site.body'); ?></p>
				<?php if ($cta['label'] !== '') : ?>
					<a class="btn btn--primary" href="<?php echo esc_url(tsa_href($cta['href'])); ?>">
						<?php echo esc_html($cta['label']); ?>
						<span class="arw" aria-hidden="true">&rarr;</span>
					</a>
				<?php endif; ?>
			</article>
		</div>

		<?php tsa_note('licensing.note', 'max-width:82ch'); ?>
	</div>
</section>
