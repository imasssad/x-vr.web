<?php
/** A standalone page. The site is one page, so this is a plain fallback. */
defined('ABSPATH') || exit;
get_header();

if (!tsa_do_location('single')) :
	$built = tsa_built_with_elementor();
?>
<main id="main" class="intro">
	<?php if ($built) : ?>
		<?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
	<?php else : ?>
		<div class="wrap">
			<?php while (have_posts()) : the_post(); ?>
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</main>
<?php
endif;
get_footer();
