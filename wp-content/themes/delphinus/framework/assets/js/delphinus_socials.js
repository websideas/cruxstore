/*
 *
 *	KT socials in composer
 *	------------------------------------------------
 *
 */

(function($){
    $('document').ready(function() {

        $( ".delphinus-socials-profiles" ).sortable({
            placeholder: "ui-socials-highlight",
            update: function( event, ui ) {
                var $parrent_ui = ui.item.closest('.delphinus-socials-options'),
                    $profiles_ui = $parrent_ui.find('.delphinus-socials-profiles'),
                    $value_ui = $parrent_ui.find('.delphinus-socials-value');

                $profiles_val_ui = [];
                $profiles_ui.find('li').each(function(){
                    $profiles_val_ui.push($(this).data('type'));
                });
                $value_ui.val($profiles_val_ui.join());
            }
        });
        $( 'body' ).on( 'click', '.delphinus-socials-profiles li span', function ( e ){
            e.preventDefault();

            var $remove = $(this),
                $social = $remove.closest('li'),
                $parent = $social.closest('.delphinus-socials-options');
            $profiles = $parent.find('.delphinus-socials-profiles'),
                $lists = $parent.find('.delphinus-socials-lists'),
                $value = $parent.find('.delphinus-socials-value');

            $lists.find('li[data-type='+$social.data('type')+']').removeClass('selected');

            $social.remove();

            $profiles_val = [];
            $profiles.find('li').each(function(){
                $profiles_val.push($(this).data('type'));
            });

            $value.val($profiles_val.join());

        });


        $( 'body' ).on( 'click', '.delphinus-socials-lists li', function ( e ){
            e.preventDefault();

            var $social = $(this),
                $parent = $social.closest('.delphinus-socials-options');
            $profiles = $parent.find('.delphinus-socials-profiles'),
                $value = $parent.find('.delphinus-socials-value');

            if(!$social.hasClass('selected')){
                $social.addClass('selected');
                $profiles.append($social.clone());
                $profiles_val = [];
                $profiles.find('li').each(function(){
                    $profiles_val.push($(this).data('type'));
                });

                $value.val($profiles_val.join());
            }

        });
    });
})(jQuery);