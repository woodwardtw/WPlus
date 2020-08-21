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
	
	if ( get_query_var('paged') ) {
        $paged = get_query_var('paged'); 
    } elseif ( get_query_var('page') ) { 
        $paged = get_query_var('page'); 
    } else { 
        $paged = 1; 
    }

	$plus_args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'orderby' => 'date',
		'posts_per_page' => 50,
		'paged' => $paged,
	);

	$plus_query = new WP_Query( $plus_args );
	$html = '';
	$logged_in = get_current_user_id();
	$current_img = get_avatar_url($logged_in, array('width'=>'36','height'=>'36'));	
	// The Loop
	if ( $plus_query->have_posts() ) {
		echo '<div class="row plus" id="gplus">';
		if(is_user_logged_in()){
		$html .= '<div class="col-md-3 card-holder" data-order="1000000000000"><div class="card"><div class="plus-author"><img class="plus-author-photo" src="'. $current_img . '" alt="Author profile photo."><div class="whats-new"><button id="write"  data-toggle="modal" data-target="#plus-post">What\'s new with you?</button></div></div></div></div>';
		}
		$count = 1;
		while ( $plus_query->have_posts() ) {
			$count++;
			$plus_query->the_post();
			$post_id = get_the_id();
			$name = get_the_author_meta('display_name');					
			$author_id = get_the_author_meta('ID');
			$author_img = get_avatar_url($author_id, array('width'=>'36','height'=>'36'));
			$html .= '<div data-sort="'.$count.'" class="card-holder col-md-3' . sticky_true($post_id) . '"><div class="card" data-order="'. get_post_timestamp($post_id) . '">';
			if(sticky_true($post_id) === ' sticky '){
				$html .= '<i class="fa fa-thumb-tack pinned" aria-label="Pinned post." title="This post is pinned." id="pin-' . $post_id . '" data-post_id="' . $post_id .'"></i>';
			}
			$html .= '<div class="plus-author"><img class="plus-author-photo" src="'. $author_img . '" alt="author profile image.">';
			$html .= '<div class="plus-author-name"><a href="'. get_author_posts_url($author_id) . '">' . $name .'</a></div>';
			$html .= '<div class="plus-date">' . get_the_date( 'M j, Y' ) . '<button class="fa fa-ellipsis-v editor-button" data-post="'.$post_id.'"></button></div><div class="edit-block" id="edit-block-'.$post_id.'">';
			$html .=  edit_it($post_id, $author_id) . post_go_away($post_id) .'</div></div>';
			if(get_the_title()){
				$html .= '<a href="' . get_post_permalink() . '"><h2>' . get_the_title() . '</h2></a>';
			}
			$html .= get_the_post_thumbnail($post_id, 'medium', array( 'class' => 'plus-photo img-' . $post_id ) );
			$html .= '<div class="card-text">' . apply_filters('the_content', get_the_content()) . '</div>';
			$html .= '<div id="comment-post-' . $post_id . '">';
			$html .= '<div class="comment-count">' . comment_count($post_id) . '</div>';
			$html .= display_comments_shortcode();//do_shortcode("[display_comments]");
			$html .= wplus_rater($post_id);
			$html .= '</div></div></div>';
		}
		echo $html; 

		//from https://stackoverflow.com/questions/11430392/wordpress-pagination-in-a-shortcode
		$big = 999999999; // need an unlikely integer
		echo '</div><div class="row"><div class="col-md-12 d-flex justify-content-center">'.paginate_links( array(
		   'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		   'format' => '?paged=%#%',
		   'current' => max( 1, $paged ),//watch out that this sticks with lines 40-46
		   'total' => $plus_query->max_num_pages, // your custom query
		   'prev_next' => true,
 		) ); 
 		echo '</div></div>';
		
		
		wp_reset_postdata();
	} else {
		// no posts found
	}

}


