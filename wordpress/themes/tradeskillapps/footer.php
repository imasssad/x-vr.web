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
				<?php tsa_brand('brand', 'full'); ?>
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
						printf('<li><a href="%s">%s</a></li>', esc_url(tsa_href($href)), esc_html($label));
					}
					// The pages carrying the sections that are not on the
					// front page. Listed here so none of them is reachable
					// only by typing its address. A page the owner has
					// deleted simply does not appear.
					foreach (tsa_inner_pages() as $slug => $spec) {
						$url = tsa_page_url($slug, '');
						if ($url === '') continue;
						printf('<li><a href="%s">%s</a></li>', esc_url($url), esc_html($spec['title']));
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
