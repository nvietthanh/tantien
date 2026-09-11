<?php
/**
 * Consultation (Tư Vấn Khách Hàng) Management Module
 *
 * @package TantienFlatsome
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Đăng ký Custom Post Type cho Liên hệ tư vấn
 */
add_action( 'init', 'ttw_register_consultation_cpt' );
function ttw_register_consultation_cpt() {
	$labels = array(
		'name'               => 'Liên hệ',
		'singular_name'      => 'Liên hệ',
		'menu_name'          => 'Liên Hệ',
		'name_admin_bar'     => 'Liên Hệ',
		'add_new'            => 'Thêm liên hệ',
		'add_new_item'       => 'Thêm liên hệ mới',
		'new_item'           => 'Liên hệ mới',
		'edit_item'          => 'Chi tiết liên hệ',
		'view_item'          => 'Xem liên hệ',
		'all_items'          => 'Tất cả liên hệ',
		'search_items'       => 'Tìm kiếm liên hệ',
		'not_found'          => 'Không có liên hệ nào.',
		'not_found_in_trash' => 'Không có liên hệ nào trong thùng rác.',
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => false,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 26,
		'menu_icon'          => 'dashicons-phone',
		'supports'           => array( 'title' ),
	);

	register_post_type( 'ttw_consultation', $args );
}

/**
 * Hiển thị số lượng liên hệ chưa xử lý dạng bubble badge màu cam/đỏ trên menu Admin
 */
add_action( 'admin_menu', 'ttw_add_consultation_pending_count_bubble' );
function ttw_add_consultation_pending_count_bubble() {
	global $menu;

	// Đếm số lượng lead chưa liên hệ (status != 'contacted' hoặc status = 'pending')
	$pending_query = new WP_Query( array(
		'post_type'      => 'ttw_consultation',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_ttw_status',
				'value'   => 'contacted',
				'compare' => '!=',
			),
			array(
				'key'     => '_ttw_status',
				'compare' => 'NOT EXISTS',
			),
		),
	) );

	$pending_count = $pending_query->found_posts;

	if ( $pending_count > 0 ) {
		foreach ( $menu as $key => $item ) {
			if ( isset( $item[2] ) && 'edit.php?post_type=ttw_consultation' === $item[2] ) {
				$menu[ $key ][0] = sprintf(
					'Liên Hệ <span class="update-plugins count-%1$d"><span class="plugin-count">%1$d</span></span>',
					$pending_count
				);
				break;
			}
		}
	}
}

/**
 * 2. Tùy chỉnh cột hiển thị trong danh sách Admin
 */
add_filter( 'manage_ttw_consultation_posts_columns', 'ttw_consultation_columns' );
function ttw_consultation_columns( $columns ) {
	$new_columns = array(
		'cb'         => '<input type="checkbox" />',
		'title'      => 'Tên khách hàng',
		'phone'      => 'Số điện thoại',
		'address'    => 'Địa chỉ',
		'message'    => 'Nội dung tư vấn',
		'status'     => 'Trạng thái',
		'date'       => 'Thời gian gửi',
	);
	return $new_columns;
}

add_action( 'manage_ttw_consultation_posts_custom_column', 'ttw_consultation_custom_column', 10, 2 );
function ttw_consultation_custom_column( $column, $post_id ) {
	switch ( $column ) {
		case 'phone':
			$phone = get_post_meta( $post_id, '_ttw_phone', true );
			if ( ! empty( $phone ) ) {
				echo '<a href="tel:' . esc_attr( $phone ) . '" class="button button-small" style="font-weight:700; color:#006591; border-color:#bcdbe8; background:#f0f7fa; border-radius:6px; display:inline-flex; align-items:center; gap:4px; padding:2px 8px;">'
					. '<span class="dashicons dashicons-phone" style="font-size:15px; width:15px; height:15px; line-height:1; vertical-align:middle; color:#006591;"></span> ' 
					. esc_html( $phone ) 
					. '</a>';
			} else {
				echo '<span style="color:#a0aec0;">—</span>';
			}
			break;

		case 'address':
			$address = get_post_meta( $post_id, '_ttw_address', true );
			if ( ! empty( $address ) ) {
				echo '<div style="font-weight:500; color:#2d3748; line-height:1.4;">' . esc_html( $address ) . '</div>';
			} else {
				echo '<span style="color:#a0aec0;">—</span>';
			}
			break;

		case 'message':
			$message = get_post_meta( $post_id, '_ttw_message', true );
			if ( ! empty( $message ) ) {
				echo '<div class="ttw-admin-msg-box">'
					. '<div class="ttw-admin-msg-content">' . esc_html( $message ) . '</div>'
					. '</div>';
			} else {
				echo '<span style="color:#a0aec0;">—</span>';
			}
			break;


		case 'status':
			$status = get_post_meta( $post_id, '_ttw_status', true );
			if ( 'contacted' === $status ) {
				echo '<span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#def7ec; color:#03543f; border:1px solid #bcf0da;"><span style="width:6px; height:6px; background:#0e9f6e; border-radius:50%;"></span> Đã liên hệ</span>';
			} else {
				echo '<span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#fef08a; color:#713f12; border:1px solid #fde047;"><span style="width:6px; height:6px; background:#ca8a04; border-radius:50%;"></span> Chưa xử lý</span>';
			}
			break;
	}
}

