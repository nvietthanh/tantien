<?php
/**
 * Single Product Template - Tân Tiến Window (Figma Node 1:2621)
 *
 * @package TantienWindow
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $product, $post;
if ( have_posts() ) {
	the_post();
}
if ( ! is_a( $product, 'WC_Product' ) ) {
	$product = wc_get_product( get_the_ID() );
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'ttw-product-detail-page', $product ); ?>>
	<div class="ttw-product-detail-container">
		<!-- 1. Breadcrumb -->
		<nav class="ttw-pd-breadcrumb" aria-label="Breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">TRANG CHỦ</a>
			<span class="ttw-pd-bc-sep">
				<svg width="6" height="10" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3.06667 4L0 0.933333L0.933333 0L4.93333 4L0.933333 8L0 7.06667L3.06667 4Z" fill="currentColor"/>
				</svg>
			</span>
			<a href="<?php echo esc_url( home_url( '/san-pham-2/' ) ); ?>">SẢN PHẨM</a>
			<span class="ttw-pd-bc-sep">
				<svg width="6" height="10" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3.06667 4L0 0.933333L0.933333 0L4.93333 4L0.933333 8L0 7.06667L3.06667 4Z" fill="currentColor"/>
				</svg>
			</span>
			<span class="ttw-pd-bc-current"><?php echo esc_html( mb_strtoupper( get_the_title(), 'UTF-8' ) ); ?></span>
		</nav>

		<!-- 2. Product Hero Section (Gallery Left + Info Right) -->
		<section class="ttw-pd-hero">
			<!-- Left Gallery -->
			<div class="ttw-pd-gallery">
				<?php
				$main_img_id = $product ? $product->get_image_id() : 0;
				$main_img_url = $main_img_id ? wp_get_attachment_image_url( $main_img_id, 'full' ) : get_the_post_thumbnail_url( get_the_ID(), 'full' );
				if ( ! $main_img_url ) {
					$main_img_url = get_stylesheet_directory_uri() . '/assets/img/design/product-detail-main.jpg';
				}

				$attachment_ids = $product ? $product->get_gallery_image_ids() : array();
				if ( empty( $attachment_ids ) && $main_img_id ) {
					$attachment_ids = array( $main_img_id );
				}
				?>
				<div class="ttw-pd-main-img-wrap">
					<img id="ttw-pd-main-image" src="<?php echo esc_url( $main_img_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />
				</div>

				<?php if ( ! empty( $attachment_ids ) ) : ?>
					<div class="ttw-pd-thumbs-list">
						<?php
						$thumb_count = 0;
						foreach ( $attachment_ids as $att_id ) :
							$t_url = wp_get_attachment_image_url( $att_id, 'large' );
							$t_full = wp_get_attachment_image_url( $att_id, 'full' );
							$is_act = ( 0 === $thumb_count ) ? ' active' : '';
							$thumb_count++;
							?>
							<div class="ttw-pd-thumb-item<?php echo $is_act; ?>" data-full="<?php echo esc_url( $t_full ); ?>">
								<img src="<?php echo esc_url( $t_url ); ?>" alt="Thumbnail" />
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Right Info -->
			<div class="ttw-pd-info">
				<?php
				// Lấy Danh mục chính (Primary Product Category) được chọn làm mặc định trong Admin
				$cat_badge_text = '';
				$primary_cat_id = get_post_meta( get_the_ID(), '_yoast_wpseo_primary_product_cat', true );
				if ( $primary_cat_id ) {
					$primary_term = get_term( $primary_cat_id, 'product_cat' );
					if ( $primary_term && ! is_wp_error( $primary_term ) ) {
						$cat_badge_text = mb_strtoupper( $primary_term->name, 'UTF-8' );
					}
				}

				if ( empty( $cat_badge_text ) ) {
					$product_terms = get_the_terms( get_the_ID(), 'product_cat' );
					if ( ! empty( $product_terms ) && ! is_wp_error( $product_terms ) ) {
						// Ưu tiên danh mục cấp con / cụ thể (bỏ qua slug 'san-pham', 'san-pham-2')
						foreach ( $product_terms as $term_item ) {
							if ( 'san-pham-2' !== $term_item->slug && 'san-pham' !== $term_item->slug ) {
								$cat_badge_text = mb_strtoupper( $term_item->name, 'UTF-8' );
								break;
							}
						}
						if ( empty( $cat_badge_text ) ) {
							$cat_badge_text = mb_strtoupper( $product_terms[0]->name, 'UTF-8' );
						}
					}
				}
				?>
				<?php if ( ! empty( $cat_badge_text ) ) : ?>
					<span class="ttw-pd-badge"><?php echo esc_html( $cat_badge_text ); ?></span>
				<?php endif; ?>


				<h1 class="ttw-pd-title"><?php echo esc_html( get_the_title() ); ?></h1>
				
				<?php
				global $post;
				$raw_excerpt = ! empty( $post->post_excerpt ) ? $post->post_excerpt : ( $product ? $product->get_short_description() : '' );
				// Loại bỏ thẻ bọc uxb-post-excerpt nếu Flatsome vô tình gắn vào dạng string
				$raw_excerpt = str_replace( array( '<div class="uxb-post-excerpt">', '</div>' ), '', $raw_excerpt );
				if ( ! empty( $raw_excerpt ) ) :
				?>
					<div class="ttw-pd-desc">
						<?php echo do_shortcode( wpautop( $raw_excerpt ) ); ?>
					</div>
				<?php endif; ?>



				<!-- Features Badges (Lấy toàn bộ các danh mục / tags thực tế của sản phẩm từ DB) -->
				<?php
				$all_cat_tags = array();
				$p_cats = get_the_terms( get_the_ID(), 'product_cat' );
				if ( ! empty( $p_cats ) && ! is_wp_error( $p_cats ) ) {
					foreach ( $p_cats as $c_item ) {
						if ( 'san-pham-2' !== $c_item->slug && 'san-pham' !== $c_item->slug && 'uncategorized' !== $c_item->slug ) {
							$all_cat_tags[] = $c_item->name;
						}
					}
				}
				$p_tags = get_the_terms( get_the_ID(), 'product_tag' );
				if ( ! empty( $p_tags ) && ! is_wp_error( $p_tags ) ) {
					foreach ( $p_tags as $t_item ) {
						$all_cat_tags[] = $t_item->name;
					}
				}

				// Nếu có custom field ttw_product_features_badges thì hợp nhất thêm
				$feats_raw = get_post_meta( get_the_ID(), 'ttw_product_features_badges', true );
				if ( ! empty( $feats_raw ) ) {
					$custom_feats = is_array( $feats_raw ) ? $feats_raw : array_map( 'trim', explode( ',', $feats_raw ) );
					$all_cat_tags = array_merge( $all_cat_tags, $custom_feats );
				}

				$all_cat_tags = array_unique( array_filter( $all_cat_tags ) );

				if ( ! empty( $all_cat_tags ) ) :
				?>
					<div class="ttw-pd-features">
						<?php foreach ( $all_cat_tags as $f_item ) : ?>
							<div class="ttw-pd-feat-item">
								<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
								</svg>
								<span><?php echo esc_html( mb_strtoupper( $f_item, 'UTF-8' ) ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>


				<!-- CTA Button -->
				<a href="<?php echo esc_url( ttw_consult_url() ); ?>" class="ttw-pd-cta-btn ttw-open-consult-modal">
					<span>NHẬN BÁO GIÁ</span>
				</a>
			</div>
		</section>

		<!-- 3. Detailed Section: MÔ TẢ CHI TIẾT (Full Width Block) -->
		<?php
		$main_content = get_the_content();
		$highlights   = get_post_meta( get_the_ID(), 'ttw_product_highlights', true );
		if ( ! empty( $main_content ) || ! empty( $highlights ) ) :
		?>
			<section class="ttw-pd-detail-block">
				<h2 class="ttw-pd-block-heading">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
					</svg>
					MÔ TẢ CHI TIẾT
				</h2>
				<div class="ttw-pd-content-wrapper entry-content">
					<?php
					if ( isset( $_GET['uxb_iframe'] ) ) {
						// Khi mở trong UX Builder, trả về the_content() gốc để Angular gắn post-wrapper
						the_content();
					} elseif ( ! empty( $highlights ) && is_array( $highlights ) ) {
						echo '<div class="ttw-pd-highlights-grid">';
						foreach ( $highlights as $hl ) {
							echo '<div class="ttw-pd-hl-item">';
							echo '<h3>' . esc_html( $hl['title'] ) . '</h3>';
							echo '<p>' . esc_html( $hl['desc'] ) . '</p>';
							echo '</div>';
						}
						echo '</div>';
					} else {
						echo wp_kses_post( apply_filters( 'the_content', $main_content ) );
					}
					?>

				</div>
			</section>
		<?php endif; ?>

		<!-- 4. Section Sản phẩm tương tự (3 bài từ DB cùng danh mục) -->
		<?php
		$cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );
		$rel_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => 'rand',
		);
		if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
			$rel_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $cats,
				),
			);
		}
		$rel_query = new WP_Query( $rel_args );

		// Fallback nếu không tìm thấy cùng danh mục thì lấy 3 sản phẩm mới nhất
		if ( ! $rel_query->have_posts() ) {
			$rel_args['tax_query'] = array();
			$rel_query = new WP_Query( $rel_args );
		}

		if ( $rel_query->have_posts() ) :
		?>
			<section class="ttw-pd-related-section">
				<h2 class="ttw-pd-related-heading">SẢN PHẨM TƯƠNG TỰ</h2>
				<div class="ttw-product-grid ttw-pd-related-grid">
					<?php
					while ( $rel_query->have_posts() ) :
						$rel_query->the_post();
						$r_id    = get_the_ID();
						$r_title = get_the_title( $r_id );
						$r_link  = get_permalink( $r_id );
						$r_img   = get_the_post_thumbnail_url( $r_id, 'large' );
						if ( ! $r_img ) {
							$r_img = get_stylesheet_directory_uri() . '/assets/img/design/product1.jpg';
						}
						$r_terms = get_the_terms( $r_id, 'product_cat' );
						$r_cat_name = 'SẢN PHẨM';
						if ( ! empty( $r_terms ) && ! is_wp_error( $r_terms ) ) {
							foreach ( $r_terms as $r_t ) {
								if ( 'san-pham-2' !== $r_t->slug && 'san-pham' !== $r_t->slug ) {
									$r_cat_name = $r_t->name;
									break;
								}
							}
						}
						?>
						<article class="ttw-card-bento ttw-animate ttw-fade-up">
							<a class="ttw-card-thumb" href="<?php echo esc_url( $r_link ); ?>" title="<?php echo esc_attr( $r_title ); ?>">
								<img src="<?php echo esc_url( $r_img ); ?>" alt="<?php echo esc_attr( $r_title ); ?>" loading="lazy" />
							</a>
							<div class="ttw-card-content">
								<span class="ttw-card-tag"><?php echo esc_html( mb_strtoupper( $r_cat_name, 'UTF-8' ) ); ?></span>
								<h3 class="ttw-card-title">
									<a href="<?php echo esc_url( $r_link ); ?>"><?php echo esc_html( $r_title ); ?></a>
								</h3>
								<div class="ttw-card-action">
									<a href="<?php echo esc_url( $r_link ); ?>" class="ttw-card-btn">
										<span>XEM CHI TIẾT</span>
										<svg width="12" height="12" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
											<path d="M8.3703 6.18749H0V4.81249H8.3703L4.5203 0.962498L5.49999 0L11 5.49999L5.49999 11L4.5203 10.0375L8.3703 6.18749Z" fill="currentColor"/>
										</svg>
									</a>
								</div>
							</div>
						</article>
					<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>

	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Gallery Thumbnail Switcher
	const mainImg = document.getElementById('ttw-pd-main-image');
	const thumbs = document.querySelectorAll('.ttw-pd-thumb-item');
	if (mainImg && thumbs.length > 0) {
		thumbs.forEach(function(thumb) {
			thumb.addEventListener('click', function() {
				thumbs.forEach(t => t.classList.remove('active'));
				this.classList.add('active');
				const fullUrl = this.getAttribute('data-full');
				if (fullUrl) {
					mainImg.src = fullUrl;
				}
			});
		});
	}
});
</script>

<?php
get_footer();
