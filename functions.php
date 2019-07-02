<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$understrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/jetpack.php',                         // Load Jetpack compatibility file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker.
	'/woocommerce.php',                     // Load WooCommerce functions.
	'/editor.php',                          // Load Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
);

foreach ( $understrap_includes as $file ) {
	$filepath = locate_template( 'inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}

function the_front_posts(){
	$plus_args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'orderby' => 'date',
		'posts_per_page' => 10,
	);

	$plus_query = new WP_Query( $plus_args );
	$html = '';
	$logged_in = get_current_user_id();
	$current_img = get_avatar_url($logged_in, array('width'=>'36','height'=>'36'));	
	// The Loop
	if ( $plus_query->have_posts() ) {
		echo '<div class="card-columns plus" id="gplus">';
		$html .= '<div class="card"><div class="plus-author"><img class="plus-author-photo" src="'. $current_img . '"><div class="whats-new"><button id="write"  data-toggle="modal" data-target="#plus-post">What\'s new with you?</button></div></div></div>';
		while ( $plus_query->have_posts() ) {
			$plus_query->the_post();
			$post_id = get_the_id();
			$name = get_the_author_meta('display_name');					
			$author_id = get_the_author_meta('ID');
			$author_img = get_avatar_url($author_id, array('width'=>'36','height'=>'36'));
			$html .= '<div class="card">';
			$html .= '<div class="plus-author"><img class="plus-author-photo" src="'. $author_img . '">';
			$html .= '<div class="plus-author-name">' . $name .'</div></div>';
			$html .= '<h2>' . get_the_title() . '</h2>';
			$html .= get_the_post_thumbnail($post_id, 'medium', array( 'class' => 'plus-photo' ) );
			$html .= '<div class="card-text">' . get_the_content() . '</div>';
			$html .= '<div id="comment-post-' . $post_id . '">';
			$html .= '<div class="comment-count">' . comment_count($post_id) . '</div>';
			$html .= '<div class="plus-comment-box"><img class="plus-logged" src="' . $current_img . '">Add a comment . . . </div></div>';
			$html .= '</div>';
		}
		echo $html;
		echo '</div>';
		wp_reset_postdata();
	} else {
		// no posts found
	}

}

function comment_count($post_id){
	$num = get_comments_number();
	if ($num > 0){
		return 'See all ' . $num . ' comments.';
	}
}

function plus_author_image(){
	$author_id = get_the_author_meta('ID');
	echo get_avatar_url($author_id, array('width'=>'36','height'=>'36'));
}

function plus_author_name(){
	echo get_the_author_meta('display_name');
}

//plus poster
function plus_post(){
	$content = '';
        $editor_id = 'plus_post';
        $settings =   array(
            'wpautop' => true,
            'editor_height' => '400',
            'media_buttons' => true,
            'textarea_name' => $editor_id, 
            'tabindex' => '5',
            'editor_css' => '', 
            'editor_class' => '',
            // 'teeny' => true,
             'dfw' => true,
            // 'tinymce' => true,
            'quicktags' => false,
            'tinymce' => array(
		         'toolbar1'=> 'bold,italic,underline,link,unlink',
		         'toolbar2' => '',
		         'toolbar3' => ''
		    ),
            'drag_drop_upload' => true, 
            );
        wp_editor( $content, $editor_id, $settings); 
}
