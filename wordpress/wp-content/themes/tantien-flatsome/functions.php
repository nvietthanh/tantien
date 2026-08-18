<?php
/**
 * Tân Tiến Window - Flatsome Child Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TTW_FLATSOME_VERSION', '1.0.2' );


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
	return class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/san-pham-2/' );
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
		array( home_url( '/gioi-thieu/' ), __( 'Giới thiệu', 'tantien-window' ) ),
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
[ttw_about_eyebrow text="About Tan Tien Window"]
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
				'default' => 'About Tan Tien Window',
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
		'name'     => __( 'Tân Tiến - Sản Phẩm Nổi Bật' ),
		'category' => __( 'Tân Tiến Window' ),
		'options'  => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Sản phẩm nổi bật',
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
		'name'      => __( 'Tân Tiến - Giá Trị Cốt Lõi' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_value_item' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Giá trị cốt lõi',
			),
		),
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định (4 mục)' ),
				'content' => '[ttw_values heading="Giá trị cốt lõi"]
[ttw_value_item num="01" title="Chất lượng" desc="Cam kết sử dụng vật liệu cao cấp, đảm bảo độ bền và tính thẩm mỹ lâu dài cho mọi công trình."]
[ttw_value_item num="02" title="Kinh nghiệm" desc="Đội ngũ kỹ thuật viên lành nghề với hơn 10 năm kinh nghiệm trong lĩnh vực nhôm kính."]
[ttw_value_item num="03" title="Thi công" desc="Quy trình lắp đặt chuẩn xác, an toàn, đảm bảo tiến độ và vệ sinh công trình."]
[ttw_value_item num="04" title="Đồng hành" desc="Chính sách bảo hành dài hạn, hỗ trợ kỹ thuật nhanh chóng và tận tâm."]
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
		'name'     => __( 'Tân Tiến - Công Trình Tiêu Biểu' ),
		'category' => __( 'Tân Tiến Window' ),
		'options'  => array(
			'heading' => array( 'type' => 'textfield', 'heading' => 'Tiêu đề khối', 'default' => 'Công trình tiêu biểu' ),
			'desc'    => array( 'type' => 'textfield', 'heading' => 'Mô tả ngắn', 'default' => 'Những công trình thể hiện chất lượng và thẩm mỹ trong từng chi tiết.' ),
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
				'name'    => __( 'Mặc định (3 đánh giá)' ),
				'content' => '[ttw_testimonials heading="Góc nhìn người tiêu dùng" desc="Sự hài lòng của khách hàng là minh chứng rõ nét nhất cho chất lượng dịch vụ của chúng tôi."]
[ttw_testimonial_item author="Anh Minh Tuấn" role="Chủ đầu tư biệt thự Vinhomes" quote="Tôi rất ấn tượng với sự chuyên nghiệp của đội ngũ Tân Tiến. Hệ thống cửa nhôm Xingfa được lắp đặt hoàn hảo, cách âm cực tốt và mang lại vẻ đẹp hiện đại cho ngôi nhà."]
[ttw_testimonial_item author="Chị Lan Hương" role="Giám đốc dự án Tech Office" quote="Vách kính mặt dựng cho tòa nhà văn phòng của chúng tôi được thi công đúng tiến độ, chất lượng kính tuyệt vời. Dịch vụ hậu mãi cũng rất tận tình."]
[ttw_testimonial_item author="Anh Hoàng Quân" role="Quản lý dự án Ocean Resort" quote="Tân Tiến Window đã tư vấn giải pháp lan can kính rất phù hợp với thiết kế tổng thể của resort. Khách hàng của chúng tôi rất thích không gian mở này."]
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
		'type'      => 'container',
		'name'      => __( 'Tân Tiến - Tin Tức & Kiến Thức' ),
		'category'  => __( 'Tân Tiến Window' ),
		'allow'     => array( 'ttw_news_item_select' ),
		'options'   => array(
			'heading' => array(
				'type'    => 'textfield',
				'heading' => __( 'Tiêu đề khối' ),
				'default' => 'Tin tức & Kiến thức',
			),
			'count' => array(
				'type'        => 'textfield',
				'heading'     => __( 'Số lượng lấy bài (Khi không chọn bài cụ thể)' ),
				'description' => __( 'Số bài tự động hiển thị khi bạn không thêm mục bài viết chỉ định.' ),
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
		'presets'   => array(
			array(
				'name'    => __( 'Mặc định (3 bài mới nhất)' ),
				'content' => '[ttw_news heading="Tin tức & Kiến thức" count="3"][/ttw_news]',
			),
		),
	) );

	add_ux_builder_shortcode( 'ttw_news_item_select', array(
		'name'      => __( 'Bài Viết Chỉ Định' ),
		'category'  => __( 'Tân Tiến Window' ),
		'require'   => array( 'ttw_news' ),
		'wrap'      => false,
		'options'   => array(
			'post_id' => array(
				'type'    => 'select',
				'heading' => __( 'Chọn Bài Viết Hiển Thị' ),
				'default' => '',
				'options' => ( function() {
					$opts = array( '' => __( '-- Chọn bài viết --' ) );
					$posts = get_posts( array(
						'numberposts' => 100,
						'post_status' => 'publish',
						'orderby'     => 'date',
						'order'       => 'DESC',
					) );
					foreach ( $posts as $p ) {
						$opts[ $p->ID ] = $p->post_title;
					}
					return $opts;
				} )(),
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
			'image'      => '',
			'bg_color'   => '',
			'padding'    => '',
			'custom_css' => '',
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
						<a class="ttw-textlink" href="<?php echo esc_url(home_url('/gioi-thieu/')); ?>">Tìm hiểu thêm
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
			'text'      => 'About Tan Tien Window',
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
			'heading' => 'Sản phẩm nổi bật',
			'count'   => '6',
			'orderby' => 'date',
			'order'   => 'DESC',
		), $atts );
		ob_start();
		?>
		<section class="ttw-section ttw-section-gray" id="san-pham">
			<div class="container">
				<div class="ttw-section-row ttw-animate ttw-fade-up">
					<h2 class="ttw-section-title"><?php echo esc_html($a['heading']); ?></h2>
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
			'heading' => 'Giá trị cốt lõi',
		), $atts );

		$inner_content = ! empty( $content ) ? do_shortcode( $content ) : '';

		// Nếu người dùng chưa thêm item nào, dùng 4 item mặc định
		if ( empty( trim( $inner_content ) ) ) {
			$default_items = array(
				array('01', 'Chất lượng', 'Cam kết sử dụng vật liệu cao cấp, đảm bảo độ bền và tính thẩm mỹ lâu dài cho mọi công trình.'),
				array('02', 'Kinh nghiệm', 'Đội ngũ kỹ thuật viên lành nghề với hơn 10 năm kinh nghiệm trong lĩnh vực nhôm kính.'),
				array('03', 'Thi công', 'Quy trình lắp đặt chuẩn xác, an toàn, đảm bảo tiến độ và vệ sinh công trình.'),
				array('04', 'Đồng hành', 'Chính sách bảo hành dài hạn, hỗ trợ kỹ thuật nhanh chóng và tận tâm.'),
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


	// Shortcode Projects
	add_shortcode( 'ttw_projects', function( $atts ) {
		$a = shortcode_atts( array(
			'heading' => 'Công trình tiêu biểu',
			'desc'    => 'Những công trình thể hiện chất lượng và thẩm mỹ trong từng chi tiết.',
		), $atts );

		$projects = array(
			array('proj1-villa', 'large', 'Biệt thự cao cấp', 'Ocean Villa Retreat', 'Đà Nẵng • Hệ cửa lùa panorama'),
			array('proj2-office', 'small', 'Văn phòng', 'Tech Hub Tower', 'TP.HCM • Vách kính mặt dựng'),
			array('proj3-apartment', 'small', 'Căn hộ', 'Skyrise Penthouse', 'Hà Nội • Cửa sổ cách âm'),
		);
		$img_uri = get_stylesheet_directory_uri() . '/assets/img/design/';
		ob_start();
		?>
		<section class="ttw-section ttw-section-gray" id="cong-trinh">
			<div class="container">
				<div class="ttw-projects-head ttw-animate ttw-fade-up">
					<h2 class="ttw-section-title"><?php echo esc_html($a['heading']); ?></h2>
					<p><?php echo esc_html($a['desc']); ?></p>
				</div>
				<div class="ttw-projects ttw-animate ttw-fade-up">
					<?php foreach ($projects as $project) : ?>
						<a class="ttw-project ttw-project-<?php echo esc_attr($project[1]); ?>" href="<?php echo esc_url(ttw_projects_url()); ?>">
							<img src="<?php echo esc_url($img_uri . $project[0] . '.jpg'); ?>" alt="<?php echo esc_attr($project[3]); ?>" loading="lazy">
							<div class="ttw-project-overlay"></div>
							<div class="ttw-project-body">
								<span class="ttw-project-cat"><?php echo esc_html($project[2]); ?></span>
								<h3><?php echo esc_html($project[3]); ?></h3>
								<p><?php echo esc_html($project[4]); ?></p>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
				<div class="ttw-projects-action">
					<a class="ttw-textlink" href="<?php echo esc_url(ttw_projects_url()); ?>">Xem tất cả dự án
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
					'<div class="ttw-step"><div class="ttw-step-num">%s</div><h3>%s</h3><p>%s</p></div>',
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
			<h3><?php echo esc_html($a['title']); ?></h3>
			<p><?php echo esc_html($a['desc']); ?></p>
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

		// Nếu chưa có item nào, dùng 3 đánh giá mặc định
		if ( empty( trim( $inner_content ) ) ) {
			$default_testimonials = array(
				array('Anh Minh Tuấn', 'Chủ đầu tư biệt thự Vinhomes', '“Tôi rất ấn tượng với sự chuyên nghiệp của đội ngũ Tân Tiến. Hệ thống cửa nhôm Xingfa được lắp đặt hoàn hảo, cách âm cực tốt và mang lại vẻ đẹp hiện đại cho ngôi nhà.”'),
				array('Chị Lan Hương', 'Giám đốc dự án Tech Office', '“Vách kính mặt dựng cho tòa nhà văn phòng của chúng tôi được thi công đúng tiến độ, chất lượng kính tuyệt vời. Dịch vụ hậu mãi cũng rất tận tình.”'),
				array('Anh Hoàng Quân', 'Quản lý dự án Ocean Resort', '“Tân Tiến Window đã tư vấn giải pháp lan can kính rất phù hợp với thiết kế tổng thể của resort. Khách hàng của chúng tôi rất thích không gian mở này.”'),
			);
			foreach ($default_testimonials as $t) {
				$inner_content .= sprintf(
					'<div class="ttw-quote"><svg class="ttw-quote-mark" width="34" height="26" viewBox="0 0 45 32" fill="currentColor" aria-hidden="true"><path d="M0 32V20.4C0 9.1 6.1 2.7 17.6 0l2.1 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H0zm25.3 0V20.4C25.3 9.1 31.4 2.7 42.9 0L45 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H25.3z" /></svg><p class="ttw-quote-text">%s</p><div class="ttw-quote-author"><strong>%s</strong><span>%s</span></div></div>',
					esc_html($t[2]),
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
				<div class="ttw-quotes ttw-animate ttw-fade-up">
					<?php echo $inner_content; ?>
				</div>
			</div>
		</section>
		<?php
		return ob_get_clean();
	} );

	// Shortcode Testimonial Item (Child)
	add_shortcode( 'ttw_testimonial_item', function( $atts ) {
		$a = shortcode_atts( array(
			'author' => 'Anh Minh Tuấn',
			'role'   => 'Chủ đầu tư biệt thự Vinhomes',
			'quote'  => 'Tôi rất ấn tượng với sự chuyên nghiệp...',
		), $atts );

		ob_start();
		?>
		<div class="ttw-quote">
			<svg class="ttw-quote-mark" width="34" height="26" viewBox="0 0 45 32" fill="currentColor" aria-hidden="true">
				<path d="M0 32V20.4C0 9.1 6.1 2.7 17.6 0l2.1 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H0zm25.3 0V20.4C25.3 9.1 31.4 2.7 42.9 0L45 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H25.3z" />
			</svg>
			<p class="ttw-quote-text"><?php echo esc_html($a['quote']); ?></p>
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



