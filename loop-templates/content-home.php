<?php
/**
 * Partial template for content in page.php
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">

		<?php the_content(); ?>
		
		<?php the_front_posts();?><!--posts loop-->

		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'understrap' ),
				'after'  => '</div>',
			)
		);
		?>

	</div><!-- .entry-content -->

	<!--CONTACT Modal -->

	<?php if (is_user_logged_in()): ?>

		<div class="modal fade" id="plus-post" tabindex="-1" role="dialog" aria-labelledby="the-greeting" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		    <div class="modal-content">		     		     
		      <div class="modal-body">
		        <div id="the-person"></div>
		        <?php //echo do_shortcode('[gravityform id="1" title="false" description="false"]');?>
		        <div id="plus-post"></div>
		         <?php echo plus_post(); ?> 

		      </div>
		        <button type="button" class="close" data-dismiss="modal" id="closer" aria-label="Close">
		          Cancel
		        </button>  
		        <input type="submit" value="Submit" id="submit" name="submit" tabindex="12">
   
		    </div>
		  </div>
		</div>
	<?php endif;?>
    <!-- END Modal -->

	<footer class="entry-footer">

		<?php edit_post_link( __( 'Edit', 'understrap' ), '<span class="edit-link">', '</span>' ); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
