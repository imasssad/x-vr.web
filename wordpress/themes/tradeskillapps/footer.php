<?php
defined('ABSPATH') || exit;
if (tsa_do_location('footer')) {
	wp_footer();
	echo '</body></html>';
	return;
}
?>
<footer class="foot">
	<div class="wrap">
		<div class="foot__in">
			<div>
				<?php tsa_brand(); ?>
				<p class="foot__tag"><?php tsa_e('footer.tagline'); ?></p>
			</div>
			<nav aria-label="<?php esc_attr_e('Footer', 'tradeskillapps'); ?>">
				<?php
				if (has_nav_menu('footer')) {
					wp_nav_menu(array(
						'theme_location' => 'footer', 'menu_class' => 'foot__links',
						'container' => false, 'depth' => 1,
					));
				} else {
					echo '<ul class="foot__links">';
					foreach ((array) tsa_default('footer.links') as $i => $unused) {
						$label = tsa_text('footer.links.' . $i . '.label');
						$href  = tsa_text('footer.links.' . $i . '.href');
						if ($label === '') continue;
						printf('<li><a href="%s">%s</a></li>', esc_url($href), esc_html($label));
					}
					echo '</ul>';
				}
				?>
			</nav>
		</div>
		<div class="foot__base">
			<p><?php tsa_e('footer.copyright'); ?></p>
			<p><?php tsa_e('footer.strap'); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
