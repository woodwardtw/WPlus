//from https://wordpress.stackexchange.com/questions/166077/keyup-events-in-tinymce-editor-not-working
	console.log('editor loaded')

jQuery(document).ready(function($) {
    // Create 'keyup_event' tinymce plugin
    tinymce.PluginManager.add('keyup_event', function(editor, url) {

        // Create keyup event
        editor.on('keydown', function(e) {
			if(e.keyCode == 13){ //just on return do we check
	            // Get the editor content (html)
	            get_ed_content = tinymce.activeEditor.getContent();

	            // Do stuff here... (run do_stuff_here() function)            
	            openGraphMatch(get_ed_content);
	        }
        });
    });

    // This is needed for running the keyup event in the text (HTML) view of the editor
    // $('#content').on('keyup', function(e) {

    //     // Get the editor content (html)
    //     get_ed_content = tinymce.activeEditor.getContent();
    //     var furl = tinyMCE.activeEditor;
    //     // Do stuff here... (run do_stuff_here() function)
    //     processOgText(get_ed_content, furl );
    // });

    // This function allows the script to run from both locations (visual and text)
    function openGraphMatch(content) {
        // Now, you can further process the data in a single function
        //console.log(content);
        console.log('what')
        processOgText(content);
    }
});


//url tester https://stackoverflow.com/questions/8667070/javascript-regular-expression-to-validate-url
// function validateUrl(value) {
//   return /((<p>http(s)?(\:\/\/))+(www\.)?([\w\-\.\/])*(\.[a-zA-Z]{2,3}\/?))[^\s\b\n|]*[^.,;:\?\!\@\^\$ -]<\/p>/i.test(value);
// }

// function getUrls(value) {
//   let regEx = /(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/igm;
//    let urls = value.match(regEx)
//   console.log(urls);
//   return urls;
// }


function processOgText(content){
	console.log(content);
	let getResults = getUrls(content);

	function getUrls(value) {
	  let regEx = /(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/igm;
	  let urls = value.match(regEx);
	  console.log(urls);
	  var unique = new Set(urls);
	  return unique;
	}


	getResults.forEach(function(link) {
	  let theLink = link;
	  //maybe do this in a smarter way
	  if (theLink.includes("youtube.com") || theLink.includes("twitter.com") || theLink.includes("vimeo.com") ){
	  	return;
	  }
	  let url = 'https://bionicteaching.com/tools/open-graph-api/?url=' + link;
	  console.log(url)

	  fetch(url)
	    .then(function(response) {
	    	console.log(response.json())
	      return response.json();
	    })
	    .then(function(myJson) {
	      var data = JSON.stringify(myJson);
	      console.log(data)
	      makePreviews(content,JSON.parse(data),theLink);
	    });


	  function makePreviews(destination, data, theLink){
	    //let destination = document.getElementById('furl-list');
	    let newData = data.values;
	    console.log(newData)
	    let theName = '';
	    if (newData.hasOwnProperty('title') && newData.title != null){
	       theName = newData.title;
	    }
	    
	    let description = ''
	    if (newData.hasOwnProperty('description') && newData.description != null){
	        description = newData.description;
	        }
	    
	    let img = 'https://via.placeholder.com/150';
	    if (newData.hasOwnProperty('images') && newData['images'].length != 0 && newData['images'] != null){
	      img = newData.images[0].url;
	    } 
	    if (newData.siteName === null && newData.title === null && newData.description === null){
	    	return;
	    }
	    let text = '<div class="furl-content"><img class="furl-img" src="'+img+'"><h2 class="furl-name"><a href="'+theLink+'">' + theName + '</a></h2>';
	    //text += '<div class="furl-title"><p><a href="'+theLink+'">' + title + '</a></p></div>';
	    text += '<p class="furl-description"><a class="done" href="'+theLink+'">'+description+'</a></p></div>';
	    let currentText = tinymce.activeEditor.getContent();
	    let urlRemoved = currentText.replace(theLink,'')
		tinymce.activeEditor.setContent(urlRemoved + text, {format: 'raw'})
	  }
	});
}

