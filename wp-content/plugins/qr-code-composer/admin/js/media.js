jQuery(document).ready(function ($) {
 
    var wooqr_uploader;
    $('#wooqr_upload_button').click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (wooqr_uploader) {
            wooqr_uploader.open();
            return;
        }
        //Extend the wp.media object
        wooqr_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        wooqr_uploader.on('select', function() {
            attachment = wooqr_uploader.state().get('selection').first().toJSON();
            $('#wooqr_upload_image').val(attachment.url);
            $('#wooqr_upload_image').attr("value", attachment.url);
            $('#wooqr_upload_image').trigger('change');

            $("#wooqrimg-buffer").attr("src", attachment.url);

        });
        //Open the uploader dialog
        wooqr_uploader.open();
    });


});
jQuery(document).ready(function($){
                   if ( typeof wp.media !== 'undefined' ) {
                     var _custom_media = true,
                     _orig_send_attachment = wp.media.editor.send.attachment;
                     $('.new-media').click(function(e) {
                       var send_attachment_bkp = wp.media.editor.send.attachment;
                       var button = $(this);
                       var id = button.attr('id').replace('_button', '');
                       _custom_media = true;
                       wp.media.editor.send.attachment = function(props, attachment){
                         if ( _custom_media ) {
                           if ($('input#' + id).data('return') == 'url') {
                             $('input#' + id).val(attachment.url);
                           } else {
                             $('input#' + id).val(attachment.id);
                           }
                           $('div#vCradpreview'+id).css('background-image', 'url('+attachment.url+')');
                         } else {
                           return _orig_send_attachment.apply( this, [props, attachment] );
                         };
                       }
                       wp.media.editor.open(button);
                       return false;
                     });
                     $('.add_media').on('click', function(){
                       _custom_media = false;
                     });
                     $('.remove-media').on('click', function(){
                       var parent = $(this).parent();
                       parent.find('input[type="text"]').val('');
                       parent.find('div').css('background-image', 'url()');
                     });
                   }
                 });
				 
				 
				 jQuery(document).ready(function ($) {
 
   $(document).on("click", ".qrc_image_button", function (e) {
      e.preventDefault();
      var $button = $(this);
 
 
      // Create the media frame.
      var file_frame = wp.media.frames.file_frame = wp.media({
         title: 'Select or upload image',
         library: { // remove these to show all
            type: 'image' // specific mime
         },
         button: {
            text: 'Select'
         },
         multiple: false  // Set to true to allow multiple files to be selected
      });
 
      // When an image is selected, run a callback.
      file_frame.on('select', function () {
         // We set multiple to false so only get one image from the uploader
 
         var attachment = file_frame.state().get('selection').first().toJSON();
 
         $button.siblings('input').val(attachment.url);
 
      });
 
      // Finally, open the modal
      file_frame.open();
   });
});