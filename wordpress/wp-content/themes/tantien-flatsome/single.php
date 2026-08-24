<?php
/**
 * Single post template cho tantien-flatsome.
 *
 * @package TantienFlatsome
 */

get_header();

// Kiểm tra nếu nội dung bài viết là landing page / culture / warranty bento grid thì render full-width không có sidebar blog
global $post;
$current_id = get_the_ID();
if ( ! $current_id && isset( $_GET['post'] ) ) {
	$current_id = (int) $_GET['post'];
}
if ( ! $current_id && isset( $_GET['post_id'] ) ) {
	$current_id = (int) $_GET['post_id'];
}
$post_obj = $current_id ? get_post( $current_id ) : $post;

$is_culture_post = false;
if ( $post_obj && isset( $post_obj->post_content ) ) {
	if ( false !== strpos( $post_obj->post_content, 'ttw-culture-page' )
		|| false !== strpos( $post_obj->post_content, 'ttw-warranty-page' )
		|| in_array( $post_obj->post_name, array( 'ban-sac-van-hoa', 'quy-dinh-bao-hanh-cua-tan-tien-window', 'quy-dinh-bao-hanh', 'chinh-sach-bao-hanh' ), true )
		|| in_array( (int) $post_obj->ID, array( 1554, 1556, 285, 2454 ), true ) ) {
		$is_culture_post = true;
	}
}

if ( in_array( $current_id, array( 1554, 1556, 285, 2454 ), true ) ) {
	$is_culture_post = true;
}

$has_sidebar = ( ! $is_culture_post ) && ( is_active_sidebar( 'sidebar-main' ) || is_active_sidebar( 'sidebar-blog' ) );
?>



<?php if ( $is_culture_post ) : ?>
	<div id="content" class="content-area" role="main">
		<?php
		remove_filter( 'the_content', 'wpautop' );
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
		?>
	</div>
<?php else : ?>


<div class="ttw-news-page ttw-single-page-wrap">
	<div class="ttw-news-container">
		<div class="ttw-layout <?php echo ! $has_sidebar ? 'ttw-layout-no-sidebar' : ''; ?>">
			<main class="ttw-main">
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'ttw-single-article' ); ?>>
						<div class="ttw-single-header">
							<?php if ( function_exists( 'ttw_breadcrumb' ) ) : ?>
								<div class="ttw-single-breadcrumb">
									<?php ttw_breadcrumb(); ?>
								</div>
							<?php endif; ?>
							<h1 class="ttw-single-title"><?php the_title(); ?></h1>

							<div class="ttw-news-meta">
								<span class="ttw-meta-date"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> <?php echo esc_html( get_the_date() ); ?></span>
								<span class="ttw-meta-author"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> <?php the_author(); ?></span>
								<?php if ( has_category() ) : ?>
									<span class="ttw-meta-cat"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg> <?php the_category( ', ' ); ?></span>
								<?php endif; ?>
							</div>
						</div>

						<div class="entry-content ttw-entry-content">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>

						<?php the_tags( '<div class="ttw-tags"><span>Tags:</span> ', ' ', '</div>' ); ?>

						<div class="ttw-comments">
							<?php
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}
							?>
						</div>
					</article>
				<?php endwhile; ?>
			</main>

			<?php if ( $has_sidebar ) : ?>
				<aside class="ttw-sidebar">
					<?php if ( is_active_sidebar( 'sidebar-main' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar-main' ); ?>
					<?php elseif ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
						<?php dynamic_sidebar( 'sidebar-blog' ); ?>
					<?php endif; ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php
get_footer();

