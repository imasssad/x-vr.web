<?php
/**
 * Template Name: Full width (for page builders)
 *
 * No reading column and no heading: the page controls its own layout, which
 * is what a page built in Elementor expects. Header and footer stay, so the
 * page still sits inside the site.
 */
defined('ABSPATH') || exit;
get_header();
?>
<main id="main" class="tsa-elementor-page">
	<?php while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
