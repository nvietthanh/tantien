-- ==============================================================================
-- Migration SQL: Update Trang "Về Tân Tiến Window" (Năng Lực Công Ty Chi Tiết Sâu)
-- Slug: gioi-thieu-ve-tan-tien-window & gioi-thieu
-- Mục tiêu: Mở rộng chuyên sâu toàn bộ quy trình 6 bước với danh mục công việc kỹ thuật cụ thể
-- ==============================================================================

UPDATE `wp_posts`
SET `post_content` = '<div class="ttw-about-page">
<section class="ttw-about-hero">
<div class="ttw-about-hero-bg" style="background-image: url(\'/wp-content/themes/tantien-flatsome/assets/img/about/about-hero.png\');">&nbsp;</div>
<div class="ttw-about-hero-overlay">&nbsp;</div>
<div class="container">
<div class="ttw-about-hero-card ttw-animate ttw-fade-up">
<h1 class="ttw-about-hero-title">VỀ TÂN TIẾN WINDOW</h1>
<p class="ttw-about-hero-subtitle">Kiến tạo không gian sống &amp; làm việc qua sự chuẩn xác tuyệt đối của kỹ thuật nhôm kính kiến trúc cao cấp.</p>
</div>
</div>
</section>

<div class="ttw-about-main">

<!-- Section 1: Giới thiệu & Tầm nhìn -->
<section class="ttw-about-intro-section">
<div class="container">
<div class="ttw-about-intro-grid">
<div class="ttw-about-intro-media ttw-animate ttw-fade-left">
<img src="/wp-content/themes/tantien-flatsome/assets/img/about/about-intro.png" alt="Tân Tiến Window - Tầm nhìn &amp; Sứ mệnh" />
</div>
<div class="ttw-about-intro-content ttw-animate ttw-fade-right">
<div class="ttw-about-badge">TẦM NHÌN &amp; SỨ MỆNH</div>
<h2 class="ttw-about-intro-title">Tiên phong giải pháp cửa nhôm kính cao cấp cho mọi công trình</h2>
<div class="ttw-about-intro-desc">
<p>Được thành lập với khát vọng nâng tầm chuẩn mực kiến trúc Việt, <strong>Tân Tiến Window</strong> là đơn vị chuyên nghiệp trong tư vấn thiết kế, sản xuất và thi công lắp đặt tổng thể hệ thống cửa nhôm, vách kính mặt dựng, lan can kính và cửa tự động cao cấp.</p>
<p>Chúng tôi hiểu rằng mỗi bộ cửa không đơn thuần là vật liệu ngăn che, mà là linh hồn kết nối ánh sáng, tối ưu không gian sống, bảo vệ ngôi nhà trước mọi điều kiện thời tiết khắc nghiệt và tôn vinh đẳng cấp của công trình.</p>
</div>
<div class="ttw-about-intro-stats">
<div class="ttw-about-intro-stat">
<div class="ttw-about-stat-num"><span class="ttw-count" data-count="15">15</span>+</div>
<div class="ttw-about-stat-label">NĂM KINH NGHIỆM</div>
</div>
<div class="ttw-about-intro-stat">
<div class="ttw-about-stat-num"><span class="ttw-count" data-count="1000">1000</span>+</div>
<div class="ttw-about-stat-label">DỰ ÁN HOÀN THÀNH</div>
</div>
<div class="ttw-about-intro-stat">
<div class="ttw-about-stat-num"><span class="ttw-count" data-count="50">50</span>K+</div>
<div class="ttw-about-stat-label">M² SẢN XUẤT / NĂM</div>
</div>
</div>
</div>
</div>
</div>
</section>

<!-- Section 2: Năng lực sản xuất & Nhà máy -->
<section class="ttw-about-factory-section">
<div class="container">
<div class="ttw-about-factory-header ttw-animate ttw-fade-up">
<div class="ttw-about-badge" style="margin: 0 auto 12px;">CƠ SỞ VẬT CHẤT</div>
<h2 class="ttw-about-factory-title">Năng Lực Sản Xuất &amp; Quy Mô Nhà Máy</h2>
<p class="ttw-about-factory-subtitle">Nhà máy sản xuất hiện đại hơn 1,500m² được đầu tư đồng bộ dây chuyền tự động hóa CNC nhập khẩu, vận hành theo mô hình quản trị chất lượng 5S &amp; Lean Manufacturing.</p>
</div>

