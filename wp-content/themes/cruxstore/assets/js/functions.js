(function($){
    "use strict"; // Start of use strict


    /* --------------------------------------------
     Mobile detect
     --------------------------------------------- */
    var ktmobile;
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        ktmobile = true;
        $("html").addClass("mobile");
    }else {
        ktmobile = false;
        $("html").addClass("no-mobile");
    }

    /* ---------------------------------------------
     Scripts initialization
     --------------------------------------------- */

    $(window).load(function(){

        $(window).trigger("scroll");
        $(window).trigger("resize");
        init_ktCustomCss();
    });


    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */
    $(window).resize(function(){

        init_ktCustomCss();
        /**==============================
         ***  Sticky header
         ===============================**/

        if ($.fn.ktSticky) {
            $('.sticky-header').ktSticky({
                contentSticky : ''
            });
        }

        /**==============================
         ***  Disable mobile menu in desktop
         ===============================**/
        if ($(window).width() >= 1200) {
            $('body').removeClass('opened-nav-animate');
            $('#hamburger-icon').removeClass('active');
        }



        $('.blog-posts-masonry').each(function(){
            var $masonry = $(this);
            $masonry.imagesLoaded(function() {
                $masonry.find('.row').isotope({
                    itemSelector: '.blog-post-wrap',
                    percentPosition: true,
                    masonry: {
                        columnWidth: '.blog-post-sizer'
                    }
                })
            });
        });


    });


    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */

    init_MainMenu();
    init_carousel();
    init_shortcodes();
    init_backtotop();
    init_SearchFull();
    init_MobileMenu();
    init_page_option();
    init_popup();
    init_wow();
    init_lightBox();
    init_image_tooltip();
    show_item_vertical_menu();
    action_menu_vertical();
    setInterval(init_remove_space, 100);

    /* ---------------------------------------------
     WOW show_item_vertical_menu
     --------------------------------------------- */
    function show_item_vertical_menu(){

        var numbershow=$('.icon_show_menu').attr('data-show');;
        $("#main-vertical > li").each(function(index){
            var i=index;
            if(i  >=  numbershow){
                $(this).addClass('nemushownumber');
            }
        });
        $('span.icon_show_menu').on('click',function(){
            if($('li.nemushownumber').hasClass('activeon')){
                $('li.nemushownumber ').removeClass('activeon');
                $(this).removeClass('roter');
            }else{
                $('li.nemushownumber').addClass('activeon');
                $(this).addClass('roter');
            }
        });

        $('ul.menu-right-nav > li > a > span').on('click',function(){
            if($(this).parent().parent().hasClass('showmenumobile')){
                $(this).parent().parent().removeClass('showmenumobile');
            }else{
                $(this).parent().parent().addClass('showmenumobile');
            }
            return false
        });
    }
    function action_menu_vertical(){
        var header= $('#header');
        if(header.hasClass('hover-menu-vertical')){

            $('.menu-right').addClass('action-hover-menu-vertical');
        }
        if(header.hasClass('click-menu-vertical')){
            $('.menu-right').addClass('action-click-menu-vertical');
            $('.menu-category').on('click', function(){
                if($('.menu-right').hasClass('action-click-menu-vertical')){
                    $('.menu-right').removeClass('action-click-menu-vertical');
                }else{
                    $('.menu-right').addClass('action-click-menu-vertical');
                }
            });

        }

    }

    /* ---------------------------------------------
     WOW animations
     --------------------------------------------- */
    
    function init_wow(){
        var wow = new WOW({
            boxClass: 'wow',
            live: true
        });
        

        if ($("body").hasClass("appear-animate")){
           wow.init();
        }
    }

    /* ---------------------------------------------
     Remove all space empty
     --------------------------------------------- */
    function init_remove_space() {

        $("p:empty").remove();
        $(".wpb_text_column:empty").remove();
        $(".wpb_wrapper:empty").remove();
        $(".wpb_column:empty").remove();
        $(".wpb_row:empty").remove();

    }
    /* ---------------------------------------------
     KT Image Tooltip
     --------------------------------------------- */
    
    function init_image_tooltip(){
        $('.image-tooltip-content').on('mouseenter', function(){
            var $this = $(this),
                $count = $this.data('count'),
                $parent = $(this).closest('.image-tooltip');
            
            $parent.find('.image-tooltip-item').removeClass('active');
            $parent.find('.image-tooltip-element').removeClass('active');
            
            $this.parent('.image-tooltip-item').addClass('active');
            $parent.find('.image-tooltip-element[data-count='+$count+']').addClass('active');
        });
        
        $('.image-tooltip-element').on('mouseenter', function(){
            var $this = $(this),
                $count = $this.data('count'),
                $parent = $(this).closest('.image-tooltip');
                
            $parent.find('.image-tooltip-item').removeClass('active');
            $parent.find('.image-tooltip-element').removeClass('active');
            
            $parent.find('.image-tooltip-content[data-count='+$count+']').parent('.image-tooltip-item').addClass('active');
            $this.addClass('active');
        });
        
        
    }

    /* ---------------------------------------------
     KT custom css
     --------------------------------------------- */
    function init_ktCustomCss(){
        $('.cruxstore_custom_css').each(function(){
            var $this = $(this);
            if(!$this.children('style').length){
                $this.html('<style>'+$this.data('css')+'</style>');
            }
        });
    }

    $('.social_icons').on('hover', 'li', function(){
        var $this= $(this);

        $this.siblings().removeClass('active');
        $this.addClass('active');
    });


    /* ---------------------------------------------
     Back to top
     --------------------------------------------- */
    function init_backtotop(){
        var $backtotop = $('#back-to-top');
        $backtotop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop:0},500);
        });
        $(window).scroll(function() {
            var heightbody = $('body').outerHeight(),
                window_height = $(window).outerHeight(),
                top_pos = heightbody/2-25;
            if($(window).scrollTop() + window_height/2 >= top_pos && heightbody > window_height) {
                $backtotop.fadeIn();
            } else {
                $backtotop.fadeOut();
            }
        });
    }

    /* ---------------------------------------------
     Mobile Menu
     --------------------------------------------- */
    function init_MobileMenu(){

        $('body')
            .on('click','#hamburger-icon',function(e){
                e.preventDefault();
                $(this).toggleClass('active');
                $('body').toggleClass('opened-nav-animate');
                setTimeout(function(){
                    $('body').toggleClass('opened-nav');
                }, 100)

            });

        $('ul.navigation-mobile ul.sub-menu-dropdown, ul.navigation-mobile .cruxstore-megamenu-wrapper').each(function(){
            $(this).parent().children('a').prepend( '<span class="open-submenu"></span>' );
        });

        $('.open-submenu').on('click', function(e){
            e.stopPropagation();
            e.preventDefault();
            $( this ).closest('li').toggleClass('active-menu-item');
            $( this ).closest('li').children( '.sub-menu-dropdown, .cruxstore-megamenu-wrapper' ).slideToggle();
        });

        $(window).resize(function(){
            var $navHeight = $(window).height() - $('.navbar-container').height();
            $('.main-nav-mobile').css({'max-height': $navHeight});
        });

        $('.main-nav-mobile').onePageNav({
            currentClass: 'current-menu-item',
            changeHash: true,
            begin: function() {
                $('body').removeClass('opened-nav-animate opened-nav');
                $('#hamburger-icon').removeClass('active');
            }
        });


    }


    /* ---------------------------------------------
     Search
     --------------------------------------------- */
    function init_SearchFull(){

        $('.search-action a, a.mobile-search').magnificPopup({
            type: 'inline',
            mainClass : 'mfp-zoom-in',
            items: { src: '#search-fullwidth' },
            focus : 'input[name=s]',
            removalDelay: 200
        });

        $('.header-subscribe').each(function(){
            var $subscribe = $(this);
            $subscribe.magnificPopup({
                type: 'inline',
                mainClass : 'mfp-zoom-in',
                items: { src: $subscribe.attr('href') },
                removalDelay: 200
            });
        });



    }

    /* ---------------------------------------------
     Owl carousel
     --------------------------------------------- */
    function init_carousel(){
        $('.cruxstore-owl-carousel').each(function(){

            var objCarousel = $(this),
                objParent = objCarousel.closest('.owl-carousel-kt'),
                options = $(objCarousel).data('options') || {},
                func_cb;
            options.theme = 'owl-kttheme';

            if(typeof options.desktop !== "undefined"){
                options.itemsDesktop = [1199,options.desktop];
                options.items = options.desktop;
            }
            if(typeof options.desktopsmall !== "undefined"){
                options.itemsDesktopSmall = [991,options.desktopsmall];
            }
            if(typeof options.tablet !== "undefined"){
                options.itemsTablet = [768,options.tablet];
            }

            options.navigationText = ["", ""];


            if(typeof options.mobile !== "undefined"){
                options.itemsMobile = [480,options.mobile];
            }

            func_cb =  window[options.callback];

            options.afterInit  = function(elem) {

                if(typeof options.pagination_pos !== "undefined" && options.pagination){
                    if(options.pagination_pos == 'center-top'){
                        var $pagination = elem.find('.owl-pagination');
                        $pagination.prependTo(objCarousel.closest('.owl-carousel-kt'));
                    }
                }
                if(typeof options.navigation_pos !== "undefined" && options.navigation){
                    if(options.navigation_pos == 'heading'){
                        var $navigation = elem.find('.owl-buttons');
                        $navigation.prependTo(objCarousel.closest('#related-article'));
                    }
                }



                if( typeof func_cb === 'function'){
                    func_cb( 'afterInit',   elem );
                }
            };
            options.afterUpdate = function(elem){
                if( typeof func_cb === 'function'){
                    func_cb( 'afterUpdate',   elem );
                }
            };

            options.afterMove = function(elem){
                if( typeof func_cb === 'function'){
                    func_cb( 'afterMove',   elem );
                }
            };

            objCarousel.imagesLoaded(function() {
                objCarousel.owlCarousel(options);
            });
        });


    }

    /* ---------------------------------------------
     Shortcodes
     --------------------------------------------- */
    function init_shortcodes() {

        // Tooltips (bootstrap plugin activated)
        $('[data-toggle="tooltip"]').each(function(){
            var $this = $(this),
                $product = $this.closest('.product-type-classic'),
                $placement = $this.data('placement');
            if($product.length){
                var $effect = $product.data('effect');
                if( $effect == '1' || $effect == '3' || $effect == '5'){
                    $placement = 'left';
                }
            }

            if($this.hasClass('product-swatche-item')){
                $placement = 'top';
            }

            $this.tooltip({
                container : 'body',
                delay: { "show": 100, "hide": 50},
                placement: $placement
            });
        });

        $(".entry-content").fitVids();

        $('.counter-wrapper').each(function(){
            var $this = $(this);
            $this.waypoint(function () {
                $this.find('.counter').countTo();
            }, { offset:'85%', triggerOnce:true });
        });


        $('.panel-group')
            .on('hidden.bs.collapse', bootstrapToggleIcon)
            .on('shown.bs.collapse', bootstrapToggleIcon);

    }


    function bootstrapToggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .toggleClass('panel-heading-active');
    }



    /* ---------------------------------------------
     Main Menu
     --------------------------------------------- */
    function init_MainMenu(){
        $("ul#main-navigation")
            .superfish({
                hoverClass: 'hovered',
                popUpSelector: 'ul.sub-menu-dropdown,.cruxstore-megamenu-wrapper',
                animation: {},
                animationOut: {},
                delay:100
            }).onePageNav({
                currentClass: 'current-menu-item',
                changeHash: true
            });

    }

    function init_page_option(){
        var _body = $('body');
        if(_body.hasClass('page-type-bullet')){
            var $lists = $('#page-entry-content > .vc_row'),
                $items ='';
            if($lists.length) {
                $lists.each(function (i) {
                    var $link = $(this).attr('id'),
                        $skin = $(this).data('bullet-skin');
                    if(typeof $link === "undefined"){
                        $link = makeid();
                        $(this).attr('id', $link);
                    }
                    $items += '<li><a href="#'+$link+'" data-skin="'+$skin+'" data-item="' + i + '"><span></span></a></li>';
                });

                if($('#footer').length){
                    $items += '<li><a href="#footer"><span></span></a></li>';
                }
                _body.append('<ul id="cruxstore-row-nav">'+$items+'</ul>');
                var $bullet_nav = $('#cruxstore-row-nav');
                $bullet_nav.onePageNav({
                    currentClass: 'current',
                    changeHash: false,
                    end: function( ) {
                        var $currentListItem = $bullet_nav.find('.current'),
                            $skin = $currentListItem.find('a').data('skin');
                        $bullet_nav.removeAttr('class');
                        $bullet_nav.addClass($skin);
                    },
                    scrollChange: function($currentListItem) {
                        var $skin = $currentListItem.find('a').data('skin');
                        $bullet_nav.removeAttr('class');
                        $bullet_nav.addClass($skin);
                    }
                });
            }
        }
    }

    function makeid()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for( var i=0; i < 5; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    /**==============================
     *** Popup Content
     ===============================**/
    function init_popup(){
        if($('#popup-wrap').length > 0){
            var time_show = $('#popup-wrap').data('timeshow');
            setTimeout(function(){
                $.magnificPopup.open({
                    items: { src: '#popup-wrap' },
                    mainClass : 'mfp-zoom-in',
                    removalDelay: 200,
                    type: 'inline',
                    callbacks: {
                        beforeClose: function() {
                            var data = {action: 'fronted_popup'};
                            $.post(ajax_frontend.ajaxurl, data, function(response) { }, 'json');
                        }
                    }
                });
            }, time_show*1000);
        }

    }
    
    /* ---------------------------------------------
     VC Lightbox
     --------------------------------------------- */
    function init_lightBox(){
        $('.lightbox-link').each(function(){

            var $type = $(this).data('type'),
                $effect = $(this).data('effect'),
                $iframe_width = $(this).data('width'),
                $removalDelay = 500;
                
            if(typeof $effect === "undefined" || $effect == ''){
                $effect = '';
                $removalDelay = 0;
            }
            
            $(this).magnificPopup({
                type: $type,
                mainClass: $effect,
                removalDelay: $removalDelay,
                midClick: true,
                callbacks: {
                    beforeOpen: function() {
                        if($type == 'image'){
                            this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                            this.st.mainClass = $effect;
                        }else if($type == 'iframe'){
                            this.st.iframe.markup = this.st.iframe.markup.replace('mfp-iframe-scaler', 'mfp-iframe-scaler mfp-with-anim');
                            this.st.mainClass = $effect;
                        }
                    },
                    open: function() {
                        if($type == 'iframe' || $type == 'inline'){
                            $('.mfp-container .mfp-content').css('max-width', $iframe_width);
                        }
                    }
                }
            });
        });
    }

    function init_scrollMenu(){

        var $scrollMenu = $('#main-navigation .dropdown-scroll .megamenu-mgitem');


        $scrollMenu.each(function () {
            var $this = $(this);

            var $innerContent = $this.find('.megamenu-mgitem-content');

            $this.on('mousemove', function (e) {


                var parentOffset = $this.offset();

                var relY = e.pageY - parentOffset.top;

                var deltaHeight = $innerContent.outerHeight() - $this.height();


                if (deltaHeight < 0) return;

                var percentY = relY / $this.height();

                var margin = 0;

                if (percentY <= 0) {
                    margin = 0;
                } else if (percentY >= 1) {
                    margin = -deltaHeight;
                } else {
                    margin = -percentY * deltaHeight;
                }

                margin = parseInt(margin);

                $innerContent.css({
                    'position': 'relative',
                    'top': margin
                });
            });
        });
    }

    setTimeout(function() {
        init_scrollMenu();
    }, 1000);

    init_scrollMenu();



})(jQuery); // End of use strict


