<?php
/**
 * Template Name: Liên Hệ Tư Vấn
 * Template Post Type: page
 *
 * @package TantienFlatsome
 */

get_header();

$ttw_fs = function_exists( 'ttw_get_footer_setting' ) ? ttw_get_footer_setting() : array();
$hl_main = ! empty( $ttw_fs['hotline_main'] ) ? $ttw_fs['hotline_main'] : '0907.247.111';
$hl_main_link = 'tel:' . str_replace( array( '.', ' ', '-' ), '', $hl_main );
$cta_zalo = ! empty( $ttw_fs['cta_zalo'] ) ? $ttw_fs['cta_zalo'] : ( function_exists( 'ttw_zalo_link' ) ? ttw_zalo_link() : 'https://zalo.me/0907247111' );
?>

<div class="ttw-contact-page">
	<div class="ttw-contact-container">

		<!-- Header Banner -->
		<section class="ttw-contact-hero">
			<h1 class="ttw-contact-title">LIÊN HỆ TƯ VẤN &amp; BÁO GIÁ</h1>
			<p class="ttw-contact-subtitle">Hãy để lại thông tin công trình của bạn. Đội ngũ kỹ sư và chuyên viên tư vấn Tân Tiến Window sẽ liên hệ khảo sát, tư vấn giải pháp tối ưu và gửi báo giá chi tiết nhất.</p>
		</section>

		<div class="ttw-contact-layout">
			
			<!-- Cột thông tin liên hệ & Hệ thống cơ sở -->
			<div class="ttw-contact-info-col">
				<div class="ttw-contact-card">
					<h3 class="ttw-card-heading">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						Tổng Đài Tư Vấn 24/7
					</h3>
					<div class="ttw-hotline-highlight">
						<span>Hotline hỗ trợ nhanh:</span>
						<a href="<?php echo esc_url( $hl_main_link ); ?>" class="ttw-phone-big"><?php echo esc_html( $hl_main ); ?></a>
					</div>
					<div class="ttw-action-buttons">
						<a href="<?php echo esc_url( $hl_main_link ); ?>" class="ttw-btn ttw-btn-call">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
							Gọi Trực Tiếp
						</a>
						<a href="<?php echo esc_url( $cta_zalo ); ?>" target="_blank" rel="noopener" class="ttw-btn ttw-btn-zalo">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.04 2 11c0 2.9 1.42 5.46 3.66 7.2V22l3.46-1.9c.9.25 1.86.39 2.88.39 5.52 0 10-4.04 10-9.02S17.52 2 12 2z"/></svg>
							Chat Qua Zalo
						</a>
					</div>
				</div>

				<div class="ttw-contact-card">
					<h3 class="ttw-card-heading">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
						Hệ Thống Trụ Sở &amp; Nhà Máy
					</h3>
					
					<div class="ttw-location-block">
						<span class="ttw-loc-badge"><?php echo esc_html( ! empty( $ttw_fs['loc_1_tag'] ) ? $ttw_fs['loc_1_tag'] : 'VPGD HÀ NỘI' ); ?></span>
						<p><?php echo esc_html( ! empty( $ttw_fs['loc_1_text'] ) ? $ttw_fs['loc_1_text'] : 'Khu biệt thự liền kề số 162 Khuất Duy Tiến, Thanh Xuân, Hà Nội' ); ?></p>
					</div>

					<div class="ttw-location-block">
						<span class="ttw-loc-badge"><?php echo esc_html( ! empty( $ttw_fs['loc_2_tag'] ) ? $ttw_fs['loc_2_tag'] : 'CƠ SỞ 2 (NINH BÌNH)' ); ?></span>
						<p><?php echo esc_html( ! empty( $ttw_fs['loc_2_text'] ) ? $ttw_fs['loc_2_text'] : 'Số 56, Quốc Lộ 21 Nam Hồng, Ninh Bình • Hotline: 0919.856.295' ); ?></p>
					</div>

					<div class="ttw-location-block">
						<span class="ttw-loc-badge"><?php echo esc_html( ! empty( $ttw_fs['loc_3_tag'] ) ? $ttw_fs['loc_3_tag'] : 'NHÀ MÁY SẢN XUẤT' ); ?></span>
						<p><?php echo esc_html( ! empty( $ttw_fs['loc_3_text'] ) ? $ttw_fs['loc_3_text'] : 'Sóc Sơn - Hà Nội. Quy mô cung ứng toàn diện các dự án trên toàn quốc.' ); ?></p>
					</div>
				</div>
			</div>

			<!-- Cột Form Điền Thông Tin Tư Vấn -->
			<div class="ttw-contact-form-col">
				<div class="ttw-form-wrapper">
					<div class="ttw-form-header">
						<h2>GỬI YÊU CẦU TƯ VẤN</h2>
						<p>Vui lòng điền đầy đủ các thông tin bên dưới, kỹ sư của chúng tôi sẽ gọi lại ngay:</p>
					</div>

					<form id="ttw-consultation-form" class="ttw-consultation-form" method="POST">
						<?php wp_nonce_field( 'ttw_consultation_nonce', 'ttw_nonce' ); ?>

						<div class="ttw-form-group">
							<label for="ttw_fullname">Họ và tên khách hàng <span class="required">*</span></label>
							<div class="ttw-input-wrap">
								<svg class="ttw-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
								<input type="text" name="fullname" id="ttw_fullname" placeholder="Ví dụ: Nguyễn Văn A" required />
							</div>
						</div>

						<div class="ttw-form-row">
							<div class="ttw-form-group">
								<label for="ttw_phone_input">Số điện thoại liên hệ <span class="required">*</span></label>
								<div class="ttw-input-wrap">
									<svg class="ttw-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
									<input type="tel" name="phone" id="ttw_phone_input" placeholder="Ví dụ: 0912 345 678" required />
								</div>
							</div>

							<div class="ttw-form-group">
								<label for="ttw_address_input">Địa chỉ / Khu vực công trình <span class="required">*</span></label>
								<div class="ttw-input-wrap">
									<svg class="ttw-input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
									<input type="text" name="address" id="ttw_address_input" placeholder="Ví dụ: Cầu Giấy, Hà Nội" required />
								</div>
							</div>
						</div>

						<div class="ttw-form-group">
							<label for="ttw_message_input">Nội dung tư vấn &amp; yêu cầu chi tiết <span class="required">*</span></label>
							<div class="ttw-input-wrap ttw-textarea-wrap">
								<textarea name="message" id="ttw_message_input" rows="5" placeholder="Mô tả nhu cầu (Ví dụ: Cần tư vấn cửa nhôm kính cho biệt thự 3 tầng, hệ nhôm xếp trượt, báo giá trọn gói...)" required></textarea>
							</div>
						</div>

						<div id="ttw-form-feedback" class="ttw-form-feedback" style="display:none;"></div>

						<button type="submit" id="ttw-submit-btn" class="ttw-submit-btn">
							<span class="btn-text">GỬI YÊU CẦU TƯ VẤN NGAY</span>
							<span class="btn-loading" style="display:none;">
								<svg class="spinner" width="20" height="20" viewBox="0 0 50 50"><circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle></svg>
								Đang gửi thông tin...
							</span>
						</button>
					</form>
				</div>
			</div>

		</div>

	</div>