/**
 * CSS tùy chỉnh cho Admin table consultation & Menu Badge
 */
add_action( 'admin_head', 'ttw_consultation_admin_styles' );
function ttw_consultation_admin_styles() {
	?>
	<style>
	#adminmenu li.menu-top.menu-icon-ttw_consultation .update-plugins,
	#adminmenu a[href*="post_type=ttw_consultation"] .update-plugins {
		background-color: #d63638 !important;
		color: #ffffff !important;
	}
	#adminmenu li.menu-top.menu-icon-ttw_consultation.current .update-plugins,
	#adminmenu li.menu-top.menu-icon-ttw_consultation.wp-has-current-submenu .update-plugins {
		background-color: #d63638 !important;
		color: #ffffff !important;
	}
	.column-message {
		width: 320px;
	}
	.ttw-admin-msg-box {
		position: relative;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 6px;
		padding: 8px 12px;
		font-size: 13px;
		line-height: 1.5;
		color: #334155;
		transition: all 0.2s ease;
		cursor: default;
	}
	.ttw-admin-msg-content {
		display: -webkit-box;
		-webkit-line-clamp: 3;
		-webkit-box-orient: vertical;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: normal;
		word-break: break-word;
	}
	.ttw-admin-msg-box:hover {
		background: #ffffff;
		border-color: #006591;
		box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
		z-index: 10;
	}
	.ttw-admin-msg-box:hover .ttw-admin-msg-content {
		-webkit-line-clamp: unset;
		max-height: none;
		white-space: pre-wrap;
	}
	</style>
	<?php
}




/**
 * 3. Thêm Meta Box chi tiết liên hệ tư vấn trong màn Edit
 */
add_action( 'add_meta_boxes', 'ttw_consultation_meta_box' );
function ttw_consultation_meta_box() {
	add_meta_box(
		'ttw_consultation_details',
		'Thông tin chi tiết liên hệ tư vấn',
		'ttw_consultation_meta_box_html',
		'ttw_consultation',
		'normal',
		'high'
	);
}

function ttw_consultation_meta_box_html( $post ) {
	wp_nonce_field( 'ttw_save_consultation_meta', 'ttw_consultation_nonce' );

	$phone   = get_post_meta( $post->ID, '_ttw_phone', true );
	$address = get_post_meta( $post->ID, '_ttw_address', true );
	$message = get_post_meta( $post->ID, '_ttw_message', true );
	$status  = get_post_meta( $post->ID, '_ttw_status', true );
	$note    = get_post_meta( $post->ID, '_ttw_admin_note', true );
	?>
	<table class="form-table">
		<tr>
			<th scope="row"><label>Tên khách hàng:</label></th>
			<td><strong><?php echo esc_html( $post->post_title ); ?></strong></td>
		</tr>
		<tr>
			<th scope="row"><label for="ttw_phone">Số điện thoại:</label></th>
			<td>
				<div style="display:flex; align-items:center; gap:10px; max-width:450px;">
					<input type="text" name="ttw_phone" id="ttw_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" style="height:32px;" />
					<?php if ( ! empty( $phone ) ) : ?>
						<a href="tel:<?php echo esc_attr( $phone ); ?>" class="button button-secondary" style="height:32px; display:inline-flex; align-items:center; gap:4px; padding:0 12px; font-weight:600; color:#006591; border-color:#006591;">
							<span class="dashicons dashicons-phone" style="font-size:16px; width:16px; height:16px; line-height:1; color:#006591;"></span> Gọi ngay
						</a>
					<?php endif; ?>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="ttw_address">Địa chỉ:</label></th>
			<td><input type="text" name="ttw_address" id="ttw_address" value="<?php echo esc_attr( $address ); ?>" class="large-text" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="ttw_message">Nội dung tư vấn:</label></th>
			<td><textarea name="ttw_message" id="ttw_message" rows="5" class="large-text"><?php echo esc_textarea( $message ); ?></textarea></td>
		</tr>
		<tr>
			<th scope="row"><label for="ttw_status">Trạng thái xử lý:</label></th>
			<td>
				<select name="ttw_status" id="ttw_status">
					<option value="pending" <?php selected( $status, 'pending' ); ?>>Chưa xử lý</option>
					<option value="contacted" <?php selected( $status, 'contacted' ); ?>>Đã liên hệ</option>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="ttw_admin_note">Ghi chú nội bộ:</label></th>
			<td>
				<textarea name="ttw_admin_note" id="ttw_admin_note" rows="3" class="large-text" placeholder="Ghi chú nhân viên tư vấn..."><?php echo esc_textarea( $note ); ?></textarea>
			</td>
		</tr>
	</table>
	<?php
}

/**
 * Lưu thông tin khi admin cập nhật
 */