/* ---------------------------------------------
 Google Map Short code
 --------------------------------------------- */
function init_google_map() {
    var styleMap = [];
    styleMap[0] = [];
    styleMap[1] = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#fcfcfc"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#fcfcfc"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#dddddd"}]}],
        styleMap[2] = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#b4d4e1"},{"visibility":"on"}]}],
        styleMap[3] = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"administrative.country","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.country","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.country","elementType":"labels.text","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"all","stylers":[{"visibility":"simplified"},{"saturation":"-100"},{"lightness":"30"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"},{"gamma":"0.00"},{"lightness":"74"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"lightness":"3"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}],
        styleMap[4] = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#0c0b0b"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#090909"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#d4e4eb"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#fef7f7"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9b7f7f"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#fef7f7"}]}],
        styleMap[5] = [{"featureType":"administrative","elementType":"labels","stylers":[{"visibility":"on"},{"gamma":"1.82"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"visibility":"on"},{"gamma":"1.96"},{"lightness":"-9"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"on"},{"lightness":"25"},{"gamma":"1.00"},{"saturation":"-100"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#ffaa00"},{"saturation":"-43"},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"},{"hue":"#ffaa00"},{"saturation":"-70"}]},{"featureType":"road.highway.controlled_access","elementType":"labels","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"on"},{"saturation":"-100"},{"lightness":"30"}]},{"featureType":"road.local","elementType":"all","stylers":[{"saturation":"-100"},{"lightness":"40"},{"visibility":"off"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"gamma":"0.80"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"off"}]}],
        styleMap[6] = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"administrative","elementType":"labels","stylers":[{"saturation":"-100"}]},{"featureType":"administrative","elementType":"labels.text","stylers":[{"gamma":"0.75"}]},{"featureType":"administrative.neighborhood","elementType":"labels.text.fill","stylers":[{"lightness":"-37"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f9f9f9"}]},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"40"},{"visibility":"off"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"saturation":"-100"},{"lightness":"-37"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"saturation":"-100"},{"lightness":"100"},{"weight":"2"}]},{"featureType":"landscape.natural","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"saturation":"-100"},{"lightness":"80"}]},{"featureType":"poi","elementType":"labels","stylers":[{"saturation":"-100"},{"lightness":"0"}]},{"featureType":"poi.attraction","elementType":"geometry","stylers":[{"lightness":"-4"},{"saturation":"-100"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"},{"visibility":"on"},{"saturation":"-95"},{"lightness":"62"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road","elementType":"labels","stylers":[{"saturation":"-100"},{"gamma":"1.00"}]},{"featureType":"road","elementType":"labels.text","stylers":[{"gamma":"0.50"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"saturation":"-100"},{"gamma":"0.50"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"},{"saturation":"-100"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"lightness":"-13"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"lightness":"0"},{"gamma":"1.09"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"},{"saturation":"-100"},{"lightness":"47"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"lightness":"-12"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"},{"lightness":"77"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"lightness":"-5"},{"saturation":"-100"}]},{"featureType":"road.local","elementType":"geometry.stroke","stylers":[{"saturation":"-100"},{"lightness":"-15"}]},{"featureType":"transit.station.airport","elementType":"geometry","stylers":[{"lightness":"47"},{"saturation":"-100"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]},{"featureType":"water","elementType":"geometry","stylers":[{"saturation":"53"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":"-42"},{"saturation":"17"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":"61"}]}],
        styleMap[7] = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#b4d4e1"},{"visibility":"on"}]}],
        styleMap[8] = [{"featureType":"administrative","elementType":"geometry","stylers":[{"saturation":"2"},{"visibility":"simplified"}]},{"featureType":"administrative","elementType":"labels","stylers":[{"saturation":"-28"},{"lightness":"-10"},{"visibility":"on"}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"saturation":"-1"},{"lightness":"-12"}]},{"featureType":"landscape.natural","elementType":"labels.text","stylers":[{"lightness":"-31"}]},{"featureType":"landscape.natural","elementType":"labels.text.fill","stylers":[{"lightness":"-74"}]},{"featureType":"landscape.natural","elementType":"labels.text.stroke","stylers":[{"lightness":"65"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry","stylers":[{"lightness":"-15"}]},{"featureType":"landscape.natural.landcover","elementType":"geometry.fill","stylers":[{"lightness":"0"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry","stylers":[{"visibility":"on"},{"saturation":"0"},{"lightness":"-9"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"lightness":"-14"}]},{"featureType":"road","elementType":"labels","stylers":[{"lightness":"-35"},{"gamma":"1"},{"weight":"1.39"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":"-19"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":"46"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"labels.icon","stylers":[{"lightness":"-13"},{"weight":"1.23"},{"invert_lightness":true},{"visibility":"simplified"},{"hue":"#ff0000"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#adadad"},{"visibility":"on"}]}],
        styleMap[9] = [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#95968e"},{"lightness":-25},{"saturation":-97}]}];

    jQuery(".googlemap").each(function () {
        var mapObj = jQuery(this),
            scrollwheel = (mapObj.data('scrollwheel') == '1') ? false : true,
            mapStyle = parseInt(mapObj.data('style')),
            $center = mapObj.data('center'),
            $locations = mapObj.find('.googlemap-item'),
            $center_obj,
            $location_arr = [];

        jQuery.each($locations, function( a ){
            var $location = jQuery(this);
            $location_arr[a] = {};
            $location_arr[a].position = [$location.data('lat'), $location.data('long')];
            $location_arr[a].icon = mapObj.data('iconmap');
            $location_arr[a].address = $location.data('address');

        });

        $center_obj = $center.split(",");
        mapObj
            .gmap3({
                center:[$center_obj[0], $center_obj[1]],
                zoom: mapObj.data('zoom'),
                mapTypeId : mapObj.data('type').toLowerCase(),
                scrollwheel: scrollwheel,
                styles: styleMap[mapStyle]
            })
            .marker($location_arr);
    });

}