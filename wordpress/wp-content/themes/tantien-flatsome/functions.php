<?php
/**
 * Tân Tiến Window - Flatsome Child Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TTW_FLATSOME_VERSION', '1.0.2' );

/**
 * Đăng ký query var cho phân trang sản phẩm
 */
add_filter( 'query_vars', function( $vars ) {
	$vars[] = 'page';
	$vars[] = 'paged';
	return $vars;
} );

// Đăng ký query_vars phân trang













/**
 * Company helpers & Theme Functions
 */
function ttw_phone() {
	return apply_filters( 'ttw_phone', '0907.247.111' );
}

function ttw_phone_link() {
	return 'tel:' . str_replace( array( '.', ' ', '-' ), '', ttw_phone() );
}

function ttw_email() {
	return apply_filters( 'ttw_email', 'tantienwindow365@gmail.com' );
}

function ttw_zalo_link() {
	return apply_filters( 'ttw_zalo_link', 'https://zalo.me/0907247111' );
}

function ttw_consult_url() {
	$page = get_page_by_path( 'bao-gia' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/bao-gia/' );
}

function ttw_shop_url() {
	$page = get_page_by_path( 'san-pham-2' );
	if ( $page ) {
		return get_permalink( $page );
	}
	$page = get_page_by_path( 'san-pham' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/san-pham-2/' );
}

function ttw_projects_url() {
	$page = get_page_by_path( 'nhung-cong-trinh-tieu-bieu' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/nhung-cong-trinh-tieu-bieu/' );
}

function ttw_news_url() {
	$page = get_page_by_path( 'tin-tuc' );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/tin-tuc/' );
}

function ttw_primary_menu_fallback() {
	$items = array(
		array( home_url( '/' ), __( 'Trang chủ', 'tantien-window' ) ),
		array( home_url( '/gioi-thieu/' ), __( 'Về chúng tôi', 'tantien-window' ) ),
		array( ttw_shop_url(), __( 'Sản phẩm', 'tantien-window' ) ),
		array( home_url( '/bao-gia/' ), __( 'Báo giá', 'tantien-window' ) ),
		array( ttw_projects_url(), __( 'Công trình', 'tantien-window' ) ),
		array( ttw_news_url(), __( 'Tin tức', 'tantien-window' ) ),
	);
	echo '<ul>';
	foreach ( $items as $ttw_item ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $ttw_item[0] ), esc_html( $ttw_item[1] ) );
	}
	echo '</ul>';
}

function ttw_logo() {
	$logo = get_custom_logo();
	if ( $logo ) {
		return $logo;
	}

	$site_url  = esc_url( home_url( '/' ) );
	$site_name = esc_html( apply_filters( 'ttw_site_name', 'Tân Tiến Window' ) );
	$logo_url  = get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg';

	return '<a class="ttw-logo" href="' . $site_url . '"><img src="' . esc_url( $logo_url ) . '" alt="' . $site_name . '" /></a>';
}

function ttw_product_short_description( $product ) {
	if ( ! $product ) {
		return '';
	}
	$excerpt = $product->get_short_description();
	if ( ! $excerpt ) {
		$excerpt = wp_trim_words( wp_strip_all_tags( $product->get_description() ), 20 );
	}
	return $excerpt;
}

function ttw_breadcrumb() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<div class="ttw-breadcrumb">', '</div>' );
		return;
	}
	echo '<div class="ttw-breadcrumb"><a href="' . esc_url( home_url( '/' ) ) . '">Trang chủ</a></div>';
}

function ttw_page_hero_title() {
	$title = get_the_title();
	if ( is_home() ) {
		$title = __( 'Tin tức', 'tantien-window' );
	} elseif ( is_search() ) {
		$title = __( 'Kết quả tìm kiếm', 'tantien-window' );
	} elseif ( is_404() ) {
		$title = __( 'Không tìm thấy trang', 'tantien-window' );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
		$title = trim( preg_replace( '/^[^:]+:\s*/', '', $title ) );
	}
	return $title;
}

register_nav_menus( array(
	'primary'        => __( 'Menu chính', 'tantien-window' ),
	'primary_mobile' => __( 'Menu mobile', 'tantien-window' ),
	'footer'         => __( 'Menu footer', 'tantien-window' ),
) );


// Tự động chèn Submenu cho "Sản phẩm" (6 sp), "Công trình" (3 dự án), và "Tin tức" (Tin tức & Tin tuyển dụng)
add_filter( 'wp_nav_menu_objects', function( $items, $args ) {
	if ( empty( $items ) ) {
		return $items;
	}

	$product_menu_parent_id = 0;
	$project_menu_parent_id = 0;
	$news_menu_parent_id    = 0;

	foreach ( $items as $item ) {
		$title_upper = mb_strtoupper( trim( $item->title ), 'UTF-8' );
		if ( $title_upper === 'SẢN PHẨM' || $item->object_id == 2466 ) {
			$product_menu_parent_id = $item->ID;
		} elseif ( $title_upper === 'CÔNG TRÌNH' ) {
			$project_menu_parent_id = $item->ID;
		} elseif ( strpos( $title_upper, 'TIN TỨC' ) !== false || $item->object_id == 320 ) {
			$news_menu_parent_id = $item->ID;
		}
	}

	if ( ! $product_menu_parent_id && ! $project_menu_parent_id && ! $news_menu_parent_id ) {
		return $items;
	}

	// Truy vấn 6 sản phẩm tạo mới nhất
	$latest_products = array();
	if ( $product_menu_parent_id ) {
		$latest_products = get_posts( array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
	}

	$new_items = array();
	$order     = 1000;

	foreach ( $items as $item ) {
		$new_items[] = $item;

		// 1. Submenu cho SẢN PHẨM (6 sản phẩm)
		if ( $item->ID == $product_menu_parent_id && ! empty( $latest_products ) ) {
			if ( ! in_array( 'menu-item-has-children', $item->classes ) ) {
				$item->classes[] = 'menu-item-has-children';
			}

			foreach ( $latest_products as $prod ) {
				$order++;
				$sub_item                  = new stdClass();
				$sub_item->ID              = 9999000 + $prod->ID;
				$sub_item->db_id           = $sub_item->ID;
				$sub_item->title           = get_the_title( $prod->ID );
				$sub_item->url             = get_permalink( $prod->ID );
				$sub_item->menu_item_parent = (string) $product_menu_parent_id;
				$sub_item->post_parent     = 0;
				$sub_item->type            = 'post_type';
				$sub_item->object          = 'product';
				$sub_item->object_id       = (string) $prod->ID;
				$sub_item->type_label      = __( 'Sản phẩm', 'tantien-window' );
				$sub_item->classes         = array( 'menu-item', 'menu-item-type-post_type', 'menu-item-object-product' );
				$sub_item->target          = '';
				$sub_item->attr_title      = '';
				$sub_item->description     = '';
				$sub_item->xfn             = '';
				$sub_item->status          = 'publish';
				$sub_item->menu_order      = $order;

				$new_items[] = $sub_item;
			}
		}

		// 2. Submenu cho CÔNG TRÌNH (lấy đúng 3 phần tử công trình tiêu biểu giống mảng dự án tantien-window)
		if ( $item->ID == $project_menu_parent_id ) {
			if ( ! in_array( 'menu-item-has-children', $item->classes ) ) {
				$item->classes[] = 'menu-item-has-children';
			}

			// Mảng 3 công trình tiêu biểu mẫu từ theme tantien-window
			$demo_projects = array(
				array( 'title' => 'Ocean Villa Retreat', 'cat' => 'Biệt thự cao cấp', 'desc' => 'Đà Nẵng • Hệ cửa lùa panorama' ),
				array( 'title' => 'Tech Hub Tower', 'cat' => 'Văn phòng', 'desc' => 'TP.HCM • Vách kính mặt dựng' ),
				array( 'title' => 'Skyrise Penthouse', 'cat' => 'Căn hộ', 'desc' => 'Hà Nội • Cửa sổ cách âm' ),
			);

			// Nếu có bài viết công trình trong DB thì lấy 3 bài thực tế, nếu không dùng 3 dự án mẫu
			$real_projects = get_posts( array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
				'orderby'        => 'date',
				'order'          => 'DESC',
			) );

			if ( ! empty( $real_projects ) ) {
				foreach ( $real_projects as $proj ) {
					$order++;
					$sub_item                  = new stdClass();
					$sub_item->ID              = 8888000 + $proj->ID;
					$sub_item->db_id           = $sub_item->ID;
					$sub_item->title           = get_the_title( $proj->ID );
					$sub_item->url             = get_permalink( $proj->ID );
					$sub_item->menu_item_parent = (string) $project_menu_parent_id;
					$sub_item->post_parent     = 0;
					$sub_item->type            = 'post_type';
					$sub_item->object          = $proj->post_type;
					$sub_item->object_id       = (string) $proj->ID;
					$sub_item->type_label      = __( 'Công trình', 'tantien-window' );
					$sub_item->classes         = array( 'menu-item', 'menu-item-type-post_type' );
					$sub_item->target          = '';
					$sub_item->attr_title      = '';
					$sub_item->description     = '';
					$sub_item->xfn             = '';
					$sub_item->status          = 'publish';
					$sub_item->menu_order      = $order;

					$new_items[] = $sub_item;
				}
			} else {
				foreach ( $demo_projects as $idx => $proj ) {
					$order++;
					$sub_item                  = new stdClass();
					$sub_item->ID              = 8888000 + $idx;
					$sub_item->db_id           = $sub_item->ID;
					$sub_item->title           = $proj['title'];
					$sub_item->url             = home_url( '/nhung-cong-trinh-tieu-bieu/' );
					$sub_item->menu_item_parent = (string) $project_menu_parent_id;
					$sub_item->post_parent     = 0;
					$sub_item->type            = 'custom';
					$sub_item->object          = 'custom';
					$sub_item->object_id       = '0';
					$sub_item->type_label      = __( 'Công trình', 'tantien-window' );
					$sub_item->classes         = array( 'menu-item', 'menu-item-type-custom' );
					$sub_item->target          = '';
					$sub_item->attr_title      = '';
					$sub_item->description     = '';
					$sub_item->xfn             = '';
					$sub_item->status          = 'publish';
					$sub_item->menu_order      = $order;

					$new_items[] = $sub_item;
				}
			}
		}

		// 3. Submenu cho TIN TỨC (2 trang: 1. Tin tức, 2. Tin tuyển dụng)
		if ( $item->ID == $news_menu_parent_id ) {
			if ( ! in_array( 'menu-item-has-children', $item->classes ) ) {
				$item->classes[] = 'menu-item-has-children';
			}

			$news_sub_items = array(
				array( 'title' => 'Tin tức & Bài viết', 'url' => home_url( '/tin-tuc/' ) ),
				array( 'title' => 'Tin tuyển dụng', 'url' => home_url( '/tuyen-dung/' ) ),
			);

			foreach ( $news_sub_items as $idx => $nsub ) {
				$order++;
				$sub_item                  = new stdClass();
				$sub_item->ID              = 7777000 + $idx;
				$sub_item->db_id           = $sub_item->ID;
				$sub_item->title           = $nsub['title'];
				$sub_item->url             = $nsub['url'];
				$sub_item->menu_item_parent = (string) $news_menu_parent_id;
				$sub_item->post_parent     = 0;
				$sub_item->type            = 'custom';
				$sub_item->object          = 'custom';
				$sub_item->object_id       = '0';
				$sub_item->type_label      = __( 'Trang', 'tantien-window' );
				$sub_item->classes         = array( 'menu-item', 'menu-item-type-custom' );
				$sub_item->target          = '';
				$sub_item->attr_title      = '';
				$sub_item->description     = '';
				$sub_item->xfn             = '';
				$sub_item->status          = 'publish';
				$sub_item->menu_order      = $order;

				$new_items[] = $sub_item;
			}
		}

	}

	return $new_items;
}, 10, 2 );


// 1. Tắt Gutenberg editor để ưu tiên Classic Editor / UX Builder giống theme dich-vu-bao-ve

add_filter( 'use_block_editor_for_post', '__return_false' );

// 2. Enqueue Parent (Flatsome) & Child Stylesheets + Custom JS
function ttw_flatsome_enqueue_scripts() {
	// Google Fonts của Tân Tiến Window
	wp_enqueue_style(
		'ttw-google-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Inter:wght@400;600&display=swap',
		array(),
		null
	);

	// Child theme style (tantien-flatsome)
	wp_enqueue_style( 'ttw-flatsome-style', get_stylesheet_uri(), array( 'ttw-google-fonts' ), TTW_FLATSOME_VERSION );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'ttw-woocommerce', get_stylesheet_directory_uri() . '/assets/css/woocommerce.css', array( 'ttw-flatsome-style' ), TTW_FLATSOME_VERSION );
	}

	// Tân Tiến Window JS Scripts
	wp_enqueue_script(
		'ttw-main',
		get_stylesheet_directory_uri() . '/assets/js/main.js',
		array(),
		TTW_FLATSOME_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'ttw_flatsome_enqueue_scripts', 9999 );


// Hàm nạp danh sách các Bài Viết từ Database cho ô Select của UX Builder
function ttw_get_posts_options_array() {
	$options = array( '' => __( '-- Chọn bài viết --' ) );
	$posts = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 100,
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );
	if ( ! empty( $posts ) ) {
		foreach ( $posts as $p ) {
			$options[ $p->ID ] = $p->post_title . ' (ID: ' . $p->ID . ')';
		}
	}
	return $options;
}


