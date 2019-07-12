//select title on launch of modal
jQuery('#plus-post').on('shown.bs.modal', function () {
    jQuery('#plus_title').focus();

})  

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

//maybe auto embed upload
jQuery(document).ready(function(){
    if (typeof 'switchUploader' === 'function') switchUploader(1);
    if(jQuery(".savesend input")){
        jQuery(".savesend input").click();
        console.log('fired it')
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


//PREVENTS RAPID SUBMIT OF FORM ON THE FRONT END
jQuery( "#plus-form" ).submit(function( event ) {
   jQuery(this).submit(function() {
        return false;
    });
    return true;
});



//IN PROGRESS

//from https://gist.github.com/RadGH/523bed274f307830752c

// function tmce_getContent(editor_id, textarea_id) {
//   if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
//   if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
  
//   if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
//     return tinyMCE.get(editor_id).getContent();
//   }else{
//     return jQuery('#'+textarea_id).val();
//   }
// }

// function watchEditor(){
// 	if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
// 	if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
	  
// 	  if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
// 	    .addEventListener('input', function() {
// 	    // event handling code for sane browsers
// 	    tmce_getContent();
// 	  }, false);
// 	  }
// }



//url tester https://stackoverflow.com/questions/8667070/javascript-regular-expression-to-validate-url
function validateUrl(value) {
  return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(value);
}


