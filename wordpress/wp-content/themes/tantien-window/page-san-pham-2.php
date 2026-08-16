<?php
/**
 * Page template - Danh sách sản phẩm.
 *
 * @package TantienWindow
 */

get_header();

$ttw_paged = max( 1, (int) get_query_var( 'paged' ) );

$ttw_query = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 12,
	'paged'          => $ttw_paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'tax_query'      => array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => 'san-pham-2',
		),
	),
) );
?>

<div class="ttw-page-hero">
	<div class="container">
		<h1>Danh sách sản phẩm</h1>
		<?php ttw_breadcrumb(); ?>
	</div>
</div>

<div class="ttw-content-area">
	<div class="container">
		<main class="ttw-main">
			<?php if ( $ttw_query->have_posts() ) : ?>
				<div class="ttw-products">
					<?php
					while ( $ttw_query->have_posts() ) :
						$ttw_query->the_post();
						$ttw_product = class_exists( 'WooCommerce' ) ? wc_get_product( get_the_ID() ) : null;
						if ( ! $ttw_product ) {
							continue;
						}
						?>
						<article class="ttw-product">
							<a class="ttw-product-thumb" href="<?php the_permalink(); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'ttw-card' ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/placeholder.svg' ); ?>" alt="<?php the_title_attribute(); ?>">
								<?php endif; ?>
							</a>
							<div class="ttw-product-body">
								<h3 class="ttw-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php if ( $ttw_price = $ttw_product->get_price_html() ) : ?>
									<div class="ttw-product-price"><?php echo wp_kses_post( $ttw_price ); ?></div>
								<?php endif; ?>
								<a class="ttw-product-btn" href="<?php the_permalink(); ?>">Xem chi tiết</a>
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
				<p>Chưa có sản phẩm.</p>
			<?php endif; ?>
		</main>
	</div>
</div>

<?php
get_footer();