// 3. Đăng ký các Shortcode & UX Builder Elements cho Tân Tiến Window với đầy đủ Options chỉnh sửa
add_action( 'ux_builder_setup', function() {

	add_ux_builder_shortcode( 'ttw_hero', array(
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Hero Banner (Khối Mẹ)' ),
		'category'  => __( 'Tân Tiến Window' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/design/hero-bg.jpg',
		'allow'     => array( 'ttw_hero_title', 'ttw_hero_desc', 'ttw_hero_button' ),
		'options'   => array(
			'bg_image' => array(
				'type'    => 'image',
				'heading' => __( 'Hình nền Banner' ),
			),
			'height' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Chiều cao Banner (Height)' ),
				'description' => __( 'Ví dụ: 600px, 80vh...' ),
				'default'     => '',
			),
			'text_align' => array(
				'type'    => 'select',
				'heading' => __( 'Căn lề toàn bộ nội dung' ),
				'default' => 'left',
				'options' => array(
					'left'   => __( 'Căn trái (Left)' ),
					'center' => __( 'Căn giữa (Center)' ),
					'right'  => __( 'Căn phải (Right)' ),
				),
			),
			'overlay' => array(
				'type'        => 'colorpicker',
				'heading'     => __( 'Lớp phủ màu tối nền (Overlay)' ),
				'default'     => '',
			),
			'padding' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Khoảng cách lề trong (Padding)' ),
				'default'     => '',
			),
			'custom_css' => array(
				'type'        => 'textarea',
				'heading'     => __( 'Mã CSS riêng cho Khối Banner Mẹ' ),
				'default'     => '',
			),
		),
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định' ),
				'content' => '[ttw_hero]
[ttw_hero_title text="Giải pháp nhôm kính cho kiến trúc hiện đại"]
[ttw_hero_desc text="Tân Tiến Window cung cấp các giải pháp cửa nhôm kính, kính cường lực và vách kính mặt dựng với định hướng hiện đại, bền vững và thẩm mỹ cao."]
[ttw_hero_button text="Khám phá sản phẩm" link="/san-pham-2/" type="primary"]
[ttw_hero_button text="Nhận tư vấn" link="/bao-gia/" type="light"]
[/ttw_hero]',
			),
		),
	) );

	// Element con: Tiêu đề Hero
	add_ux_builder_shortcode( 'ttw_hero_title', array(
		'name'      => __( 'Hero - Tiêu Đề' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_hero' ),
		'wrap'      => false,
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung Tiêu đề' ),
				'default' => 'Giải pháp nhôm kính cho kiến trúc hiện đại',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '#ffffff',
			),
			'font_size' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Cỡ chữ (Font Size)' ),
				'description' => __( 'Ví dụ: 48px, 3.5rem...' ),
				'default'     => '',
			),
			'css' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Mã CSS tùy chỉnh riêng' ),
				'description' => __( 'Ví dụ: line-height: 1.2; letter-spacing: 1px;' ),
				'default'     => '',
			),
		),
	) );

	// Element con: Mô tả Hero
	add_ux_builder_shortcode( 'ttw_hero_desc', array(
		'name'      => __( 'Hero - Mô Tả' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_hero' ),
		'wrap'      => false,
		'options'   => array(
			'text' => array(
				'type'    => 'textarea',
				'heading' => __( 'Nội dung Mô tả' ),
				'default' => 'Tân Tiến Window cung cấp các giải pháp cửa nhôm kính...',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => 'rgba(255, 255, 255, 0.9)',
			),
			'font_size' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Cỡ chữ (Font Size)' ),
				'description' => __( 'Ví dụ: 18px...' ),
				'default'     => '',
			),
			'css' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Mã CSS tùy chỉnh riêng' ),
				'description' => __( 'Ví dụ: max-width: 600px; margin-bottom: 30px;' ),
				'default'     => '',
			),
		),
	) );

	// Element con: Nút Bấm Hero
	add_ux_builder_shortcode( 'ttw_hero_button', array(
		'name'      => __( 'Hero - Nút (Khám phá sản phẩm / Tư vấn)' ),
		'category'  => __( 'Tân Tiến Window' ),

		'require'   => array( 'ttw_hero' ),
		'wrap'      => false,
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tên Nút' ),
				'default' => 'Khám phá sản phẩm',
			),
			'link' => array(
				'type'    => 'textfield',
				'heading' => __( 'Đường dẫn (Link)' ),
				'default' => '#',
			),
			'type' => array(
				'type'    => 'select',
				'heading' => __( 'Kiểu dáng Nút' ),
				'default' => 'primary',
				'options' => array(
					'primary' => __( 'Nút chính (Primary / Màu đậm)' ),
					'light'   => __( 'Nút phụ (Light / Màu trắng)' ),
				),
			),
			'bg_color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu nền tùy chọn' ),
				'default' => '',
			),
			'text_color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ tùy chọn' ),
				'default' => '',
			),
			'css' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Mã CSS tùy chỉnh riêng' ),
				'description' => __( 'Ví dụ: border-radius: 30px; padding: 14px 30px;' ),
				'default'     => '',
			),
		),
	) );



	add_ux_builder_shortcode( 'ttw_stats', array(
		'name'     => __( 'Tân Tiến - Thống Kê' ),
		'category' => __( 'Tân Tiến Window' ),
		'options'  => array(
			'stat1_num' => array( 'type' => 'textfield', 'heading' => 'Số 1', 'default' => '10+' ),
			'stat1_lbl' => array( 'type' => 'textfield', 'heading' => 'Nhãn 1', 'default' => 'NĂM KINH NGHIỆM' ),
			'stat2_num' => array( 'type' => 'textfield', 'heading' => 'Số 2', 'default' => '1.500m²' ),
			'stat2_lbl' => array( 'type' => 'textfield', 'heading' => 'Nhãn 2', 'default' => 'NHÀ XƯỞNG' ),
			'stat3_num' => array( 'type' => 'textfield', 'heading' => 'Số 3', 'default' => '100%' ),
			'stat3_lbl' => array( 'type' => 'textfield', 'heading' => 'Nhãn 3', 'default' => 'THI CÔNG TOÀN QUỐC' ),
			'stat4_num' => array( 'type' => 'textfield', 'heading' => 'Số 4', 'default' => 'TOP' ),
			'stat4_lbl' => array( 'type' => 'textfield', 'heading' => 'Nhãn 4', 'default' => 'SẢN PHẨM CHÍNH HÃNG' ),
		),
	) );

	add_ux_builder_shortcode( 'ttw_about', array(
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Giới Thiệu (Khối Mẹ)' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_about_eyebrow', 'ttw_about_title', 'ttw_about_desc', 'ttw_about_image' ),
		'options'   => array(
			'image' => array(
				'type'    => 'image',
				'heading' => __( 'Hình Ảnh Giới Thiệu (Cột Trái)' ),
			),
			'bg_color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu nền Khối Giới Thiệu' ),
				'default' => '',
			),
			'padding' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Khoảng cách lề trong (Padding Top/Bottom)' ),
				'description' => __( 'Ví dụ: 80px 0, 100px 20px...' ),
				'default'     => '',
			),
			'text_link' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tên nút liên kết (Ví dụ: CÁC GIẢI PHÁP TÂN TIẾN WINDOW)' ),
				'default' => 'CÁC GIẢI PHÁP TÂN TIẾN WINDOW',
			),
			'text_link_url' => array(
				'type'    => 'textfield',
				'heading' => __( 'Đường dẫn liên kết (URL)' ),
				'default' => '/gioi-thieu/',
			),

			'custom_css' => array(
				'type'        => 'textarea',
				'heading'     => __( 'Mã CSS riêng cho toàn bộ Khối Giới Thiệu' ),
				'default'     => '',
			),
		),

		'presets'   => array(
			array(
				'name'    => __( 'Mặc định' ),
				'content' => '[ttw_about]
[ttw_about_image]
[ttw_about_eyebrow text="GIẢI PHÁP TÂN TIẾN WINDOW"]
[ttw_about_title text="Kiến tạo không gian từ những mảng kính"]
[ttw_about_desc text="Chúng tôi tập trung vào sự hoàn mỹ trong từng chi tiết nhôm kính, mang đến giải pháp mặt đứng, cửa và vách ngăn tối ưu cho không gian kiến trúc đương đại."]
[/ttw_about]',
			),
		),
	) );


	// Element con: Dòng phụ (Eyebrow)
	add_ux_builder_shortcode( 'ttw_about_eyebrow', array(
		'name'      => __( 'Giới Thiệu - Dòng Phụ (Eyebrow)' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_about' ),
		'wrap'      => false,
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung Dòng Phụ' ),
				'default' => 'GIẢI PHÁP TÂN TIẾN WINDOW',
			),

			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Cỡ chữ (Font Size)' ),
				'description' => __( 'Ví dụ: 14px, 0.9rem...' ),
				'default'     => '',
			),
			'css' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Mã CSS tùy chỉnh riêng' ),
				'description' => __( 'Ví dụ: text-transform: uppercase; letter-spacing: 2px;' ),
				'default'     => '',
			),
		),
	) );

	// Element con: Tiêu đề chính About
	add_ux_builder_shortcode( 'ttw_about_title', array(
		'name'      => __( 'Giới Thiệu - Tiêu Đề Chính' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_about' ),
		'wrap'      => false,
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung Tiêu Đề' ),
				'default' => 'Kiến tạo không gian từ những mảng kính',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Cỡ chữ (Font Size)' ),
				'description' => __( 'Ví dụ: 36px, 2.5rem...' ),
				'default'     => '',
			),
			'css' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Mã CSS tùy chỉnh riêng' ),
				'description' => __( 'Ví dụ: font-weight: 700; line-height: 1.3;' ),
				'default'     => '',
			),
		),
	) );

	// Element con: Nội dung Mô tả About
	add_ux_builder_shortcode( 'ttw_about_desc', array(
		'name'      => __( 'Giới Thiệu - Nội Dung Mô Tả' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_about' ),
		'wrap'      => false,
		'options'   => array(
			'text' => array(
				'type'    => 'textarea',
				'heading' => __( 'Nội dung Mô Tả' ),
				'default' => 'Chúng tôi tập trung vào sự hoàn mỹ trong từng chi tiết nhôm kính...',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Cỡ chữ (Font Size)' ),
				'description' => __( 'Ví dụ: 16px...' ),
				'default'     => '',
			),
			'css' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Mã CSS tùy chỉnh riêng' ),
				'description' => __( 'Ví dụ: line-height: 1.7;' ),
				'default'     => '',
			),
		),
	) );

	// Element con: Hình Ảnh About
	add_ux_builder_shortcode( 'ttw_about_image', array(
		'name'      => __( 'Giới Thiệu - Hình Ảnh Minh Họa' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_about' ),
		'wrap'      => false,
		'options'   => array(
			'image' => array(
				'type'    => 'image',
				'heading' => __( 'Chọn Hình Ảnh' ),
			),
			'border_radius' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Bo góc hình ảnh (Border Radius)' ),
				'description' => __( 'Ví dụ: 12px, 20px, 50%...' ),
				'default'     => '',
			),
			'css' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Mã CSS tùy chỉnh riêng' ),
				'description' => __( 'Ví dụ: box-shadow: 0 10px 30px rgba(0,0,0,0.1);' ),
				'default'     => '',
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_products', array(
		'name'     => __( 'Tân Tiến - Chúng tôi cung cấp' ),
		'category' => __( 'Tân Tiến Window' ),
		'options'  => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Chúng tôi cung cấp',
			),
			'desc' => array(
				'type'        => 'textarea',
				'heading'     => __( 'Mô tả ngắn bên dưới tiêu đề (Chữ mờ)' ),
				'default'     => 'Tân Tiến Window cung cấp các giải pháp cửa nhôm kính, cửa kính, vách kính, mặt dựng và kính kiến trúc cho nhà ở, biệt thự, văn phòng và công trình thương mại.',
			),


			'count' => array(
				'type'    => 'textfield',
				'heading' => __( 'Số lượng sản phẩm' ),
				'default' => '6',
			),
			'orderby' => array(
				'type'    => 'select',
				'heading' => __( 'Sắp xếp theo tiêu chí' ),
				'default' => 'date',
				'options' => array(
					'date'          => __( 'Mới nhất / Ngày tạo' ),
					'title'         => __( 'Tên sản phẩm (A-Z)' ),
					'modified'      => __( 'Thời gian cập nhật' ),
					'rand'          => __( 'Ngẫu nhiên' ),
					'menu_order'    => __( 'Thứ tự ưu tiên (Menu order)' ),
					'comment_count' => __( 'Nhiều đánh giá nhất' ),
				),
			),
			'order' => array(
				'type'    => 'select',
				'heading' => __( 'Thứ tự sắp xếp' ),
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Giảm dần (Mới nhất / Z-A)' ),
					'ASC'  => __( 'Tăng dần (Cũ nhất / A-Z)' ),
				),
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_values', array(
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Năng lực của chúng tôi' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_value_item' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'NĂNG LỰC CỦA CHÚNG TÔI',
			),
		),
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định (4 mục)' ),
				'content' => '[ttw_values heading="NĂNG LỰC CỦA CHÚNG TÔI"]
