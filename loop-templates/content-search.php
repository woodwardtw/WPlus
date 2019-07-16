<?php
/**
 * Search results partial template.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	$post_id = get_the_id();
	$name = get_the_author_meta('display_name');					
	$author_id = get_the_author_meta('ID');
	$author_img = get_avatar_url($author_id, array('width'=>'36','height'=>'36'));
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="card">
	<header class="entry-header">
		<div class="plus-author"><img class="plus-author-photo" src="<?php echo $author_img ?>">
			<div class="plus-author-name"><?php echo $name;?></div>
			<a href="<?php echo get_post_permalink(the_ID());?>"><h2><?php echo get_the_title();?></h2></a>
		</header><!-- .entry-header -->
			<?php echo get_the_post_thumbnail($post_id, 'medium', array( 'class' => 'plus-photo' ) );?>
			<div class="card-text">
				<?php the_content();?>
			</div>
		</div>
		

	

	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
	