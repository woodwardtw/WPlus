//from https://wordpress.stackexchange.com/questions/166077/keyup-events-in-tinymce-editor-not-working

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
        console.log(content);
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
	let getResults = getUrls(content)

	function getUrls(value) {
	  let regEx = /(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/igm;
	   let urls = value.match(regEx)
	  console.log(urls);
	  return urls;
	}


	getResults.forEach(function(link) {
	  let theLink = link;
	  //maybe do this in a smarter way
	  if (theLink.includes("youtube.com") || theLink.includes("twitter.com") || theLink.includes("vimeo.com") ){
	  	return;
	  }
	  let url = 'https://bionicteaching.com/tools/open-graph-api/?url=' + link

	  fetch(url)
	    .then(function(response) {
	      return response.json();
	    })
	    .then(function(myJson) {
	      var data = JSON.stringify(myJson);
	      console.log(data)
	      makePreviews(content,JSON.parse(data),theLink);
	    });


	  function makePreviews(destination, data, theLink){
	    //let destination = document.getElementById('furl-list');
	
	    let theName = '';
	    if (data.hasOwnProperty('siteName') && data.siteName != null){
	       theName = data.siteName;
	    }
	    
	    let title = theLink;
	    if ( data.title && data.title != theName && data.title != theLink && data.title != null){
	      title = data.title;
	    }
	    
	    let description = ''
	    if (data.hasOwnProperty('description') && data.description != null){
	        description = data.description;
	        }
	    
	    let img = 'https://via.placeholder.com/150';
	    if (data.hasOwnProperty('images') && data['images'].length != 0 && data['images'] != null){
	      img = data.images[0].url;
	    } 
	    if (data.siteName === null && data.title === null && data.description === null){
	    	return;
	    }
	    let text = '<div><img src="'+img+'"><h2><a href="'+theLink+'">' + theName + '</a></h2>' + '<div class="title"><p><a href="'+theLink+'">' + title + '</a></p><p><a href="'+theLink+'">'+description+'</a></p></div></div>';
	    //tinymce.activeEditor.execCommand('mceInsertContent', false, text);
		tinymce.activeEditor.setContent(text, {format: 'raw'})
	  }
	});
}


fetch(baseUrl + 'api/Customer/GetCustomerAccount/' + value, {
    method: 'GET',
  })
  .then((response) => response.json())
  .then((responseJson) => {
    this.myFunction(responseJson);
  })