[ttw_value_item num="01" title="TƯ VẤN & GIẢI PHÁP" desc="Phân tích nhu cầu, kiến trúc và điều kiện thực tế để đề xuất cấu hình nhôm kính phù hợp."]
[ttw_value_item num="02" title="THIẾT KẾ & KỸ THUẬT" desc="Triển khai bản vẽ, cấu tạo và giải pháp kỹ thuật đáp ứng yêu cầu thẩm mỹ và công năng."]
[ttw_value_item num="03" title="SẢN XUẤT" desc="Gia công nhôm kính tại nhà máy với quy trình kiểm soát chất lượng trong từng công đoạn."]
[ttw_value_item num="04" title="THI CÔNG" desc="Đội ngũ kỹ thuật trực tiếp lắp đặt, đảm bảo độ chính xác, an toàn và tiến độ công trình."]
[/ttw_values]',
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_value_item', array(
		'name'      => __( 'Mục Giá Trị' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_values' ),
		'wrap'      => false,
		'options'   => array(
			'num' => array(
				'type'    => 'textfield',
				'heading' => __( 'Số thứ tự' ),
				'default' => '01',
			),
			'title' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề mục' ),
				'default' => 'Tiêu đề giá trị',
			),
			'desc' => array(
				'type'    => 'textarea',
				'heading' => __( 'Nội dung chi tiết' ),
				'default' => 'Mô tả chi tiết về giá trị này...',
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_projects', array(
		'name'      => __( 'Tân Tiến - Công Trình Tiêu Biểu', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 10,
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Công trình tiêu biểu',
			),
			'desc' => array(
				'type'    => 'textfield',
				'heading' => __( 'Mô tả ngắn' ),
				'default' => 'Những công trình thể hiện chất lượng và thẩm mỹ trong từng chi tiết.',
			),
			'ids' => array(
				'type'        => 'select',
				'heading'     => __( 'Chọn bài viết cụ thể (Tối đa 3 bài)' ),
				'description' => __( 'Nhấp chọn tối đa 3 bài viết mong muốn hiển thị theo đúng thứ tự nhấp chọn.' ),
				'config'      => array(
					'multiple'    => true,
					'maxSelect'   => 3,
					'max_items'   => 3,
					'placeholder' => __( 'Chọn tối đa 3 bài viết...' ),
				),
				'options'     => ttw_get_posts_options_array(),
				'default'     => '',
			),
			'orderby' => array(
				'type'    => 'select',
				'heading' => __( 'Sắp xếp theo' ),
				'default' => 'date',
				'options' => array(
					'date'     => __( 'Mới nhất / Ngày đăng' ),
					'title'    => __( 'Tiêu đề bài viết' ),
					'rand'     => __( 'Ngẫu nhiên' ),
					'post__in' => __( 'Theo thứ tự ID nhập ở trên' ),
				),
			),
			'order' => array(
				'type'    => 'select',
				'heading' => __( 'Thứ tự sắp xếp' ),
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Giảm dần (Mới nhất / Z-A)' ),
					'ASC'  => __( 'Tăng dần (Cũ nhất / A-Z)' ),
				),
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_process', array(
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Quy Trình' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_step_item' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Từ ý tưởng đến công trình hoàn thiện',
			),
		),
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định (5 bước)' ),
				'content' => '[ttw_process heading="Từ ý tưởng đến công trình hoàn thiện"]
[ttw_step_item num="01" title="Tư vấn" desc="Tiếp nhận yêu cầu, đề xuất giải pháp phù hợp."]
[ttw_step_item num="02" title="Khảo sát" desc="Đo đạc thực tế, đánh giá hiện trạng công trình."]
[ttw_step_item num="03" title="Thiết kế & báo giá" desc="Lên bản vẽ chi tiết và dự toán chi phí."]
[ttw_step_item num="04" title="Sản xuất" desc="Gia công tại xưởng với quy trình kiểm soát chặt chẽ."]
[ttw_step_item num="05" title="Lắp đặt & bảo hành" desc="Thi công chuyên nghiệp, bàn giao và hỗ trợ dài hạn."]
[/ttw_process]',
			),
		),
	) );

	add_ux_builder_shortcode( 'ttw_step_item', array(
		'name'      => __( 'Bước Quy Trình' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_process' ),
		'wrap'      => false,
		'options'   => array(
			'num' => array(
				'type'    => 'textfield',
				'heading' => __( 'Số thứ tự' ),
				'default' => '01',
			),
			'title' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tên bước' ),
				'default' => 'Tên bước quy trình',
			),
			'desc' => array(
				'type'    => 'textarea',
				'heading' => __( 'Nội dung chi tiết' ),
				'default' => 'Mô tả chi tiết bước thực hiện...',
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_benefits', array(
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Lý Do Lựa Chọn' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_benefit_item' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Lý do khách hàng lựa chọn',
			),
		),
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định (4 lý do)' ),
				'content' => '[ttw_benefits heading="Lý do khách hàng lựa chọn"]
[ttw_benefit_item icon="badge" title="Chất Lượng Vượt Trội" desc="Nguồn nguyên vật liệu nhập khẩu chính hãng từ các thương hiệu hàng đầu thế giới."]
[ttw_benefit_item icon="headset" title="Thi Công Chuyên Nghiệp" desc="Đội ngũ kỹ thuật viên tay nghề cao, giàu kinh nghiệm thực chiến."]
[ttw_benefit_item icon="tag" title="Giá Thành Cạnh Tranh" desc="Tối ưu chi phí, mang đến giải pháp phù hợp nhất cho mọi ngân sách."]
[ttw_benefit_item icon="shield" title="Bảo Hành Tận Tâm" desc="Chính sách bảo hành dài hạn, hỗ trợ bảo trì nhanh chóng, chuyên nghiệp."]
[/ttw_benefits]',
			),
		),
	) );

	add_ux_builder_shortcode( 'ttw_benefit_item', array(
		'name'      => __( 'Lý Do Lựa Chọn' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_benefits' ),
		'wrap'      => false,
		'options'   => array(
			'title' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề ưu điểm' ),
				'default' => 'Chất Lượng Vượt Trội',
			),
			'desc' => array(
				'type'    => 'textarea',
				'heading' => __( 'Nội dung chi tiết' ),
				'default' => 'Mô tả chi tiết về ưu điểm này...',
			),
			'icon' => array(
				'type'    => 'select',
				'heading' => __( 'Biểu tượng (Icon)' ),
				'default' => 'badge',
				'options' => array(
					'badge'   => __( 'Huy hiệu chất lượng (badge)' ),
					'headset' => __( 'Hỗ trợ / Kỹ thuật (headset)' ),
					'tag'     => __( 'Giá cả / Thẻ giá (tag)' ),
					'shield'  => __( 'Bảo hành / Khiên (shield)' ),
				),
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_partners', array(
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Đối Tác' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_partner_item' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Đối tác của chúng tôi',
			),
		),
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định (9 đối tác)' ),
				'content' => '[ttw_partners heading="Đối tác của chúng tôi"]
[ttw_partner_item image="2403"]
[ttw_partner_item image="2419"]
[ttw_partner_item image="2418"]
[ttw_partner_item image="2417"]
[ttw_partner_item image="2416"]
[ttw_partner_item image="2415"]
[ttw_partner_item image="2406"]
[ttw_partner_item image="2405"]
[ttw_partner_item image="2404"]
[/ttw_partners]',
			),
		),
	) );

	add_ux_builder_shortcode( 'ttw_partner_item', array(
		'name'      => __( 'Logo Đối Tác' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_partners' ),
		'wrap'      => false,
		'options'   => array(
			'image' => array(
				'type'    => 'image',
				'heading' => __( 'Ảnh Logo Đối Tác' ),
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_testimonials', array(
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Đánh Giá Khách Hàng' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_testimonial_item' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Góc nhìn người tiêu dùng',
			),
			'desc' => array(
				'type'    => 'textfield',
				'heading' => __( 'Mô tả ngắn' ),
				'default' => 'Sự hài lòng của khách hàng là minh chứng rõ nét nhất cho chất lượng dịch vụ của chúng tôi.',
			),
		),
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định (6 đánh giá)' ),
				'content' => '[ttw_testimonials heading="Góc nhìn người tiêu dùng" desc="Sự hài lòng của khách hàng là minh chứng rõ nét nhất cho chất lượng dịch vụ của chúng tôi."]
[ttw_testimonial_item author="Anh Minh Tuấn" role="Chủ đầu tư biệt thự Vinhomes" quote="Tôi rất ấn tượng với sự chuyên nghiệp của đội ngũ Tân Tiến. Hệ thống cửa nhôm Xingfa được lắp đặt hoàn hảo, cách âm cực tốt và mang lại vẻ đẹp hiện đại cho ngôi nhà." image="2403"]
[ttw_testimonial_item author="Chị Lan Hương" role="Giám đốc dự án Tech Office" quote="Vách kính mặt dựng cho tòa nhà văn phòng của chúng tôi được thi công đúng tiến độ, chất lượng kính tuyệt vời. Dịch vụ hậu mãi cũng rất tận tình." image="2419"]
[ttw_testimonial_item author="Anh Hoàng Quân" role="Quản lý dự án Ocean Resort" quote="Tân Tiến Window đã tư vấn giải pháp lan can kính rất phù hợp với thiết kế tổng thể của resort. Khách hàng của chúng tôi rất thích không gian mở này." image="2418"]
[ttw_testimonial_item author="Chị Thu Trang" role="Chủ căn hộ Penthouse Landmark" quote="Hệ cửa lùa khoang kính mở rộng tối đa tầm nhìn tuyệt đẹp. Kỹ thuật thi công lắp đặt vô cùng tỉ mỉ và chuyên nghiệp." image="2417"]
[ttw_testimonial_item author="Anh Đức Nam" role="Chủ thầu thi công Villa Thảo Điền" quote="Sản phẩm nhôm kính Tân Tiến luôn đúng tiêu chuẩn kỹ thuật khắt khe, phụ kiện cao cấp và bàn giao đúng hẹn." image="2416"]
[ttw_testimonial_item author="Anh Quốc Bảo" role="Giám đốc chuỗi Showroom Auto" quote="Mặt dựng kính khung nhôm cao cấp tạo nên diện mạo sang trọng vượt trội cho toàn bộ hệ thống đại lý của chúng tôi." image="2415"]
[/ttw_testimonials]',
			),
		),
	) );

	add_ux_builder_shortcode( 'ttw_testimonial_item', array(
		'name'      => __( 'Đánh Giá Khách Hàng' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_testimonials' ),
		'wrap'      => false,
		'options'   => array(
			'image' => array(
				'type'    => 'image',
				'heading' => __( 'Ảnh công trình thực tế (Nền card)' ),
			),
			'author' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tên khách hàng' ),
				'default' => 'Anh Minh Tuấn',
			),
			'role' => array(
				'type'    => 'textfield',
				'heading' => __( 'Chức danh / Vai trò' ),
				'default' => 'Chủ đầu tư biệt thự Vinhomes',
			),
			'quote' => array(
				'type'    => 'textarea',
				'heading' => __( 'Nội dung nhận xét' ),
				'default' => 'Tôi rất ấn tượng với sự chuyên nghiệp...',
			),
		),
	) );


	add_ux_builder_shortcode( 'ttw_news', array(
		'name'      => __( 'Tân Tiến - Tin Tức & Kiến Thức' ),
		'category'  => __( 'Tân Tiến Window' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Tin tức & Kiến thức',
			),
			'count' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Số lượng bài viết' ),
				'description' => __( 'Số bài viết hiển thị.' ),
				'default'     => '3',
			),
			'orderby' => array(
				'type'    => 'select',
				'heading' => __( 'Sắp xếp tự động theo' ),
				'default' => 'date',
				'options' => array(
					'date'          => __( 'Mới nhất / Ngày đăng' ),
					'title'         => __( 'Tên bài viết (A-Z)' ),
					'modified'      => __( 'Thời gian cập nhật' ),
					'rand'          => __( 'Ngẫu nhiên' ),
					'comment_count' => __( 'Nhiều bình luận nhất' ),
				),
			),
			'order' => array(
				'type'    => 'select',
				'heading' => __( 'Thứ tự sắp xếp' ),
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Giảm dần (Mới nhất / Z-A)' ),
					'ASC'  => __( 'Tăng dần (Cũ nhất / A-Z)' ),
				),
			),
		),
	) );

	// 10. Shortcode Products Hero (Container)
	add_ux_builder_shortcode( 'ttw_products_hero', array(
		'name'      => __( 'TTW - Khối Banner Sản Phẩm (Hero)', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 10,
		'type'      => 'container',
		'allow'     => array( 'ttw_products_title', 'ttw_products_subtitle' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(),
	) );

	// 11. Shortcode Products Title
	add_ux_builder_shortcode( 'ttw_products_title', array(
		'name'      => __( 'TTW - Tiêu Đề Trang Sản Phẩm', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 11,
		'require'   => array( 'ttw_products_hero' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung tiêu đề' ),
				'default' => 'SẢN PHẨM',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'    => 'textfield',
				'heading' => __( 'Kích thước chữ (ví dụ: 48px, 3.5rem)' ),
				'default' => '',
			),
			'css' => array(
				'type'    => 'textfield',
				'heading' => __( 'CSS tùy chỉnh bổ sung' ),
				'default' => '',
			),
		),
	) );

	// 12. Shortcode Products Subtitle
	add_ux_builder_shortcode( 'ttw_products_subtitle', array(
		'name'      => __( 'TTW - Phụ Đề Trang Sản Phẩm', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 12,
		'require'   => array( 'ttw_products_hero' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung phụ đề' ),
				'default' => 'GIẢI PHÁP NHÔM KÍNH CHO KIẾN TRÚC HIỆN ĐẠI',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'    => 'textfield',
				'heading' => __( 'Kích thước chữ (ví dụ: 16px, 1.2rem)' ),
				'default' => '',
			),
			'css' => array(
				'type'    => 'textfield',
				'heading' => __( 'CSS tùy chỉnh bổ sung' ),
				'default' => '',
			),
		),
	) );


	// 13. Shortcode Product Archive (Danh sách sản phẩm Figma + Lọc DB)
	add_ux_builder_shortcode( 'ttw_product_archive', array(
		'name'      => __( 'TTW - Danh Sách Sản Phẩm (Bento + Lọc DB)', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 13,
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'count' => array(
				'type'        => 'slider',
				'heading'     => __( 'Số lượng sản phẩm hiển thị / trang' ),
				'description' => __( 'Kéo chọn số lượng sản phẩm hiển thị trên 1 trang.' ),
				'default'     => '6',
				'max'         => '24',
				'min'         => '3',
				'step'        => '3',
			),
			'posts_per_page' => array(
				'type'    => 'textfield',
				'heading' => __( 'Hoặc nhập số lượng' ),
				'default' => '6',
			),
			'orderby' => array(
				'type'    => 'select',
				'heading' => __( 'Sắp xếp theo' ),
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Mới nhất / Ngày đăng' ),
					'title'      => __( 'Tên sản phẩm (A-Z / Z-A)' ),
					'modified'   => __( 'Thời gian cập nhật' ),
					'rand'       => __( 'Ngẫu nhiên' ),
					'menu_order' => __( 'Thứ tự tùy chỉnh' ),
				),
			),
			'order' => array(
				'type'    => 'select',
				'heading' => __( 'Thứ tự sắp xếp' ),
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Giảm dần (Mới nhất / Z -> A)' ),
					'ASC'  => __( 'Tăng dần (Cũ nhất / A -> Z)' ),
				),
			),
		),
	) );

	// 14. Shortcode Projects Hero (Container)
	add_ux_builder_shortcode( 'ttw_projects_hero', array(
		'name'      => __( 'TTW - Khối Banner Công Trình (Hero)', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 14,
		'type'      => 'container',
		'allow'     => array( 'ttw_projects_title', 'ttw_projects_subtitle' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(),
	) );

	// 15. Shortcode Projects Title
	add_ux_builder_shortcode( 'ttw_projects_title', array(
		'name'      => __( 'TTW - Tiêu Đề Trang Công Trình', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 15,
		'require'   => array( 'ttw_projects_hero' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung tiêu đề' ),
				'default' => 'CÔNG TRÌNH TIÊU BIỂU',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'    => 'textfield',
				'heading' => __( 'Kích thước chữ (ví dụ: 64px, 4rem)' ),
				'default' => '',
			),
			'css' => array(
				'type'    => 'textfield',
				'heading' => __( 'CSS tùy chỉnh bổ sung' ),
				'default' => '',
			),
		),
	) );

	// 16. Shortcode Projects Subtitle
	add_ux_builder_shortcode( 'ttw_projects_subtitle', array(
		'name'      => __( 'TTW - Phụ Đề Trang Công Trình', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 16,
		'require'   => array( 'ttw_projects_hero' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung phụ đề' ),
				'default' => 'NHỮNG CÔNG TRÌNH THỂ HIỆN CHẤT LƯỢNG VÀ THẨM MỸ TRONG TỪNG CHI TIẾT.',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'    => 'textfield',
				'heading' => __( 'Kích thước chữ (ví dụ: 18px, 1.2rem)' ),
				'default' => '',
			),
			'css' => array(
				'type'    => 'textfield',
				'heading' => __( 'CSS tùy chỉnh bổ sung' ),
				'default' => '',
			),
		),
	) );


	// 17. Shortcode Projects Archive (Danh sách công trình Figma + Lọc DB)
	add_ux_builder_shortcode( 'ttw_projects_archive', array(
		'name'      => __( 'TTW - Danh Sách Công Trình (Grid Figma)', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 17,
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'posts_per_page' => array(
				'type'    => 'textfield',
				'heading' => __( 'Số lượng công trình hiển thị' ),
				'default' => '6',
			),
			'orderby' => array(
				'type'    => 'select',
				'heading' => __( 'Sắp xếp theo' ),
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Mới nhất / Ngày đăng' ),
					'title'      => __( 'Tên công trình (A-Z / Z-A)' ),
					'modified'   => __( 'Thời gian cập nhật' ),
					'rand'       => __( 'Ngẫu nhiên' ),
					'menu_order' => __( 'Thứ tự tùy chỉnh' ),
				),
			),
			'order' => array(
				'type'    => 'select',
				'heading' => __( 'Thứ tự sắp xếp' ),
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Giảm dần (Mới nhất / Z -> A)' ),
					'ASC'  => __( 'Tăng dần (Cũ nhất / A -> Z)' ),
				),
			),
		),
	) );

	// 18. Shortcode News Hero (Container)
	add_ux_builder_shortcode( 'ttw_news_hero', array(
		'name'      => __( 'TTW - Khối Banner Tin Tức (Hero)', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 18,
		'type'      => 'container',
		'allow'     => array( 'ttw_news_title', 'ttw_news_subtitle' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(),
	) );

	// 19. Shortcode News Title
	add_ux_builder_shortcode( 'ttw_news_title', array(
		'name'      => __( 'TTW - Tiêu Đề Trang Tin Tức', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 19,
		'require'   => array( 'ttw_news_hero' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung tiêu đề' ),
				'default' => 'TIN TỨC & KIẾN THỨC',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'    => 'textfield',
				'heading' => __( 'Kích thước chữ' ),
				'default' => '',
			),
			'css' => array(
				'type'    => 'textfield',
				'heading' => __( 'CSS tùy chỉnh bổ sung' ),
				'default' => '',
			),
		),
	) );

	// 20. Shortcode News Subtitle
	add_ux_builder_shortcode( 'ttw_news_subtitle', array(
		'name'      => __( 'TTW - Phụ Đề Trang Tin Tức', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 20,
		'require'   => array( 'ttw_news_hero' ),
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'text' => array(
				'type'    => 'textfield',
				'heading' => __( 'Nội dung phụ đề' ),
				'default' => 'Cập nhật tin tức mới nhất, xu hướng kiến trúc và kiến thức chuyên sâu về giải pháp nhôm kính.',
			),
			'color' => array(
				'type'    => 'colorpicker',
				'heading' => __( 'Màu chữ' ),
				'default' => '',
			),
			'font_size' => array(
				'type'    => 'textfield',
				'heading' => __( 'Kích thước chữ' ),
				'default' => '',
			),
			'css' => array(
				'type'    => 'textfield',
				'heading' => __( 'CSS tùy chỉnh bổ sung' ),
				'default' => '',
			),
		),
	) );

	// 21. Shortcode News Archive (Danh sách bài viết tin tức bento grid chuẩn Figma 1:2767)
	add_ux_builder_shortcode( 'ttw_news_archive', array(
		'name'      => __( 'TTW - Danh Sách Bài Viết Tin Tức (Figma 1:2767)', 'tantien-window' ),
		'category'  => __( 'Tân Tiến Window', 'tantien-window' ),
		'priority'  => 21,
		'thumbnail' => get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg',
		'options'   => array(
			'posts_per_page' => array(
				'type'    => 'textfield',
				'heading' => __( 'Số lượng bài viết trên 1 trang' ),
				'default' => '6',
			),
			'orderby' => array(
				'type'    => 'select',
				'heading' => __( 'Sắp xếp theo' ),
				'default' => 'date',
				'options' => array(
					'date'       => __( 'Mới nhất / Ngày đăng' ),
					'title'      => __( 'Tiêu đề bài viết' ),
					'modified'   => __( 'Thời gian cập nhật' ),
					'rand'       => __( 'Ngẫu nhiên' ),
				),
			),
			'order' => array(
				'type'    => 'select',
				'heading' => __( 'Thứ tự sắp xếp' ),
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'Giảm dần (Mới nhất)' ),
					'ASC'  => __( 'Tăng dần (Cũ nhất)' ),
				),
			),
		),
	) );
} );







