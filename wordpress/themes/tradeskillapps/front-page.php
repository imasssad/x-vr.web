<?php
/**
 * The home page.
 *
 * Normally the one-page site, assembled from template-parts/ in the order
 * the sections appear on screen. But if the owner has set a static front
 * page and built THAT page with Elementor, their page wins - that is the
 * documented way to take ownership of the home page in the builder, and
 * without this branch the template would silently override their work.
 */
defined('ABSPATH') || exit;
get_header();

$tsa_front_id = (int) get_option('page_on_front');
$tsa_owner_built = 'page' === get_option('show_on_front')
	&& $tsa_front_id
	&& function_exists('tsa_built_with_elementor')
	&& tsa_built_with_elementor($tsa_front_id);

if ($tsa_owner_built) : ?>
<main id="main" class="tsa-elementor-page">
	<?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
</main>
<?php else : ?>
<main id="main">
	<?php
	foreach (array(
		'hero', 'ticker', 'purpose', 'trades', 'approach', 'cycle', 'videos',
		'pathways', 'educators', 'levels', 'mission', 'glossary', 'planner',
		'access', 'faq', 'contact', 'closing',
	) as $part) {
		get_template_part('template-parts/' . $part);
	}
	?>
</main>
<?php endif;
get_footer();
