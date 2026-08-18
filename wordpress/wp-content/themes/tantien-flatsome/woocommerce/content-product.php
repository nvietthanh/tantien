<?php
/**
 * Product card (loop).
 *
 * @package TantienWindow
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'ttw-product', $product ); ?>>
	<a class="ttw-product-thumb" href="<?php the_permalink(); ?>">
		<?php echo $product->get_image( 'ttw-card' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</a>
	<div class="ttw-product-body">
		<h3 class="ttw-product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php
		if ( $price_html = $product->get_price_html() ) :
			?>
			<div class="ttw-product-price"><?php echo wp_kses_post( $price_html ); ?></div>
		<?php endif; ?>
		<?php
		// Nút xem chi tiết - custom, không dùng add to cart trực tiếp vì site catalog-mode.
		?>
		<a class="ttw-product-btn" href="<?php the_permalink(); ?>">Xem chi tiết</a>
	</div>
</li>