</div>

<!-- Popup Modal Thông Báo Gửi Thành Công -->
<div id="ttw-success-modal" class="ttw-modal-overlay" style="display:none;">
	<div class="ttw-modal-dialog">
		<button type="button" class="ttw-modal-close" id="ttw-modal-close" aria-label="Đóng">&times;</button>
		<div class="ttw-modal-icon">
			<svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#0e9f6e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
				<polyline points="22 4 12 14.01 9 11.01"></polyline>
			</svg>
		</div>
		<h3 class="ttw-modal-title">GỬI YÊU CẦU THÀNH CÔNG!</h3>
		<p class="ttw-modal-desc">Cảm ơn quý khách đã tin tưởng <strong>Tân Tiến Window</strong>. Đội ngũ kỹ sư và chuyên viên tư vấn của chúng tôi sẽ liên hệ lại qua số điện thoại của quý khách trong thời gian sớm nhất.</p>
		<div class="ttw-modal-actions">
			<button type="button" class="ttw-modal-btn" id="ttw-modal-btn-confirm">ĐÃ HIỂU &amp; ĐÓNG</button>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var form = document.getElementById('ttw-consultation-form');
	var feedback = document.getElementById('ttw-form-feedback');
	var submitBtn = document.getElementById('ttw-submit-btn');
	var btnText = submitBtn.querySelector('.btn-text');
	var btnLoading = submitBtn.querySelector('.btn-loading');

	// Modal elements
	var modal = document.getElementById('ttw-success-modal');
	var modalClose = document.getElementById('ttw-modal-close');
	var modalConfirm = document.getElementById('ttw-modal-btn-confirm');

	function openSuccessModal() {
		if (modal) {
			modal.style.display = 'flex';
			document.body.classList.add('ttw-modal-open');
		}
	}

	function closeSuccessModal() {
		if (modal) {
			modal.style.display = 'none';
			document.body.classList.remove('ttw-modal-open');
		}
	}

	if (modalClose) modalClose.addEventListener('click', closeSuccessModal);
	if (modalConfirm) modalConfirm.addEventListener('click', closeSuccessModal);
	if (modal) {
		modal.addEventListener('click', function(e) {
			if (e.target === modal) {
				closeSuccessModal();
			}
		});
	}

	if (!form) return;

	form.addEventListener('submit', function(e) {
		e.preventDefault();

		feedback.style.display = 'none';
		feedback.className = 'ttw-form-feedback';
		feedback.innerHTML = '';

		var fullname = document.getElementById('ttw_fullname').value.trim();
		var phone = document.getElementById('ttw_phone_input').value.trim();
		var address = document.getElementById('ttw_address_input').value.trim();
		var message = document.getElementById('ttw_message_input').value.trim();
		var nonce = form.querySelector('input[name="ttw_nonce"]').value;

		if (!fullname || !phone || !address || !message) {
			feedback.className = 'ttw-form-feedback ttw-alert-error';
			feedback.innerHTML = 'Vui lòng điền đầy đủ các trường thông tin bắt buộc (*).';
			feedback.style.display = 'block';
			return;
		}

		// Disable button & show spinner
		submitBtn.disabled = true;
		btnText.style.display = 'none';
		btnLoading.style.display = 'inline-flex';

		var formData = new FormData();
		formData.append('action', 'ttw_submit_consultation');
		formData.append('security', nonce);
		formData.append('fullname', fullname);
		formData.append('phone', phone);
		formData.append('address', address);
		formData.append('message', message);

		fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
			method: 'POST',
			body: formData
		})
		.then(function(response) {
			return response.json();
		})
		.then(function(data) {
			submitBtn.disabled = false;
			btnText.style.display = 'inline';
			btnLoading.style.display = 'none';

			if (data.success) {
				form.reset();
				openSuccessModal();
			} else {
				feedback.className = 'ttw-form-feedback ttw-alert-error';
				feedback.innerHTML = (data.data && data.data.message) ? data.data.message : 'Có lỗi xảy ra, vui lòng thử lại.';
				feedback.style.display = 'block';
			}
		})
		.catch(function(err) {
			submitBtn.disabled = false;
			btnText.style.display = 'inline';
			btnLoading.style.display = 'none';
			feedback.className = 'ttw-form-feedback ttw-alert-error';
			feedback.innerHTML = 'Lỗi kết nối máy chủ. Vui lòng thử lại sau.';
			feedback.style.display = 'block';
		});
	});
});
</script>

<?php
get_footer();

