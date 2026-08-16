<?php

/**
 * Front page - homepage theo design Figma.
 *
 * @package TantienWindow
 */

get_header();

$ttw_img = get_template_directory_uri() . '/assets/img/design/';

$ttw_stats = array(
	array('10+', 'NĂM KINH NGHIỆM'),
	array('1.500m²', 'NHÀ XƯỞNG'),
	array('100%', 'THI CÔNG TOÀN QUỐC'),
	array('TOP', 'SẢN PHẨM CHÍNH HÃNG'),
);

$ttw_values = array(
	array('01', 'Chất lượng', 'Cam kết sử dụng vật liệu cao cấp, đảm bảo độ bền và tính thẩm mỹ lâu dài cho mọi công trình.'),
	array('02', 'Kinh nghiệm', 'Đội ngũ kỹ thuật viên lành nghề với hơn 10 năm kinh nghiệm trong lĩnh vực nhôm kính.'),
	array('03', 'Thi công', 'Quy trình lắp đặt chuẩn xác, an toàn, đảm bảo tiến độ và vệ sinh công trình.'),
	array('04', 'Đồng hành', 'Chính sách bảo hành dài hạn, hỗ trợ kỹ thuật nhanh chóng và tận tâm.'),
);

$ttw_projects = array(
	array('proj1-villa', 'large', 'Biệt thự cao cấp', 'Ocean Villa Retreat', 'Đà Nẵng • Hệ cửa lùa panorama'),
	array('proj2-office', 'small', 'Văn phòng', 'Tech Hub Tower', 'TP.HCM • Vách kính mặt dựng'),
	array('proj3-apartment', 'small', 'Căn hộ', 'Skyrise Penthouse', 'Hà Nội • Cửa sổ cách âm'),
);

$ttw_project_ids = array();
$ttw_products_q = new WP_Query(array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
));
while ($ttw_products_q->have_posts()) {
	$ttw_products_q->the_post();
	$ttw_project_ids[] = get_the_ID();
}
wp_reset_postdata();

$ttw_testimonials = array(
	array(
		'Anh Minh Tuấn',
		'Chủ đầu tư biệt thự Vinhomes',
		'“Tôi rất ấn tượng với sự chuyên nghiệp của đội ngũ Tân Tiến. Hệ thống cửa nhôm Xingfa được lắp đặt hoàn hảo, cách âm cực tốt và mang lại vẻ đẹp hiện đại cho ngôi nhà.”',
	),
	array(
		'Chị Lan Hương',
		'Giám đốc dự án Tech Office',
		'“Vách kính mặt dựng cho tòa nhà văn phòng của chúng tôi được thi công đúng tiến độ, chất lượng kính tuyệt vời. Dịch vụ hậu mãi cũng rất tận tình.”',
	),
	array(
		'Anh Hoàng Quân',
		'Quản lý dự án Ocean Resort',
		'“Tân Tiến Window đã tư vấn giải pháp lan can kính rất phù hợp với thiết kế tổng thể của resort. Khách hàng của chúng tôi rất thích không gian mở này.”',
	),
);

$ttw_steps = array(
	array('01', 'Tư vấn', 'Tiếp nhận yêu cầu, đề xuất giải pháp phù hợp.'),
	array('02', 'Khảo sát', 'Đo đạc thực tế, đánh giá hiện trạng công trình.'),
	array('03', 'Thiết kế & báo giá', 'Lên bản vẽ chi tiết và dự toán chi phí.'),
	array('04', 'Sản xuất', 'Gia công tại xưởng với quy trình kiểm soát chặt chẽ.'),
	array('05', 'Lắp đặt & bảo hành', 'Thi công chuyên nghiệp, bàn giao và hỗ trợ dài hạn.'),
);

$ttw_benefits = array(
	array('badge', 'Chất Lượng Vượt Trội', 'Nguồn nguyên vật liệu nhập khẩu chính hãng từ các thương hiệu hàng đầu thế giới.'),
	array('headset', 'Thi Công Chuyên Nghiệp', 'Đội ngũ kỹ thuật viên tay nghề cao, giàu kinh nghiệm thực chiến.'),
	array('tag', 'Giá Thành Cạnh Tranh', 'Tối ưu chi phí, mang đến giải pháp phù hợp nhất cho mọi ngân sách.'),
	array('shield', 'Bảo Hành Tận Tâm', 'Chính sách bảo hành dài hạn, hỗ trợ bảo trì nhanh chóng, chuyên nghiệp.'),
);

