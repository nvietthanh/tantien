<?php
/**
 * Single Product Template - Tân Tiến Window (Figma Node 1:2621)
 *
 * @package          TantienWindow
 * @version          1.6.4
 * @flatsome-version 3.16.0
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
				if ( $main_img_id && ! in_array( $main_img_id, $attachment_ids ) ) {
					array_unshift( $attachment_ids, $main_img_id );
				} elseif ( empty( $attachment_ids ) && $main_img_id ) {
					$attachment_ids = array( $main_img_id );
				}
				?>
				<div class="ttw-pd-main-img-wrap" id="ttw-pd-main-wrap" title="Nhấp để xem ảnh đầy đủ">
					<img id="ttw-pd-main-image" src="<?php echo esc_url( $main_img_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" />

					<?php if ( count( $attachment_ids ) > 1 ) : ?>
						<button type="button" class="ttw-pd-slider-btn ttw-pd-slider-prev" id="ttw-pd-slider-prev" aria-label="Ảnh trước" title="Ảnh trước">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M15 18l-6-6 6-6"/>
							</svg>
						</button>
						<button type="button" class="ttw-pd-slider-btn ttw-pd-slider-next" id="ttw-pd-slider-next" aria-label="Ảnh tiếp theo" title="Ảnh tiếp theo">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
								<path d="M9 18l6-6-6-6"/>
							</svg>
						</button>
					<?php endif; ?>

					<div class="ttw-pd-zoom-hint" aria-hidden="true">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="11" cy="11" r="8"></circle>
							<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							<line x1="11" y1="8" x2="11" y2="14"></line>
							<line x1="8" y1="11" x2="14" y2="11"></line>
						</svg>
					</div>
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
						// Ưu tiên danh mục cấp con / cụ thể (bỏ qua slug 'san-pham', 'san-pham-2', 'bao-gia', 'tin-tuc')
						foreach ( $product_terms as $term_item ) {
							if ( ! in_array( $term_item->slug, array( 'san-pham', 'san-pham-2', 'bao-gia', 'tin-tuc', 'chua-phan-loai', 'uncategorized' ), true ) ) {
								$cat_badge_text = mb_strtoupper( html_entity_decode( $term_item->name, ENT_QUOTES, 'UTF-8' ), 'UTF-8' );
								break;
							}
						}
						if ( empty( $cat_badge_text ) ) {
							$cat_badge_text = mb_strtoupper( html_entity_decode( $product_terms[0]->name, ENT_QUOTES, 'UTF-8' ), 'UTF-8' );
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
						if ( ! in_array( $c_item->slug, array( 'san-pham', 'san-pham-2', 'bao-gia', 'tin-tuc', 'chua-phan-loai', 'uncategorized' ), true ) ) {
							$all_cat_tags[] = html_entity_decode( $c_item->name, ENT_QUOTES, 'UTF-8' );
						}
					}
				}
				$p_tags = get_the_terms( get_the_ID(), 'product_tag' );
				if ( ! empty( $p_tags ) && ! is_wp_error( $p_tags ) ) {
					foreach ( $p_tags as $t_item ) {
						$all_cat_tags[] = html_entity_decode( $t_item->name, ENT_QUOTES, 'UTF-8' );
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

		<!-- 4. Section Sản phẩm tương tự (4 bài từ DB cùng danh mục) -->
		<?php
		$cats = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );
		$rel_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
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

		// Fallback nếu không tìm thấy cùng danh mục thì lấy 4 sản phẩm mới nhất
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
								if ( ! in_array( $r_t->slug, array( 'san-pham', 'san-pham-2', 'bao-gia', 'tin-tuc', 'chua-phan-loai', 'uncategorized' ), true ) ) {
									$r_cat_name = html_entity_decode( $r_t->name, ENT_QUOTES, 'UTF-8' );
									break;
								}
							}
							if ( 'SẢN PHẨM' === $r_cat_name && ! empty( $r_terms[0] ) ) {
								$r_cat_name = html_entity_decode( $r_terms[0]->name, ENT_QUOTES, 'UTF-8' );
							}
						}
						?>
						<article class="ttw-card-bento ttw-animate ttw-fade-up">
							<a class="ttw-card-thumb" href="<?php echo esc_url( $r_link ); ?>" title="<?php echo esc_attr( $r_title ); ?>">
								<img src="<?php echo esc_url( $r_img ); ?>" alt="<?php echo esc_attr( $r_title ); ?>" loading="lazy" />
								<div class="ttw-card-overlay">
									<div class="ttw-card-meta">
										<span class="ttw-card-tag"><?php echo esc_html( mb_strtoupper( $r_cat_name, 'UTF-8' ) ); ?></span>
									</div>
									<h3 class="ttw-card-title"><?php echo esc_html( $r_title ); ?></h3>
								</div>
							</a>
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

<!-- Modal Lightbox Preview Full Image Premium -->
<div id="ttw-image-lightbox" class="ttw-lightbox-modal" aria-hidden="true" role="dialog" aria-label="Xem ảnh sản phẩm kích thước đầy đủ">
	<div class="ttw-lightbox-backdrop"></div>
	
	<!-- Top Bar Header: Title & Action Tools -->
	<div class="ttw-lightbox-header">
		<div class="ttw-lightbox-title-wrap">
			<span class="ttw-lightbox-tag">ẢNH SẢN PHẨM</span>
			<h4 class="ttw-lightbox-title"><?php echo esc_html( get_the_title() ); ?></h4>
		</div>

		<!-- Control Toolbar -->
		<div class="ttw-lightbox-toolbar">
			<button type="button" class="ttw-lightbox-btn" id="ttw-lb-zoom-in" title="Phóng to (+)">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					<line x1="11" y1="8" x2="11" y2="14"></line>
					<line x1="8" y1="11" x2="14" y2="11"></line>
				</svg>
			</button>
			<button type="button" class="ttw-lightbox-btn" id="ttw-lb-zoom-out" title="Thu nhỏ (-)">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					<line x1="8" y1="11" x2="14" y2="11"></line>
				</svg>
			</button>
			<button type="button" class="ttw-lightbox-btn" id="ttw-lb-rotate" title="Xoay ảnh 90°">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="23 4 23 10 17 10"></polyline>
					<path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
				</svg>
			</button>
			<button type="button" class="ttw-lightbox-btn" id="ttw-lb-reset" title="Khôi phục kích thước ban đầu">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
					<path d="M3 3v5h5"></path>
				</svg>
			</button>
			<div class="ttw-lightbox-divider"></div>
			<button type="button" class="ttw-lightbox-btn ttw-lightbox-close-btn" id="ttw-lightbox-close-btn" title="Đóng (ESC)">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"></line>
					<line x1="6" y1="6" x2="18" y2="18"></line>
				</svg>
			</button>
		</div>
	</div>

	<!-- Navigation Arrow Buttons (Nếu có nhiều ảnh) -->
	<?php if ( ! empty( $attachment_ids ) && count( $attachment_ids ) > 1 ) : ?>
		<button type="button" class="ttw-lightbox-nav prev" id="ttw-lb-prev" aria-label="Ảnh trước">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="15 18 9 12 15 6"></polyline>
			</svg>
		</button>
		<button type="button" class="ttw-lightbox-nav next" id="ttw-lb-next" aria-label="Ảnh kế tiếp">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="9 18 15 12 9 6"></polyline>
			</svg>
		</button>
	<?php endif; ?>

	<div class="ttw-lightbox-viewport" id="ttw-lightbox-viewport">
		<div class="ttw-lightbox-img-stage" id="ttw-lightbox-stage">
			<img id="ttw-lightbox-img" src="" alt="<?php echo esc_attr( get_the_title() ); ?>" draggable="false" />
		</div>
	</div>

	<!-- Bottom Indicator / Counter -->
	<div class="ttw-lightbox-footer">
		<span id="ttw-lb-counter">Cuộn chuột hoặc nhấp phím +/- để phóng to/thu nhỏ</span>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// 1. Gallery Thumbnail Switcher & Autoplay Slider (1s)
	const mainImg = document.getElementById('ttw-pd-main-image');
	const mainWrap = document.getElementById('ttw-pd-main-wrap');
	const btnSliderPrev = document.getElementById('ttw-pd-slider-prev');
	const btnSliderNext = document.getElementById('ttw-pd-slider-next');
	const thumbs = Array.from(document.querySelectorAll('.ttw-pd-thumb-item'));
	let currentIndex = 0;
	let autoplayTimer = null;
	const AUTOPLAY_INTERVAL = 3000; // Tự động nhảy ảnh sau 1 giây

	function setActiveThumb(index) {
		if (thumbs.length === 0) return;
		if (index < 0) {
			index = thumbs.length - 1;
		} else if (index >= thumbs.length) {
			index = 0;
		}
		currentIndex = index;
		thumbs.forEach(t => t.classList.remove('active'));
		if (thumbs[index]) {
			thumbs[index].classList.add('active');
			const fullUrl = thumbs[index].getAttribute('data-full');
			if (fullUrl && mainImg) {
				mainImg.classList.remove('ttw-img-fade');
				void mainImg.offsetWidth;
				mainImg.src = fullUrl;
				mainImg.classList.add('ttw-img-fade');
			}
		}
	}

	function nextSlide() {
		if (thumbs.length > 1) {
			setActiveThumb(currentIndex + 1);
		}
	}

	function prevSlide() {
		if (thumbs.length > 1) {
			setActiveThumb(currentIndex - 1);
		}
	}

	function startAutoplay() {
		if (autoplayTimer || thumbs.length <= 1) return;
		autoplayTimer = setInterval(nextSlide, AUTOPLAY_INTERVAL);
	}

	function pauseAutoplay() {
		if (autoplayTimer) {
			clearInterval(autoplayTimer);
			autoplayTimer = null;
		}
	}

	function restartAutoplay() {
		pauseAutoplay();
		startAutoplay();
	}

	if (btnSliderPrev) {
		btnSliderPrev.addEventListener('click', function(e) {
			e.stopPropagation();
			prevSlide();
			restartAutoplay();
		});
	}

	if (btnSliderNext) {
		btnSliderNext.addEventListener('click', function(e) {
			e.stopPropagation();
			nextSlide();
			restartAutoplay();
		});
	}

	if (thumbs.length > 0) {
		thumbs.forEach(function(thumb, idx) {
			thumb.addEventListener('click', function() {
				setActiveThumb(idx);
				restartAutoplay();
			});
		});
	}

	if (mainWrap) {
		mainWrap.addEventListener('mouseenter', pauseAutoplay);
		mainWrap.addEventListener('mouseleave', startAutoplay);
	}

	startAutoplay();

	// 2. Advanced Lightbox Modal
	const lightbox = document.getElementById('ttw-image-lightbox');
	const lightboxImg = document.getElementById('ttw-lightbox-img');
	const stage = document.getElementById('ttw-lightbox-stage');
	const viewport = document.getElementById('ttw-lightbox-viewport');
	const closeBtn = document.getElementById('ttw-lightbox-close-btn');
	const backdrop = lightbox ? lightbox.querySelector('.ttw-lightbox-backdrop') : null;
	const btnZoomIn = document.getElementById('ttw-lb-zoom-in');
	const btnZoomOut = document.getElementById('ttw-lb-zoom-out');
	const btnRotate = document.getElementById('ttw-lb-rotate');
	const btnReset = document.getElementById('ttw-lb-reset');
	const btnPrev = document.getElementById('ttw-lb-prev');
	const btnNext = document.getElementById('ttw-lb-next');
	const counter = document.getElementById('ttw-lb-counter');

	let scale = 1;
	let rotation = 0;
	let posX = 0;
	let posY = 0;
	let isDragging = false;
	let startX = 0;
	let startY = 0;

	function updateTransform() {
		if (stage) {
			stage.style.transform = `translate(${posX}px, ${posY}px) scale(${scale}) rotate(${rotation}deg)`;
			if (scale > 1) {
				stage.classList.add('is-zoomed');
			} else {
				stage.classList.remove('is-zoomed');
				posX = 0;
				posY = 0;
			}
		}
		if (counter && thumbs.length > 0) {
			counter.textContent = `Ảnh ${currentIndex + 1} / ${thumbs.length} — Thu phóng: ${Math.round(scale * 100)}%`;
		}
	}

	function resetTransform() {
		scale = 1;
		rotation = 0;
		posX = 0;
		posY = 0;
		updateTransform();
	}

	function zoom(delta) {
		scale = Math.min(Math.max(0.5, scale + delta), 4);
		updateTransform();
	}

	function rotate() {
		rotation = (rotation + 90) % 360;
		updateTransform();
	}

	function updateLightboxImage() {
		if (lightboxImg && mainImg) {
			lightboxImg.src = mainImg.src;
			resetTransform();
		}
	}

	function openLightbox() {
		pauseAutoplay();
		if (lightbox && mainImg) {
			updateLightboxImage();
			lightbox.classList.add('active');
			lightbox.setAttribute('aria-hidden', 'false');
			document.body.style.overflow = 'hidden';
		}
	}

	function closeLightbox() {
		if (lightbox) {
			lightbox.classList.remove('active');
			lightbox.setAttribute('aria-hidden', 'true');
			document.body.style.overflow = '';
			resetTransform();
			startAutoplay();
		}
	}

	function showPrevImage() {
		if (thumbs.length > 1) {
			const nextIdx = (currentIndex - 1 + thumbs.length) % thumbs.length;
			setActiveThumb(nextIdx);
			updateLightboxImage();
		}
	}

	function showNextImage() {
		if (thumbs.length > 1) {
			const nextIdx = (currentIndex + 1) % thumbs.length;
			setActiveThumb(nextIdx);
			updateLightboxImage();
		}
	}

	// Event listeners
	if (mainWrap) mainWrap.addEventListener('click', openLightbox);
	if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
	if (backdrop) backdrop.addEventListener('click', closeLightbox);
	if (btnZoomIn) btnZoomIn.addEventListener('click', () => zoom(0.3));
	if (btnZoomOut) btnZoomOut.addEventListener('click', () => zoom(-0.3));
	if (btnRotate) btnRotate.addEventListener('click', rotate);
	if (btnReset) btnReset.addEventListener('click', resetTransform);
	if (btnPrev) btnPrev.addEventListener('click', showPrevImage);
	if (btnNext) btnNext.addEventListener('click', showNextImage);

	// Mouse wheel zoom inside lightbox
	if (viewport) {
		viewport.addEventListener('wheel', function(e) {
			e.preventDefault();
			const delta = e.deltaY < 0 ? 0.2 : -0.2;
			zoom(delta);
		}, { passive: false });

		// Dragging to Pan image when zoomed (Mouse + Touch)
		viewport.addEventListener('mousedown', function(e) {
			if (scale > 1) {
				isDragging = true;
				startX = e.clientX - posX;
				startY = e.clientY - posY;
				stage.classList.add('is-dragging');
			}
		});

		window.addEventListener('mousemove', function(e) {
			if (isDragging) {
				posX = e.clientX - startX;
				posY = e.clientY - startY;
				updateTransform();
			}
		});

		window.addEventListener('mouseup', function() {
			if (isDragging) {
				isDragging = false;
				if (stage) stage.classList.remove('is-dragging');
			}
		});

		// Touch swipe / pan on Mobile
		let touchStartX = 0;
		let touchStartY = 0;
		viewport.addEventListener('touchstart', function(e) {
			if (e.touches.length === 1) {
				touchStartX = e.touches[0].clientX;
				touchStartY = e.touches[0].clientY;
				if (scale > 1) {
					isDragging = true;
					startX = e.touches[0].clientX - posX;
					startY = e.touches[0].clientY - posY;
				}
			}
		}, { passive: true });

		viewport.addEventListener('touchmove', function(e) {
			if (isDragging && scale > 1 && e.touches.length === 1) {
				posX = e.touches[0].clientX - startX;
				posY = e.touches[0].clientY - startY;
				updateTransform();
			}
		}, { passive: true });

		viewport.addEventListener('touchend', function(e) {
			if (isDragging) {
				isDragging = false;
			} else if (scale === 1 && e.changedTouches.length === 1) {
				const touchEndX = e.changedTouches[0].clientX;
				const diffX = touchEndX - touchStartX;
				if (diffX > 50) {
					showPrevImage(); // Vuốt sang phải -> Ảnh trước
				} else if (diffX < -50) {
					showNextImage(); // Vuốt sang trái -> Ảnh sau
				}
			}
		});
	}

	// Keyboard navigation & Shortcuts
	document.addEventListener('keydown', function(e) {
		if (!lightbox || !lightbox.classList.contains('active')) return;
		if (e.key === 'Escape') closeLightbox();
		if (e.key === 'ArrowLeft') showPrevImage();
		if (e.key === 'ArrowRight') showNextImage();
		if (e.key === '+' || e.key === '=') zoom(0.3);
		if (e.key === '-' || e.key === '_') zoom(-0.3);
		if (e.key === 'r' || e.key === 'R') rotate();
	});
});
</script>

<?php
get_footer();