<div class="ttw-about-factory-grid ttw-animate ttw-fade-up">
<div class="ttw-about-factory-card">
<div class="ttw-about-factory-icon"><img src="/wp-content/themes/tantien-flatsome/assets/img/about/icon-factory.svg" alt="Quy mô nhà xưởng" width="30" height="30" /></div>
<h3 class="ttw-about-factory-card-title">Nhà máy 1,500m²+</h3>
<p class="ttw-about-factory-card-desc">Khuôn viên sản xuất quy chuẩn, phân khu chức năng riêng biệt: cắt thanh định hình, đột dập, ép góc thủy lực, lắp ráp phụ kiện và kiểm định KCS.</p>
</div>

<div class="ttw-about-factory-card">
<div class="ttw-about-factory-icon"><img src="/wp-content/themes/tantien-flatsome/assets/img/about/icon-equipment.svg" alt="Máy cắt 2 đầu CNC" width="28" height="28" /></div>
<h3 class="ttw-about-factory-card-title">Dàn máy cắt 2 đầu CNC</h3>
<p class="ttw-about-factory-card-desc">Cắt góc chính xác tuyệt đối 45° - 90° điều khiển bằng thước điện tử kỹ thuật số, dung sai kích thước nhỏ hơn 0.1mm, đảm bảo góc ghép kín khít 100%.</p>
</div>

<div class="ttw-about-factory-card">
<div class="ttw-about-factory-icon"><img src="/wp-content/themes/tantien-flatsome/assets/img/about/icon-equipment.svg" alt="Máy ép góc tự động" width="28" height="28" /></div>
<h3 class="ttw-about-factory-card-title">Máy ép góc tự động thủy lực</h3>
<p class="ttw-about-factory-card-desc">Sử dụng dao ép nhập khẩu kết hợp ke góc định hình dày dặn và keo tăng cường chuyên dụng, liên kết góc phẳng mịn, vững chãi, chống xô lệch vượt thời gian.</p>
</div>

<div class="ttw-about-factory-card">
<div class="ttw-about-factory-icon"><img src="/wp-content/themes/tantien-flatsome/assets/img/about/icon-equipment.svg" alt="Máy phay khóa CNC" width="28" height="28" /></div>
<h3 class="ttw-about-factory-card-title">Máy phay khóa &amp; rãnh thoát nước</h3>
<p class="ttw-about-factory-card-desc">Gia công tự động các lỗ khóa, bản lề và rãnh thoát nước mưa với độ nét tinh xảo, chống tràn nước và lắp đặt phụ kiện vừa vặn chính xác tuyệt đối.</p>
</div>

<div class="ttw-about-factory-card">
<div class="ttw-about-factory-icon"><img src="/wp-content/themes/tantien-flatsome/assets/img/about/icon-inspection.svg" alt="KCS Nghiêm Ngặt" width="24" height="30" /></div>
<h3 class="ttw-about-factory-card-title">Kiểm định KCS 3 cấp</h3>
<p class="ttw-about-factory-card-desc">Kiểm soát chất lượng nghiêm ngặt từ vật tư nhôm kính đầu vào, bán thành phẩm từng công đoạn đến sản phẩm hoàn thiện trước khi đóng gói xuất xưởng.</p>
</div>

<div class="ttw-about-factory-card">
<div class="ttw-about-factory-icon"><img src="/wp-content/themes/tantien-flatsome/assets/img/about/icon-factory.svg" alt="Bơm keo chuyên dụng" width="30" height="30" /></div>
<h3 class="ttw-about-factory-card-title">Công nghệ keo kín khít</h3>
<p class="ttw-about-factory-card-desc">Sử dụng hệ keo Dow Corning / Apollo cao cấp đạt chuẩn quốc tế kết hợp hệ gioăng cao su EPDM đàn hồi tốt, cách âm tới 40dB và cách nhiệt tối ưu.</p>
</div>
</div>
</div>
</section>

