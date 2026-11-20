<?php defined('ABSPATH') || exit; get_header(); ?>
<main id="main" class="band band--white">
	<div class="wrap wrap--narrow" style="text-align:center">
		<p class="eyebrow"><?php esc_html_e('404', 'tradeskillapps'); ?></p>
		<h1><?php esc_html_e('That page does not exist.', 'tradeskillapps'); ?></h1>
		<p class="copy"><?php esc_html_e('The link may be out of date, or the page may have moved.', 'tradeskillapps'); ?></p>
		<p style="margin-top:2rem">
			<a class="btn btn--primary" href="<?php echo esc_url(home_url('/')); ?>">
				<?php esc_html_e('Back to the home page', 'tradeskillapps'); ?>
				<span class="arw" aria-hidden="true">&rarr;</span>
			</a>
		</p>
	</div>
</main>
<?php get_footer(); ?>