$ttw_partners = array(2403, 2419, 2418, 2417, 2416, 2415, 2406, 2405, 2404);
?>

<!-- ===== HERO ===== -->
<section class="ttw-hero">
	<div class="ttw-hero-bg" style="background-image:url('<?php echo esc_url($ttw_img . 'hero-bg.jpg'); ?>')"></div>
	<div class="ttw-hero-content">
		<h1>Giải pháp nhôm<br>kính cho kiến trúc<br>hiện đại</h1>
		<p>Tân Tiến Window cung cấp các giải pháp cửa nhôm kính, kính cường lực và vách kính mặt dựng với định hướng hiện đại, bền vững và thẩm mỹ cao.</p>
		<div class="ttw-hero-buttons">
			<a class="ttw-btn ttw-btn-primary" href="<?php echo esc_url(ttw_shop_url()); ?>">Khám phá sản phẩm</a>
			<a class="ttw-btn ttw-btn-light" href="<?php echo esc_url(ttw_consult_url()); ?>">Nhận tư vấn</a>
		</div>
	</div>
</section>

<!-- ===== STATS ===== -->
<section class="ttw-stats">
	<div class="container">
		<div class="ttw-stats-grid ttw-animate ttw-fade-up">
			<?php foreach ($ttw_stats as $ttw_i => $ttw_stat) : ?>
				<div class="ttw-stat<?php echo 0 === $ttw_i ? '' : ' ttw-stat-divider'; ?>">
					<div class="ttw-stat-number"><?php echo esc_html($ttw_stat[0]); ?></div>
					<div class="ttw-stat-label"><?php echo esc_html($ttw_stat[1]); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== INTRODUCTION ===== -->
<section class="ttw-section">
	<div class="container">
		<div class="ttw-about">
			<div class="ttw-about-media ttw-animate ttw-fade-left">
				<img src="<?php echo esc_url($ttw_img . 'about.jpg'); ?>" alt="Kiến tạo không gian từ những mảng kính" loading="lazy">
			</div>
			<div class="ttw-about-text ttw-animate ttw-fade-right">
				<span class="ttw-eyebrow">About Tan Tien Window</span>
				<h2>Kiến tạo không gian từ những mảng kính</h2>
				<p>Chúng tôi tập trung vào sự hoàn mỹ trong từng chi tiết nhôm kính, mang đến giải pháp mặt đứng, cửa và vách ngăn tối ưu cho không gian kiến trúc đương đại.</p>
				<a class="ttw-textlink" href="<?php echo esc_url(home_url('/gioi-thieu/')); ?>">Tìm hiểu thêm
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M5 12h14" />
						<path d="M12 5l7 7-7 7" />
					</svg>
				</a>
			</div>
		</div>
	</div>
</section>

<!-- ===== PRODUCTS ===== -->
<section class="ttw-section ttw-section-gray" id="san-pham">
	<div class="container">
		<div class="ttw-section-row ttw-animate ttw-fade-up">
			<h2 class="ttw-section-title">Sản phẩm nổi bật</h2>
			<a class="ttw-textlink" href="<?php echo esc_url(ttw_shop_url()); ?>">Xem tất cả
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M5 12h14" />
					<path d="M12 5l7 7-7 7" />
				</svg>
			</a>
		</div>
		<div class="ttw-showcase ttw-animate ttw-fade-up">
			<?php
			$ttw_products_query = new WP_Query(array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'orderby'        => 'post__in',
				'posts_per_page' => 6,
				'no_found_rows'  => true,
			));
			while ($ttw_products_query->have_posts()) :
				$ttw_products_query->the_post();
				$ttw_product_obj = wc_get_product(get_the_ID());
				$ttw_product_img = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
			?>
				<a class="ttw-showcase-card" href="<?php the_permalink(); ?>">
					<?php if ($ttw_product_img) : ?>
						<img src="<?php echo esc_url($ttw_product_img); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
					<?php else : ?>
						<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/placeholder.svg'); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
					<?php endif; ?>
					<div class="ttw-showcase-overlay"></div>
					<div class="ttw-showcase-body">
						<h3><?php the_title(); ?></h3>
					</div>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
