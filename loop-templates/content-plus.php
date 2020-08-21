<?php
/**
 * Post rendering content according to caller of get_template_part.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<!--<article <?php //post_class(); ?> id="post-<?php //the_ID(); ?>">-->
			<?php $post_id = get_the_id();
			$name = get_the_author_meta('display_name');					
			$author_id = get_the_author_meta('ID');
			$author_img = get_avatar_url($author_id, array('width'=>'36','height'=>'36'));?>

			<div class="card" data-order="<?php echo get_the_time($post_id);?>">
			
			<div class="plus-author">
				<img class="plus-author-photo" src="<?php echo $author_img ;?>" alt="author profile image.">
				<div class="plus-author-name"><a href="<?php echo get_author_posts_url($author_id) ;?>"> 
					<?php echo $name;?>						
					</a>
				</div>
				<div class="plus-date"><?php echo get_the_date( 'M j, Y' ) ;?>
					<button class="fa fa-ellipsis-v editor-button" data-post="<?php echo $post_id;?>">		
					</button>
				</div>
				<div class="edit-block" id="edit-block-<?php echo $post_id ;?>">
					<?php 
							echo edit_it($post_id, $author_id); 
							echo post_go_away($post_id)
							;?>					
					</div>
				</div>
			<?php if(get_the_title()) : ?>
				<a href="<?php the_permalink();?>"><h2><?php the_title();?></h2></a>
			<?php endif;?>
			<?php echo get_the_post_thumbnail($post_id, 'medium', array( 'class' => 'plus-photo img-' . $post_id ) );?>
			<div class="card-text">
				<?php apply_filters('the_content', the_content());?> 
			</div>
			<div id="comment-post-<?php echo $post_id;?>">
			<div class="comment-count">
				<?php echo comment_count($post_id);?>
			</div>
			<?php 
				echo display_comments_shortcode();
			 	echo wplus_rater($post_id)
			 ;?>
			
			</div>
		</div>
	
	<!--<footer class="entry-footer">

		<?php //understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

<!--</article>--><!-- #post-## -->
