//from https://wordpress.stackexchange.com/questions/166077/keyup-events-in-tinymce-editor-not-working

jQuery(document).ready(function($) {

    // Create 'keyup_event' tinymce plugin
    tinymce.PluginManager.add('keyup_event', function(editor, url) {

        // Create keyup event
        editor.on('keyup', function(e) {

            // Get the editor content (html)
            get_ed_content = tinymce.activeEditor.getContent();
            // Do stuff here... (run do_stuff_here() function)            
            openGraphMatch(get_ed_content);
        });
    });

    // This is needed for running the keyup event in the text (HTML) view of the editor
    $('#content').on('keyup', function(e) {

        // Get the editor content (html)
        get_ed_content = tinymce.activeEditor.getContent();
        // Do stuff here... (run do_stuff_here() function)
        do_stuff_here(get_ed_content);
    });

    // This function allows the script to run from both locations (visual and text)
    function openGraphMatch(content) {

        // Now, you can further process the data in a single function
        console.log(content);
        console.log(validateUrl(content))
    }
});


//url tester https://stackoverflow.com/questions/8667070/javascript-regular-expression-to-validate-url
function validateUrl(value) {
  return /^(?:(?:(?:<p>https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?<\/p>/i.test(value);
}