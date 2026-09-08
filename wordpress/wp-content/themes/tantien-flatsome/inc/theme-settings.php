<?php
/**
 * Theme Settings Module for Tân Tiến Window
 * Tách riêng:
 * 1. Menu "Cài đặt Tân Tiến" (Chính)
 * 2. Menu con "Cài đặt Logo"
 * 3. Menu con "Cài đặt Footer & Hotline"
 *
 * @package TantienFlatsome
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Đăng ký menu con bên trong menu "Giao diện" (Appearance) của WordPress
 */
add_action( 'admin_menu', 'ttw_register_theme_settings_menu' );
function ttw_register_theme_settings_menu() {
	// Menu con 1: Giao diện -> Cài đặt Logo
	add_theme_page(
		__( 'Cài đặt Logo', 'tantien-window' ),
		__( 'Cài đặt Logo', 'tantien-window' ),
		'manage_options',
		'ttw-logo-settings',
		'ttw_render_logo_settings_page'
	);

	// Menu con 2: Giao diện -> Cài đặt Footer & Hotline
	add_theme_page(
		__( 'Cài đặt Footer & Hotline', 'tantien-window' ),
		__( 'Cài đặt Footer & Hotline', 'tantien-window' ),
		'manage_options',
		'ttw-footer-settings',
		'ttw_render_footer_settings_page'
	);
}

/**
 * Đăng ký Settings qua Settings API
 */
add_action( 'admin_init', 'ttw_register_theme_settings' );
function ttw_register_theme_settings() {
	// Group Footer
	register_setting( 'ttw_footer_settings_group', 'ttw_footer_settings', 'ttw_sanitize_footer_settings' );
	// Group Logo & Slogan riêng
	register_setting( 'ttw_logo_settings_group', 'ttw_site_logo', 'esc_url_raw' );
	register_setting( 'ttw_logo_settings_group', 'ttw_site_logo_hover', 'esc_url_raw' );
	register_setting( 'ttw_logo_settings_group', 'ttw_site_slogan', 'sanitize_text_field' );
}

/**
 * Lấy cấu hình logo chính
 */
function ttw_get_logo_setting() {
	$logo = get_option( 'ttw_site_logo', '' );
	if ( empty( $logo ) ) {
		$footer_settings = get_option( 'ttw_footer_settings', array() );
		if ( ! empty( $footer_settings['site_logo'] ) ) {
			$logo = $footer_settings['site_logo'];
		}
	}
	return $logo;
}

/**
 * Lấy cấu hình logo khi hover (nếu có)
 */
function ttw_get_logo_hover_setting() {
	return get_option( 'ttw_site_logo_hover', '' );
}

/**
 * Lấy cấu hình slogan khi hover logo
 */
function ttw_get_slogan_setting() {
	$slogan = get_option( 'ttw_site_slogan', 'LUÔN ĐỔI MỚI TIẾN BỘ<br />KIẾN TẠO KHÔNG GIAN' );
	return $slogan;
}

/**
 * Hàm lấy cấu hình footer có fallback giá trị mặc định
 */
function ttw_get_footer_setting( $key = '', $default = '' ) {
	$defaults = array(
		// CTA Band
		'cta_title'         => 'TƯ VẤN & BÁO GIÁ NHÔM KÍNH TRỰC TIẾP',
		'cta_desc'          => 'Liên hệ ngay với đội ngũ kỹ thuật Tân Tiến Window để nhận giải pháp tối ưu cho công trình của bạn.',
		'cta_phone'         => '0907.247.111',
		'cta_zalo'          => 'https://zalo.me/0907247111',
		'cta_facebook'      => 'https://facebook.com/tantienwindow',

		// Cột 1: Thông tin công ty
		'company_brand'     => 'Tân Tiến Window',
		'company_name'      => 'CÔNG TY CP XÂY DỰNG PHÁT TRIỂN & ĐẦU TƯ TÂN TIẾN',
		'company_desc'      => 'Đơn vị chuyên sâu về thiết kế, sản xuất và thi công tổng thể hệ cửa nhôm kính cao cấp, vách kính mặt dựng và kính kiến trúc tại Việt Nam.',

		// Cột 3: Hệ thống trụ sở & nhà máy
		'loc_1_tag'         => 'VPGD HÀ NỘI',
		'loc_1_text'        => 'Khu biệt thự liền kề số 162 Khuất Duy Tiến, Thanh Xuân, Hà Nội',
		'loc_2_tag'         => 'CƠ SỞ 2 (NINH BÌNH)',
		'loc_2_text'        => 'Số 56, Quốc Lộ 21 Nam Hồng, Ninh Bình • Hotline: 0919.856.295',
		'loc_3_tag'         => 'NHÀ MÁY SẢN XUẤT',
		'loc_3_text'        => 'Sóc Sơn - Hà Nội. Quy mô cung ứng toàn diện các dự án trên toàn quốc.',

		// Cột 4: Hotline & Tư vấn
		'hotline_main'      => '0907.247.111',
		'hotline_tech_name' => 'Kỹ Thuật Viên',
		'hotline_tech_phone'=> '0943.529.111',
		'hotline_sales_name'=> 'Tư Vấn Bán Hàng',
		'hotline_sales_phone'=> '090.1515.695',

		// Copyright
		'copyright_text'    => 'Architectural Precision in Glass & Aluminum.',
	);

	$saved = get_option( 'ttw_footer_settings', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}

	$merged = wp_parse_args( $saved, $defaults );

	if ( ! empty( $key ) ) {
		return isset( $merged[ $key ] ) ? $merged[ $key ] : $default;
	}

	return $merged;
}