<!-- Section 3: Đối tác & Vật liệu tiêu chuẩn -->
<section class="ttw-about-materials-section">
<div class="container">
<div class="ttw-about-materials-header ttw-animate ttw-fade-up">
<div class="ttw-about-badge" style="margin: 0 auto 12px;">CHẤT LƯỢNG HÀNG ĐẦU</div>
<h2 class="ttw-about-materials-title">Vật Liệu Cao Cấp &amp; Đối Tác Chiến Lược</h2>
<p class="ttw-about-materials-subtitle">Tân Tiến Window chỉ sử dụng các profile nhôm, kính an toàn và phụ kiện kim khí chính hãng nhập khẩu từ các thương hiệu uy tín hàng đầu thế giới.</p>
</div>

<div class="ttw-about-materials-grid ttw-animate ttw-fade-up">
<div class="ttw-about-material-card">
<h3 class="ttw-about-mat-title">Hệ Profile Nhôm</h3>
<ul class="ttw-about-mat-list">
<li><strong>Xingfa Quảng Đông (Nhập khẩu tem đỏ):</strong> Độ dày tiêu chuẩn 1.4mm - 2.0mm, sơn tĩnh điện chống ăn mòn vượt trội.</li>
<li><strong>Civro (Đức) &amp; Hopo (Mỹ):</strong> Hệ nhôm cao cấp rãnh C tiêu chuẩn Châu Âu, giải pháp panorama mở rộng tối đa tầm nhìn.</li>
<li><strong>Maxpro, PMI, PMA:</strong> Độ bền màu trên 25 năm, thích ứng tốt với khí hậu duyên hải và mưa bão tại Việt Nam.</li>
</ul>
</div>

<div class="ttw-about-material-card">
<h3 class="ttw-about-mat-title">Hệ Kính An Toàn</h3>
<ul class="ttw-about-mat-list">
<li><strong>Kính dán an toàn 2 lớp (Laminated Glass):</strong> Giữ nguyên vị trí khi nứt vỡ, đảm bảo an toàn tuyệt đối cho người sử dụng.</li>
<li><strong>Kính cường lực (Tempered Glass):</strong> Chịu lực va đập gấp 4 - 5 lần kính thường, chịu sốc nhiệt vượt trội.</li>
<li><strong>Kính hộp cách âm cách nhiệt (Low-E / Solar Control):</strong> Ngăn cản tia UV, giữ mát mùa hè, ấm mùa đông và tiết kiệm điện năng.</li>
</ul>
</div>

<div class="ttw-about-material-card">
<h3 class="ttw-about-mat-title">Hệ Phụ Kiện Kim Khí Đồng Bộ</h3>
<ul class="ttw-about-mat-list">
<li><strong>CMECH (Mỹ), Roto (Đức), Hopo:</strong> Đẳng cấp thượng lưu, vận hành êm ái hơn 100,000 lần đóng mở.</li>
<li><strong>Kinlong, Sigico, Bogo:</strong> Phụ kiện chính hãng chống gỉ sét, khóa đa điểm an toàn tuyệt đối chống trộm.</li>
<li><strong>Gioăng EPDM &amp; Keo chuyên dụng:</strong> Chống lão hóa nhiệt, chống thấm nước tuyệt đối 100%.</li>
</ul>
</div>
</div>
</div>
</section>

<!-- Section 4: Quy trình 6 bước làm việc chuyên nghiệp (Chi tiết chuyên sâu) -->
<section class="ttw-about-process-section">
<div class="container">
<div class="ttw-about-process-header ttw-animate ttw-fade-up">
<div class="ttw-about-badge" style="margin: 0 auto 12px;">QUY TRÌNH CHUẨN MỰC</div>
<h2 class="ttw-about-process-title">Quy Trình Triển Khai Chuyên Nghiệp 6 Bước</h2>
<p class="ttw-about-process-subtitle">Kiểm soát chặt chẽ từng milimet từ khâu khảo sát thực địa đến vận hành bàn giao, đảm bảo độ chuẩn xác và thẩm mỹ hoàn hảo.</p>
</div>

