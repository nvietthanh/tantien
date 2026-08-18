<?php
/**
 * Page template - Tin tức.
 *
 * @package TantienWindow
 */

get_header();

$ttw_paged = max( 1, (int) get_query_var( 'paged' ) );

$ttw_query = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'paged'          => $ttw_paged,
) );
?>

<div class="ttw-page-hero">
	<div class="container">
		<h1>Tin tức</h1>
		<?php ttw_breadcrumb(); ?>
	</div>
</div>

<div class="ttw-content-area">
	<div class="container">
		<div class="ttw-layout">
			<main class="ttw-main">
				<?php if ( $ttw_query->have_posts() ) : ?>
					<div class="ttw-blog-grid">
						<?php
						while ( $ttw_query->have_posts() ) :
							$ttw_query->the_post();
							?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( 'ttw-post-card' ); ?>>
								<?php if ( has_post_thumbnail() ) : ?>
									<a class="ttw-post-card-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'ttw-card-16x10' ); ?></a>
								<?php else : ?>
									<a class="ttw-post-card-thumb" href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/placeholder.svg' ); ?>" alt="<?php the_title_attribute(); ?>"></a>
								<?php endif; ?>
								<div class="ttw-post-card-body">
									<div class="ttw-news-meta">
										<span><?php echo esc_html( get_the_date() ); ?></span>
										<span><?php the_category( ', ' ); ?></span>
									</div>
									<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<p class="ttw-post-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
									<a class="btn" href="<?php the_permalink(); ?>">Đọc thêm</a>
								</div>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>

					<?php if ( $ttw_query->max_num_pages > 1 ) : ?>
						<div class="ttw-pagination">
							<?php
							echo paginate_links( array(
								'total'     => $ttw_query->max_num_pages,
								'current'   => $ttw_paged,
								'prev_text' => '←',
								'next_text' => '→',
							) );
							?>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<p>Chưa có tin tức.</p>
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
