<?php defined('ABSPATH') || exit;
$sec_sfx = tsa_uid('sec-mission'); $cta = tsa_link('mission.cta'); ?>
<section class="band mission on-navy" id="mission<?php echo esc_attr($sec_sfx); ?>" aria-labelledby="mission-h<?php echo esc_attr($sec_sfx); ?>">
	<div class="wrap mission__grid">
		<div>
			<p class="eyebrow rv"><?php tsa_e('mission.eyebrow'); ?></p>
			<h2 id="mission-h<?php echo esc_attr($sec_sfx); ?>" class="vh"><?php esc_html_e('Our mission', 'tradeskillapps'); ?></h2>
			<blockquote class="rv"><?php tsa_e('mission.quote'); ?> <em><?php tsa_e('mission.quoteEm'); ?></em></blockquote>
			<?php foreach (tsa_lines('mission.body') as $i => $b) : ?>
				<p class="copy rv rv-d1"<?php echo $i === 0 ? ' style="margin-top:1.75rem"' : ''; ?>><?php echo esc_html($b); ?></p>
			<?php endforeach; ?>
			<p class="rv rv-d2" style="margin-top:1.9rem">
				<a class="btn btn--primary" href="<?php echo esc_url($cta['href']); ?>">
					<?php echo esc_html($cta['label']); ?> <span class="arw" aria-hidden="true">&rarr;</span>
				</a>
			</p>
		</div>
		<?php tsa_figure('mission.image', '4/3', null, '', 'mission__media rv-d1'); ?>
	</div>
</section>