add_action( 'save_post_ttw_consultation', 'ttw_save_consultation_meta' );
function ttw_save_consultation_meta( $post_id ) {
	if ( ! isset( $_POST['ttw_consultation_nonce'] ) || ! wp_verify_nonce( $_POST['ttw_consultation_nonce'], 'ttw_save_consultation_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['ttw_phone'] ) ) {
		update_post_meta( $post_id, '_ttw_phone', sanitize_text_field( $_POST['ttw_phone'] ) );
	}
	if ( isset( $_POST['ttw_address'] ) ) {
		update_post_meta( $post_id, '_ttw_address', sanitize_text_field( $_POST['ttw_address'] ) );
	}
	if ( isset( $_POST['ttw_message'] ) ) {
		update_post_meta( $post_id, '_ttw_message', sanitize_textarea_field( $_POST['ttw_message'] ) );
	}
	if ( isset( $_POST['ttw_status'] ) ) {
		update_post_meta( $post_id, '_ttw_status', sanitize_text_field( $_POST['ttw_status'] ) );
	}
	if ( isset( $_POST['ttw_admin_note'] ) ) {
		update_post_meta( $post_id, '_ttw_admin_note', sanitize_textarea_field( $_POST['ttw_admin_note'] ) );
	}
}

/**
 * 4. Xử lý AJAX nhận form gửi từ Frontend
 */
add_action( 'wp_ajax_ttw_submit_consultation', 'ttw_ajax_handle_submit_consultation' );
add_action( 'wp_ajax_nopriv_ttw_submit_consultation', 'ttw_ajax_handle_submit_consultation' );

function ttw_ajax_handle_submit_consultation() {
	check_ajax_referer( 'ttw_consultation_nonce', 'security' );

	$name    = isset( $_POST['fullname'] ) ? sanitize_text_field( wp_unslash( $_POST['fullname'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$address = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	// Validation
	if ( empty( $name ) ) {
		wp_send_json_error( array( 'message' => 'Vui lòng nhập họ và tên của bạn.' ) );
	}

	if ( empty( $phone ) ) {
		wp_send_json_error( array( 'message' => 'Vui lòng nhập số điện thoại liên hệ.' ) );
	}

	// Validate phone format cơ bản
	$clean_phone = preg_replace( '/[^0-9+]/', '', $phone );
	if ( strlen( $clean_phone ) < 8 || strlen( $clean_phone ) > 15 ) {
		wp_send_json_error( array( 'message' => 'Số điện thoại không hợp lệ. Vui lòng kiểm tra lại.' ) );
	}

	if ( empty( $address ) ) {
		wp_send_json_error( array( 'message' => 'Vui lòng nhập địa chỉ của bạn.' ) );
	}

	if ( empty( $message ) ) {
		wp_send_json_error( array( 'message' => 'Vui lòng nhập nội dung cần tư vấn.' ) );
	}

	// Tạo bài post lưu lead
	$post_id = wp_insert_post( array(
		'post_title'  => $name,
		'post_type'   => 'ttw_consultation',
		'post_status' => 'publish',
	) );

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( array( 'message' => 'Có lỗi xảy ra khi lưu thông tin. Vui lòng thử lại sau.' ) );
	}

	update_post_meta( $post_id, '_ttw_phone', $phone );
	update_post_meta( $post_id, '_ttw_address', $address );
	update_post_meta( $post_id, '_ttw_message', $message );
	update_post_meta( $post_id, '_ttw_status', 'pending' );

	wp_send_json_success( array(
		'message' => 'Cảm ơn bạn! Thông tin tư vấn đã được gửi thành công. Chúng tôi sẽ liên hệ lại với bạn trong thời gian sớm nhất.',
	) );
}

/**
 * 5. Đăng ký Shortcode [ttw_consultation_form] và URL rewrite /lien-he
 */
add_action( 'init', 'ttw_consultation_rewrite_rules' );
function ttw_consultation_rewrite_rules() {
	add_rewrite_rule( '^lien-he/?$', 'index.php?ttw_contact_page=1', 'top' );
}

add_filter( 'query_vars', 'ttw_consultation_query_vars' );
function ttw_consultation_query_vars( $vars ) {
	$vars[] = 'ttw_contact_page';
	return $vars;
}

add_action( 'template_redirect', 'ttw_consultation_template_redirect' );
function ttw_consultation_template_redirect() {
	global $wp_query;
	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) : '';
	$req_uri = trim( (string) $req_uri, '/' );

	if ( get_query_var( 'ttw_contact_page' ) || 'lien-he' === $req_uri || 'tu-van' === $req_uri ) {
		$wp_query->is_404  = false;
		$wp_query->is_page = true;
		status_header( 200 );

		// Đặt Title chuẩn SEO cho trang Liên hệ
		add_filter( 'pre_get_document_title', function() {
			return 'Liên Hệ Tư Vấn - ' . get_bloginfo( 'name' );
		}, 999 );

		add_filter( 'wpseo_title', function() {
			return 'Liên Hệ Tư Vấn - ' . get_bloginfo( 'name' );
		}, 999 );

		$template = get_stylesheet_directory() . '/page-lien-he.php';
		if ( file_exists( $template ) ) {
			include $template;
			exit;
		}
	}
}

