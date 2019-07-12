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
  return /((<p>http(s)?(\:\/\/))+(www\.)?([\w\-\.\/])*(\.[a-zA-Z]{2,3}\/?))[^\s\b\n|]*[^.,;:\?\!\@\^\$ -]<\/p>/i.test(value);
}