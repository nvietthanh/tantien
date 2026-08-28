<?php
/**
 * Fallback template.
 *
 * @package          TantienWindow
 * @flatsome-version 3.16.0
 */

get_header();
?>

<div class="ttw-page-hero">
	<div class="container">
		<h1><?php echo esc_html( ttw_page_hero_title() ); ?></h1>
		<?php ttw_breadcrumb(); ?>
	</div>
</div>

<div class="ttw-content-area">
	<div class="container">
		<div class="ttw-layout">
			<main class="ttw-main">
				<?php if ( have_posts() ) : ?>
					<div class="ttw-blog-grid">
						<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'ttw-post-card' ); ?>>
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="ttw-post-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'ttw-card-16x10' ); ?></a>
								<?php endif; ?>
								<div class="ttw-post-card-body">
									<div class="ttw-news-meta">
										<span><?php echo esc_html( get_the_date() ); ?></span>
									</div>
									<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<p class="ttw-post-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
									<a class="btn" href="<?php the_permalink(); ?>">Đọc thêm</a>
								</div>
							</article>
						<?php endwhile; ?>
					</div>

					<div class="ttw-pagination">
						<?php
						the_posts_pagination( array(
							'mid_size'  => 2,
							'prev_text' => '←',
							'next_text' => '→',
						) );
						?>
					</div>

				<?php else : ?>
					<p><?php esc_html_e( 'Chưa có nội dung.', 'tantien-window' ); ?></p>
				<?php endif; ?>
			</main>

			<aside class="ttw-sidebar">
				<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
					<?php dynamic_sidebar( 'sidebar-blog' ); ?>
				<?php endif; ?>
			</aside>
		</div>
	</div>
</div>

<?php
get_footer();
