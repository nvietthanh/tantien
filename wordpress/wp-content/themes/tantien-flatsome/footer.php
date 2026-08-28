<?php
/**
 * Footer template for tantien-flatsome.
 *
 * @package          TantienFlatsome
 * @flatsome-version 3.16.0
 */
?>
	<footer class="ttw-footer">
		<div class="container">
			<!-- Khối CTA tinh gọn, hiện đại -->
			<div class="ttw-footer-cta-band">
				<div class="ttw-footer-cta-text">
					<h3>TƯ VẤN & BÁO GIÁ NHÔM KÍNH TRỰC TIẾP</h3>
					<p>Liên hệ ngay với đội ngũ kỹ thuật Tân Tiến Window để nhận giải pháp tối ưu cho công trình của bạn.</p>
				</div>
				<div class="ttw-footer-cta-buttons">
					<a class="ttw-footer-cta-btn ttw-cta-call" href="<?php echo esc_url( ttw_phone_link() ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						0907.247.111
					</a>
					<a class="ttw-footer-cta-btn ttw-cta-zalo" href="<?php echo esc_url( ttw_zalo_link() ); ?>" target="_blank" rel="noopener">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.04 2 11c0 2.9 1.42 5.46 3.66 7.2V22l3.46-1.9c.9.25 1.86.39 2.88.39 5.52 0 10-4.04 10-9.02S17.52 2 12 2z"/></svg>
						Chat Zalo
					</a>
					<a class="ttw-footer-cta-btn ttw-cta-fb" href="https://facebook.com/tantienwindow" target="_blank" rel="noopener">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
						Fanpage
					</a>
				</div>
			</div>

			<!-- Bố cục 4 cột Footer hài hòa, liền mạch -->
			<div class="ttw-footer-unified-grid">
				<!-- Cột 1: Thương hiệu -->
				<div class="ttw-fcol ttw-fcol-brand">
					<div class="ttw-footer-logo">Tân Tiến Window</div>
					<div class="ttw-fcompany-name">CÔNG TY CP XÂY DỰNG PHÁT TRIỂN &amp; ĐẦU TƯ TÂN TIẾN</div>
					<p class="ttw-fcompany-desc">Đơn vị chuyên sâu về thiết kế, sản xuất và thi công tổng thể hệ cửa nhôm kính cao cấp, vách kính mặt dựng và kính kiến trúc tại Việt Nam.</p>
				</div>

				<!-- Cột 2: Điều hướng & Dịch vụ -->
				<div class="ttw-fcol ttw-fcol-nav">
					<h4 class="ttw-fcol-title">Khám Phá</h4>
					<ul class="ttw-flinks">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a></li>
						<li><a href="<?php echo esc_url( home_url( '/gioi-thieu/' ) ); ?>">Về chúng tôi</a></li>
						<li><a href="<?php echo esc_url( home_url( '/ban-sac-van-hoa/' ) ); ?>">Bản sắc văn hóa</a></li>
						<li><a href="<?php echo esc_url( ttw_shop_url() ); ?>">Sản phẩm &amp; Hệ cửa</a></li>
						<li><a href="<?php echo esc_url( ttw_projects_url() ); ?>">Công trình tiêu biểu</a></li>
						<li><a href="<?php echo esc_url( home_url( '/quy-dinh-bao-hanh/' ) ); ?>">Quy định bảo hành</a></li>
					</ul>
				</div>

				<!-- Cột 3: Địa chỉ & Nhà máy -->
				<div class="ttw-fcol ttw-fcol-locations">
					<h4 class="ttw-fcol-title">Hệ Thống Trụ Sở</h4>
					<div class="ttw-floc-item">
						<span class="ttw-floc-tag">VPGD HÀ NỘI</span>
						<p>Khu biệt thự liền kề số 162 Khuất Duy Tiến, Thanh Xuân, Hà Nội</p>
					</div>
					<div class="ttw-floc-item">
						<span class="ttw-floc-tag">CƠ SỞ 2 (NINH BÌNH)</span>
						<p>Số 56, Quốc Lộ 21 Nam Hồng, Ninh Bình &bull; <a href="tel:0919856295">0919.856.295</a></p>
					</div>
					<div class="ttw-floc-item">
						<span class="ttw-floc-tag">NHÀ MÁY SẢN XUẤT</span>
						<p>Sóc Sơn - Hà Nội. Quy mô cung ứng toàn diện các dự án trên toàn quốc.</p>
					</div>
				</div>

				<!-- Cột 4: Tổng đài tư vấn -->
				<div class="ttw-fcol ttw-fcol-contact">
					<h4 class="ttw-fcol-title">Tư Vấn Trực Tiếp</h4>
					<div class="ttw-fhotline-card">
						<div class="ttw-fhl-badge">HOTLINE 24/7</div>
						<a href="tel:0907247111" class="ttw-fhl-phone">0907.247.111</a>
					</div>
					
					<div class="ttw-fsupport-cards">
						<div class="ttw-fsupp-card">
							<span>Kỹ Thuật Viên</span>
							<a href="tel:0943529111">0943.529.111</a>
						</div>
						<div class="ttw-fsupp-card">
							<span>Tư Vấn Bán Hàng</span>
							<a href="tel:0901515695">090.1515.695</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Bản quyền chân trang -->
		<div class="ttw-footer-bottom">
			<div class="container">
				<p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <strong>Tân Tiến Window</strong>. Architectural Precision in Glass &amp; Aluminum.</p>
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
