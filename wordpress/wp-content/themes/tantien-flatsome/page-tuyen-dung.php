<?php
/**
 * Template Name: Tuyển Dụng - Danh Sách Tin Tức
 * Template Post Type: page
 *
 * @package TantienWindow
 */

get_header();

$current_cat = get_term_by( 'slug', 'tuyen-dung', 'category' );
$cat_name    = $current_cat ? $current_cat->name : 'Tuyển Dụng';
$cat_desc    = ( $current_cat && ! empty( $current_cat->description ) ) ? $current_cat->description : 'Cập nhật các thông tin và cơ hội việc làm mới nhất tại Tân Tiến Window.';

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

$args = array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'category_name'  => 'tuyen-dung',
	'posts_per_page' => 9,
	'paged'          => $paged,
);

$careers_query = new WP_Query( $args );
?>

<div class="ttw-news-page">
	<div class="ttw-news-container">

		<!-- Hero Section -->
		<section class="ttw-news-hero ttw-animate ttw-fade-up">
			<h1 class="ttw-news-title"><?php echo esc_html( mb_strtoupper( $cat_name, 'UTF-8' ) ); ?></h1>
			<p class="ttw-news-subtitle"><?php echo esc_html( $cat_desc ); ?></p>
		</section>


		<!-- 3. Posts Grid (Danh sách tin tức tuyển dụng) -->
		<?php if ( $careers_query->have_posts() ) : ?>
			<section class="ttw-news-grid" aria-label="Danh sách bài viết tuyển dụng">
				<?php
				while ( $careers_query->have_posts() ) :
					$careers_query->the_post();
					$p_id    = get_the_ID();
					$p_title = get_the_title( $p_id );
					$p_link  = get_permalink( $p_id );
					$p_date  = get_the_date( 'd.m.Y', $p_id );
					$p_thumb = get_the_post_thumbnail_url( $p_id, 'large' );
					if ( ! $p_thumb ) {
						$p_thumb = get_stylesheet_directory_uri() . '/assets/img/design/news1.jpg';
					}
					$p_desc = get_the_excerpt( $p_id );
					if ( empty( $p_desc ) ) {
						$p_desc = wp_trim_words( get_the_content(), 22, '...' );
					}
					?>
					<article class="ttw-news-card ttw-animate ttw-fade-up">
						<a class="ttw-news-thumb" href="<?php echo esc_url( $p_link ); ?>" title="<?php echo esc_attr( $p_title ); ?>">
							<img src="<?php echo esc_url( $p_thumb ); ?>" alt="<?php echo esc_attr( $p_title ); ?>" loading="lazy" />
						</a>
						<div class="ttw-news-content">
							<div class="ttw-news-meta-row">
								<span class="ttw-news-tag"><?php echo esc_html( mb_strtoupper( $cat_name, 'UTF-8' ) ); ?></span>
								<span class="ttw-news-date"><?php echo esc_html( $p_date ); ?></span>
							</div>
							<h3 class="ttw-news-card-title">
								<a href="<?php echo esc_url( $p_link ); ?>"><?php echo esc_html( $p_title ); ?></a>
							</h3>
							<a href="<?php echo esc_url( $p_link ); ?>" class="ttw-news-readmore">
								<span>ĐỌC THÊM</span>
								<svg width="12" height="12" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
									<path d="M8.3703 6.18749H0V4.81249H8.3703L4.5203 0.962498L5.49999 0L11 5.49999L5.49999 11L4.5203 10.0375L8.3703 6.18749Z" fill="currentColor"/>
								</svg>
							</a>
						</div>
					</article>

				<?php endwhile; wp_reset_postdata(); ?>
			</section>

			<!-- 4. Phân trang Pagination -->
			<div class="ttw-news-pagination ttw-animate ttw-fade-up">
				<?php
				$total_pages = $careers_query->max_num_pages;
				if ( $total_pages > 1 ) {
					echo paginate_links( array(
						'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'format'    => '?paged=%#%',
						'current'   => max( 1, $paged ),
						'total'     => $total_pages,
						'prev_text' => '<svg width="8" height="12" viewBox="0 0 6 10" fill="none"><path d="M5 1L1 5L5 9" stroke="currentColor" stroke-width="1.5"/></svg>',
						'next_text' => '<svg width="8" height="12" viewBox="0 0 6 10" fill="none"><path d="M1 1L5 5L1 9" stroke="currentColor" stroke-width="1.5"/></svg>',
						'type'      => 'list',
					) );
				}
				?>
			</div>

		<?php else : ?>
			<div class="ttw-news-empty">
				<p>Hiện chưa có bài viết nào trong chuyên mục Tuyển Dụng.</p>
				<a href="<?php echo esc_url( home_url( '/tin-tuc/' ) ); ?>" class="btn">Xem Tất Cả Tin Tức</a>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php
get_footer();
