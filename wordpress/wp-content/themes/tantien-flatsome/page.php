<?php
/**
 * Page template cho tantien-flatsome.
 *
 * @package          TantienFlatsome
 * @flatsome-version 3.19.9
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
	<?php
		$page_slug = get_post_field( 'post_name', get_the_ID() );
		$page_class = 'ttw-page-' . sanitize_html_class( $page_slug );
	?>
	<div class="ttw-page-wrapper <?php echo esc_attr( $page_class ); ?>">
		<div class="ttw-page-inner-container">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
	</div>
<?php endwhile; ?>

<?php
get_footer();
