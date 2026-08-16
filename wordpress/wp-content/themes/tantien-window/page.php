<?php
/**
 * Page template.
 *
 * @package TantienWindow
 */

get_header();
?>

<div class="ttw-page-hero">
	<div class="container">
		<h1><?php the_title(); ?></h1>
		<?php ttw_breadcrumb(); ?>
	</div>
</div>

<div class="ttw-content-area">
	<div class="container">
		<main class="ttw-main ttw-single">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="ttw-post-card-thumb">
							<?php the_post_thumbnail( 'ttw-card-16x10' ); ?>
						</div>
					<?php endif; ?>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		</main>
	</div>
</div>

<?php
get_footer();
