<?php
/**
 * 404 template.
 *
 * @package          TantienWindow
 * @flatsome-version 3.16.0
 */

get_header();
?>

<div class="ttw-404">
	<div class="container">
		<h1>404</h1>
		<h2>Không tìm thấy trang</h2>
		<p>Trang bạn đang tìm không tồn tại hoặc đã bị di chuyển.</p>
		<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Về trang chủ</a>
	</div>
</div>

<?php
get_footer();
