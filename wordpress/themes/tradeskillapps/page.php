<?php
/** A standalone page, carrying the same design as the home page. */
defined('ABSPATH') || exit;
get_header();

if (!tsa_do_location('single')) :
	$built = tsa_built_with_elementor();

	// One of the theme's own inner pages, carrying the sections that are
	// not on the front page. The owner can still take it over in
	// Elementor - that branch wins, exactly as it does for the home page.
	$tsa_inner = null;
	if (!$built) {
		$tsa_post = get_post();
		if ($tsa_post) $tsa_inner = tsa_inner_page_for($tsa_post->post_name);
	}

	if ($tsa_inner) :
		$tsa_prev = tsa_set_overrides($tsa_inner['overrides']);
?>
<main id="main">
	<?php foreach ($tsa_inner['sections'] as $tsa_part) {
		get_template_part('template-parts/' . $tsa_part);
	} ?>
</main>
<?php
		tsa_set_overrides($tsa_prev);
	else :
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
endif;
get_footer();