<div class="ttw-about-process-grid ttw-animate ttw-fade-up">
<div class="ttw-about-process-card">
<div class="ttw-about-process-step">01</div>
<h3 class="ttw-about-process-name">Khảo sát &amp; Tư vấn giải pháp</h3>
<p class="ttw-about-process-text">Đội ngũ kỹ sư trực tiếp đo đạc trắc địa tại công trình bằng thước laser chuyên dụng, thẩm định hướng gió, ánh sáng và kết cấu tường.</p>
<ul class="ttw-about-process-list">
<li>Đo đạc kích thước lọt lòng, cốt sàn và tường xây.</li>
<li>Tư vấn quy cách mở cửa (mở quay, trượt, xếp trượt, mở hất).</li>
<li>Đề xuất mẫu nhôm kính phù hợp ngân sách và phong cách kiến trúc.</li>
</ul>
</div>

<div class="ttw-about-process-card">
<div class="ttw-about-process-step">02</div>
<h3 class="ttw-about-process-name">Thiết kế &amp; Dựng bản vẽ 2D/3D</h3>
<p class="ttw-about-process-text">Phòng kỹ thuật sử dụng phần mềm chuyên dụng để mô phỏng chính xác chi tiết cắt profile nhôm và định vị phụ kiện.</p>
<ul class="ttw-about-process-list">
<li>Lập bản vẽ kỹ thuật chi tiết mặt đứng, mặt cắt tỷ lệ chuẩn.</li>
<li>Bóc tách khối lượng vật tư, tối ưu chi phí nguyên liệu.</li>
<li>Thống nhất mã màu sơn, chủng loại kính và phụ kiện kim khí.</li>
</ul>
</div>

<div class="ttw-about-process-card">
<div class="ttw-about-process-step">03</div>
<h3 class="ttw-about-process-name">Sản xuất &amp; Gia công CNC</h3>
<p class="ttw-about-process-text">Quy trình gia công khép kín tại nhà xưởng Tân Tiến Window với hệ thống máy CNC tự động hóa đạt chuẩn ISO.</p>
<ul class="ttw-about-process-list">
<li>Cắt thanh nhôm bằng máy CNC 2 đầu với góc cắt 45° - 90° chuẩn xác.</li>
<li>Phay lỗ khóa, khoét rãnh thoát nước tự động chống đọng nước.</li>
<li>Ép góc thủy lực bằng keo tăng cứng liên kết chắc chắn vĩnh viễn.</li>
</ul>
</div>

<div class="ttw-about-process-card">
<div class="ttw-about-process-step">04</div>
<h3 class="ttw-about-process-name">Kiểm định KCS &amp; Đóng gói</h3>
<p class="ttw-about-process-text">Mỗi bộ cửa trước khi rời xưởng đều phải vượt qua bài kiểm tra nghiêm ngặt của bộ phận kiểm soát chất lượng KCS.</p>
<ul class="ttw-about-process-list">
<li>Kiểm tra đường chéo, độ vuông góc và dung sai khớp nối &lt; 0.1mm.</li>
<li>Test đóng mở vận hành thử nghiệm, kiểm tra độ êm và kín khít.</li>
<li>Bọc màng PE chống xước, góc đệm mút xốp chống va đập khi vận chuyển.</li>
</ul>
</div>

<div class="ttw-about-process-card">
<div class="ttw-about-process-step">05</div>
<h3 class="ttw-about-process-name">Vận chuyển &amp; Lắp đặt hoàn thiện</h3>
<p class="ttw-about-process-text">Đội ngũ thợ tay nghề cao thi công cẩn trọng, đúng kỹ thuật đảm bảo an toàn tuyệt đối và tính thẩm mỹ cao nhất.</p>
<ul class="ttw-about-process-list">
<li>Cân chỉnh tia laser định vị khung bao cân bằng tuyệt đối.</li>
<li>Bắt vít nở chịu lực dày dặn, cố định khung vững chắc vào tường.</li>
<li>Bơm keo bọt và keo thời tiết chuyên dụng chống thấm dột 100%.</li>
</ul>
</div>

