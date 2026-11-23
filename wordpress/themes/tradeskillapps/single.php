<?php
/** A standalone page, carrying the same design as the home page. */
defined('ABSPATH') || exit;
get_header();

if (!tsa_do_location('single')) :
	$built = tsa_built_with_elementor();
?>
<main id="main" class="band band--white">
	<?php if ($built) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<div class="wrap wrap--narrow">
			<?php while (have_posts()) : the_post(); ?>
				<div class="sec-head"><div><h1 class="rv"><?php the_title(); ?></h1></div></div>
				<div class="copy entry"><?php the_content(); ?></div>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
</main>
<?php
endif;
get_footer();
