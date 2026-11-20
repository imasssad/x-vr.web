<?php
defined('ABSPATH') || exit;
$trades = tsa_items('tsa_trade');
if (!$trades) return;
?>
<section class="band band--white" id="trades" aria-labelledby="trades-h">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('tradesSection.eyebrow'); ?></p>
				<?php tsa_heading('tradesSection', 'trades-h'); ?>
			</div>
			<p class="lede rv rv-d1"><?php tsa_e('tradesSection.lede'); ?></p>
		</div>

		<div class="trades">
			<div class="tradelist rv" role="tablist" aria-label="<?php esc_attr_e('Choose a trade', 'tradeskillapps'); ?>" aria-orientation="vertical">
				<?php foreach ($trades as $i => $t) :
					$slug = $t->post_name;
					$icon = get_post_meta($t->ID, '_tsa_icon', true) ?: 'i-weld';
				?>
					<button class="tradebtn" role="tab" id="tab-<?php echo esc_attr($slug); ?>"
						aria-controls="panel-<?php echo esc_attr($slug); ?>"
						aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
						tabindex="<?php echo $i === 0 ? '0' : '-1'; ?>" type="button">
						<svg class="tradebtn__ico" aria-hidden="true"><use href="#<?php echo esc_attr($icon); ?>" /></svg>
						<span class="tradebtn__name"><?php echo esc_html($t->post_title); ?></span>
						<svg class="tradebtn__chev" width="16" height="16" aria-hidden="true"><use href="#i-chev" /></svg>
					</button>
				<?php endforeach; ?>
			</div>

			<div>
				<?php foreach ($trades as $i => $t) :
					$slug  = $t->post_name;
					$icon  = get_post_meta($t->ID, '_tsa_icon', true) ?: 'i-weld';
					$img   = tsa_post_image($t->ID);
					$skills = array_values(array_filter(array_map('trim',
						preg_split('/\r\n|\r|\n/', (string) get_post_meta($t->ID, '_tsa_skills', true))), 'strlen'));
				?>
					<article class="tradepanel rv rv-d1" role="tabpanel"
						id="panel-<?php echo esc_attr($slug); ?>"
						aria-labelledby="tab-<?php echo esc_attr($slug); ?>" tabindex="0"
						<?php echo $i === 0 ? '' : 'hidden'; ?>>
						<figure class="frame tradepanel__media frame--zoom">
							<?php if ($img['src'] !== '') : ?>
								<img src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy" decoding="async" />
							<?php else : tsa_placeholder($slug, $icon); endif; ?>
						</figure>
						<div class="tradepanel__body">
							<span class="chip-label chip-label--solid"><?php
								printf(esc_html__('Trade focus / %s', 'tradeskillapps'), esc_html($t->post_title)); ?></span>
							<h3><?php echo esc_html(get_post_meta($t->ID, '_tsa_title', true)); ?></h3>
							<p class="tradepanel__lead"><?php echo esc_html(get_post_meta($t->ID, '_tsa_desc', true)); ?></p>
							<?php if ($skills) : ?>
								<ul class="skills"><?php foreach ($skills as $s) : ?><li><?php echo esc_html($s); ?></li><?php endforeach; ?></ul>
							<?php endif; ?>
							<div class="panel-split">
								<div class="panel-box">
									<span class="minilabel"><?php esc_html_e('Learning goal', 'tradeskillapps'); ?></span>
									<p><?php echo esc_html(get_post_meta($t->ID, '_tsa_goal', true)); ?></p>
								</div>
								<div class="panel-box">
									<span class="minilabel"><?php esc_html_e('Sample immersive experience', 'tradeskillapps'); ?></span>
									<p><?php echo esc_html(get_post_meta($t->ID, '_tsa_sample', true)); ?></p>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>

		<?php tsa_note('tradesSection.note', 'max-width:78ch'); ?>
	</div>
</section>