function edit_it($post_id, $author_id){
	if ($author_id === get_current_user_id() || current_user_can('administrator')){
		$link = get_edit_post_link( $post_id );
		return '<a href="' . $link . '">edit</a>';
	}
}


function sticky_true($post_id){
	if ( is_sticky($post_id) ) {
     	return ' sticky ';
	} 
}

/*
PLUS RATER ThiNG adapted slightly from https://code.tutsplus.com/articles/how-to-create-a-simple-post-rating-system-with-wordpress-and-jquery--wp-24474
*/

function wplus_rater($post_id){
	return '<p class="post-like">
    <button data-post_id="' . $post_id . '" class="like-button">
        <span class="qtip like" title="I like this article">+1</span>
    </button>
    <span class="count">' . get_post_meta($post_id, 'votes_count', true) . '</span>
</p>';
}

add_action('wp_ajax_nopriv_post-like', 'wplus_post_like');
add_action('wp_ajax_post-like', 'wplus_post_like');

function wplus_post_like()
{
    // Check for nonce security
    $nonce = $_POST['nonce'];
  
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');
     
    if(isset($_POST['post_like']))
    {
        // Retrieve user IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        $post_id = $_POST['post_id'];
         
        // Get voters'IPs for the current post
        $meta_IP = get_post_meta($post_id, "voted_IP");
        $voted_IP = $meta_IP[0];
 
        if(!is_array($voted_IP))
            $voted_IP = array();
         
        // Get votes count for the current post
        $meta_count = get_post_meta($post_id, "votes_count", true);
 
        // Use has already voted ?
        if(!hasAlreadyVoted($post_id))
        {
            $voted_IP[$ip] = time();
 
            // Save IP and increase votes count
            update_post_meta($post_id, "voted_IP", $voted_IP);
            update_post_meta($post_id, "votes_count", ++$meta_count);
             
            // Display count (ie jQuery return value)
            echo $meta_count;
        }
        else
            echo "already";
    }
    exit;
}

function hasAlreadyVoted($post_id)
{
    global $timebeforerevote;
 
    // Retrieve post votes IPs
    $meta_IP = get_post_meta($post_id, "voted_IP");
    $voted_IP = $meta_IP[0];
     
    if(!is_array($voted_IP))
        $voted_IP = array();
         
    // Retrieve current user IP
    $ip = $_SERVER['REMOTE_ADDR'];
     
    // If user has already voted
    if(in_array($ip, array_keys($voted_IP)))
    {
        $time = $voted_IP[$ip];
        $now = time();
         
        // Compare between current time and vote time
        if(round(($now - $time) / 60) > $timebeforerevote)
            return false;
             
        return true;
    }
     
    return false;
}

//UNSTICK POST
if (current_user_can('edit_others_posts')) {
	add_action('wp_ajax_unstick_post', 'wplus_unstick_post');
}

function wplus_unstick_post()
{
    // Check for nonce security
    $nonce = $_POST['nonce'];
  
    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');
     
    if(isset($_POST['unstick_post']))
    {
        // Retrieve user IP address
        $ip = $_SERVER['REMOTE_ADDR'];
        $post_id = $_POST['post_id'];
        unstick_post($post_id);
    }
    exit;
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
    $arg['comment_field'] = '<div class="comment-form-comment"><div class="plus-comment-box"><img class="plus-logged" src="' . $current_img . '" alt="user photo"><textarea id="comment-'. $post->ID .'" name="comment" cols="45" rows="1" aria-required="true" aria-label="Comment" placeholder="Add a comment..."></textarea></div></div>';
     $defaults['title_reply_before'] = '<span id="reply-title-'.$post->ID.'" class="comment-reply-title">';
    return $arg;
}

add_filter('comment_form_defaults', 'wpsites_modify_comment_form_text_area');



