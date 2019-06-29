jQuery(document).ready(function(){

	// tinymce.init({
 //    	selector: '#input_1_2'
 //  	});
 wp.editor.initialize( 'input_1_2', 
 	{
	 mediaButtons: true,
	 plugins:    [ "anchor link" ],
    tinymce:      {
        toolbar1: 'bold,italic,bullist,numlist,link,alignleft,aligncenter,alignright,forecolor'
    },
    quicktags:    true,
	}
 );


});


//this deals with the modal tinymce issues
jQuery(document).on('focusin', function(e) {
    if (jQuery(e.target).closest(".wp-link-input").length || jQuery(e.target).closest("#link-selector").length) {
        e.stopImmediatePropagation();
    }
});