<div class="ttw-about-process-card">
<div class="ttw-about-process-step">06</div>
<h3 class="ttw-about-process-name">Nghiệm thu, Bàn giao &amp; Bảo hành</h3>
<p class="ttw-about-process-text">Khách hàng trực tiếp kiểm tra từng chi tiết vận hành, ký biên bản bàn giao và kích hoạt dịch vụ hậu mãi.</p>
<ul class="ttw-about-process-list">
<li>Vệ sinh sạch sẽ khung nhôm, kính trước khi bàn giao.</li>
<li>Hướng dẫn khách hàng sử dụng và bảo dưỡng định kỳ đúng cách.</li>
<li>Cấp phiếu bảo hành chính hãng và cam kết hỗ trợ kỹ thuật 24/7.</li>
</ul>
</div>
</div>
</div>
</section>

<!-- Section 5: Giá trị cốt lõi -->
<section class="ttw-about-values-section">
<div class="container">
<div class="ttw-about-values-header ttw-animate ttw-fade-up">
<div class="ttw-about-badge" style="margin: 0 auto 12px;">NGUYÊN TẮC HOẠT ĐỘNG</div>
<h2 class="ttw-about-values-title">Giá Trị Cốt Lõi Tạo Nên Uy Tín</h2>
</div>
<div class="ttw-about-values-grid ttw-animate ttw-fade-up">
<div class="ttw-about-value-card">
<div class="ttw-about-value-letter">TÂM</div>
<h3 class="ttw-about-value-name">Tận Tâm Phục Vụ</h3>
<p class="ttw-about-value-desc">Đặt lợi ích và sự hài lòng của khách hàng làm trung tâm, luôn lắng nghe và mang đến giải pháp tối ưu nhất.</p>
</div>
<div class="ttw-about-value-card">
<div class="ttw-about-value-letter">TÍN</div>
<h3 class="ttw-about-value-name">Giữ Trọn Chữ Tín</h3>
<p class="ttw-about-value-desc">Cam kết chuẩn 100% về nguồn gốc xuất xứ vật tư, trung thực trong báo giá và đảm bảo đúng tiến độ bàn giao.</p>
</div>
<div class="ttw-about-value-card">
<div class="ttw-about-value-letter">TINH</div>
<h3 class="ttw-about-value-name">Kỹ Thuật Tinh Hoa</h3>
<p class="ttw-about-value-desc">Không ngừng cải tiến kỹ thuật, trau chuốt từng đường cắt, góc ghép, đáp ứng tiêu chuẩn thẩm mỹ khắt khe nhất.</p>
</div>
<div class="ttw-about-value-card">
<div class="ttw-about-value-letter">TỐC</div>
<h3 class="ttw-about-value-name">Tốc Độ &amp; Linh Hoạt</h3>
<p class="ttw-about-value-desc">Đáp ứng nhanh chóng mọi yêu cầu báo giá, tiến độ thi công thần tốc nhưng vẫn đảm bảo trọn vẹn chất lượng.</p>
</div>
</div>
</div>
</section>

<!-- Section 6: CTA Đồng hành -->
<section class="ttw-about-cta-section">
<div class="container">
<div class="ttw-about-cta-card ttw-animate ttw-fade-up">
<h2 class="ttw-about-cta-title">ĐỒNG HÀNH CÙNG CÔNG TRÌNH CỦA BẠN</h2>
<p class="ttw-about-cta-desc">Liên hệ với đội ngũ kỹ sư của Tân Tiến Window để nhận tư vấn chuyên sâu, khảo sát hiện trạng và nhận bản vẽ kỹ thuật chi tiết cùng báo giá tốt nhất cho dự án của bạn.</p>
<p><a class="ttw-about-cta-btn" href="/lien-he/">NHẬN BÁO GIÁ &amp; TƯ VẤN NGAY</a></p>
</div>
</div>
</section>

</div>
</div>'
WHERE `post_name` IN ('gioi-thieu-ve-tan-tien-window', 'gioi-thieu') OR `ID` IN (1552, 32);