</section>

<!-- ===== VALUES (Why us) ===== -->
<section class="ttw-section">
	<div class="container">
		<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up">Giá trị cốt lõi</h2>
		<div class="ttw-values ttw-animate ttw-fade-up">
			<?php foreach ($ttw_values as $ttw_value) : ?>
				<div class="ttw-value">
					<span class="ttw-value-num"><?php echo esc_html($ttw_value[0]); ?></span>
					<h3 class="ttw-value-title"><?php echo esc_html($ttw_value[1]); ?></h3>
					<p class="ttw-value-desc"><?php echo esc_html($ttw_value[2]); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== PROJECTS ===== -->
<section class="ttw-section ttw-section-gray" id="cong-trinh">
	<div class="container">
		<div class="ttw-projects-head ttw-animate ttw-fade-up">
			<h2 class="ttw-section-title">Công trình tiêu biểu</h2>
			<p>Những công trình thể hiện chất lượng và thẩm mỹ trong từng chi tiết.</p>
		</div>
		<div class="ttw-projects ttw-animate ttw-fade-up">
			<?php foreach ($ttw_projects as $ttw_pi => $ttw_project) : ?>
				<a class="ttw-project ttw-project-<?php echo esc_attr($ttw_project[1]); ?>" href="<?php echo esc_url(! empty($ttw_project_ids[$ttw_pi]) ? get_permalink($ttw_project_ids[$ttw_pi]) : ttw_projects_url()); ?>">
					<img src="<?php echo esc_url($ttw_img . $ttw_project[0] . '.jpg'); ?>" alt="<?php echo esc_attr($ttw_project[3]); ?>" loading="lazy">
					<div class="ttw-project-overlay"></div>
					<div class="ttw-project-body">
						<span class="ttw-project-cat"><?php echo esc_html($ttw_project[2]); ?></span>
						<h3><?php echo esc_html($ttw_project[3]); ?></h3>
						<p><?php echo esc_html($ttw_project[4]); ?></p>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
		<div class="ttw-projects-action">
			<a class="ttw-textlink" href="<?php echo esc_url(ttw_projects_url()); ?>">Xem tất cả dự án
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M5 12h14" />
					<path d="M12 5l7 7-7 7" />
				</svg>
			</a>
		</div>
	</div>
</section>

<!-- ===== PROCESS ===== -->
<section class="ttw-section">
	<div class="container">
		<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up">Từ ý tưởng đến công trình hoàn thiện</h2>
		<div class="ttw-process ttw-animate ttw-fade-up">
			<div class="ttw-process-line"></div>
			<?php foreach ($ttw_steps as $ttw_step) : ?>
				<div class="ttw-step">
					<div class="ttw-step-num"><?php echo esc_html($ttw_step[0]); ?></div>
					<h3><?php echo esc_html($ttw_step[1]); ?></h3>
					<p><?php echo esc_html($ttw_step[2]); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== BENEFITS ===== -->
<section class="ttw-section">
	<div class="container">
		<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up">Lý do khách hàng lựa chọn</h2>
		<div class="ttw-benefits ttw-animate ttw-fade-up">
			<?php foreach ($ttw_benefits as $ttw_benefit) : ?>
				<div class="ttw-benefit">
					<div class="ttw-benefit-icon">
						<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/icons/' . $ttw_benefit[0] . '.svg'); ?>" alt="<?php echo esc_attr($ttw_benefit[1]); ?>" loading="lazy">
					</div>
					<h3><?php echo esc_html($ttw_benefit[1]); ?></h3>
					<p><?php echo esc_html($ttw_benefit[2]); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== PARTNERS ===== -->
