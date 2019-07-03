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
