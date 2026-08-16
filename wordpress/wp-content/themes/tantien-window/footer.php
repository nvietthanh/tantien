<?php
/**
 * Theme footer.
 *
 * @package TantienWindow
 */
?>
	<footer class="ttw-footer">
		<div class="container">
			<div class="ttw-footer-grid">
				<div class="ttw-footer-brand">
					<div class="ttw-footer-logo">Tân Tiến Window</div>
					<p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> Tân Tiến Window.<br>Architectural Precision in Glass &amp; Aluminum.</p>
				</div>

				<div class="ttw-footer-col">
					<h4>Menu</h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a></li>
						<li><a href="<?php echo esc_url( home_url( '/gioi-thieu/' ) ); ?>">Giới thiệu</a></li>
						<li><a href="<?php echo esc_url( ttw_shop_url() ); ?>">Sản phẩm</a></li>
						<li><a href="<?php echo esc_url( ttw_projects_url() ); ?>">Dự án</a></li>
					</ul>
				</div>

				<div class="ttw-footer-col">
					<h4>Support</h4>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/chinh-sach-bao-mat/' ) ); ?>">Chính sách bảo mật</a></li>
						<li><a href="<?php echo esc_url( home_url( '/chinh-sach-bao-hanh/' ) ); ?>">Điều khoản sử dụng</a></li>
						<li><a href="<?php echo esc_url( home_url( '/quy-dinh-bao-hanh/' ) ); ?>">Bảo hành</a></li>
					</ul>
				</div>

				<div class="ttw-footer-col">
					<h4>Contact</h4>
					<ul>
						<li><a href="<?php echo esc_url( ttw_phone_link() ); ?>"><?php echo esc_html( ttw_phone() ); ?></a></li>
						<li><a href="mailto:<?php echo esc_attr( ttw_email() ); ?>"><?php echo esc_html( ttw_email() ); ?></a></li>
						<li><a href="https://maps.google.com/?q=TP. Hồ Chí Minh, Việt Nam" target="_blank" rel="noopener">TP. Hồ Chí Minh, Việt Nam</a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>

	<div class="ttw-sticky-call">
		<a href="<?php echo esc_url( ttw_zalo_link() ); ?>" target="_blank" rel="noopener">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.04 2 11c0 2.9 1.42 5.46 3.66 7.2V22l3.46-1.9c.9.25 1.86.39 2.88.39 5.52 0 10-4.04 10-9.02S17.52 2 12 2z"/></svg>
			Chat Zalo
		</a>
		<a href="<?php echo esc_url( ttw_phone_link() ); ?>">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
			Gọi ngay
		</a>
	</div>

</div><!-- .ttw-site -->

<?php wp_footer(); ?>
</body>
</html>