function comment_count($post_id){
	$num = get_comments_number();
	if ($num > 0){
		return '<button class="see-comments" id="show-' . $post_id . '" data-postId="' . $post_id . '">Show all ' . $num . ' comments.</button><div class="comment-holder" id="comment-home-'.$post_id.'"></div>';
	}
}

function plus_author_image(){
	$author_id = get_the_author_meta('ID');
	echo get_avatar_url($author_id, array('width'=>'36','height'=>'36'));
}

function plus_author_name(){
	echo get_the_author_meta('display_name');
}

//FRONTEND wp_editor 
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
//https://ipfs-sec.stackexchange.cloudflare-ipfs.com/wordpress/A/question/166077.html
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

//make tinymce settings
function plus_post(){
	$content = 'Write your post here . . . ';
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

function plus_post_creation($title, $body, $my_cats, $sticky) {
    $my_post = array(
	  'post_title'    => wp_strip_all_tags( $title, true ), 
	  'post_content'  => $body, 
	  'post_status'   => 'publish',
	  'post_author'   => get_current_user_id(),
	  'post_category' => $my_cats
	);
	 
	// Insert the post into the database
	$post_id = wp_insert_post( $my_post );
	//make sticky if sticky on
	if ($sticky === 'on'){
		stick_post($post_id);
	}
}

function get_cat_ids($cats){
	$cat_ids = [];
	if ($cats){
		foreach ($cats as $cat ) {
		    $cat_id = get_category_by_slug($cat)->term_id;
		    array_push($cat_ids, $cat_id); 
		}
	}
	//var_dump($cat_ids);
	return $cat_ids;
}


function form_builder(){
	echo '<form id="plus-form" method="post" action="' . admin_url( 'admin-post.php' ) .'">';
}

//add_shortcode( 'the-form', 'form_builder' );


add_action( 'admin_post_process_form', 'process_form_data' );
function process_form_data() {

	if ( 
	    ! isset( $_POST['wplus_nonce'] ) 
	    || ! wp_verify_nonce( $_POST['wplus_nonce'], 'process_form' ) 
	) {

   print 'Sorry, your nonce did not verify.';
   exit;

	} else {

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
		if(isset($_POST['postCategories'])){
			$cats = $_POST['postCategories'];
			$my_cats = get_cat_ids($cats);
		} else {
			$my_cats = '';
		}
		if(isset($_POST['sticky'])){
			$sticky = $_POST['sticky'];
		} else {
			$sticky = 'off';
		}
		if(isset($_SESSION['message']))
		{
		    echo $_SESSION['message'];
		    unset($_SESSION['message']);
		}

		plus_post_creation($title, $body, $my_cats, $sticky);
		//var_dump($_POST);
		header('Location: ' . get_home_url()); //redirect to page to reload
	}
}



function menubar_user_icon(){
	if(get_current_user_id()){
		$logged_in = get_current_user_id();
		$current_img = get_avatar_url($logged_in, array('width'=>'36','height'=>'36'));	
		return '<a href="'.get_edit_user_link($logged_in).'"><img class="plus-logged-menu" src="' . $current_img . '" alt="user profile image"></a>';
	}
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
function get_twitter_handles($content) {
    $pattern = '/(?<=^|(?<=[^a-zA-Z0-9-_\.]))@([A-Za-z]+[A-Za-z0-9_]+)/i';
    $replace = '<a href="http://www.twitter.com/$1">@$1</a>';
    $content = preg_replace($pattern, $replace, $content);
    $content = t_co_link_maker($content);
    return $content;
}
 
//add_filter( "the_content", "get_twitter_handles" );


/*
Plugin Name: Image P tag remover
Description: Plugin to remove p tags from around images in content outputting, after WP autop filter has added them. (oh the irony)
Version: 1.0
Author: Fublo Ltd
Author URI: http://fublo.net/
*/

function filter_ptags_on_images($content)
{
    // do a regular expression replace...
    // find all p tags that have just
    // <p>maybe some white space<img all stuff up to /> then maybe whitespace </p>
    // replace it with just the image tag...
    return preg_replace('/<p>(\s*)(<img .* \/>)(\s*)<\/p>/iU', '\2', $content);
}

// we want it to be run after the autop stuff... 10 is default.
add_filter('the_content', 'filter_ptags_on_images');


//add filter for title when post has no title BUT ONLY ON ADMIN SIDE
add_filter('the_title', 'new_title', 10, 2);
function new_title($title, $id) {
	if ( is_admin() ) {
	    if ($title === null || $title == '' || $title == '(no title)'){
	    	$title = super_short_excerpt();
		}
	}
    return $title;
}


function super_short_excerpt() {
    return wp_trim_words(get_the_excerpt(), 5);
}


function better_default_image_size() {
    // Set default values for the upload media box
    update_option('image_default_align', 'center' );
}
add_action('after_setup_theme', 'better_default_image_size');


//adds author image to comment JSON
add_action( 'rest_api_init', function () {
    register_rest_field( 'comment', 'comment_author_img', array(
        'get_callback' => function( $comment_arr ) {
            $comment_obj = get_comment( $comment_arr['id'] );
            $author_id = $comment_arr['author'];
            $current_img = get_avatar_url($author_id, array('width'=>'36','height'=>'36'));	
            return $comment_obj->comment_author_img = $current_img;
        },
        // 'update_callback' => function( $karma, $comment_obj ) {
        //     $ret = wp_update_comment( array(
        //         'comment_ID'    => $comment_obj->comment_ID,
        //         'comment_karma' => $karma
        //     ) );
        //     if ( false === $ret ) {
        //         return new WP_Error(
        //           'rest_comment_author_img_failed',
        //           __( 'Failed to update author image.' ),
        //           array( 'status' => 500 )
        //         );
        //     }
        //     return true;
        // },
        'schema' => array(
            'description' => __( 'comment_author_img' ),
            'type'        => 'string'
        ),
    ) );
} );


//ONL BUDDYPRESS Extended profile additions


function display_onl_profile_detail($user_id, $field) {
	//make sure field name parallels extended profile piece exactly
    $field = xprofile_get_field_data( $field, $user_id);
    if ($field) {
       return  $field ;
    }
}

function display_onl_authors_summary(){
	$users = get_users();
	$html = '<div class="author-holder">';	
	$active_locations = ['All'];// build list of active locations to use to make buttons
	foreach ($users as $user) {
		if (display_onl_profile_detail($user->ID, 'Institution')){
			$institute = display_onl_profile_detail($user->ID, 'Institution');
			array_push($active_locations, $institute);
			$institute_clean = sanitize_title($institute);
		} else {
			$institute_clean = 'none';
		}
		   //echo $user->ID;http://192.168.33.10/wordpress/plus/author/ffake/
		   $html .= '<a class="all ' . $institute_clean . '" href="' . get_author_posts_url( $user->ID ) . '">';
		   $html .= '<div class="single-author">';
		   $html .= '<img src="'.get_avatar_url($user->ID).'" alt="Avatar.">';
		   $html .= '<h2>' . $user->display_name . '</h2>';
		   $html .= '</div></a>';
		}
		return  make_user_buttons($active_locations) . $html . '</div>';
}

add_shortcode( 'onl-authors', 'display_onl_authors_summary' );

function make_user_buttons($locations){
	$html = '<button class="btn btn-onl" type="button" data-toggle="collapse" data-target="#author-search" aria-expanded="false" aria-controls="collapseExample">
    Filter by Institution
  </button><div class="collapse" id="author-search"><div id="institute-search">';
	$unique_locations = array_unique($locations);
	foreach ($unique_locations as $key => $location) {
		$html .= '<button class="searcher" id="' . sanitize_title($location) . '">' . $location . '</button>';
	}
	return $html . '</div></div>';
}

//USER BLOGS SHORTCODE

function onl_get_user_blogs(){
	$user_id = get_current_user_id();
	if(get_current_user_id()){
		$user_blogs = get_blogs_of_user( $user_id );
			echo '<h3>Your Groups</h3><ul>';
			foreach ($user_blogs AS $user_blog) {
			    echo '<li><a href="' . $user_blog->siteurl .'">'.$user_blog->blogname.'</a></li>';
			}
		echo '</ul>';
	}
}

add_shortcode( 'onl-sites', 'onl_get_user_blogs');


//DELETE BLOG POST BUTTON

function post_go_away($post_id){
	$url = get_bloginfo('url');
	$html = '';
	  if (current_user_can('edit_post', $post_id)){
	  	$html .= '<a onclick="return confirm(\'Are you SURE you want to delete this post?\')" href="' .get_delete_post_link( $post_id ) .'">delete</a>';
	  }
	  return $html;
}

//SHORTCODE FOR showing wp json content 
function neat_getpost_shortcode( $atts, $content = null ) {
    extract(shortcode_atts( array(
         'url' => '', //base url      
         'display' => '', //defaults to list but grid with thumbnail featured images    
         'number' => '', 
         'featured' => '',
         'type' => '',  
         'custom' => '',
    ), $atts));         

    if($url){
        $url = 'data-url="'.$url.'"';
    }    
    if($number){
        $num = 'data-num="'.$number.'"';
    } else {
        $num = 'data-num="15"';
    }
     if($display){
        $display = 'data-display="'.$display.'"';
    } else {
        $display = 'data-display="list"';
    }
     if($featured){
        $display = 'data-img="'.$featured.'"';
    } else {
        $featured = 'data-img="false"';
    }
     if($type){
        $type = 'data-type="'.$type.'"';
    } else {
        $type = 'data-type="post"';
    }
     if($custom){
        $custom = 'data-custom="'.$custom.'"';
    } 
    $html = '<div class="container"><div id="neat-getposts" class="row" ' . $url . ' ' . $num . ' ' . $display .'></div></div>';

    return  $html;
}

add_shortcode( 'get-posts', 'neat_getpost_shortcode' );


//add image class to inserted images 
function add_image_class($class){

    $class .= ' additional-class';
    return $class;
}
add_filter('get_image_tag_class','add_image_class');




//WP API fix filtering from https://www.danielauener.com/wordpress-rest-api-extensions-for-going-headless-wp/
//wp-json/wp/v2/posts?categories=3,4&and=true

 /**
   * Ads AND relation on rest category filter queries
   */
  add_action( 'pre_get_posts', 'wuxt_override_relation' );
 
  function wuxt_override_relation( $query ) {
 
    // bail early when not a rest request
   if ( ! defined( 'REST_REQUEST' ) || ! REST_REQUEST ) {
      return;
   }
 
    // check if we want to force an "and" relation
    if ( ! isset( $_GET['and'] ) || !$_GET['and'] || 'false' === $_GET['and'] || !is_array( $tax_query = $query->get( 'tax_query' ) ) ) {
      return;
   }
 
    foreach ( $tax_query as $index => $tax ) {
      $tax_query[$index]['operator'] = 'AND';
    }
 
   $query->set( 'tax_query', $tax_query );
 
  }
 

 if ( function_exists('register_sidebar') )
  register_sidebar(array(
    'name' => 'Home Top',
    'before_widget' => '<div class="home-top-widget">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
    'id' => 'home-widg'
  )
);


//MENU SHORTCODE
//ties to menu for example    [menu-fetch name="cats"]
function wplus_menu_shortcode($atts){
	$a = shortcode_atts( array(
		'name' => '',
	), $atts );	

	$menu = wp_nav_menu( array(
    'menu'   => $a['name'],
    'echo'   => false,
	) );
	return $menu;
}

add_shortcode( 'menu-fetch', 'wplus_menu_shortcode' );