function ttw_register_shortcodes() {
	// Shortcode Hero (Container Mẹ)
	add_shortcode( 'ttw_hero', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'bg_image'   => '',
			'height'     => '',
			'text_align' => 'left',
			'overlay'    => '',
			'padding'    => '',
			'custom_css' => '',
		), $atts );

		$img_url = get_stylesheet_directory_uri() . '/assets/img/design/hero-bg.jpg';
		if ( ! empty( $a['bg_image'] ) ) {
			if ( is_numeric( $a['bg_image'] ) ) {
				$img_src = wp_get_attachment_image_src( $a['bg_image'], 'full' );
				if ( $img_src ) {
					$img_url = $img_src[0];
				}
			} else {
				$img_url = $a['bg_image'];
			}
		}

		$hero_styles = array();
		if ( ! empty( $a['height'] ) ) {
			$hero_styles[] = 'min-height:' . esc_attr($a['height']);
			$hero_styles[] = 'height:' . esc_attr($a['height']);
		}
		if ( ! empty( $a['padding'] ) ) {
			$hero_styles[] = 'padding:' . esc_attr($a['padding']);
		}
		if ( ! empty( $a['custom_css'] ) ) {
			$hero_styles[] = esc_attr($a['custom_css']);
		}
		$hero_style_attr = ! empty( $hero_styles ) ? ' style="' . implode(';', $hero_styles) . '"' : '';

		$content_styles = array();
		if ( ! empty( $a['text_align'] ) ) {
			$content_styles[] = 'text-align:' . esc_attr($a['text_align']);
		}
		$content_style_attr = ! empty( $content_styles ) ? ' style="' . implode(';', $content_styles) . '"' : '';

		$overlay_style_attr = '';
		if ( ! empty( $a['overlay'] ) ) {
			$overlay_style_attr = ' style="background-color:' . esc_attr($a['overlay']) . ';"';
		}

		// Reset biến gom các nút bấm
		global $ttw_hero_buttons_list, $ttw_hero_in_container;
		$ttw_hero_buttons_list = array();
		$ttw_hero_in_container = true;

		$inner_html = ! empty( $content ) ? do_shortcode( $content ) : '';

		$ttw_hero_in_container = false;

		// Nếu có danh sách nút được gom từ shortcode con ttw_hero_button
		$buttons_html = '';
		if ( ! empty( $ttw_hero_buttons_list ) ) {
			$buttons_html = '<div class="ttw-hero-buttons">' . implode( '', $ttw_hero_buttons_list ) . '</div>';
		}

		ob_start();
		?>
		<section class="ttw-hero"<?php echo $hero_style_attr; ?>>
			<div class="ttw-hero-bg" style="background-image:url('<?php echo esc_url($img_url); ?>')"></div>
			<?php if ( ! empty( $a['overlay'] ) ) : ?>
				<div class="ttw-hero-overlay"<?php echo $overlay_style_attr; ?>></div>
			<?php endif; ?>
			<div class="ttw-hero-content"<?php echo $content_style_attr; ?>>
				<?php echo $inner_html; ?>
				<?php echo $buttons_html; ?>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );


	// Shortcode Hero Title (Child)
	add_shortcode( 'ttw_hero_title', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'Giải pháp nhôm kính cho kiến trúc hiện đại',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr($a['color']) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr($a['font_size']);
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr($a['css']);
		$style_attr = ! empty( $styles ) ? ' style="' . implode(';', $styles) . '"' : '';

		return '<h1' . $style_attr . '>' . wp_kses_post($a['text']) . '</h1>';
	} );

	// Shortcode Hero Desc (Child)
	add_shortcode( 'ttw_hero_desc', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => '',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr($a['color']) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr($a['font_size']);
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr($a['css']);
		$style_attr = ! empty( $styles ) ? ' style="' . implode(';', $styles) . '"' : '';

		return '<p' . $style_attr . '>' . esc_html($a['text']) . '</p>';
	} );

	// Shortcode Hero Button (Child)
	add_shortcode( 'ttw_hero_button', function( $atts ) {
		$a = shortcode_atts( array(
			'text'       => 'Khám phá sản phẩm',
			'link'       => '#',
			'type'       => 'primary',
			'bg_color'   => '',
			'text_color' => '',
			'css'        => '',
		), $atts );


		$class = ('light' === $a['type']) ? 'ttw-btn ttw-btn-light' : 'ttw-btn ttw-btn-primary';

		$styles = array();
		if ( ! empty( $a['bg_color'] ) )   $styles[] = 'background-color:' . esc_attr($a['bg_color']) . ' !important; border-color:' . esc_attr($a['bg_color']) . ' !important';
		if ( ! empty( $a['text_color'] ) ) $styles[] = 'color:' . esc_attr($a['text_color']) . ' !important';
		if ( ! empty( $a['css'] ) )        $styles[] = esc_attr($a['css']);
		$style_attr = ! empty( $styles ) ? ' style="' . implode(';', $styles) . '"' : '';

		$btn_html = sprintf(
			'<a class="%s" href="%s"%s>%s</a>',
			esc_attr($class),
			esc_url($a['link']),
			$style_attr,
			esc_html($a['text'])
		);

		global $ttw_hero_in_container;
		if ( ! empty( $ttw_hero_in_container ) ) {
			global $ttw_hero_buttons_list;
			if ( ! is_array( $ttw_hero_buttons_list ) ) {
				$ttw_hero_buttons_list = array();
			}
			$ttw_hero_buttons_list[] = $btn_html;
			return '';
		}

		// Trả về trực tiếp nút bấm khi ở màn hình xem trước UX Builder
		return '<div class="ttw-hero-buttons" style="display:inline-block; margin-right:10px;">' . $btn_html . '</div>';
	} );






	// Shortcode Stats
	add_shortcode( 'ttw_stats', function( $atts ) {
		$a = shortcode_atts( array(
			'stat1_num' => '10+',
			'stat1_lbl' => 'NĂM KINH NGHIỆM',
			'stat2_num' => '1.500m²',
			'stat2_lbl' => 'NHÀ XƯỞNG',
			'stat3_num' => '100%',
			'stat3_lbl' => 'THI CÔNG TOÀN QUỐC',
			'stat4_num' => 'TOP',
			'stat4_lbl' => 'SẢN PHẨM CHÍNH HÃNG',
		), $atts );

		$stats = array(
			array($a['stat1_num'], $a['stat1_lbl']),
			array($a['stat2_num'], $a['stat2_lbl']),
			array($a['stat3_num'], $a['stat3_lbl']),
			array($a['stat4_num'], $a['stat4_lbl']),
		);
		ob_start();
		?>
		<section class="ttw-stats">
			<div class="container">
				<div class="ttw-stats-grid ttw-animate ttw-fade-up">
					<?php foreach ($stats as $i => $stat) : ?>
						<div class="ttw-stat<?php echo 0 === $i ? '' : ' ttw-stat-divider'; ?>">
							<div class="ttw-stat-number"><?php echo esc_html($stat[0]); ?></div>
							<div class="ttw-stat-label"><?php echo esc_html($stat[1]); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode About (Container Mẹ)
	add_shortcode( 'ttw_about', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'image'         => '',
			'bg_color'      => '',
			'padding'       => '',
			'text_link'     => 'CÁC GIẢI PHÁP TÂN TIẾN WINDOW',
			'text_link_url' => '/gioi-thieu/',
			'custom_css'    => '',
		), $atts );

		$section_styles = array();
		if ( ! empty( $a['bg_color'] ) )   $section_styles[] = 'background-color:' . esc_attr($a['bg_color']);
		if ( ! empty( $a['padding'] ) )    $section_styles[] = 'padding:' . esc_attr($a['padding']);
		if ( ! empty( $a['custom_css'] ) ) $section_styles[] = esc_attr($a['custom_css']);
		$section_style_attr = ! empty( $section_styles ) ? ' style="' . implode(';', $section_styles) . '"' : '';

		global $ttw_about_image_html;
		$ttw_about_image_html = '';

		$text_html = ! empty( $content ) ? do_shortcode( $content ) : '';

		// Nếu có ảnh chọn ở ô option của Khối Mẹ ttw_about
		if ( ! empty( $a['image'] ) ) {
			$img_url = $a['image'];
			if ( is_numeric( $a['image'] ) ) {
				$src = wp_get_attachment_image_src( $a['image'], 'full' );
				if ( $src ) {
					$img_url = $src[0];
				}
			}
			$ttw_about_image_html = '<img src="' . esc_url($img_url) . '" alt="About" loading="lazy">';
		}

		// Nếu chưa có ảnh từ shortcode con hay option mẹ, dùng ảnh mặc định
		if ( empty( $ttw_about_image_html ) ) {
			$img_url = get_stylesheet_directory_uri() . '/assets/img/design/about.jpg';
			$ttw_about_image_html = '<img src="' . esc_url($img_url) . '" alt="About" loading="lazy">';
		}

		$link_label = ! empty( $a['text_link'] ) ? $a['text_link'] : 'CÁC GIẢI PHÁP TÂN TIẾN WINDOW';
		$link_url   = ! empty( $a['text_link_url'] ) ? $a['text_link_url'] : '/gioi-thieu/';

		ob_start();
		?>
		<section class="ttw-section"<?php echo $section_style_attr; ?>>
			<div class="container">
				<div class="ttw-about">
					<div class="ttw-about-media ttw-animate ttw-fade-left">
						<?php echo $ttw_about_image_html; ?>
					</div>
					<div class="ttw-about-text ttw-animate ttw-fade-right">
						<?php echo $text_html; ?>
						<a class="ttw-textlink" href="<?php echo esc_url(home_url($link_url)); ?>"><?php echo esc_html($link_label); ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="M5 12h14" />
								<path d="M12 5l7 7-7 7" />
							</svg>
						</a>
					</div>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );



	// Shortcode About Eyebrow (Child)
	add_shortcode( 'ttw_about_eyebrow', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'GIẢI PHÁP TÂN TIẾN WINDOW',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );


		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr($a['color']) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr($a['font_size']);
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr($a['css']);
		$style_attr = ! empty( $styles ) ? ' style="' . implode(';', $styles) . '"' : '';

		return '<span class="ttw-eyebrow"' . $style_attr . '>' . esc_html($a['text']) . '</span>';
	} );

	// Shortcode About Title (Child)
	add_shortcode( 'ttw_about_title', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'Kiến tạo không gian từ những mảng kính',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr($a['color']) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr($a['font_size']);
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr($a['css']);
		$style_attr = ! empty( $styles ) ? ' style="' . implode(';', $styles) . '"' : '';

		return '<h2' . $style_attr . '>' . esc_html($a['text']) . '</h2>';
	} );

	// Shortcode About Desc (Child)
	add_shortcode( 'ttw_about_desc', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'Chúng tôi tập trung vào sự hoàn mỹ trong từng chi tiết nhôm kính...',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr($a['color']) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr($a['font_size']);
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr($a['css']);
		$style_attr = ! empty( $styles ) ? ' style="' . implode(';', $styles) . '"' : '';

		return '<p' . $style_attr . '>' . esc_html($a['text']) . '</p>';
	} );

	// Shortcode About Image (Child)
	add_shortcode( 'ttw_about_image', function( $atts ) {
		$a = shortcode_atts( array(
			'image'         => '',
			'border_radius' => '',
			'css'           => '',
		), $atts );

		$img_url = get_stylesheet_directory_uri() . '/assets/img/design/about.jpg';
		if ( ! empty( $a['image'] ) ) {
			if ( is_numeric( $a['image'] ) ) {
				$src = wp_get_attachment_image_src( $a['image'], 'full' );
				if ( $src ) {
					$img_url = $src[0];
				}
			} else {
				$img_url = $a['image'];
			}
		}

		$styles = array();
		if ( ! empty( $a['border_radius'] ) ) $styles[] = 'border-radius:' . esc_attr($a['border_radius']);
		if ( ! empty( $a['css'] ) )           $styles[] = esc_attr($a['css']);
		$style_attr = ! empty( $styles ) ? ' style="' . implode(';', $styles) . '"' : '';

		$img_html = sprintf(
			'<img src="%s" alt="Giới thiệu" loading="lazy"%s>',
			esc_url($img_url),
			$style_attr
		);

		global $ttw_about_in_container;
		if ( ! empty( $ttw_about_in_container ) ) {
			global $ttw_about_image_html;
			$ttw_about_image_html = $img_html;
			return '';
		}

		return '<div class="ttw-about-media ttw-animate ttw-fade-left">' . $img_html . '</div>';
	} );


	// Shortcode Products
	add_shortcode( 'ttw_products', function( $atts ) {
		$a = shortcode_atts( array(
			'heading' => 'Chúng tôi cung cấp',
			'desc'    => 'Tân Tiến Window cung cấp các giải pháp cửa nhôm kính, cửa kính, vách kính, mặt dựng và kính kiến trúc cho nhà ở, biệt thự, văn phòng và công trình thương mại.',
			'count'   => '6',
			'orderby' => 'date',
			'order'   => 'DESC',
		), $atts );

		ob_start();
		?>
		<section class="ttw-section ttw-section-gray" id="san-pham">
			<div class="container">
				<div class="ttw-section-row ttw-animate ttw-fade-up">
					<div>
						<h2 class="ttw-section-title"><?php echo esc_html($a['heading']); ?></h2>
						<?php if ( ! empty( $a['desc'] ) ) : ?>
							<p class="ttw-section-desc"><?php echo esc_html($a['desc']); ?></p>
						<?php endif; ?>

					</div>
					<a class="ttw-textlink" href="<?php echo esc_url(ttw_shop_url()); ?>">Xem tất cả
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M5 12h14" />
							<path d="M12 5l7 7-7 7" />
						</svg>
					</a>
				</div>

				<div class="ttw-showcase ttw-animate ttw-fade-up">
					<?php
					$q_args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => intval($a['count']),
						'orderby'        => sanitize_key($a['orderby']),
						'order'          => strtoupper(sanitize_key($a['order'])),
						'no_found_rows'  => true,
					);

					$q = new WP_Query($q_args);
					while ($q->have_posts()) :
						$q->the_post();
						$product_img = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
					?>
						<a class="ttw-showcase-card" href="<?php the_permalink(); ?>">
							<?php if ($product_img) : ?>
								<img src="<?php echo esc_url($product_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
							<?php else : ?>
								<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/img/placeholder.svg'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
							<?php endif; ?>
							<div class="ttw-showcase-overlay"></div>
							<div class="ttw-showcase-body">
								<h3><?php the_title(); ?></h3>
							</div>
						</a>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode Values (Container)
	add_shortcode( 'ttw_values', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'heading' => 'NĂNG LỰC CỦA CHÚNG TÔI',
		), $atts );


		$inner_content = ! empty( $content ) ? do_shortcode( $content ) : '';

		// Nếu người dùng chưa thêm item nào, dùng 4 item mặc định
		if ( empty( trim( $inner_content ) ) ) {
			$default_items = array(
				array('01', 'TƯ VẤN & GIẢI PHÁP', 'Phân tích nhu cầu, kiến trúc và điều kiện thực tế để đề xuất cấu hình nhôm kính phù hợp.'),
				array('02', 'THIẾT KẾ & KỸ THUẬT', 'Triển khai bản vẽ, cấu tạo và giải pháp kỹ thuật đáp ứng yêu cầu thẩm mỹ và công năng.'),
				array('03', 'SẢN XUẤT', 'Gia công nhôm kính tại nhà máy với quy trình kiểm soát chất lượng trong từng công đoạn.'),
				array('04', 'THI CÔNG', 'Đội ngũ kỹ thuật trực tiếp lắp đặt, đảm bảo độ chính xác, an toàn và tiến độ công trình.'),
			);
			foreach ($default_items as $v) {
				$inner_content .= sprintf(
					'<div class="ttw-value"><span class="ttw-value-num">%s</span><h3 class="ttw-value-title">%s</h3><p class="ttw-value-desc">%s</p></div>',
					esc_html($v[0]),
					esc_html($v[1]),
					esc_html($v[2])
				);
			}
		}


		ob_start();
		?>
		<section class="ttw-section">
			<div class="container">
				<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up"><?php echo esc_html($a['heading']); ?></h2>
				<div class="ttw-values ttw-animate ttw-fade-up">
					<?php echo $inner_content; ?>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode Value Item (Child)
	add_shortcode( 'ttw_value_item', function( $atts ) {
		$a = shortcode_atts( array(
			'num'   => '01',
			'title' => 'Chất lượng',
			'desc'  => 'Mô tả chi tiết...',
		), $atts );

		ob_start();
		?>
		<div class="ttw-value">
			<span class="ttw-value-num"><?php echo esc_html($a['num']); ?></span>
			<h3 class="ttw-value-title"><?php echo esc_html($a['title']); ?></h3>
			<p class="ttw-value-desc"><?php echo esc_html($a['desc']); ?></p>
		</div>
		<?php
		return ob_get_clean();
	} );


	// Shortcode Projects (Trang chủ - Lấy động từ Database category 72 CÔNG TRÌNH TIÊU BIỂU)
	add_shortcode( 'ttw_projects', function( $atts ) {
		$a = shortcode_atts( array(
			'heading' => 'Công trình tiêu biểu',
			'desc'    => 'Những công trình thể hiện chất lượng và thẩm mỹ trong từng chi tiết.',
			'count'   => '3',
			'ids'     => '',
			'orderby' => 'date',
			'order'   => 'DESC',
		), $atts );

		$query_args = array(
			'post_type'   => 'post',
			'post_status' => 'publish',
		);

		// Nếu người dùng chọn danh sách ID bài viết cụ thể
		if ( ! empty( $a['ids'] ) ) {
			$raw_ids = is_array( $a['ids'] ) ? $a['ids'] : explode( ',', $a['ids'] );
			$clean_ids = array_filter( array_map( 'intval', $raw_ids ), function( $id ) { return $id > 0; } );
			
			if ( ! empty( $clean_ids ) ) {
				// Giới hạn chỉ chọn & hiển thị tối đa đúng 3 bài
				$clean_ids = array_slice( array_values( $clean_ids ), 0, 3 );

				$query_args['post__in']       = $clean_ids;
				$query_args['posts_per_page'] = count( $clean_ids );
				$query_args['orderby']        = 'post__in'; // Hiển thị chuẩn 100% theo đúng thứ tự bạn đã nhấp chọn từng bài
			}
		} else {
			// Người dùng không chọn ID -> Tự động lấy theo số lượng count và điều kiện sắp xếp
			$query_args['cat']            = 72; // Category CÔNG TRÌNH TIÊU BIỂU
			$query_args['posts_per_page'] = max( 1, intval( $a['count'] ) );
			$query_args['orderby']        = sanitize_key( $a['orderby'] );
			$query_args['order']          = strtoupper( sanitize_key( $a['order'] ) );
		}



		$q = new WP_Query( $query_args );
		if ( ! $q->have_posts() ) return '';



		ob_start();
		?>
		<section class="ttw-section ttw-section-gray" id="cong-trinh">
			<div class="container">
				<div class="ttw-projects-head ttw-animate ttw-fade-up">
					<h2 class="ttw-section-title"><?php echo esc_html($a['heading']); ?></h2>
					<p><?php echo esc_html($a['desc']); ?></p>
				</div>
				<div class="ttw-projects ttw-animate ttw-fade-up">
					<?php
					$index = 0;
					while ( $q->have_posts() ) :
						$q->the_post();
						$proj_id    = get_the_ID();
						$proj_title = get_the_title();
						$proj_link  = get_permalink();
						$proj_thumb = get_the_post_thumbnail_url( $proj_id, 'large' );
						if ( ! $proj_thumb ) {
							$proj_thumb = get_stylesheet_directory_uri() . '/assets/img/design/proj1-villa.jpg';
						}

						// Lấy thẻ tag/chủng loại & địa điểm từ post_meta
						$tag_label = get_post_meta( $proj_id, 'ttw_project_tag_label', true );
						if ( ! $tag_label ) {
							$tags = get_the_tags( $proj_id );
							$tag_label = ( ! empty( $tags ) ) ? $tags[0]->name : 'BIỆT THỰ CAO CẤP';
						}

						$size_class = ( 0 === $index ) ? 'large' : 'small';
						$index++;
					?>
						<a class="ttw-project ttw-project-<?php echo esc_attr( $size_class ); ?>" href="<?php echo esc_url( $proj_link ); ?>">
							<img src="<?php echo esc_url( $proj_thumb ); ?>" alt="<?php echo esc_attr( $proj_title ); ?>" loading="lazy">
							<div class="ttw-project-overlay"></div>
							<div class="ttw-project-body">
								<span class="ttw-project-cat"><?php echo esc_html( mb_strtoupper( $tag_label, 'UTF-8' ) ); ?></span>
								<h3><?php echo esc_html( $proj_title ); ?></h3>
							</div>
						</a>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
				<div class="ttw-projects-action">
					<a class="ttw-textlink" href="<?php echo esc_url( ttw_projects_url() ); ?>">Xem tất cả dự án
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M5 12h14" />
							<path d="M12 5l7 7-7 7" />
						</svg>
					</a>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode Process (Container)
	add_shortcode( 'ttw_process', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'heading' => 'Từ ý tưởng đến công trình hoàn thiện',
		), $atts );

		$inner_content = ! empty( $content ) ? do_shortcode( $content ) : '';

		// Nếu chưa có item nào, dùng 5 bước mặc định
		if ( empty( trim( $inner_content ) ) ) {
			$default_steps = array(
				array('01', 'Tư vấn', 'Tiếp nhận yêu cầu, đề xuất giải pháp phù hợp.'),
				array('02', 'Khảo sát', 'Đo đạc thực tế, đánh giá hiện trạng công trình.'),
				array('03', 'Thiết kế & báo giá', 'Lên bản vẽ chi tiết và dự toán chi phí.'),
				array('04', 'Sản xuất', 'Gia công tại xưởng với quy trình kiểm soát chặt chẽ.'),
				array('05', 'Lắp đặt & bảo hành', 'Thi công chuyên nghiệp, bàn giao và hỗ trợ dài hạn.'),
			);
			foreach ($default_steps as $step) {
				$inner_content .= sprintf(
					'<div class="ttw-step"><div class="ttw-step-num">%s</div><div class="ttw-step-content"><h3>%s</h3><p>%s</p></div></div>',
					esc_html($step[0]),
					esc_html($step[1]),
					esc_html($step[2])
				);
			}
		}

		ob_start();
		?>
		<section class="ttw-section">
			<div class="container">
				<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up"><?php echo esc_html($a['heading']); ?></h2>
				<div class="ttw-process ttw-animate ttw-fade-up">
					<div class="ttw-process-line"></div>
					<?php echo $inner_content; ?>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode Step Item (Child)
	add_shortcode( 'ttw_step_item', function( $atts ) {
		$a = shortcode_atts( array(
			'num'   => '01',
			'title' => 'Tên bước',
			'desc'  => 'Mô tả chi tiết...',
		), $atts );

		ob_start();
		?>
		<div class="ttw-step">
			<div class="ttw-step-num"><?php echo esc_html($a['num']); ?></div>
			<div class="ttw-step-content">
				<h3><?php echo esc_html($a['title']); ?></h3>
				<p><?php echo esc_html($a['desc']); ?></p>
			</div>
		</div>

		<?php
		return ob_get_clean();
	} );


	// Shortcode Benefits (Container)
	add_shortcode( 'ttw_benefits', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'heading' => 'Lý do khách hàng lựa chọn',
		), $atts );

		$inner_content = ! empty( $content ) ? do_shortcode( $content ) : '';

		// Nếu chưa có item nào, dùng 4 ưu điểm mặc định
		if ( empty( trim( $inner_content ) ) ) {
			$default_benefits = array(
				array('badge', 'Chất Lượng Vượt Trội', 'Nguồn nguyên vật liệu nhập khẩu chính hãng từ các thương hiệu hàng đầu thế giới.'),
				array('headset', 'Thi Công Chuyên Nghiệp', 'Đội ngũ kỹ thuật viên tay nghề cao, giàu kinh nghiệm thực chiến.'),
				array('tag', 'Giá Thành Cạnh Tranh', 'Tối ưu chi phí, mang đến giải pháp phù hợp nhất cho mọi ngân sách.'),
				array('shield', 'Bảo Hành Tận Tâm', 'Chính sách bảo hành dài hạn, hỗ trợ bảo trì nhanh chóng, chuyên nghiệp.'),
			);
			foreach ($default_benefits as $b) {
				$inner_content .= sprintf(
					'<div class="ttw-benefit"><div class="ttw-benefit-icon"><img src="%s" alt="%s" loading="lazy"></div><h3>%s</h3><p>%s</p></div>',
					esc_url(get_stylesheet_directory_uri() . '/assets/icons/' . $b[0] . '.svg'),
					esc_attr($b[1]),
					esc_html($b[1]),
					esc_html($b[2])
				);
			}
		}

		ob_start();
		?>
		<section class="ttw-section">
			<div class="container">
				<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up"><?php echo esc_html($a['heading']); ?></h2>
				<div class="ttw-benefits ttw-animate ttw-fade-up">
					<?php echo $inner_content; ?>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode Benefit Item (Child)
	add_shortcode( 'ttw_benefit_item', function( $atts ) {
		$a = shortcode_atts( array(
			'icon'  => 'badge',
			'title' => 'Chất Lượng Vượt Trội',
			'desc'  => 'Mô tả chi tiết...',
		), $atts );

		$icon_name = ! empty( $a['icon'] ) ? sanitize_key( $a['icon'] ) : 'badge';
		$icon_url  = get_stylesheet_directory_uri() . '/assets/icons/' . $icon_name . '.svg';

		ob_start();
		?>
		<div class="ttw-benefit">
			<div class="ttw-benefit-icon">
				<img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($a['title']); ?>" loading="lazy">
			</div>
			<h3><?php echo esc_html($a['title']); ?></h3>
			<p><?php echo esc_html($a['desc']); ?></p>
		</div>
		<?php
		return ob_get_clean();
	} );


	// Shortcode Products Hero (Container)
	add_shortcode( 'ttw_products_hero', function( $atts, $content = null ) {
		$inner = ! empty( $content ) ? do_shortcode( $content ) : '';
		return '<section class="ttw-products-hero ttw-animate ttw-fade-up">' . $inner . '</section>';
	} );

	// Shortcode Products Title
	add_shortcode( 'ttw_products_title', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'SẢN PHẨM',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr( $a['color'] ) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr( $a['font_size'] );
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr( $a['css'] );
		$style_attr = ! empty( $styles ) ? ' style="' . implode( ';', $styles ) . '"' : '';

		return '<h1 class="ttw-products-title"' . $style_attr . '>' . esc_html( $a['text'] ) . '</h1>';
	} );

	// Shortcode Products Subtitle
	add_shortcode( 'ttw_products_subtitle', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'GIẢI PHÁP NHÔM KÍNH CHO KIẾN TRÚC HIỆN ĐẠI',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr( $a['color'] ) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr( $a['font_size'] );
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr( $a['css'] );
		$style_attr = ! empty( $styles ) ? ' style="' . implode( ';', $styles ) . '"' : '';

		return '<p class="ttw-products-subtitle"' . $style_attr . '>' . esc_html( $a['text'] ) . '</p>';
	} );

	// Shortcode Projects Hero (Container)
	add_shortcode( 'ttw_projects_hero', function( $atts, $content = null ) {
		$inner = ! empty( $content ) ? do_shortcode( $content ) : '';
		return '<div class="ttw-projects-page"><div class="ttw-projects-container"><section class="ttw-projects-hero ttw-animate ttw-fade-up">' . $inner . '</section></div></div>';
	} );


	// Shortcode Projects Title
	add_shortcode( 'ttw_projects_title', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'CÔNG TRÌNH TIÊU BIỂU',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr( $a['color'] ) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr( $a['font_size'] );
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr( $a['css'] );
		$style_attr = ! empty( $styles ) ? ' style="' . implode( ';', $styles ) . '"' : '';

		return '<h1 class="ttw-projects-title"' . $style_attr . '>' . esc_html( $a['text'] ) . '</h1>';
	} );

	// Shortcode Projects Subtitle
	add_shortcode( 'ttw_projects_subtitle', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'NHỮNG CÔNG TRÌNH THỂ HIỆN CHẤT LƯỢNG VÀ THẨM MỸ TRONG TỪNG CHI TIẾT.',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr( $a['color'] ) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr( $a['font_size'] );
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr( $a['css'] );
		$style_attr = ! empty( $styles ) ? ' style="' . implode( ';', $styles ) . '"' : '';

		return '<p class="ttw-projects-subtitle"' . $style_attr . '>' . esc_html( $a['text'] ) . '</p>';
	} );



	// Shortcode Projects Archive (Danh sách công trình bento grid chuẩn Figma 1:2361 + Dữ liệu động DB 100%)
	add_shortcode( 'ttw_projects_archive', function( $atts ) {
		$a = shortcode_atts( array(
			'posts_per_page' => '6',
			'orderby'        => 'date',
			'order'          => 'DESC',
		), $atts );

		$ttw_paged    = isset( $_GET['paged'] ) && (int) $_GET['paged'] > 0 ? (int) $_GET['paged'] : 1;
		$ttw_curr_cat = isset( $_GET['cat'] ) ? sanitize_text_field( $_GET['cat'] ) : 'all';

		$query_args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $a['posts_per_page'],
			'paged'          => $ttw_paged,
			'cat'            => 72, // Category CÔNG TRÌNH TIÊU BIỂU
			'orderby'        => sanitize_key( $a['orderby'] ),
			'order'          => strtoupper( sanitize_key( $a['order'] ) ),
		);


		if ( 'all' !== $ttw_curr_cat && ! empty( $ttw_curr_cat ) ) {
			$query_args['tag'] = $ttw_curr_cat;
		}

		$projects_query = new WP_Query( $query_args );
		ob_start();

		?>
		<div class="ttw-projects-page" style="padding-top: 0;"><div class="ttw-projects-container" style="padding-top: 0; padding-bottom: 80px;">
			<section class="ttw-project-grid" aria-label="Danh sách công trình">
				<?php if ( $projects_query->have_posts() ) : ?>
					<?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
						<?php
						$proj_id    = get_the_ID();
						$proj_title = get_the_title();
						$proj_link  = get_permalink();
						$proj_thumb = get_the_post_thumbnail_url( $proj_id, 'large' );
						if ( ! $proj_thumb ) {
							$proj_thumb = get_stylesheet_directory_uri() . '/assets/img/design/proj1-villa.jpg';
						}

						// Đọc dữ liệu động thực tế từ post_meta và tags trong Database
						$tag_label = get_post_meta( $proj_id, 'ttw_project_tag_label', true );
						if ( ! $tag_label ) {
							$tags_terms = get_the_tags( $proj_id );
							$tag_label  = ( ! empty( $tags_terms ) && ! is_wp_error( $tags_terms ) ) ? mb_strtoupper( $tags_terms[0]->name, 'UTF-8' ) : 'CÔNG TRÌNH TIÊU BIỂU';
						}

						?>
						<article class="ttw-project-card ttw-animate ttw-fade-up">
							<a class="ttw-project-thumb" href="<?php echo esc_url( $proj_link ); ?>" title="<?php echo esc_attr( $proj_title ); ?>">
								<img src="<?php echo esc_url( $proj_thumb ); ?>" alt="<?php echo esc_attr( $proj_title ); ?>" loading="lazy" />
								<div class="ttw-project-overlay">
									<div class="ttw-project-meta">
										<span class="ttw-project-tag"><?php echo esc_html( $tag_label ); ?></span>
									</div>
									<h3 class="ttw-project-title"><?php echo esc_html( $proj_title ); ?></h3>
								</div>
							</a>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				<?php else : ?>
					<div class="ttw-project-empty" style="grid-column: 1 / -1;">
						<p>Chưa có bài viết công trình nào trong mục này.</p>
					</div>
				<?php endif; ?>
			</section>

			<?php if ( $projects_query->max_num_pages > 1 ) : ?>
				<nav class="ttw-pagination-figma ttw-animate ttw-fade-up" aria-label="Phân trang công trình" style="margin-top: 20px;">
					<?php
					$total_pages = $projects_query->max_num_pages;
					$base_url    = strtok( get_permalink(), '?' );
					$page_base   = ( 'all' !== $ttw_curr_cat ) ? add_query_arg( 'cat', $ttw_curr_cat, $base_url ) : $base_url;

					for ( $i = 1; $i <= $total_pages; $i++ ) :
						$page_url  = ( 1 === $i ) ? $page_base : add_query_arg( 'paged', $i, $page_base );
						$is_active = ( $i === $ttw_paged );
						?>
						<a href="<?php echo esc_url( $page_url ); ?>"
						   class="ttw-page-btn<?php echo $is_active ? ' active' : ''; ?>"
						   <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
							<?php echo $i; ?>
						</a>
					<?php endfor; ?>

					<?php if ( $ttw_paged < $total_pages ) : ?>
						<a href="<?php echo esc_url( add_query_arg( 'paged', $ttw_paged + 1, $page_base ) ); ?>"
						   class="ttw-page-btn ttw-page-next"
						   aria-label="Trang tiếp theo">
							<svg width="6" height="10" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M3.06667 4L0 0.933333L0.933333 0L4.93333 4L0.933333 8L0 7.06667L3.06667 4Z" fill="currentColor"/>
							</svg>
						</a>
					<?php endif; ?>
				</nav>
			<?php endif; ?>


		</div></div>
		<?php
		return ob_get_clean();
	} );

	// -------------------------------------------------------------------------
	// SHORTCODES TRANG TIN TỨC & KIẾN THỨC (FIGMA NODE 1:2767)
	// -------------------------------------------------------------------------

	// Shortcode News Hero Container
	add_shortcode( 'ttw_news_hero', function( $atts, $content = null ) {
		$inner = ! empty( $content ) ? do_shortcode( $content ) : '';
		return '<section class="ttw-news-hero ttw-animate ttw-fade-up">' . $inner . '</section>';
	} );


	// Shortcode News Title
	add_shortcode( 'ttw_news_title', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'TIN TỨC & KIẾN THỨC',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr( $a['color'] ) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr( $a['font_size'] );
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr( $a['css'] );
		$style_attr = ! empty( $styles ) ? ' style="' . implode( ';', $styles ) . '"' : '';

		return '<h1 class="ttw-news-title"' . $style_attr . '>' . esc_html( $a['text'] ) . '</h1>';
	} );

	// Shortcode News Subtitle
	add_shortcode( 'ttw_news_subtitle', function( $atts ) {
		$a = shortcode_atts( array(
			'text'      => 'Cập nhật tin tức mới nhất, xu hướng kiến trúc và kiến thức chuyên sâu về giải pháp nhôm kính.',
			'color'     => '',
			'font_size' => '',
			'css'       => '',
		), $atts );

		$styles = array();
		if ( ! empty( $a['color'] ) )     $styles[] = 'color:' . esc_attr( $a['color'] ) . ' !important';
		if ( ! empty( $a['font_size'] ) ) $styles[] = 'font-size:' . esc_attr( $a['font_size'] );
		if ( ! empty( $a['css'] ) )       $styles[] = esc_attr( $a['css'] );
		$style_attr = ! empty( $styles ) ? ' style="' . implode( ';', $styles ) . '"' : '';

		return '<p class="ttw-news-subtitle"' . $style_attr . '>' . esc_html( $a['text'] ) . '</p>';
	} );

	// Shortcode News Archive (Bento Grid Tin tức + Bài viết nổi bật Featured + Phân trang chuẩn Figma 1:2767)
	add_shortcode( 'ttw_news_archive', function( $atts ) {
		$a = shortcode_atts( array(
			'posts_per_page' => '6',
			'orderby'        => 'date',
			'order'          => 'DESC',
		), $atts );

		$ttw_paged    = isset( $_GET['paged'] ) && (int) $_GET['paged'] > 0 ? (int) $_GET['paged'] : 1;
		$ttw_curr_cat = isset( $_GET['cat'] ) ? sanitize_text_field( $_GET['cat'] ) : 'all';

		$query_args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => (int) $a['posts_per_page'],
			'paged'          => $ttw_paged,
			'category__not_in' => array( 72 ), // Loại bỏ category công trình tiêu biểu
			'orderby'        => sanitize_key( $a['orderby'] ),
			'order'          => strtoupper( sanitize_key( $a['order'] ) ),
		);

		if ( 'all' !== $ttw_curr_cat && ! empty( $ttw_curr_cat ) ) {
			$query_args['category_name'] = $ttw_curr_cat;
		}

		$news_query = new WP_Query( $query_args );

		// Lấy danh sách danh mục (category) trực tiếp từ Database (loại bỏ category Công trình tiêu biểu ID 72 & Uncategorized 1)
		$ttw_db_terms = get_terms( array(
			'taxonomy'   => 'category',
			'hide_empty' => false,
			'exclude'    => array( 72, 1 ), // Exclude Công trình tiêu biểu & Dịch vụ
			'orderby'    => 'name',
			'order'      => 'ASC',
		) );

		$ttw_categories = array(
			array( 'slug' => 'all', 'name' => 'TẤT CẢ' ),
		);

		if ( ! empty( $ttw_db_terms ) && ! is_wp_error( $ttw_db_terms ) ) {
			foreach ( $ttw_db_terms as $term_obj ) {
				$ttw_categories[] = array(
					'slug' => $term_obj->slug,
					'name' => mb_strtoupper( $term_obj->name, 'UTF-8' ),
				);
			}
		}



		ob_start();
		?>
		<!-- Filter Categories Nav -->

				<nav class="ttw-news-filter-nav ttw-animate ttw-fade-up" aria-label="Bộ lọc tin tức">
					<ul class="ttw-news-filter-list">
						<?php
						$base_url = strtok( get_permalink(), '?' );
						foreach ( $ttw_categories as $ttw_cat ) :
							$cat_slug   = $ttw_cat['slug'];
							$is_cat_act = ( $ttw_curr_cat === $cat_slug );
							$cat_url    = ( 'all' === $cat_slug ) ? $base_url : add_query_arg( 'cat', $cat_slug, $base_url );
							?>
							<li class="ttw-news-filter-item">
								<a href="<?php echo esc_url( $cat_url ); ?>" class="ttw-news-filter-tab<?php echo $is_cat_act ? ' active' : ''; ?>">
									<?php echo esc_html( $ttw_cat['name'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<?php if ( $news_query->have_posts() ) : ?>
					<?php
					$post_index = 0;
					$featured_post = null;
					$grid_posts = array();

					while ( $news_query->have_posts() ) {
						$news_query->the_post();
						if ( 0 === $post_index && 1 === $ttw_paged ) {
							$featured_post = get_post();
						} else {
							$grid_posts[] = get_post();
						}
						$post_index++;
					}
					wp_reset_postdata();
					?>

					<!-- Featured Big Card (Trang 1 hiển thị bài viết nổi bật đầu tiên) -->
					<?php if ( $featured_post ) : ?>
						<?php
						$feat_id    = $featured_post->ID;
						$feat_title = get_the_title( $feat_id );
						$feat_link  = get_permalink( $feat_id );
						$feat_date  = get_the_date( 'd.m.Y', $feat_id );
						$feat_desc  = get_the_excerpt( $feat_id );
						if ( empty( $feat_desc ) ) {
							$feat_desc = wp_trim_words( $featured_post->post_content, 25, '...' );
						}
						$feat_thumb = get_the_post_thumbnail_url( $feat_id, 'full' );
						if ( ! $feat_thumb ) {
							$feat_thumb = get_stylesheet_directory_uri() . '/assets/img/design/news-feat.jpg';
						}
						$cats = get_the_category( $feat_id );
						$feat_cat = ( ! empty( $cats ) ) ? mb_strtoupper( $cats[0]->name, 'UTF-8' ) : 'TIN TỨC & SỰ KIỆN';
						?>
						<article class="ttw-news-featured ttw-animate ttw-fade-up">
							<div class="ttw-featured-media">
								<a href="<?php echo esc_url( $feat_link ); ?>" title="<?php echo esc_attr( $feat_title ); ?>">
									<img src="<?php echo esc_url( $feat_thumb ); ?>" alt="<?php echo esc_attr( $feat_title ); ?>" loading="lazy" />
									<div class="ttw-featured-overlay"></div>
								</a>
							</div>
							<div class="ttw-featured-card">
								<div class="ttw-news-meta-row">
									<span class="ttw-news-tag"><?php echo esc_html( $feat_cat ); ?></span>
									<span class="ttw-news-date"><?php echo esc_html( $feat_date ); ?></span>
								</div>
								<h2 class="ttw-featured-title">
									<a href="<?php echo esc_url( $feat_link ); ?>"><?php echo esc_html( $feat_title ); ?></a>
								</h2>
								<p class="ttw-featured-desc"><?php echo esc_html( $feat_desc ); ?></p>
								<a href="<?php echo esc_url( $feat_link ); ?>" class="ttw-news-readmore">
									<span>ĐỌC THÊM</span>
									<svg width="12" height="12" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<path d="M8.3703 6.18749H0V4.81249H8.3703L4.5203 0.962498L5.49999 0L11 5.49999L5.49999 11L4.5203 10.0375L8.3703 6.18749Z" fill="currentColor"/>
									</svg>
								</a>
							</div>
						</article>

					<?php endif; ?>

					<!-- News Grid 3 Columns -->
					<section class="ttw-news-grid" aria-label="Danh sách tin tức">
						<?php
						$card_posts = ( $featured_post ) ? $grid_posts : $news_query->posts;
						foreach ( $card_posts as $g_post ) :
							$p_id    = $g_post->ID;
							$p_title = get_the_title( $p_id );
							$p_link  = get_permalink( $p_id );
							$p_date  = get_the_date( 'd.m.Y', $p_id );
							$p_thumb = get_the_post_thumbnail_url( $p_id, 'large' );
							if ( ! $p_thumb ) {
								$p_thumb = get_stylesheet_directory_uri() . '/assets/img/design/news1.jpg';
							}
							$g_cats = get_the_category( $p_id );
							$p_cat  = ( ! empty( $g_cats ) ) ? mb_strtoupper( $g_cats[0]->name, 'UTF-8' ) : 'TIN TỨC & SỰ KIỆN';
							?>
							<article class="ttw-news-card ttw-animate ttw-fade-up">
								<div class="ttw-news-content">
									<a class="ttw-news-thumb" href="<?php echo esc_url( $p_link ); ?>" title="<?php echo esc_attr( $p_title ); ?>">
										<img src="<?php echo esc_url( $p_thumb ); ?>" alt="<?php echo esc_attr( $p_title ); ?>" loading="lazy" />
									</a>
									<div class="ttw-news-meta-row">
										<span class="ttw-news-tag"><?php echo esc_html( $p_cat ); ?></span>
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


						<?php endforeach; ?>
					</section>

				<?php else : ?>
					<div class="ttw-news-empty">
						<p>Chưa có bài viết tin tức nào trong mục này.</p>
					</div>
				<?php endif; ?>

				<!-- News Pagination -->
				<?php if ( $news_query->max_num_pages > 1 ) : ?>
					<nav class="ttw-news-pagination ttw-animate ttw-fade-up" aria-label="Phân trang tin tức">
						<?php
						$total_pages = $news_query->max_num_pages;
						$page_base   = ( 'all' !== $ttw_curr_cat ) ? add_query_arg( 'cat', $ttw_curr_cat, $base_url ) : $base_url;

						for ( $i = 1; $i <= $total_pages; $i++ ) :
							$page_url  = ( 1 === $i ) ? $page_base : add_query_arg( 'paged', $i, $page_base );
							$is_active = ( $i === $ttw_paged );
							?>
							<a href="<?php echo esc_url( $page_url ); ?>"
							   class="ttw-news-page-btn<?php echo $is_active ? ' active' : ''; ?>"
							   <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
								<?php echo $i; ?>
							</a>
						<?php endfor; ?>

						<?php if ( $ttw_paged < $total_pages ) : ?>
							<a href="<?php echo esc_url( add_query_arg( 'paged', $ttw_paged + 1, $page_base ) ); ?>"
							   class="ttw-news-page-btn"
							   aria-label="Trang tiếp theo">
								<svg width="6" height="10" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M3.06667 4L0 0.933333L0.933333 0L4.93333 4L0.933333 8L0 7.06667L3.06667 4Z" fill="currentColor"/>
								</svg>
							</a>
						<?php endif; ?>
					</nav>
				<?php endif; ?>

		<?php
		return ob_get_clean();
	} );










	// Shortcode Product Archive (Danh sách sản phẩm bento + lọc DB + phân trang)
	add_shortcode( 'ttw_product_archive', function( $atts ) {
		$a = shortcode_atts( array(
			'posts_per_page' => '6',
			'count'          => '',
			'orderby'        => 'date',
			'order'          => 'DESC',
		), $atts );

		$per_page = ! empty( $a['count'] ) ? intval( $a['count'] ) : intval( $a['posts_per_page'] );
		if ( $per_page <= 0 ) {
			$per_page = 6;
		}

		$ttw_paged    = isset( $_GET['paged'] ) && (int) $_GET['paged'] > 0 ? (int) $_GET['paged'] : 1;
		$ttw_curr_cat = isset( $_GET['cat'] ) ? sanitize_text_field( $_GET['cat'] ) : 'all';


		$ttw_db_terms = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'exclude'    => array( 28, 66, 56, 57 ),
		) );

		$ttw_categories = array(
			array( 'slug' => 'all', 'name' => 'Tất cả' ),
		);

		if ( ! empty( $ttw_db_terms ) && ! is_wp_error( $ttw_db_terms ) ) {
			foreach ( $ttw_db_terms as $term_obj ) {
				$ttw_categories[] = array(
					'slug' => $term_obj->slug,
					'name' => $term_obj->name,
				);
			}
		}

		$query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $ttw_paged,
			'orderby'        => sanitize_key( $a['orderby'] ),
			'order'          => strtoupper( sanitize_key( $a['order'] ) ),
		);



		if ( 'all' !== $ttw_curr_cat && ! empty( $ttw_curr_cat ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $ttw_curr_cat,
				),
			);
		}

		$ttw_products_query = new WP_Query( $query_args );

		ob_start();
		?>
		<div class="ttw-products-container" style="padding-top:0;">
			<nav class="ttw-category-nav ttw-animate ttw-fade-up" aria-label="Danh mục sản phẩm">

			<ul class="ttw-category-list" id="ttw-category-filter">
				<?php
				$base_url = strtok( get_permalink(), '?' );
				foreach ( $ttw_categories as $ttw_cat ) :
					$cat_slug   = $ttw_cat['slug'];
					$is_cat_act = ( $ttw_curr_cat === $cat_slug );
					$cat_url    = ( 'all' === $cat_slug ) ? $base_url : add_query_arg( 'cat', $cat_slug, $base_url );
					?>
					<li class="ttw-category-item">
						<a href="<?php echo esc_url( $cat_url ); ?>"
						   class="ttw-category-tab<?php echo $is_cat_act ? ' active' : ''; ?>">
							<?php echo esc_html( $ttw_cat['name'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<section class="ttw-product-grid" id="ttw-product-grid" aria-label="Danh sách thẻ sản phẩm">
			<?php if ( $ttw_products_query->have_posts() ) : ?>
				<?php while ( $ttw_products_query->have_posts() ) : $ttw_products_query->the_post(); ?>
					<?php
					$prod_id    = get_the_ID();
					$prod_title = get_the_title();
					$prod_link  = get_permalink();
					$prod_thumb = get_the_post_thumbnail_url( $prod_id, 'medium_large' );
					if ( ! $prod_thumb ) {
						$prod_thumb = get_stylesheet_directory_uri() . '/assets/img/design/hero-bg.jpg';
					}
					$prod_excerpt = wp_trim_words( get_the_excerpt(), 20 );
					if ( ! $prod_excerpt ) {
						$prod_excerpt = 'Giải pháp nhôm kính cao cấp Tân Tiến Window, thiết kế hiện đại, bền bỉ và thẩm mỹ cao.';
					}
					$terms = get_the_terms( $prod_id, 'product_cat' );
					$tag   = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? mb_strtoupper( $terms[0]->name, 'UTF-8' ) : 'HỆ NHÔM CAO CẤP';
					?>
					<article class="ttw-card-bento ttw-animate ttw-fade-up">
						<a class="ttw-card-thumb" href="<?php echo esc_url( $prod_link ); ?>" title="<?php echo esc_attr( $prod_title ); ?>">
							<img src="<?php echo esc_url( $prod_thumb ); ?>" alt="<?php echo esc_attr( $prod_title ); ?>" loading="lazy" />
						</a>

						<div class="ttw-card-content">
							<span class="ttw-card-tag"><?php echo esc_html( $tag ); ?></span>
							<h3 class="ttw-card-title">
								<a href="<?php echo esc_url( $prod_link ); ?>"><?php echo esc_html( $prod_title ); ?></a>
							</h3>
							<p class="ttw-card-desc"><?php echo esc_html( $prod_excerpt ); ?></p>
							
							<div class="ttw-card-footer">
								<a class="ttw-card-action" href="<?php echo esc_url( $prod_link ); ?>">
									<span>XEM CHI TIẾT</span>
									<svg class="ttw-card-arrow" width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<path d="M8.3703 6.18749H0V4.81249H8.3703L4.5203 0.962498L5.49999 0L11 5.49999L5.49999 11L4.5203 10.0375L8.3703 6.18749Z" fill="currentColor"/>
									</svg>
								</a>
							</div>
						</div>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<div class="ttw-filter-empty" style="grid-column: 1 / -1;">
					<p>Không tìm thấy sản phẩm nào trong danh mục này.</p>
				</div>
			<?php endif; ?>
		</section>

		<?php if ( $ttw_products_query->max_num_pages > 1 ) : ?>
			<nav class="ttw-pagination-figma ttw-animate ttw-fade-up" aria-label="Phân trang sản phẩm">
				<?php
				$total_pages = $ttw_products_query->max_num_pages;
				$page_base   = ( 'all' !== $ttw_curr_cat ) ? add_query_arg( 'cat', $ttw_curr_cat, $base_url ) : $base_url;

				for ( $i = 1; $i <= $total_pages; $i++ ) :
					$page_url  = ( 1 === $i ) ? $page_base : add_query_arg( 'paged', $i, $page_base );
					$is_active = ( $i === $ttw_paged );
					?>
					<a href="<?php echo esc_url( $page_url ); ?>"
					   class="ttw-page-btn<?php echo $is_active ? ' active' : ''; ?>"
					   <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
						<?php echo $i; ?>
					</a>
				<?php endfor; ?>

				<?php if ( $ttw_paged < $total_pages ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'paged', $ttw_paged + 1, $page_base ) ); ?>"
					   class="ttw-page-btn ttw-page-next"
					   aria-label="Trang tiếp theo">
						<svg width="6" height="10" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M3.06667 4L0 0.933333L0.933333 0L4.93333 4L0.933333 8L0 7.06667L3.06667 4Z" fill="currentColor"/>
						</svg>
					</a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	} );



	// Shortcode Partners (Container)

	add_shortcode( 'ttw_partners', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'heading' => 'Đối tác của chúng tôi',
		), $atts );

		$inner_content = ! empty( $content ) ? do_shortcode( $content ) : '';

		// Nếu chưa có item nào, dùng 9 logo mặc định
		if ( empty( trim( $inner_content ) ) ) {
			$default_pids = array(2403, 2419, 2418, 2417, 2416, 2415, 2406, 2405, 2404);
			foreach ($default_pids as $pid) {
				$url = wp_get_attachment_image_url($pid, 'full');
				if ($url) {
					$inner_content .= sprintf(
						'<span class="ttw-partner"><img src="%s" alt="%s" loading="lazy"></span>',
						esc_url($url),
						esc_attr(get_the_title($pid))
					);
				}
			}
		}

		ob_start();
		?>
		<section class="ttw-section ttw-partners">
			<div class="container">
				<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up"><?php echo esc_html($a['heading']); ?></h2>
				<div class="ttw-partners-divider ttw-animate ttw-fade-in"></div>
				<div class="ttw-partners-track">
					<div class="ttw-partners-slide">
						<?php echo $inner_content; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode Partner Item (Child)
	add_shortcode( 'ttw_partner_item', function( $atts ) {
		$a = shortcode_atts( array(
			'image' => '',
		), $atts );

		$img_url = '';
		if ( ! empty( $a['image'] ) ) {
			if ( is_numeric( $a['image'] ) ) {
				$src = wp_get_attachment_image_src( $a['image'], 'full' );
				if ( $src ) {
					$img_url = $src[0];
				}
			} else {
				$img_url = $a['image'];
			}
		}

		if ( empty( $img_url ) ) {
			return '';
		}

		ob_start();
		?>
		<span class="ttw-partner"><img src="<?php echo esc_url($img_url); ?>" alt="Partner Logo" loading="lazy"></span>
		<?php
		return ob_get_clean();
	} );


	// Shortcode Testimonials (Container)
	add_shortcode( 'ttw_testimonials', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'heading' => 'Góc nhìn người tiêu dùng',
			'desc'    => 'Sự hài lòng của khách hàng là minh chứng rõ nét nhất cho chất lượng dịch vụ của chúng tôi.',
		), $atts );

		$inner_content = ! empty( $content ) ? do_shortcode( $content ) : '';

		// Nếu chưa có item nào hoặc chưa truyền thuộc tính image, hiển thị 6 đánh giá kèm ảnh công trình
		if ( empty( trim( $inner_content ) ) ) {
			$default_testimonials = array(
				array('Anh Hưởng', 'Q.Đống Đa, Hà Nội', 'Tôi rất vui và hài lòng với lựa chọn của mình, sau khi nhận bàn giao hệ thống cửa nhôm kính cao cấp của Tân Tiến.', '2721'),
				array('Anh Thắng', 'Sóc Sơn, Hà Nội', 'Là công trình mặt dựng ngắt tầng khổ lớn nhưng tôi thấy hoàn toàn yên tâm về kỹ thuật và độ an toàn.', '2695'),
				array('CTY. An Phước', 'Chủ đầu tư tòa nhà', 'Sản phẩm công trình mặt dựng thông tầng chất lượng và sang trọng, đội ngũ TanTienwindow làm việc rất chuyên nghiệp.', '2696'),
				array('Anh Minh Tuấn', 'Chủ đầu tư biệt thự Vinhomes', 'Hệ thống cửa nhôm Xingfa cao cấp được thi công hoàn hảo, cách âm cách nhiệt cực tốt cho biệt thự.', '2697'),
				array('Chị Lan Hương', 'Giám đốc dự án Tech Office', 'Vách kính mặt dựng cho tòa nhà văn phòng được thi công chuẩn tiến độ, chất lượng kính tuyệt vời.', '2698'),
				array('Anh Hoàng Quân', 'Quản lý dự án Ocean Resort', 'Giải pháp lan can kính và cửa nhôm kính lùa panorama rất phù hợp với kiến trúc resort hiện đại.', '2724'),
			);
			foreach ($default_testimonials as $t) {
				$img_src = wp_get_attachment_image_src( $t[3], 'large' );
				$img_url = $img_src ? $img_src[0] : '';
				$img_html = $img_url ? sprintf('<div class="ttw-quote-img-box"><img src="%s" alt="%s" loading="lazy"></div>', esc_url($img_url), esc_attr($t[0])) : '';
				$inner_content .= sprintf(
					'<div class="ttw-quote"><svg class="ttw-quote-mark" width="34" height="26" viewBox="0 0 45 32" fill="currentColor" aria-hidden="true"><path d="M0 32V20.4C0 9.1 6.1 2.7 17.6 0l2.1 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H0zm25.3 0V20.4C25.3 9.1 31.4 2.7 42.9 0L45 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H25.3z" /></svg><p class="ttw-quote-text">%s</p>%s<div class="ttw-quote-author"><strong>%s</strong><span>%s</span></div></div>',
					esc_html($t[2]),
					$img_html,
					esc_html($t[0]),
					esc_html(strtoupper($t[1]))
				);
			}
		}



		ob_start();
		?>
		<section class="ttw-section ttw-section-gray" id="danh-gia">
			<div class="container">
				<div class="ttw-projects-head ttw-animate ttw-fade-up">
					<h2 class="ttw-section-title"><?php echo esc_html($a['heading']); ?></h2>
					<p><?php echo esc_html($a['desc']); ?></p>
				</div>
				<div class="ttw-quotes-wrapper" style="position: relative;">
					<button type="button" class="ttw-slider-arrow ttw-slider-arrow-prev ttw-slider-prev" aria-label="Đánh giá trước">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M15 18l-6-6 6-6" />
						</svg>
					</button>
					<div class="ttw-quotes ttw-animate ttw-fade-up">
						<?php echo $inner_content; ?>
					</div>
					<button type="button" class="ttw-slider-arrow ttw-slider-arrow-next ttw-slider-next" aria-label="Đánh giá tiếp theo">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<path d="M9 18l6-6-6-6" />
						</svg>
					</button>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );



	// Shortcode Testimonial Item (Child)
	add_shortcode( 'ttw_testimonial_item', function( $atts ) {
		$a = shortcode_atts( array(
			'image'  => '',
			'author' => 'Anh Minh Tuấn',
			'role'   => 'Chủ đầu tư biệt thự Vinhomes',
			'quote'  => 'Tôi rất ấn tượng với sự chuyên nghiệp...',
		), $atts );

		$img_url = '';
		if ( ! empty( $a['image'] ) ) {
			if ( is_numeric( $a['image'] ) ) {
				$src = wp_get_attachment_image_src( $a['image'], 'large' );
				if ( $src ) {
					$img_url = $src[0];
				}
			} else {
				$img_url = $a['image'];
			}
		}

		ob_start();
		?>
		<div class="ttw-quote">
			<svg class="ttw-quote-mark" width="34" height="26" viewBox="0 0 45 32" fill="currentColor" aria-hidden="true">
				<path d="M0 32V20.4C0 9.1 6.1 2.7 17.6 0l2.1 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H0zm25.3 0V20.4C25.3 9.1 31.4 2.7 42.9 0L45 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H25.3z" />
			</svg>
			<p class="ttw-quote-text"><?php echo esc_html($a['quote']); ?></p>
			
			<?php if ( ! empty( $img_url ) ) : ?>
				<div class="ttw-quote-img-box">
					<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $a['author'] ); ?>" loading="lazy">
				</div>
			<?php endif; ?>

			<div class="ttw-quote-author">
				<strong><?php echo esc_html($a['author']); ?></strong>
				<span><?php echo esc_html(strtoupper($a['role'])); ?></span>
			</div>
		</div>
		<?php
		return ob_get_clean();
	} );




	// Shortcode News (Container)
	add_shortcode( 'ttw_news', function( $atts, $content = null ) {
		$a = shortcode_atts( array(
			'heading' => 'Tin tức & Kiến thức',
			'count'   => '3',
			'orderby' => 'date',
			'order'   => 'DESC',
		), $atts );

		// Lưu trữ các ID bài viết từ các shortcode con [ttw_news_item_select]
		global $ttw_selected_news_ids;
		$ttw_selected_news_ids = array();

		if ( ! empty( $content ) ) {
			do_shortcode( $content );
		}

		$query_args = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( ! empty( $ttw_selected_news_ids ) ) {
			// Người dùng đã bấm + Thêm các bài viết cụ thể
			$query_args['post__in']       = $ttw_selected_news_ids;
			$query_args['orderby']        = 'post__in';
			$query_args['posts_per_page'] = count( $ttw_selected_news_ids );
		} else {
			// Người dùng không thêm bài cụ thể -> Tự động lấy theo số lượng count
			$query_args['posts_per_page'] = max( 1, intval( $a['count'] ) );
			$query_args['orderby']        = sanitize_key( $a['orderby'] );
			$query_args['order']          = strtoupper( sanitize_key( $a['order'] ) );
		}

		$q = new WP_Query( $query_args );
		if ( ! $q->have_posts() ) return '';
		ob_start();
		?>
		<section class="ttw-section ttw-section-gray" id="tin-tuc">
			<div class="container">
				<div class="ttw-section-row ttw-animate ttw-fade-up">
					<h2 class="ttw-section-title"><?php echo esc_html($a['heading']); ?></h2>
					<a class="ttw-textlink" href="<?php echo esc_url(ttw_news_url()); ?>">Xem tất cả
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M5 12h14" />
							<path d="M12 5l7 7-7 7" />
						</svg>
					</a>
				</div>
				<div class="ttw-news ttw-animate ttw-fade-up">
					<?php
					while ($q->have_posts()) :
						$q->the_post();
						$cat = get_the_category();
					?>
						<article class="ttw-news-item">
							<a class="ttw-news-thumb" href="<?php the_permalink(); ?>">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail('ttw-card', array('loading' => 'lazy')); ?>
								<?php endif; ?>
							</a>
							<div class="ttw-news-body">
								<div class="ttw-news-meta">
									<span class="ttw-news-cat"><?php echo $cat ? esc_html(strtoupper($cat[0]->name)) : ''; ?></span>
									<span class="ttw-news-date"><?php echo esc_html(get_the_date('d M, Y')); ?></span>
								</div>
								<h3 class="ttw-news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<a class="ttw-textlink ttw-news-more" href="<?php the_permalink(); ?>">Đọc thêm
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M5 12h14" />
										<path d="M12 5l7 7-7 7" />
									</svg>
								</a>
							</div>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode News Item Select (Child)
	add_shortcode( 'ttw_news_item_select', function( $atts ) {
		$a = shortcode_atts( array(
			'post_id' => '',
		), $atts );

		global $ttw_selected_news_ids;
		if ( ! empty( $a['post_id'] ) ) {
			$ttw_selected_news_ids[] = intval( $a['post_id'] );
		}
		return '';
	} );


}
add_action( 'init', 'ttw_register_shortcodes' );



