<?php
/**
 * The reference page's header, markup for markup.
 * Its own source is:
 *   <header><nav class="wrap nav"><a class="brand">…</a>
 *     <div class="links">…</div><a class="button small">…</a></nav></header>
 */
defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e('Skip to content', 'tradeskillapps'); ?></a>
<header>
	<nav class="wrap nav" aria-label="<?php esc_attr_e('Main navigation', 'tradeskillapps'); ?>">
		<?php tsa_brand(); ?>
		<div class="links">
			<?php foreach ((array) tsa_default('nav') as $i => $unused) :
				$label = tsa_text('nav.' . $i . '.label');
				if ($label === '') continue;
				$href = tsa_text('nav.' . $i . '.href');
			?>
				<a href="<?php echo esc_url(tsa_href($href)); ?>"><?php echo esc_html($label); ?></a>
			<?php endforeach; ?>
		</div>
		<?php $cta = tsa_link('navCta'); ?>
		<?php if ($cta['label'] !== '') : ?>
			<a class="button small" href="<?php echo esc_url(tsa_href($cta['href'])); ?>"><?php echo esc_html($cta['label']); ?></a>
		<?php endif; ?>
	</nav>
</header>
