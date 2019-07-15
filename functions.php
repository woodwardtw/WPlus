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
		'posts_per_page' => 30,
	);

	$plus_query = new WP_Query( $plus_args );
	$html = '';
	$logged_in = get_current_user_id();
	$current_img = get_avatar_url($logged_in, array('width'=>'36','height'=>'36'));	
	// The Loop
	if ( $plus_query->have_posts() ) {
		echo '<div class="card-columns plus" id="gplus">';
		if(is_user_logged_in()){
		$html .= '<div class="card"><div class="plus-author"><img class="plus-author-photo" src="'. $current_img . '"><div class="whats-new"><button id="write"  data-toggle="modal" data-target="#plus-post">What\'s new with you?</button></div></div></div>';
		}
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
			$html .= display_comments_shortcode();//do_shortcode("[display_comments]");
			$html .= '</div></div>';
		}
		echo $html;
		echo '</div>';
		wp_reset_postdata();
	} else {
		// no posts found
	}

}


/*
COMMENTS
*/

//from https://toolset.com/forums/topic/display-comments-at-the-middle-of-a-content-template/
add_shortcode( 'display_comments', 'display_comments_shortcode' );
function display_comments_shortcode() {
 global $post;
 ob_start();
 comment_form(
 	array(
		'label_submit' => __( 'Post' ),
		'title_reply' => '',
		'id_form' => 'commentform-'. $post->ID,
		'logged_in_as' => '',
		'title_comment' => '',
		'id_submit' => 'submit-' . $post->ID,
		'class_submit' => 'plus-comment-submit',
	)
 );
 $res = ob_get_contents();
 ob_end_clean();
 return $res;
}

function wpsites_modify_comment_form_text_area($arg) {
	global $post;
	$logged_in = get_current_user_id();
	$current_img = get_avatar_url($logged_in, array('width'=>'36','height'=>'36'));	
    $arg['comment_field'] = '<div class="comment-form-comment"><div class="plus-comment-box"><img class="plus-logged" src="' . $current_img . '"><textarea id="comment-'. $post->ID .'" name="comment" cols="45" rows="1" aria-required="true" aria-label="Comment" placeholder="Add a comment..."></textarea></div></div>';
     $defaults['title_reply_before'] = '<span id="reply-title-'.$post->ID.'" class="comment-reply-title">';
    return $arg;
}

add_filter('comment_form_defaults', 'wpsites_modify_comment_form_text_area');



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

//External wp_editor 
// add new buttons
//add_filter( 'mce_buttons', 'myplugin_register_buttons' );

// function myplugin_register_buttons( $buttons ) {
//    array_push( $buttons, 'separator', 'tinymceEmoji' );
//    return $buttons;
// }
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
//emoji sort of works from https://www.npmjs.com/package/tinymce-emoji
//add_filter( 'mce_external_plugins', 'myplugin_register_tinymce_javascript' );

// function myplugin_register_tinymce_javascript( $plugin_array ) {
//    $plugin_array['tinymceEmoji'] = get_template_directory_uri().'/js/tinymce/tinymce-emoji/plugin.min.js';
//    return $plugin_array;
// }

//getting the tinymce editor stuff
function tinymce_init() {
    // Hook to tinymce plugins filter
    add_filter( 'mce_external_plugins', 'opengraph_tinymce_checker' );
}
add_filter('init', 'tinymce_init');

function opengraph_tinymce_checker($init) {
    // We create a new plugin... linked to a js file.
    // Mine was created from a plugin... but you can change this to link to a file in your plugin
    $init['keyup_event'] = get_template_directory_uri() . '/js/opengraph_tinymce_checker.js';
    return $init;
}


function plus_post(){
	$content = '';//<ul id="furl-list"></ul> was awkward anyway
    $settings =   array(
            'wpautop' => true,
            'editor_height' => '400',
            'media_buttons' => true,
            'selector' => 'textarea',
            'tabindex' => '5',
            'editor_css' => '', 
            'editor_class' => '',
            'textarea_name' => 'pluscontent',
            'paste_remove_spans' => true,
            'teeny' => false,
            'dfw' => true,
            'tinymce' => true,
            'quicktags' => false,
            //'plugins' => 'tinymceEmoji',
            'tinymce' => array(
		         'toolbar1'=> 'bold,italic,underline,link',//unlink and tinymceEmoji removed 
		         'toolbar2' => '',
		         'toolbar3' => '',
		         'content_css' => get_stylesheet_directory_uri() . '/css/front-editor-styles.css',
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
	echo '<form id="plus-form" method="post" action="' . admin_url( 'admin-post.php' ) .'">';
}

//add_shortcode( 'the-form', 'form_builder' );


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

	if(isset($_SESSION['message']))
	{
	    echo $_SESSION['message'];
	    unset($_SESSION['message']);
	}

	plus_post_creation($title, $body);
	//var_dump($_POST);
	header('Location: ' . get_home_url()); //redirect to page to reload
}


function menubar_user_icon(){
	$logged_in = get_current_user_id();
	$current_img = get_avatar_url($logged_in, array('width'=>'36','height'=>'36'));	
	return '<a href="'.get_edit_user_link($logged_in).'"><img class="plus-logged-menu" src="' . $current_img . '"></a>';
}



//SORT OF WORKS FOR WORD COUNT IN INTERNAL . . . 
function akv3_editor_char_count() {
?>
<script type="text/javascript">
(function($) {
	wpCharCount = function(txt) {
		$('.char-count').html("" + txt.length);
	};
	$(document).ready(function() {
		$('#wp-word-count').append('<br />Char count: <span class="char-count">0</span>');
	}).bind( 'wpcountwords', function(e, txt) {
		wpCharCount(txt);
	});
	$('#content').bind('keyup', function() {
		wpCharCount($('#content').val());
	});
}(jQuery));
</script>
<?php
}
//add_action('dbx_post_sidebar', 'akv3_editor_char_count');



//from https://code.tutsplus.com/articles/quick-tip-automatically-link-twitter-handles-with-a-content-filter--wp-26134
function wptuts_twitter_handles($content) {
    $pattern = '/(?<=^|(?<=[^a-zA-Z0-9-_\.]))@([A-Za-z]+[A-Za-z0-9_]+)/i';
    $replace = '<a href="http://www.twitter.com/$1">@$1</a>';
    $content = preg_replace($pattern, $replace, $content);
    $content = t_co_link_maker($content);
    return $content;
}
 
//add_filter( "the_content", "wptuts_twitter_handles" );
