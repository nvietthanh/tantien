<?php
/**
 * Single post template.
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
		<div class="ttw-layout">
			<main class="ttw-main">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'ttw-single' ); ?>>
						<div class="ttw-news-meta">
							<span><?php echo esc_html( get_the_date() ); ?></span>
							<span><?php the_author(); ?></span>
							<span><?php the_category( ', ' ); ?></span>
						</div>

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="ttw-post-card-thumb">
								<?php the_post_thumbnail( 'ttw-card-16x10' ); ?>
							</div>
						<?php endif; ?>

						<div class="entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>

						<?php the_tags( '<p class="ttw-tags">', ' ', '</p>' ); ?>

						<div class="ttw-comments">
							<?php
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}
							?>
						</div>
					</article>
				<?php endwhile; ?>
			</main>

			<aside class="ttw-sidebar">
				<?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
					<?php dynamic_sidebar( 'sidebar-main' ); ?>
				<?php elseif ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
					<?php dynamic_sidebar( 'sidebar-blog' ); ?>
				<?php endif; ?>
			</aside>
		</div>
	</div>
</div>

<?php
get_footer();
