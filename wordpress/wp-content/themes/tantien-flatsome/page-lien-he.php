<?php
/**
 * Page template - Liên hệ.
 *
 * @package TantienWindow
 */

get_header();
?>

<div class="ttw-page-hero">
	<div class="container">
		<h1>Liên hệ</h1>
		<?php ttw_breadcrumb(); ?>
	</div>
</div>

<div class="ttw-content-area">
	<div class="container">
		<div class="ttw-layout">
			<main class="ttw-main">
				<div class="ttw-contact-grid">
					<div class="ttw-contact-info">
						<span class="ttw-eyebrow">Tân Tiến Window</span>
						<h2>Liên hệ với chúng tôi</h2>
						<p>Quý khách vui lòng liên hệ theo thông tin dưới đây để được tư vấn và báo giá nhanh nhất. Đội ngũ Tân Tiến Window sẵn sàng hỗ trợ 24/7.</p>

						<ul class="ttw-contact-list">
							<li>
								<span class="ttw-contact-icon">☎</span>
								<div>
									<strong>Hotline</strong>
									<a href="tel:0907247111">0907.247.111</a>
								</div>
							</li>
							<li>
								<span class="ttw-contact-icon">✉</span>
								<div>
									<strong>Email</strong>
									<a href="mailto:tantienwindow365@gmail.com">tantienwindow365@gmail.com</a>
								</div>
							</li>
							<li>
								<span class="ttw-contact-icon">◉</span>
								<div>
									<strong>Zalo</strong>
									<a href="https://zalo.me/0907247111" rel="nofollow noopener" target="_blank">zalo.me/0907247111</a>
								</div>
							</li>
							<li>
								<span class="ttw-contact-icon">⌂</span>
								<div>
									<strong>Địa chỉ</strong>
									<span>Khu công nghiệp vừa và nhỏ Ninh Hiệp, Gia Lâm, Hà Nội</span>
								</div>
							</li>
						</ul>
					</div>

					<div class="ttw-contact-form">
						<h3>Gửi yêu cầu tư vấn</h3>
						<p>Điền thông tin bên dưới, chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.</p>
						<form id="ttw-contact-form">
							<div class="ttw-form-row">
								<label for="ttw-cf-name">Họ và tên <span>*</span></label>
								<input type="text" id="ttw-cf-name" name="name" required placeholder="Tên của bạn">
							</div>
							<div class="ttw-form-row">
								<label for="ttw-cf-phone">Số điện thoại <span>*</span></label>
								<input type="tel" id="ttw-cf-phone" name="phone" required placeholder="Số điện thoại">
							</div>
							<div class="ttw-form-row">
								<label for="ttw-cf-subject">Nội dung cần tư vấn</label>
								<input type="text" id="ttw-cf-subject" name="subject" placeholder="Ví dụ: báo giá cửa nhôm Xingfa">
							</div>
							<div class="ttw-form-row">
								<label for="ttw-cf-message">Nội dung <span>*</span></label>
								<textarea id="ttw-cf-message" name="message" required rows="5" placeholder="Mô tả yêu cầu của bạn"></textarea>
							</div>
							<button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
							<p class="ttw-form-note">Bạn cũng có thể gọi trực tiếp hotline <a href="tel:0907247111">0907.247.111</a> để được hỗ trợ ngay.</p>
						</form>
					</div>
				</div>
			</main>
		</div>
	</div>
</div>

<?php
get_footer();
