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
				  <input type="text" name="plus_title" id="plus_title" aria-label="Title" placeholder="Title">
				  <?php plus_post() ;?>
				  <?php wp_nonce_field( 'process_form', 'wplus_nonce' ); ?>
				  <div class="form-group">
				  <button class="btn btn-cat" type="button" data-toggle="collapse" data-target="#postCategories" aria-expanded="false" aria-controls="collapseExample">
				    Categories
				  </button>
				    <select multiple class="form-control collapse" id="postCategories" name="postCategories[]">
				      <option value="helpline-support">Helpline / Support</option>
				      <option value="getting-started">Getting Started</option>
				      <option value="connecting-week">Connecting Week</option>
				      <option value="topic-1-online-participation-digital-literacie">Topic 1: online participation digital literacies</option>
				      <option value="topic-2-open-learning-sharing-and-openness">Topic 2: open learning sharing and openness</option>
				      <option value="topic-3-learning-in-communities-networked-collaborative-learning">Topic 3: learning in communities networked collaborative learning</option>
				      <option value="topic-4-design-for-online-and-blended-learning">Topic 4: design for online and blended learning</option>
				      <option value="topic-5-lessons-learnt-future-practice">Topic 5: lessons learnt future practice</option>
				      <option value="reflection-week">Reflection Week</option>
				      <option value="other">Other</option>
				    </select>
				    <?php  if (current_user_can('edit_others_pages')): ?>
					    <div class="sticky-holder">
					    	Make sticky
						    <label class="switch">
							  <input type="checkbox" name="sticky">
							  <span class="sticky-slider round"></span>
							</label>
						</div>		
					<?php endif; ?>		    
				  </div>
				  <input type="submit" name="submit" value="Post" id="post-plus-button">				  
				</form>
				<button type="button" class="close" data-dismiss="modal" id="closer" aria-label="Close">Cancel</button>		       					
		      </div>		       
		    </div>
		  </div>
		</div>
    <!-- END Modal -->

    <button class="writing-circle" id="red-writing-button" data-toggle="modal" data-target="#plus-post" aria-label="write post"><i class="fa fa-pencil"></i></button>
    <?php endif;?>

	<footer class="entry-footer">

		<?php edit_post_link( __( 'Edit', 'understrap' ), '<span class="edit-link">', '</span>' ); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