/**
 * Làm sạch dữ liệu đầu vào khi lưu
 */
function ttw_sanitize_footer_settings( $input ) {
	$output = array();
	if ( is_array( $input ) ) {
		foreach ( $input as $k => $v ) {
			$output[ $k ] = sanitize_text_field( $v );
		}
	}
	return $output;
}

// Enqueue Media Uploader
add_action( 'admin_enqueue_scripts', 'ttw_admin_theme_settings_scripts' );
function ttw_admin_theme_settings_scripts( $hook ) {
	if ( strpos( $hook, 'ttw-logo-settings' ) !== false || strpos( $hook, 'ttw-footer-settings' ) !== false || strpos( $hook, 'ttw-theme-settings' ) !== false ) {
		wp_enqueue_media();
	}
}

/**
 * =========================================================================
 * 1. TRANG RIÊNG: CÀI ĐẶT LOGO WEBSITE
 * =========================================================================
 */
function ttw_render_logo_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$current_logo = ttw_get_logo_setting();
	$hover_logo   = ttw_get_logo_hover_setting();
	?>
	<div class="wrap" style="max-width: 860px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
		<h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px;">
			<span class="dashicons dashicons-format-image" style="font-size: 30px; width: 30px; height: 30px; color: #006591;"></span>
			<?php esc_html_e( 'Cài Đặt Logo Website', 'tantien-window' ); ?>
		</h1>

		<?php settings_errors(); ?>

		<form method="post" action="options.php" style="background: #fff; padding: 28px 36px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #ccd0d4;">
			<?php
			settings_fields( 'ttw_logo_settings_group' );
			?>

			<div style="margin-bottom: 28px;">
				<h2 style="font-size: 17px; color: #006591; border-bottom: 2px solid #006591; padding-bottom: 8px; margin-bottom: 20px;">
					1. Logo Chính (Trạng thái bình thường)
				</h2>

				<table class="form-table">
					<tr>
						<th scope="row" style="width: 200px;"><label for="ttw_site_logo">Logo Chính</label></th>
						<td>
							<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
								<input type="text" id="ttw_site_logo" name="ttw_site_logo" value="<?php echo esc_attr( $current_logo ); ?>" class="large-text" placeholder="https://..." style="max-width: 480px;" />
								<button type="button" class="button button-secondary ttw-upload-btn" data-target="#ttw_site_logo" data-preview="#ttw_logo_preview" data-remove="#ttw_remove_logo_btn">Tải ảnh lên / Chọn ảnh</button>
								<button type="button" class="button ttw-remove-btn" id="ttw_remove_logo_btn" data-target="#ttw_site_logo" data-preview="#ttw_logo_preview" style="<?php echo empty( $current_logo ) ? 'display:none;' : ''; ?>">Xóa Logo</button>
							</div>

							<div style="margin-top: 14px;">
								<label style="font-weight: 600; display: block; margin-bottom: 6px;">Xem trước Logo chính:</label>
								<div id="ttw_logo_preview" style="background: #f8fafc; padding: 16px 24px; border: 1px dashed #cbd5e1; border-radius: 6px; display: inline-block;">
									<?php if ( ! empty( $current_logo ) ) : ?>
										<img src="<?php echo esc_url( $current_logo ); ?>" style="max-height: 60px; width: auto; display: block;" />
									<?php else : ?>
										<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/logo/logo.svg' ); ?>" style="max-height: 60px; width: auto; display: block;" />
										<small style="color: #64748b; margin-top: 6px; display: block;">(Đang dùng logo mặc định của theme)</small>
									<?php endif; ?>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>

			<div style="margin-bottom: 28px;">
				<h2 style="font-size: 17px; color: #006591; border-bottom: 2px solid #006591; padding-bottom: 8px; margin-bottom: 20px;">
					2. Ảnh / Logo Khi Rê Chuột (Hover State)
				</h2>

				<table class="form-table">
					<tr>
						<th scope="row" style="width: 200px;"><label for="ttw_site_logo_hover">Logo khi Hover (Tùy chọn)</label></th>
						<td>
							<div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
								<input type="text" id="ttw_site_logo_hover" name="ttw_site_logo_hover" value="<?php echo esc_attr( $hover_logo ); ?>" class="large-text" placeholder="https://..." style="max-width: 480px;" />
								<button type="button" class="button button-secondary ttw-upload-btn" data-target="#ttw_site_logo_hover" data-preview="#ttw_logo_hover_preview" data-remove="#ttw_remove_logo_hover_btn">Tải ảnh lên / Chọn ảnh</button>
								<button type="button" class="button ttw-remove-btn" id="ttw_remove_logo_hover_btn" data-target="#ttw_site_logo_hover" data-preview="#ttw_logo_hover_preview" style="<?php echo empty( $hover_logo ) ? 'display:none;' : ''; ?>">Xóa</button>
							</div>

							<div style="margin-top: 14px;">
								<label style="font-weight: 600; display: block; margin-bottom: 6px;">Xem trước Logo khi Hover:</label>
								<div id="ttw_logo_hover_preview" style="background: #f8fafc; padding: 16px 24px; border: 1px dashed #cbd5e1; border-radius: 6px; display: inline-block;">
									<?php if ( ! empty( $hover_logo ) ) : ?>
										<img src="<?php echo esc_url( $hover_logo ); ?>" style="max-height: 60px; width: auto; display: block;" />
									<?php else : ?>
										<small style="color: #64748b; display: block;">(Chưa cài đặt — Khi rê chuột sẽ giữ logo chính)</small>
									<?php endif; ?>
								</div>
							</div>
							<p class="description" style="margin-top: 10px;">Nếu tải ảnh tại đây, khi rê chuột vào logo thì hình ảnh này sẽ thay thế logo chính (làm hiệu ứng đổi màu/đổi icon logo).</p>
						</td>
					</tr>
				</table>
			</div>

			<div style="padding-top: 14px; border-top: 1px solid #eee;">
				<?php submit_button( __( 'Lưu Cài Đặt Logo', 'tantien-window' ), 'primary', 'submit', false, array( 'style' => 'padding: 6px 28px; font-size: 15px;' ) ); ?>
			</div>
		</form>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.ttw-upload-btn').click(function(e) {
			e.preventDefault();
			var targetInput = $( $(this).data('target') );
			var targetPreview = $( $(this).data('preview') );
			var targetRemove = $( $(this).data('remove') );

			var mediaUploader = wp.media({
				title: 'Chọn hoặc Tải lên Hình ảnh',
				button: { text: 'Sử dụng hình ảnh này' },
				multiple: false
			});
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				targetInput.val(attachment.url);
				targetPreview.html('<img src="' + attachment.url + '" style="max-height: 60px; width: auto; display: block;" />');
				targetRemove.show();
			});
			mediaUploader.open();
		});

		$('.ttw-remove-btn').click(function(e) {
			e.preventDefault();
			var targetInput = $( $(this).data('target') );
			var targetPreview = $( $(this).data('preview') );
			targetInput.val('');
			if ($(this).attr('id') === 'ttw_remove_logo_btn') {
				var defaultLogo = '<?php echo esc_url( get_stylesheet_directory_uri() . "/assets/img/logo/logo.svg" ); ?>';
				targetPreview.html('<img src="' + defaultLogo + '" style="max-height: 60px; width: auto; display: block;" /><small style="color: #64748b; margin-top: 6px; display: block;">(Đang dùng logo mặc định của theme)</small>');
			} else {
				targetPreview.html('<small style="color: #64748b; display: block;">(Chưa cài đặt — Khi rê chuột sẽ giữ logo chính)</small>');
			}
			$(this).hide();
		});
	});
	</script>
	<?php
}

