<?php
/**
 * Page template cho tantien-flatsome.
 *
 * @package TantienFlatsome
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
	<div class="ttw-news-page">
		<div class="ttw-news-container">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
	</div>
<?php endwhile; ?>

<?php
get_footer();
