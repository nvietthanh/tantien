<?php
/**
 * Page template - Công trình tiêu biểu.
 *
 * @package TantienWindow
 */

get_header();

$ttw_gallery_ids = array( 2433, 2432, 1925, 1926, 1927, 1933, 1931, 1930 );

$ttw_query = new WP_Query( array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 9,
	'tax_query'      => array(
		array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => 'cong-trinh-tieu-bieu',
		),
	),
) );
?>

<div class="ttw-page-hero">
	<div class="container">
		<h1>Những công trình tiêu biểu</h1>
		<?php ttw_breadcrumb(); ?>
	</div>
</div>

<div class="ttw-content-area">
	<div class="container">
		<main class="ttw-main">
			<div class="ttw-intro">
				<p>Tân Tiến Window tự hào đã thi công nhiều công trình cửa nhôm kính trên toàn quốc, từ nhà phố, biệt thự cho đến văn phòng, showroom và các công trình công nghiệp lớn. Dưới đây là những công trình tiêu biểu mà chúng tôi đã thực hiện.</p>
			</div>

			<div class="ttw-gallery">
				<?php foreach ( $ttw_gallery_ids as $ttw_i => $ttw_gid ) : ?>
					<?php $ttw_gurl = wp_get_attachment_image_url( $ttw_gid, 'ttw-card' ); ?>
					<?php if ( $ttw_gurl ) : ?>
						<a class="ttw-gallery-item ttw-animate" href="<?php echo esc_url( $ttw_gurl ); ?>" data-lightbox="ttw-gallery" title="Công trình Tân Tiến Window">
							<img src="<?php echo esc_url( $ttw_gurl ); ?>" alt="Công trình Tân Tiến Window" loading="lazy">
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php if ( $ttw_query->have_posts() ) : ?>
				<div class="ttw-blog-grid" style="margin-top:2.5rem;">
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
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p class="ttw-post-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
								<a class="btn" href="<?php the_permalink(); ?>">Xem chi tiết</a>
							</div>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</main>
	</div>
</div>

<?php
get_footer();
