<?php defined('ABSPATH') || exit; $cta = tsa_link('closing.cta'); $chain = tsa_lines('closing.chain'); ?>
<section class="band closing on-navy" aria-labelledby="closing-h">
	<div class="wrap closing__in">
		<?php if ($chain) : ?>
			<ul class="chain rv"><?php foreach ($chain as $c) : ?><li><?php echo esc_html($c); ?></li><?php endforeach; ?></ul>
		<?php endif; ?>
		<h2 id="closing-h" class="rv rv-d1"><?php tsa_e('closing.headline'); ?> <em><?php tsa_e('closing.headlineEm'); ?></em></h2>
		<a class="btn btn--primary rv rv-d2" href="<?php echo esc_url($cta['href']); ?>">
			<?php echo esc_html($cta['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
		</a>
		<?php tsa_figure('closing.image', '16/7', null, 'margin:clamp(2.5rem,6vw,4rem) auto 0;max-width:980px;border-color:rgba(255,255,255,.18)', 'rv-d3'); ?>
	</div>
</section>
