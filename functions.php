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
			$html .= '<a href="' . get_post_permalink() . '"><h2>' . get_the_title() . '</h2></a>';
			$html .= get_the_post_thumbnail($post_id, 'medium', array( 'class' => 'plus-photo' ) );
			$html .= '<div class="card-text">' . apply_filters('the_content', get_the_content()) . '</div>';
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

// function ensure_post_title(){
// 	global $post;
// 	$title = get_the_title($post->ID);
// 	if($title){
// 		return $title;
// 	} else {
// 		return 'Read more . . . ';
// 	}
// }

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
    $settings =   array(
            'wpautop' => true,
            'editor_height' => '400',
            'media_buttons' => true,
            'tabindex' => '5',
            'editor_css' => '', 
            'editor_class' => '',
            'textarea_name' => 'pluscontent',
            // 'teeny' => true,
             'dfw' => true,
            // 'tinymce' => true,
            'quicktags' => false,
            'tinymce' => array(
		         'toolbar1'=> 'bold,italic,underline,link,unlink',
		         'toolbar2' => '',
		         'toolbar3' => '',
		         'content_css' => get_stylesheet_directory_uri() . '/css/font-editor-styles.css'
		    ),
            'drag_drop_upload' => true, 
            );
    return wp_editor( $content, 'mypluspost', $settings); 
}


add_action( 'pre-html-upload-ui', '_force_html_uploader' );

function _force_html_uploader( $flash ) {
    remove_action('post-html-upload-ui', 'media_upload_html_bypass' );
    return false;
}

add_action('media_upload_tabs', '_media_upload_auto_insert_js');

function _media_upload_auto_insert_js(){
    ?><script src="<?php bloginfo('stylesheet_directory'); ?>/js/upload.js"></script><?php
}

//FORM SUBMISSION


function plus_post_creation($title, $body) {
    // do something
    $my_post = array(
	  'post_title'    => wp_strip_all_tags( $title, true ), 
	  'post_content'  => $body, 
	  'post_status'   => 'publish',
	  'post_author'   => get_current_user_id(),
	);
	 
	// Insert the post into the database
	wp_insert_post( $my_post );
}



function form_builder(){
	echo '<form method="post" action="' . admin_url( 'admin-post.php' ) .'">';
}

//add_shortcode( 'the-form', 'form_builder' );


add_action( 'admin_post_nopriv_process_form', 'process_form_data' );
add_action( 'admin_post_process_form', 'process_form_data' );
function process_form_data() {
  // form processing code here
	if(isset($_POST['plus_title'])){
		$title = $_POST['plus_title'];
	} else {
		$title = 'Read more . . . ';
	}
	if(isset($_POST['pluscontent'])){
		$body = $_POST['pluscontent'];
	} else {
		$body = ' ';
	}

	plus_post_creation($title, $body);
	//var_dump($_POST);
	header('Location: ' . get_home_url());
}