/**
 * =========================================================================
 * 2. TRANG RIÊNG: CÀI ĐẶT FOOTER & HOTLINE
 * =========================================================================
 */
function ttw_render_footer_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = ttw_get_footer_setting();
	?>
	<div class="wrap" style="max-width: 960px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
		<h1 style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px;">
			<span class="dashicons dashicons-layout" style="font-size: 30px; width: 30px; height: 30px; color: #006591;"></span>
			<?php esc_html_e( 'Cài Đặt Chân Trang (Footer) & Hotline', 'tantien-window' ); ?>
		</h1>

		<?php settings_errors(); ?>

		<form method="post" action="options.php" style="background: #fff; padding: 28px 36px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #ccd0d4;">
			<?php
			settings_fields( 'ttw_footer_settings_group' );
			?>

			<!-- Tab 1: Khối CTA Tư Vấn Nhanh -->
			<div style="margin-bottom: 36px;">
				<h2 style="font-size: 17px; color: #006591; border-bottom: 2px solid #006591; padding-bottom: 8px; margin-bottom: 20px;">
					1. Khối Banner Kêu Gọi Tư Vấn (CTA Band)
				</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="cta_title">Tiêu đề CTA</label></th>
						<td>
							<input type="text" id="cta_title" name="ttw_footer_settings[cta_title]" value="<?php echo esc_attr( $settings['cta_title'] ); ?>" class="large-text" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="cta_desc">Mô tả CTA</label></th>
						<td>
							<textarea id="cta_desc" name="ttw_footer_settings[cta_desc]" rows="2" class="large-text"><?php echo esc_textarea( $settings['cta_desc'] ); ?></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="cta_phone">Số điện thoại CTA / Gọi ngay</label></th>
						<td>
							<input type="text" id="cta_phone" name="ttw_footer_settings[cta_phone]" value="<?php echo esc_attr( $settings['cta_phone'] ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="cta_zalo">Link Chat Zalo</label></th>
						<td>
							<input type="url" id="cta_zalo" name="ttw_footer_settings[cta_zalo]" value="<?php echo esc_attr( $settings['cta_zalo'] ); ?>" class="large-text" placeholder="https://zalo.me/..." />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="cta_facebook">Link Fanpage Facebook</label></th>
						<td>
							<input type="url" id="cta_facebook" name="ttw_footer_settings[cta_facebook]" value="<?php echo esc_attr( $settings['cta_facebook'] ); ?>" class="large-text" placeholder="https://facebook.com/..." />
						</td>
					</tr>
				</table>
			</div>

			<!-- Tab 2: Thông tin Doanh Nghiệp (Cột 1) -->
			<div style="margin-bottom: 36px;">
				<h2 style="font-size: 17px; color: #006591; border-bottom: 2px solid #006591; padding-bottom: 8px; margin-bottom: 20px;">
					2. Thông Tin Doanh Nghiệp (Cột 1)
				</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="company_brand">Tên thương hiệu</label></th>
						<td>
							<input type="text" id="company_brand" name="ttw_footer_settings[company_brand]" value="<?php echo esc_attr( $settings['company_brand'] ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="company_name">Tên công ty đầy đủ</label></th>
						<td>
							<input type="text" id="company_name" name="ttw_footer_settings[company_name]" value="<?php echo esc_attr( $settings['company_name'] ); ?>" class="large-text" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="company_desc">Đoạn giới thiệu ngắn</label></th>
						<td>
							<textarea id="company_desc" name="ttw_footer_settings[company_desc]" rows="3" class="large-text"><?php echo esc_textarea( $settings['company_desc'] ); ?></textarea>
						</td>
					</tr>
				</table>
			</div>

			<!-- Tab 3: Hệ thống Trụ sở & Nhà máy (Cột 3) -->
			<div style="margin-bottom: 36px;">
				<h2 style="font-size: 17px; color: #006591; border-bottom: 2px solid #006591; padding-bottom: 8px; margin-bottom: 20px;">
					3. Hệ Thống Trụ Sở & Nhà Máy (Cột 3)
				</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><strong>Cơ sở 1</strong></th>
						<td>
							<input type="text" name="ttw_footer_settings[loc_1_tag]" value="<?php echo esc_attr( $settings['loc_1_tag'] ); ?>" class="small-text" style="width: 160px; margin-bottom: 6px;" placeholder="Tên nhãn (VPGD HÀ NỘI)" />
							<input type="text" name="ttw_footer_settings[loc_1_text]" value="<?php echo esc_attr( $settings['loc_1_text'] ); ?>" class="large-text" placeholder="Địa chỉ chi tiết..." />
						</td>
					</tr>
					<tr>
						<th scope="row"><strong>Cơ sở 2</strong></th>
						<td>
							<input type="text" name="ttw_footer_settings[loc_2_tag]" value="<?php echo esc_attr( $settings['loc_2_tag'] ); ?>" class="small-text" style="width: 160px; margin-bottom: 6px;" placeholder="Tên nhãn (CƠ SỞ 2)" />
							<input type="text" name="ttw_footer_settings[loc_2_text]" value="<?php echo esc_attr( $settings['loc_2_text'] ); ?>" class="large-text" placeholder="Địa chỉ chi tiết..." />
						</td>
					</tr>
					<tr>
						<th scope="row"><strong>Cơ sở 3 / Nhà máy</strong></th>
						<td>
							<input type="text" name="ttw_footer_settings[loc_3_tag]" value="<?php echo esc_attr( $settings['loc_3_tag'] ); ?>" class="small-text" style="width: 160px; margin-bottom: 6px;" placeholder="Tên nhãn (NHÀ MÁY)" />
							<input type="text" name="ttw_footer_settings[loc_3_text]" value="<?php echo esc_attr( $settings['loc_3_text'] ); ?>" class="large-text" placeholder="Địa chỉ chi tiết..." />
						</td>
					</tr>
				</table>
			</div>

			<!-- Tab 4: Tổng đài & Bộ phận hỗ trợ (Cột 4) -->
			<div style="margin-bottom: 36px;">
				<h2 style="font-size: 17px; color: #006591; border-bottom: 2px solid #006591; padding-bottom: 8px; margin-bottom: 20px;">
					4. Tổng Đài & Số Điện Thoại Hỗ Trợ (Cột 4)
				</h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="hotline_main">Hotline Chính 24/7</label></th>
						<td>
							<input type="text" id="hotline_main" name="ttw_footer_settings[hotline_main]" value="<?php echo esc_attr( $settings['hotline_main'] ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row">Hỗ trợ Kỹ thuật</th>
						<td>
							<input type="text" name="ttw_footer_settings[hotline_tech_name]" value="<?php echo esc_attr( $settings['hotline_tech_name'] ); ?>" class="small-text" style="width: 160px;" placeholder="Chức danh (VD: Kỹ Thuật Viên)" />
							<input type="text" name="ttw_footer_settings[hotline_tech_phone]" value="<?php echo esc_attr( $settings['hotline_tech_phone'] ); ?>" class="regular-text" placeholder="Số điện thoại" />
						</td>
					</tr>
					<tr>
						<th scope="row">Tư vấn Bán hàng</th>
						<td>
							<input type="text" name="ttw_footer_settings[hotline_sales_name]" value="<?php echo esc_attr( $settings['hotline_sales_name'] ); ?>" class="small-text" style="width: 160px;" placeholder="Chức danh (VD: Tư Vấn Bán Hàng)" />
							<input type="text" name="ttw_footer_settings[hotline_sales_phone]" value="<?php echo esc_attr( $settings['hotline_sales_phone'] ); ?>" class="regular-text" placeholder="Số điện thoại" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="copyright_text">Slogan / Ghi chú bản quyền</label></th>
						<td>
							<input type="text" id="copyright_text" name="ttw_footer_settings[copyright_text]" value="<?php echo esc_attr( $settings['copyright_text'] ); ?>" class="large-text" />
						</td>
					</tr>
				</table>
			</div>

			<div style="padding-top: 14px; border-top: 1px solid #eee;">
				<?php submit_button( __( 'Lưu Thay Đổi Footer', 'tantien-window' ), 'primary', 'submit', false, array( 'style' => 'padding: 6px 28px; font-size: 15px;' ) ); ?>
			</div>
		</form>
	</div>
	<?php
}
