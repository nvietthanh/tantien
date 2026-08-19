<?php
/**
 * Theme header.
 *
 * @package TantienWindow
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="ttw-site">

	<header class="ttw-header" id="ttw-header">
		<div class="ttw-header-inner">
			<?php echo ttw_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?>

			<nav class="ttw-nav" aria-label="<?php esc_attr_e( 'Menu chính', 'tantien-window' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => '',
					'fallback_cb'    => 'ttw_primary_menu_fallback',
					'depth'          => 2,
				) );
				?>
			</nav>

			<div class="ttw-header-actions">
				<a class="ttw-header-cta" href="<?php echo esc_url( ttw_consult_url() ); ?>">Nhận tư vấn</a>

				<button class="ttw-menu-toggle" id="ttw-menu-toggle" aria-label="Menu" aria-expanded="false">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
				</button>
			</div>
		</div>

		<nav class="ttw-mobile-nav" id="ttw-mobile-nav" aria-label="Menu mobile">
			<div class="ttw-mobile-nav-header">
				<?php echo ttw_logo(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<button class="ttw-mobile-nav-close" id="ttw-mobile-nav-close" aria-label="Đóng menu">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
				</button>
			</div>
			<div class="ttw-mobile-nav-menu">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary_mobile',
					'container'      => false,
					'menu_class'     => '',
					'fallback_cb'    => 'ttw_primary_menu_fallback',
					'depth'          => 2,
				) );
				?>
			</div>
			<div class="ttw-mobile-nav-footer">
				<a class="ttw-mobile-nav-cta" href="<?php echo esc_url( ttw_consult_url() ); ?>">Nhận tư vấn</a>
			</div>
		</nav>
	</header>
