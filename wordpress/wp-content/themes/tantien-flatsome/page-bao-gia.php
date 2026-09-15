<?php
/**
 * Template Name: Báo Giá Page
 * Template for /bao-gia/
 *
 * @package TantienFlatsome
 */

get_header();

$quote_page = get_page_by_path( 'bao-gia' );
$page_content = $quote_page ? $quote_page->post_content : '';

if ( empty( trim( $page_content ) ) ) {
	$page_content = '[ttw_quote_hero][ttw_quote_title text="BÁO GIÁ"][ttw_quote_subtitle text="BẢNG BÁO GIÁ THI CÔNG CỬA NHÔM KÍNH & KÍNH KIẾN TRÚC MỚI NHẤT"][/ttw_quote_hero][ttw_quote_archive count="6" posts_per_page="6"]';
}
?>

<div class="ttw-quote-page">
	<?php echo do_shortcode( $page_content ); ?>
</div>

<?php
get_footer();
