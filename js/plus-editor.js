//select title on launch of modal
jQuery('#plus-post').on('shown.bs.modal', function () {
    jQuery('#plus_title').focus();     
})  

jQuery('#plus-post').on('hide.bs.modal', function () {
    console.log('hide') 
    tinyMCE.get('mypluspost').setContent('')//cleans out editor if canceled
    jQuery('.writing-circle').animate({
	    opacity: 1,
	    height: 60,
	    width: 60,
	  }, 500, function() {
	    // Animation complete.
	  });   
}) 

 

jQuery( "#red-writing-button, #write" ).click(function() {
 jQuery('.writing-circle').animate({
	    opacity: 0,
	    height: 0,
	    width: 0,
	  }, 400, function() {
	    // Animation complete.
	  });  
});

/*
**
SHOW EXISTING COMMENTS LAND
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
	  	buttonHolderID = 'submit-'+box.id.substring(8, box.id.length)
	    document.getElementById(buttonHolderID).parentNode.style.height = '60px';	   	
	  });
	});
}


function build_comments_api(id){
	//wp-json/wp/v2/comments?post=5198	
	let url = 'wp-json/wp/v2/comments?post=' + id;

	jQuery('#show-'+id).animate({
			    opacity: 0,
			    height: 0
			  }, 300, function() {
			    // Animation complete.
			    jQuery('#show-'+id).remove();
			  });   

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
	      	jQuery('#comment-home-'+id).append('<div class="comment-single"><img class="comment-author-img" src="'+author_img+'"><div class="comment-author">'+commentAuthor+'</div>'+comment.content.rendered+'</div>').show('normal');
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
    jQuery(".pinned").click(function(){
        pin = jQuery(this);
        console.log('pinned');
     
        // Retrieve post ID from data attribute
        post_id = pin.data("post_id");
         
        // Ajax call
        jQuery.ajax({
            type: "post",
            url: ajax_var.url,
            data: "action=unstick_post&nonce="+ajax_var.nonce+"&unstick_post=&post_id="+post_id,
            success: function(){               
            }
        });
         
        return false;
    })
})


//editor block buttons
jQuery(document).ready(function() {
	let editorButtons = document.querySelectorAll('.editor-button')
	editorButtons.forEach((button) => {
	  button.addEventListener('click', () => {
	    let post = button.getAttribute('data-post')
	    jQuery('#edit-block-'+post).toggleClass( "show-me" )
	    console.log('edit')
	  });
	});
})


//fetch wp json shortcode 

function checkResourcePostsDiv(){
 
//see if we've got an anth-posts div before running all this stuff
  try {
  var element = document.getElementById('neat-getposts');
  console.log(element);
    return element;
  }
  catch(err) {
      console.log(err.message);
  }
}

  if (checkResourcePostsDiv()){ 
  var element = document.getElementById('neat-getposts');
  var num = element.dataset.num;
  var url = sourceUrl();
   
    jQuery(document).ready(function() {
      var def = new jQuery.Deferred();
      jQuery.ajax({
        url: url,
        jsonp: "cb",
        dataType: 'json',
        success: function(data) {
            console.log(data); //dumps the data to the console to check if the callback is made successfully.
            jQuery.each(data, function(index, item) {
              jQuery('#neat-getposts').append('<div class="alt-resource-item col-md-3">'+resourceBackgroundImg(item)+'<a href="'+item.link+'"><h2 class="alt-posts-title">'+item.title.rendered+'</h2></a><p class="alt-post-excerpt">'+item.excerpt.rendered+'</p></div>' );
            }); //each          
          } //success
      }); //ajax  
    }); //ready





function sourceUrl(){
   var element = document.getElementById('neat-getposts'); 
      if(element.dataset.url != null){
        var url = element.dataset.url;
    } 
   return  url;
}  


//sets the background image based on the featured image or returns a default image
function resourceBackgroundImg (item) {
    var element = document.getElementById('neat-getposts'); 
      if(element.dataset.img == 'true'){
      try {
        var imgUrl = item._embedded['wp:featuredmedia'][0].media_details.sizes.medium.source_url;
        var alt = item._embedded['wp:featuredmedia'][0].alt_text;
        //console.log(imgUrl);
        return '<img class="alt-get-img" src="'+imgUrl+'" alt="'+alt+'">';
      }
    catch(err) {
        return '<img class="alt-get-img" src="">';
      }
    }
  }

function resourceUrl(item){
   return '<a href="' + item.meta.resource_url + '">';
}    

//chops up the date item a bit
    function dateDisplay(item){
      return item.date.substring(5,10);
    }
    

    var $loading = jQuery('#loading').hide();
      jQuery(document)
        .ajaxStart(function () {
          $loading.show();
        })
        .ajaxStop(function () {
          $loading.hide();
        });

}


//get the category restriction in data-cats or data-authors if either or both exists
function getResourceRestrictions(){
    var element = document.getElementById('anth-resource'); 
    if(element.dataset.cats){
      var cats = '&categories='+element.dataset.cats;
    } else {
      cats = "";
    }
    if(element.dataset.authors){
      var authors = '&author='+element.dataset.authors;
    }else {
      authors = "";
    }
    return cats + authors;
}  

//remove double images when featured img matches first image in post body
if (document.querySelectorAll('.wp-post-image')>0){
	let featured = document.querySelectorAll('.wp-post-image')[0]
	let content = document.querySelectorAll('.entry-content')[0]
	let firstImg = content.querySelectorAll('img')[0]

	if (featured.src === firstImg.src){
	  //firstImg.classList.add('hidden') //for 
	  firstImg.remove()
	}

}


//remove double for plus images
if(document.querySelectorAll('.card')){
	let cards = document.querySelectorAll('.card')
	cards.forEach(function(card){
		let cardFeatured = document.querySelectorAll('.plus-photo')[0]
		console.log(cardFeatured.srcset.split(','))
		let cardContent = document.querySelectorAll('.card-text')[0]
		let cardFirstImg = cardContent.querySelectorAll('img')[0]
		console.log(cardFirstImg.srcset.split(','))

		if (cardFeatured.srcset.split(',')[3] === cardFirstImg.srcset.split(',')[3]){
		  cardFirstImg.remove()
		}

	})

}

//do search institute buttons

if (document.querySelectorAll('#institute-search')) {
	let buttons = document.querySelectorAll('.searcher')
	buttons.forEach((button) => {
	  button.addEventListener('click', () => {	  	 
	    if (button.id === 'all' || jQuery('#'+button.id).hasClass('active') === true){
	    	jQuery('.author-holder a').removeClass('hide');
	    	jQuery('#'+button.id).toggleClass('active'); 
	    } else {
	    	jQuery('.searcher').not('#'+button.id).removeClass('active');
	  	    jQuery('#'+button.id).toggleClass('active'); 
	        jQuery('.author-holder a').removeClass('hide');
	    	jQuery('.author-holder a').not('.'+button.id).toggleClass('hide');
	    }
	  });
	});
}