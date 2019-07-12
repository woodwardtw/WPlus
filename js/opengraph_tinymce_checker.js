jQuery(document).ready(function($) {

    // Create 'keyup_event' tinymce plugin
    tinymce.PluginManager.add('keyup_event', function(editor, url) {

        // Create keyup event
        editor.on('keyup', function(e) {

            // Get the editor content (html)
            get_ed_content = tinymce.activeEditor.getContent();
            // Do stuff here... (run do_stuff_here() function)            
            do_stuff_here(get_ed_content);
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
    function do_stuff_here(content) {

        // Now, you can further process the data in a single function
        console.log(content);
    }
});