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

	<!-- Modal -->
	<?php if (is_user_logged_in()): ?>

		<div class="modal fade" id="plus-post" tabindex="-1" role="dialog" aria-labelledby="the-greeting" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		    <div class="modal-content">		     		     
		      <div class="modal-body">
		        <div id="the-person"></div>
		        <?php echo form_builder();?>
		        <input type="hidden" name="action" value="process_form">
				  <input type="text" name="plus_title" id="plus_title" aria-label="Title" placeholder="Your title goes here.">
				  <?php plus_post() ;?>
				  <input type="submit" name="submit" value="Post" id="post-plus-button">				  
				</form>
				<button type="button" class="close" data-dismiss="modal" id="closer" aria-label="Close">Cancel</button>		       					
		      </div>		       
		    </div>
		  </div>
		</div>
	<?php endif;?>
    <!-- END Modal -->
    <button class="writing-circle" data-toggle="modal" data-target="#plus-post" aria-label="write post"><i class="fa fa-pencil"></i></button>

	<footer class="entry-footer">

		<?php edit_post_link( __( 'Edit', 'understrap' ), '<span class="edit-link">', '</span>' ); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
