<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// if ( ! is_active_sidebar( 'left-sidebar' ) ) {
// 	echo '<h2>Home</h2>';
	
// 	//return;
// }

// when both sidebars turned on reduce col size to 3 from 4.
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<?php if ( 'both' === $sidebar_pos ) : ?>
	<div class="col-md-2 widget-area" id="left-sidebar" role="complementary">
<?php else : ?>
	<div class="col-md-2 widget-area" id="left-sidebar" role="complementary">
<?php endif; ?>
<?php if ( !is_active_sidebar( 'left-sidebar' )) : ?>
	<a href="">Home</a>
<?php endif; ?>
<?php dynamic_sidebar( 'left-sidebar' ); ?>

</div><!-- #left-sidebar -->
