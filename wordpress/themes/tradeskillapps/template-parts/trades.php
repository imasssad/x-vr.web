<?php
defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-trades');
$trades = tsa_trade_items();
if (!$trades) return;
$sfx = tsa_uid('trades');

// "cards" is the client's reference layout: four plain cards, name,
// subtitle and a sentence. "tabs" is the explorer this theme shipped,
// with skills, learning goal and a sample experience; it moves to the
// Curriculum page rather than being dropped.
$layout = tsa_text('tradesSection.layout') === 'tabs' ? 'tabs' : 'cards';

// 0 or blank means every trade. The front page shows the four the
// reference shows; the inner page shows them all.
$limit = (int) tsa_text('tradesSection.limit');
if ($limit > 0) $trades = array_slice($trades, 0, $limit);
$lede = tsa_text('tradesSection.lede');
?>
<section class="band band--pale" id="trades<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="trades-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap">
		<div class="sec-head sec-head--split">
			<div>
				<p class="eyebrow rv"><?php tsa_e('tradesSection.eyebrow'); ?></p>
				<?php tsa_heading('tradesSection', 'trades-h' . $sec_sfx); ?>
			</div>
			<?php if ($lede !== '') : ?>
				<p class="lede rv rv-d1"><?php echo esc_html($lede); ?></p>
			<?php endif; ?>
		</div>

		<?php if ($layout === 'cards') : ?>
		<div class="tcards">
			<?php foreach ($trades as $i => $t) : ?>
				<article class="tcard <?php echo esc_attr(tsa_rv($i)); ?>">
					<?php // The reference's cards carry no picture, which also means
					      // no "photography to be supplied" placeholder on the trades
					      // we have no photograph for. The pictures are still used by
					      // the explorer on the Curriculum page.
					?>
					<div class="tcard__body">
						<svg class="tcard__ico" aria-hidden="true"><use href="#<?php echo esc_attr($t['icon']); ?>" /></svg>
						<h3 class="tcard__name"><?php echo esc_html($t['name']); ?></h3>
						<p class="tcard__sub"><?php echo esc_html($t['title']); ?></p>
						<p class="tcard__desc"><?php echo esc_html($t['desc']); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php else : ?>
		<div class="trades">
			<div class="tradelist rv" role="tablist" aria-label="<?php esc_attr_e('Choose a trade', 'tradeskillapps'); ?>" aria-orientation="vertical">
				<?php foreach ($trades as $i => $t) :
					$slug = $t['slug'];
					$icon = $t['icon'];
				?>
					<button class="tradebtn" role="tab" id="tab-<?php echo esc_attr($slug . $sfx); ?>"
						aria-controls="panel-<?php echo esc_attr($slug . $sfx); ?>"
						aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
						tabindex="<?php echo $i === 0 ? '0' : '-1'; ?>" type="button">
						<svg class="tradebtn__ico" aria-hidden="true"><use href="#<?php echo esc_attr($icon); ?>" /></svg>
						<span class="tradebtn__name"><?php echo esc_html($t['name']); ?></span>
						<svg class="tradebtn__chev" width="16" height="16" aria-hidden="true"><use href="#i-chev" /></svg>
					</button>
				<?php endforeach; ?>
			</div>

			<div>
				<?php foreach ($trades as $i => $t) :
					$slug   = $t['slug'];
					$icon   = $t['icon'];
					$img    = $t['image'];
					$skills = $t['skills'];
				?>
					<article class="tradepanel<?php echo $img['src'] === '' ? ' tradepanel--text' : ''; ?> rv rv-d1" role="tabpanel"
						id="panel-<?php echo esc_attr($slug . $sfx); ?>"
						aria-labelledby="tab-<?php echo esc_attr($slug . $sfx); ?>" tabindex="0"
						<?php echo $i === 0 ? '' : 'hidden'; ?>>
						<?php // no photograph, no frame - the client's demo has none, and an
						      // illustrated placeholder here read as an unfinished page ?>
						<?php if ($img['src'] !== '') : ?>
							<figure class="frame tradepanel__media frame--zoom">
								<img src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy" decoding="async" />
							</figure>
						<?php endif; ?>
						<div class="tradepanel__body">
							<span class="chip-label chip-label--solid"><?php
								printf(esc_html__('Trade focus / %s', 'tradeskillapps'), esc_html($t['name'])); ?></span>
							<h3><?php echo esc_html($t['title']); ?></h3>
							<p class="tradepanel__lead"><?php echo esc_html($t['desc']); ?></p>
							<?php if ($skills) : ?>
								<ul class="skills"><?php foreach ($skills as $s) : ?><li><?php echo esc_html($s); ?></li><?php endforeach; ?></ul>
							<?php endif; ?>
							<div class="panel-split">
								<div class="panel-box">
									<span class="minilabel"><?php esc_html_e('Learning goal', 'tradeskillapps'); ?></span>
									<p><?php echo esc_html($t['goal']); ?></p>
								</div>
								<div class="panel-box">
									<span class="minilabel"><?php esc_html_e('Sample immersive experience', 'tradeskillapps'); ?></span>
									<p><?php echo esc_html($t['sample']); ?></p>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>

		<?php tsa_note('tradesSection.note', 'max-width:78ch'); ?>
	</div>
</section>
