//select title on launch of modal
jQuery('#plus-post').on('shown.bs.modal', function () {
    jQuery('#plus_title').focus();    
})  

jQuery('#plus-post').on('hide.bs.modal', function () {
    console.log('hide') 
    tinyMCE.get('mypluspost').setContent('')//cleans out editor if canceled
}) 

/*
**
EXISTING COMMENTS LAND
**
*/

jQuery( document ).ready(function() {
	console.log('ready');
		activate_see_comments_buttons();
		showCommentSubmit();
	})

function activate_see_comments_buttons(){
	let buttons = document.querySelectorAll('.see-comments');
	buttons.forEach((button) => {
	  button.addEventListener('click', () => {
	    let postId = button.dataset.postid;
	    build_comments_api(postId);
	  });
	});
}

function showCommentSubmit(){
	let commentBoxes = document.querySelectorAll('textarea');
	commentBoxes.forEach((box) => {
	  box.addEventListener('click', () => {
	  	console.log(box.id)
	  	buttonHolderID = 'submit-'+box.id.substring(8, box.id.length)
	    document.getElementById(buttonHolderID).parentNode.style.height = '50px';
	   	
	  });
	});
}


function build_comments_api(id){
	//wp-json/wp/v2/comments?post=5198	
	let url = 'wp-json/wp/v2/comments?post=' + id;

	  fetch(url)
	    .then(function(response) {
	      return response.json();
	    })
	    .then(function(myJson) {
	      var data = JSON.stringify(myJson);
	      
	      var comments = JSON.parse(data);
	      console.log(comments);
	       comments.forEach(function(comment){
	      	let commentBody = comment.content.rendered;
	      	let commentAuthor = comment.author_name;
	      	if (comment.comment_author_img){
	      		var author_img = comment.comment_author_img;
	      	} else {
	      		var author_img = comment.author_avatar_urls[48]
	      	}
	      	jQuery('#comment-post-'+id + ' button').hide('fast');
	      	jQuery('#comment-home-'+id).append('<div class="comment-single"><img class="comment-author-img" src="'+author_img+'"><div class="comment-author">'+commentAuthor+'</div>'+comment.content.rendered+'</div>').show('slow');
	      })
	    });
}


/*
**
MODAL LAND
**
*/
//this deals with the modal tinymce issues
jQuery(document).on('focusin', function(e) {
    if (jQuery(e.target).closest(".wp-link-input").length || jQuery(e.target).closest("#link-selector").length) {
        e.stopImmediatePropagation();
    }
});



//full size youtube video stuff
var videos = document.querySelectorAll('iframe[src^="https://www.youtube.com/"], iframe[src^="https://player.vimeo.com"], iframe[src^="https://www.youtube-nocookie.com/"], iframe[src^="https://www.nytimes.com/"]'); //get video iframes for regular youtube, privacy+ youtube, and vimeo


videos.forEach(function(video) {
   let wrapper = document.createElement('div'); //create wrapper 
      wrapper.classList.add("video-responsive"); //give wrapper the class      
      video.parentNode.insertBefore(wrapper, video); //insert wrapper      
      wrapper.appendChild(video); // move video into wrapper
});


//PREVENTS UNINTENTIONAL RAPID SUBMIT OF FORM ON THE FRONT END
jQuery( "#plus-form" ).submit(function( event ) {
   jQuery(this).submit(function() {
        return false;
    });
    return true;
});



//auto insert image on drag upload https://wordpress.stackexchange.com/questions/167143/insert-image-automatically-when-upload-finishes-wordpress-media-uploader
//to do - try to deal w making media library view not happen
jQuery( document ).ready(function() {
    typeof wp.Uploader !== 'undefined' && wp.Uploader.queue.on( 'reset', function () {
        // From the primary toolbar (".media-toolbar-primary")
        // get the insert button view (".media-button-insert")
        // and execute its click (as specified in its options).
        //document.getElementById('__wp-uploader-id-2').style.display = 'none';
        wp.media.frame.toolbar.get('primary').get('insert').options.click();
    } );
});


//LIKE BUTTON

jQuery(document).ready(function() {
 
    jQuery(".like-button").click(function(){
     	console.log(jQuery(this));
        heart = jQuery(this);
     
        // Retrieve post ID from data attribute
        post_id = heart.data("post_id");
         
        // Ajax call
        jQuery.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=post-like&nonce="+ajax_var.nonce+"&post_like=&post_id="+post_id,
            success: function(count){
                // If vote successful
                if(count != "already")
                {
                    heart.addClass("voted");
                    heart.siblings(".count").text(count);
                }
            }
        });
         
        return false;
    })
})