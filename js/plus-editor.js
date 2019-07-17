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
		activate_see_comments_buttons();

	})

function activate_see_comments_buttons(){
	let buttons = document.querySelectorAll('see-comments');
	console.log(buttons);
	buttons.forEach((button) => {
	  button.addEventListener('click', () => {
	    console.log("forEach worked");
	  });
	});
}



function fetch_comments_api(){
	//wp-json/wp/v2/comments?post=5198
	console.log('click click')
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