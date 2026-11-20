<?php
/** A standalone page, carrying the same design as the home page. */
defined('ABSPATH') || exit;
get_header();
?>
<main id="main" class="band band--white">
	<div class="wrap wrap--narrow">
		<?php while (have_posts()) : the_post(); ?>
			<div class="sec-head"><div><h1 class="rv"><?php the_title(); ?></h1></div></div>
			<div class="copy entry"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</div>
</main>
<?php get_footer(); ?>
