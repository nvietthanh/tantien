<?php
/**
 * WooCommerce wrapper - dùng cho shop, cart, checkout, account.
 *
 * @package TantienWindow
 */

get_header();
?>

<?php if ( ! is_product() ) : ?>
	<div class="ttw-page-hero">
		<div class="container">
			<h1><?php echo esc_html( ttw_page_hero_title() ); ?></h1>
			<?php ttw_breadcrumb(); ?>
		</div>
	</div>
<?php endif; ?>

<div class="ttw-content-area">
	<div class="container">
		<main class="ttw-main">
			<?php woocommerce_content(); ?>
		</main>
	</div>
</div>

<?php
get_footer();
