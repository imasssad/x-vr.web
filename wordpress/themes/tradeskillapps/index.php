<?php
/** Fallback for any view that has no more specific template. */
defined('ABSPATH') || exit;
get_header();
?>
<main id="main" class="band band--white">
	<div class="wrap wrap--narrow">
		<?php if (have_posts()) : ?>
			<div class="sec-head"><div>
				<p class="eyebrow"><?php echo esc_html(get_bloginfo('name')); ?></p>
				<h1><?php
					if (is_home() && !is_front_page()) single_post_title();
					elseif (is_archive()) the_archive_title();
					elseif (is_search()) printf(esc_html__('Search results for %s', 'tradeskillapps'), '<em>' . esc_html(get_search_query()) . '</em>');
					else esc_html_e('Latest', 'tradeskillapps');
				?></h1>
			</div></div>
			<?php while (have_posts()) : the_post(); ?>
				<article <?php post_class('card'); ?>>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php echo esc_html(get_the_excerpt()); ?></p>
				</article>
			<?php endwhile; ?>
			<div class="pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<h1><?php esc_html_e('Nothing found', 'tradeskillapps'); ?></h1>
			<p class="copy"><?php esc_html_e('There is nothing here yet.', 'tradeskillapps'); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php get_footer(); ?>
