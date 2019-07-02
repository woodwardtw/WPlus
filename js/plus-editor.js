jQuery(document).ready(function(){

	// tinymce.init({
 //    	selector: '#input_1_2'
 //  	});
//  wp.editor.initialize( 'input_1_2', 
//  	{
// 	 mediaButtons: true,
// 	 plugins : "autolink, lists, spellchecker, style, layer, table, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template",
// 	 //plugins:    [ "anchor link oembed" ],
//     tinymce:      {
//         toolbar1: 'bold,italic,bullist,numlist,link,alignleft,aligncenter,alignright,forecolor'
//     },
//     quicktags:    true,
// 	}
//  );


 });


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