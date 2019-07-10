// jQuery('document').ready(function($){
//     // Get the comment form
//     var commentform = $('.comment-form');
//     // Add a Comment Status message
//     commentform.prepend('<div id="comment-status" ></div>');
//     // Defining the Status message element 
//     var statusdiv=$('#comment-status');
//     commentform.submit(function(){
//       // Serialize and store form data
//       var formdata=commentform.serialize();
//       //Add a status message
//       statusdiv.html('<p class="ajax-placeholder">Processing...</p>');
//       //Extract action URL from commentform
//       var formurl=commentform.attr('action');
//       //Post Form with data
//       jQuery.ajax({
//         type: 'post',
//         url: formurl,
//         data: formdata,
//         error: function(XMLHttpRequest, textStatus, errorThrown){
//           statusdiv.html('<p class="ajax-error" >You might have left one of the fields blank, or be posting too quickly</p>');
//         },
//         success: function(data, textStatus){
//           if(data=="success")
//             statusdiv.html('<p class="ajax-success" >Thanks for your comment. We appreciate your response.</p>');
//           else
//             statusdiv.html('<p class="ajax-error" >Please wait a while before posting your next comment</p>');
//           commentform.find('textarea[name=comment]').val('');
//         }
//       });
//       return false;
//     });
//   });

/*
 * Let's begin with validation functions
 */
 jQuery.extend(jQuery.fn, {
  /*
   * check if field value lenth more than 3 symbols ( for name and comment ) 
   */
  validate: function () {
    if (jQuery(this).val().length < 3) {jQuery(this).addClass('error');return false} else {jQuery(this).removeClass('error');return true}
  },
  /*
   * check if email is correct
   * add to your CSS the styles of .error field, for example border-color:red;
   */
  validateEmail: function () {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
        emailToValidate = jQuery(this).val();
    if (!emailReg.test( emailToValidate ) || emailToValidate == "") {
      jQuery(this).addClass('error');return false
    } else {
      jQuery(this).removeClass('error');return true
    }
  },
});
 
jQuery(function($){
 
  /*
   * On comment form submit
   */
  $( '#commentform' ).submit(function(){
 
    // define some vars
    var button = $('#submit'), // submit button
        respond = $('#respond'), // comment form container
        commentlist = $('.comment-list'), // comment list container
        cancelreplylink = $('#cancel-comment-reply-link');
 
    // if user is logged in, do not validate author and email fields
    if( $( '#author' ).length )
      $( '#author' ).validate();
 
    if( $( '#email' ).length )
      $( '#email' ).validateEmail();
 
    // validate comment in any case
    $( '#comment' ).validate();
 
    // if comment form isn't in process, submit it
    if ( !button.hasClass( 'loadingform' ) && !$( '#author' ).hasClass( 'error' ) && !$( '#email' ).hasClass( 'error' ) && !$( '#comment' ).hasClass( 'error' ) ){
 
      // ajax request
      $.ajax({
        type : 'POST',
        url : misha_ajax_comment_params.ajaxurl, // admin-ajax.php URL
        data: $(this).serialize() + '&action=ajaxcomments', // send form data + action parameter
        beforeSend: function(xhr){
          // what to do just after the form has been submitted
          button.addClass('loadingform').val('Loading...');
        },
        error: function (request, status, error) {
          if( status == 500 ){
            alert( 'Error while adding comment' );
          } else if( status == 'timeout' ){
            alert('Error: Server doesn\'t respond.');
          } else {
            // process WordPress errors
            var wpErrorHtml = request.responseText.split("<p>"),
              wpErrorStr = wpErrorHtml[1].split("</p>");
 
            alert( wpErrorStr[0] );
          }
        },
        success: function ( addedCommentHTML ) {
 
          // if this post already has comments
          if( commentlist.length > 0 ){
 
            // if in reply to another comment
            if( respond.parent().hasClass( 'comment' ) ){
 
              // if the other replies exist
              if( respond.parent().children( '.children' ).length ){  
                respond.parent().children( '.children' ).append( addedCommentHTML );
              } else {
                // if no replies, add <ol class="children">
                addedCommentHTML = '<ol class="children">' + addedCommentHTML + '</ol>';
                respond.parent().append( addedCommentHTML );
              }
              // close respond form
              cancelreplylink.trigger("click");
            } else {
              // simple comment
              commentlist.append( addedCommentHTML );
            }
          }else{
            // if no comments yet
            addedCommentHTML = '<ol class="comment-list">' + addedCommentHTML + '</ol>';
            respond.before( $(addedCommentHTML) );
          }
          // clear textarea field
          $('#comment').val('');
        },
        complete: function(){
          // what to do after a comment has been added
          button.removeClass( 'loadingform' ).val( 'Post Comment' );
        }
      });
    }
    return false;
  });
});