<?php
/**
 * The home page: one scroll, in the order the sections appear on screen.
 * Each part reads its own content, so a section can be removed from this
 * list without touching anything else.
 */
defined('ABSPATH') || exit;
get_header();
?>
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
<?php get_footer(); ?>
