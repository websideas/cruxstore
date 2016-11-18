/*
*
*	Image widget upload
*	------------------------------------------------
*
*/

(function($){


    $( 'body' ).on( 'click', '.cruxstore_image_remove', function ( e ){
        e.preventDefault();
        var $button = $( this ),
            $widget = $button.closest('.wrapper_cruxstore_image_upload'),
            $url = $widget.find('.cruxstore_image_url'),
            $attachment = $widget.find('.cruxstore_image_attachment');

        $url.val('');
        $attachment.val('');
        $button.hide();
    });


    $( 'body' ).on( 'click', '.cruxstore_image_upload', function ( e ){
        e.preventDefault();
        var $button = $( this ),
            $widget = $button.closest('.wrapper_cruxstore_image_upload'),
            $preview = $widget.find('.cruxstore_image_preview'),
            $url = $widget.find('.cruxstore_image_url'),
            $preview_img = $preview.find('img'),
            $attachment = $widget.find('.cruxstore_image_attachment'),
            $remove = $widget.find('.cruxstore_image_remove'),
            frame,
            frameOptions = {
                className: 'media-frame rwmb-file-frame',
                multiple : false,
                title    : cruxstore_image_lange.frameTitle,
                library: {type: 'image'}
            };

        frame = wp.media( frameOptions );


        frame.off( 'select' );

        // When an image is selected in the media frame...
        frame.on( 'select', function() {

            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();
            console.log(attachment);

            $attachment.val(attachment.id);

            $preview_img
                .attr('src', attachment.url)
                .attr('alt', attachment.alt);

            $url.val(attachment.url);

            $preview.show();
            $remove.show();

        });

        frame.on('close',function() {
            // get selections and save to hidden input plus other AJAX stuff etc.
            var selection = frame.state().get('selection');

            //console.log('close');

            //console.log(selection["_byId"]);
        });


        // Open media uploader
        frame.open();

    });
})(jQuery);