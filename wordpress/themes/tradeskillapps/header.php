<?php defined('ABSPATH') || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e('Skip to main content', 'tradeskillapps'); ?></a>
<?php tsa_icon_sprite(); ?>
<span id="top"></span>

<header class="nav" id="nav">
	<div class="wrap nav__in">
		<?php tsa_brand(); ?>

		<nav aria-label="<?php esc_attr_e('Primary', 'tradeskillapps'); ?>">
			<?php
			if (has_nav_menu('primary')) {
				wp_nav_menu(array(
					'theme_location' => 'primary',
					'menu_class'     => 'nav__links',
					'menu_id'        => 'navLinks',
					'container'      => false,
					'depth'          => 1,
				));
			} else {
				tsa_nav_fallback();
			}
			?>
		</nav>

		<?php $nav_cta = tsa_link('navCta'); ?>
		<a class="btn btn--primary nav__cta" href="<?php echo esc_url($nav_cta['href']); ?>">
			<?php echo esc_html($nav_cta['label']); ?>
			<span class="arw" aria-hidden="true">&rarr;</span>
		</a>

		<button class="burger" type="button" aria-expanded="false" aria-controls="drawer"
			aria-label="<?php esc_attr_e('Open navigation menu', 'tradeskillapps'); ?>">
			<span class="burger__bars" aria-hidden="true"></span>
		</button>
	</div>

	<div class="drawer" id="drawer">
		<ul>
			<?php
			$links = array();
			foreach (array('nav', 'drawerExtra') as $group) {
				$items = tsa_default($group);
				if (!is_array($items)) continue;
				foreach ($items as $i => $unused) {
					$label = tsa_text($group . '.' . $i . '.label');
					$href  = tsa_text($group . '.' . $i . '.href');
					if ($label !== '') $links[] = array($label, $href);
				}
			}
			foreach ($links as $l) {
				printf('<li><a href="%s">%s</a></li>', esc_url($l[1]), esc_html($l[0]));
			}
			?>
		</ul>
		<a class="btn btn--primary btn--block" href="<?php echo esc_url($nav_cta['href']); ?>">
			<?php echo esc_html($nav_cta['label']); ?>
			<span class="arw" aria-hidden="true">&rarr;</span>
		</a>
	</div>
</header>
