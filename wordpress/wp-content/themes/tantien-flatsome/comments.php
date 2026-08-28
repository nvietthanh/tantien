<?php
/**
 * Comments template.
 *
 * @package          TantienWindow
 * @flatsome-version 3.16.0
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="ttw-comments">

	<?php if ( have_comments() ) : ?>
		<h3><?php comments_number( '', '1 bình luận', '% bình luận' ); ?></h3>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 48,
			) );
			?>
		</ol>

		<?php
		the_comments_pagination( array(
			'prev_text' => '←',
			'next_text' => '→',
		) );
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Bình luận đã đóng.', 'tantien-window' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form( array(
		'class_submit'  => 'btn btn-primary',
		'label_submit'  => __( 'Gửi bình luận', 'tantien-window' ),
		'title_reply'   => __( 'Để lại bình luận', 'tantien-window' ),
	) );
	?>

</div>
