<?php
/**
 * The reference page's footer: logo, the institute's name, back to top.
 */
defined('ABSPATH') || exit;
?>
<footer>
	<div class="wrap foot">
		<?php tsa_brand('brand', 'full'); ?>
		<p><?php tsa_e('footer.tagline'); ?></p>
		<a href="#main"><?php esc_html_e('Back to top', 'tradeskillapps'); ?></a>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
