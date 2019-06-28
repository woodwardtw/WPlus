<?php
/**
 * Left sidebar check.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'left' ); ?>

<div class="col-md content-area" id="primary">