<section class="ttw-section ttw-partners">
	<div class="container">
		<h2 class="ttw-section-title ttw-center ttw-animate ttw-fade-up">Đối tác của chúng tôi</h2>
		<div class="ttw-partners-divider ttw-animate ttw-fade-in"></div>
		<div class="ttw-partners-track">
			<div class="ttw-partners-slide">
				<?php foreach ($ttw_partners as $ttw_partner_id) : ?>
					<?php $ttw_partner_url = wp_get_attachment_image_url($ttw_partner_id, 'full'); ?>
					<?php if ($ttw_partner_url) : ?>
						<span class="ttw-partner"><img src="<?php echo esc_url($ttw_partner_url); ?>" alt="<?php echo esc_attr(get_the_title($ttw_partner_id)); ?>" loading="lazy"></span>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php foreach ($ttw_partners as $ttw_partner_id) : ?>
					<?php $ttw_partner_url = wp_get_attachment_image_url($ttw_partner_id, 'full'); ?>
					<?php if ($ttw_partner_url) : ?>
						<span class="ttw-partner"><img src="<?php echo esc_url($ttw_partner_url); ?>" alt="<?php echo esc_attr(get_the_title($ttw_partner_id)); ?>" loading="lazy"></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="ttw-section ttw-section-gray" id="danh-gia">
	<div class="container">
		<div class="ttw-projects-head ttw-animate ttw-fade-up">
			<h2 class="ttw-section-title">Góc nhìn người tiêu dùng</h2>
			<p>Sự hài lòng của khách hàng là minh chứng rõ nét nhất cho chất lượng dịch vụ của chúng tôi.</p>
		</div>
		<div class="ttw-quotes ttw-animate ttw-fade-up">
			<?php foreach ($ttw_testimonials as $ttw_t) : ?>
				<div class="ttw-quote">
					<svg class="ttw-quote-mark" width="34" height="26" viewBox="0 0 45 32" fill="currentColor" aria-hidden="true">
						<path d="M0 32V20.4C0 9.1 6.1 2.7 17.6 0l2.1 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H0zm25.3 0V20.4C25.3 9.1 31.4 2.7 42.9 0L45 5.2c-6.9 1.6-10.9 4.9-11.5 9.6h7.1V32H25.3z" />
					</svg>
					<p class="ttw-quote-text"><?php echo esc_html($ttw_t[2]); ?></p>
					<div class="ttw-quote-author">
						<strong><?php echo esc_html($ttw_t[0]); ?></strong>
						<span><?php echo esc_html(strtoupper($ttw_t[1])); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ===== NEWS ===== -->
<?php
$ttw_query = new WP_Query(array(
	'posts_per_page'      => 3,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
));
?>
<?php if ($ttw_query->have_posts()) : ?>
	<section class="ttw-section ttw-section-gray" id="tin-tuc">
		<div class="container">
			<div class="ttw-section-row ttw-animate ttw-fade-up">
				<h2 class="ttw-section-title">Tin tức & Kiến thức</h2>
				<a class="ttw-textlink" href="<?php echo esc_url(ttw_news_url()); ?>">Xem tất cả
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<path d="M5 12h14" />
						<path d="M12 5l7 7-7 7" />
					</svg>
				</a>
			</div>
			<div class="ttw-news ttw-animate ttw-fade-up">
				<?php
				while ($ttw_query->have_posts()) :
					$ttw_query->the_post();
					$ttw_cat = get_the_category();
				?>
					<article class="ttw-news-item">
						<a class="ttw-news-thumb" href="<?php the_permalink(); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<?php the_post_thumbnail('ttw-card', array('loading' => 'lazy')); ?>
							<?php endif; ?>
						</a>
						<div class="ttw-news-body">
							<div class="ttw-news-meta">
								<span class="ttw-news-cat"><?php echo $ttw_cat ? esc_html(strtoupper($ttw_cat[0]->name)) : ''; ?></span>
								<span class="ttw-news-date"><?php echo esc_html(get_the_date('d M, Y')); ?></span>
							</div>
							<h3 class="ttw-news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<a class="ttw-textlink ttw-news-more" href="<?php the_permalink(); ?>">Đọc thêm
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M5 12h14" />
									<path d="M12 5l7 7-7 7" />
								</svg>
							</a>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>
<?php endif; ?>

<?php
get_footer();
