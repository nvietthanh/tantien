<?php
/**
 * Search results template.
 *
 * @package          TantienWindow
 * @flatsome-version 3.16.0
 */

get_header();
?>

<div class="ttw-page-hero">
	<div class="container">
		<h1><?php esc_html_e( 'Kết quả tìm kiếm', 'tantien-window' ); ?></h1>
		<?php ttw_breadcrumb(); ?>
	</div>
</div>

<div class="ttw-content-area">
	<div class="container">
		<div class="ttw-layout">
			<main class="ttw-main">
				<form role="search" method="get" class="ttw-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Nhập từ khóa...">
					<button type="submit">Tìm</button>
				</form>

				<?php if ( have_posts() ) : ?>
					<div class="ttw-blog-grid" style="margin-top:28px;">
						<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'ttw-post-card' ); ?>>
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="ttw-post-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'ttw-card-16x10' ); ?></a>
								<?php endif; ?>
								<div class="ttw-post-card-body">
									<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<p class="ttw-post-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
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
					<p><?php esc_html_e( 'Không tìm thấy nội dung phù hợp.', 'tantien-window' ); ?></p>
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
