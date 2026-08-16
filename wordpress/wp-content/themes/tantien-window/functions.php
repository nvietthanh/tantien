<?php
/**
 * Tân Tiến Window theme functions.
 *
 * @package TantienWindow
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TTW_VERSION', '1.0.0' );

/**
 * Theme setup.
 */
function ttw_setup() {
	load_theme_textdomain( 'tantien-window', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	add_theme_support( 'customize-selective-refresh-widgets' );

	// WooCommerce.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus( array(
		'primary'      => __( 'Menu chính', 'tantien-window' ),
		'primary_mobile' => __( 'Menu mobile', 'tantien-window' ),
		'footer'       => __( 'Menu footer', 'tantien-window' ),
	) );

	add_image_size( 'ttw-card', 640, 480, true );
	add_image_size( 'ttw-card-16x10', 640, 400, true );
	add_image_size( 'ttw-hero', 1600, 700, true );
}
add_action( 'after_setup_theme', 'ttw_setup' );

/**
 * Content width.
 */
function ttw_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ttw_content_width', 780 );
}
add_action( 'after_setup_theme', 'ttw_content_width', 0 );

/**
 * Enqueue scripts & styles.
 */
function ttw_scripts() {
	wp_enqueue_style(
		'ttw-google-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Inter:wght@400;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'ttw-style',
		get_stylesheet_uri(),
		array( 'ttw-google-fonts' ),
		TTW_VERSION
	);

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'ttw-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce.css', array( 'ttw-style' ), TTW_VERSION );
	}

	wp_enqueue_script(
		'ttw-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		TTW_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ttw_scripts' );

/**
 * Widget areas.
 */
function ttw_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar blog', 'tantien-window' ),
		'id'            => 'sidebar-blog',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Sidebar shop', 'tantien-window' ),
		'id'            => 'sidebar-shop',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 1', 'tantien-window' ),
		'id'            => 'footer-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 2', 'tantien-window' ),
		'id'            => 'footer-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer 3', 'tantien-window' ),
		'id'            => 'footer-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'ttw_widgets_init' );

/**
 * Company helpers.
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

/**
 * Fallback menu khi chưa gán menu chính (theo nav trên Figma).
 */
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

/**
 * Logo: custom logo hoặc fallback text.
 */
function ttw_logo() {
	$logo = get_custom_logo();
	if ( $logo ) {
		return $logo;
	}

	$site_url  = esc_url( home_url( '/' ) );
	$site_name = esc_html( apply_filters( 'ttw_site_name', 'Tân Tiến Window' ) );
	$logo_url  = get_template_directory_uri() . '/assets/img/logo/logo.svg';

	return '<a class="ttw-logo" href="' . $site_url . '"><img src="' . esc_url( $logo_url ) . '" alt="' . $site_name . '" /></a>';
}

/**
 * Excerpt fallback cho sản phẩm.
 */
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

/**
 * Breadcrumb (Yoast nếu có, ngược lại fallback).
 */
function ttw_breadcrumb() {
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<div class="ttw-breadcrumb">', '</div>' );
		return;
	}
	echo '<div class="ttw-breadcrumb"><a href="' . esc_url( home_url( '/' ) ) . '">Trang chủ</a></div>';
}

/**
 * Flatsome/UX Builder shortcode cleanup cho nội dung page cũ.
 * Chuyển shortcode Flatsome còn sót thành HTML đọc được thay vì in raw text.
 */
function ttw_strip_legacy_shortcodes( $content ) {
	if ( ! is_admin() && false !== strpos( $content, '[' ) ) {
		$content = preg_replace( '/\[ux_products[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[blog_posts[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[ux_gallery[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[ux_slider[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[(?:ux_)?image_box[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[(?:ux_)?video[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[\/(?:ux_slider|ux_gallery|tabgroup|tab|section|row_inner|col_inner|ux_image_box|ux_video)\]/i', '', $content );
		$content = preg_replace( '/\[(?:tabgroup|tab|title|ux_text|button|row_inner|col_inner|divider|gap|logo|ux_image)\s[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[(?:section|row|col)\b[^\]]*\]/i', '', $content );
		$content = preg_replace( '/\[\/?(?:ux_products|blog_posts)\]/i', '', $content );
		$content = preg_replace( '/\[(?:gap|divider)\]/i', '', $content );
		$content = preg_replace( '/\[[a-z0-9_]+\s[^\]]*\]|\[\/[a-z0-9_]+\]/i', '', $content );
		$content = preg_replace( '/\[[a-z0-9_-]{2,30}\]/i', '', $content );
	}
	return $content;
}
add_filter( 'the_content', 'ttw_strip_legacy_shortcodes', 1 );

/**
 * Header image nếu có thể tùy biến.
 */
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
