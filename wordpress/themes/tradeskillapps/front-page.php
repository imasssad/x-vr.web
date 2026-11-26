<?php
/**
 * The home page: the reference page's six sections, in its order.
 *
 * If the owner has made a static front page and built THAT page in
 * Elementor, their page wins - the documented way to take the home page
 * over in the builder.
 */
defined('ABSPATH') || exit;
get_header();

$tsa_front_id = (int) get_option('page_on_front');
$tsa_owner_built = 'page' === get_option('show_on_front')
	&& $tsa_front_id
	&& function_exists('tsa_built_with_elementor')
	&& (tsa_built_with_elementor($tsa_front_id) || tsa_elementor_editing($tsa_front_id));

if ($tsa_owner_built) : ?>
<main id="main" class="tsa-elementor-page">
	<?php while (have_posts()) : the_post(); the_content(); endwhile; ?>
</main>
<?php else : ?>
<main id="main">
	<?php foreach (tsa_front_sections() as $part) get_template_part('template-parts/' . $part); ?>
</main>
<?php endif;
get_footer();
