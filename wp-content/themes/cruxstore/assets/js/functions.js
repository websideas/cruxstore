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


    init_scrollMenu();
    setTimeout(function() {init_scrollMenu();}, 1000);

    init_wc_grid_list();
    init_wc_currency();
    init_wc_quantily();
    init_wc_carousel();
    init_wc_masonry();
    init_wc_filter();
    init_wc_saleCountDown();
    init_wc_quickview();
    init_checkout_coupon();
    init_wc_easyZoom();
    init_wc_swatch();
    init_wc_video();
    init_wc_filters();
    init_wc_product_carousel();
    init_wc_sticky();

    /* ---------------------------------------------
     WOW show_item_vertical_menu
     --------------------------------------------- */
    function show_item_vertical_menu(){

        var numbershow=$('.icon_show_menu').attr('data-show');
        $("#main-vertical > li").each(function( index ){
            if(index  >=  numbershow){
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

        var header= $('#header'),
            $menu_right = $('.menu-right');

        if(header.hasClass('hover-menu-vertical')){
            $menu_right.addClass('action-hover-menu-vertical');
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
        $backtotop.on('click', function( e ) {
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
                }, 100);

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
            filter : ':not(.currency-item)',
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

    function init_scrollMenu($scroll , $content){

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



    function init_wc_sticky(){
        if(!$('.wc-single-product.product-layout5').length)
            return;

        $('.product-images-wrap').imagesLoaded(function(){
            $('.summary-wrapper').theiaStickySidebar({
                additionalMarginTop: 70
            });
            $(window).trigger('scroll');
        });
    }

    function init_wc_video(){

        $('.product-tool-play').magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });
    }

    function init_wc_swatch(){

        $('body').on('click', '.swatch-term', function(e){
            e.preventDefault();

            var $this = $(this),
                $val = $this.data('term'),
                $variation = $this.closest('.variation-item'),
                $terms = $variation.find('.swatch-term'),
                $select = $variation.find('.swatch-select');

            $terms.removeClass('active');
            $this.addClass('active');

            $select.val($val).change();

        });


        $('body').on('click', '.reset_variations', function(e){
            e.preventDefault();
            $('.swatch-term').removeClass('active');

        });

        $('.swatch-select').each(function(){
            var $this = $(this),
                $val = $this.val(),
                $id = $this.attr('id');
            if($val != ''){
                $('ul[data-id='+$id+']').find('span[data-term='+$val+']').addClass('active');
            }
        });

        $('body').on('click', '.attrs-item', function( e ){
            e.preventDefault();


            var $this = $(this),
                $attrs = $this.closest('.shop-header-attrs').addClass('active');

            $attrs.find('.attrs-item-li').removeClass('selected');

            $this.closest('.attrs-item-li').addClass('selected');

        });

        $('body').on('click', function( e ){
            if($('.shop-header-attrs').hasClass('active')){
                var container = $(".attrs-item-li");
                if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    container.removeClass('selected');
                }
            }
        });


    }

    function init_wc_easyZoom(){
        if ($.fn.easyZoom) {
            $('.easyzoom').easyZoom();
        }
    }

    function init_wc_masonry(){
        $('.cruxstore-products-masonry').each(function(){
            var $masonry = $(this);
            $masonry.imagesLoaded(function() {
                $('.shop-products', $masonry).isotope({
                    resizable: false,
                    itemSelector : '.product',
                    layoutMode: 'packery',
                    packery: {
                        columnWidth: '.grid-sizer'
                    }
                });
            });
        });

        $('.product-categories-masonry').each(function(){
            var $masonry = $(this);
            $masonry.imagesLoaded(function() {
                $masonry.find('.row').isotope({
                    resizable: false,
                    itemSelector: '.category-masonry-item',
                    layoutMode: 'packery',
                    percentPosition: true
                });
            });
        });

    }


    function init_wc_filter(){
        $('body').on('click', '.wc-header-filter a', function(e){
            e.preventDefault();
            var $this = $(this);
            if($this.hasClass('active')){
                $(this).removeClass('active');
                $('#cruxstore-shop-filters').slideUp('fast');
            }else{
                $(this).addClass('active');
                $('#shop-header-categories').slideUp('fast', function(){
                    $('#shop-header-categories').removeAttr('style');
                    $('.wc-header-categories a').removeClass('active');
                    $('#cruxstore-shop-filters').slideDown('fast');
                });
            }
        });

        $('body').on('click', '.products-shop-header-atts > a', function(e){
            e.preventDefault();
            $(this).closest('.products-shop-header-atts').toggleClass('active');
        });


        $('body').on('click', '.wc-header-categories a', function(e){
            e.preventDefault();
            var $this = $(this);

            if($this.hasClass('active')){
                $(this).removeClass('active');
                $('#shop-header-categories').slideUp('fast', function(){
                    $('#shop-header-categories').removeAttr('style');
                });
            }else{
                $(this).addClass('active');
                $('.wc-header-filter a').removeClass('active');
                $('#cruxstore-shop-filters').slideUp('fast', function(){
                    $('#shop-header-categories').slideDown('fast');
                });
            }
        });

        $('body').on('click', '#cruxstore-shop-filters .widget-title', function(){
            $(this).closest('.widget-content').toggleClass('widget-active');
        });




    }


    /* ---------------------------------------------
     Grid list Toggle
     --------------------------------------------- */
    function init_wc_grid_list(){
        $('body').on('click', 'ul.gridlist-toggle a', function(e){
            e.preventDefault();
            var $this = $(this),
                $gridlist = $this.closest('.gridlist-toggle'),
                $products = $this.closest('#main').find('ul.shop-products');

            var data = {
                action: 'frontend_update_posts_layout',
                security : ajax_frontend.security,
                layout: $this.data('layout')
            };

            $gridlist.find('a').removeClass('active');
            $this.addClass('active');
            $products
                .removeClass($this.data('remove'))
                .addClass($this.data('layout'));

        });
    }


    function init_wc_currency(){
        if(typeof woocs_drop_down_view !== "undefined") {
            $('.currency-switcher-content a, .menu-item-currency ul a').on('click', function(e){
                e.preventDefault();
                woocs_redirect($(this).data('currency'));
            });
        }
    }


    $('.product-main-images').magnificPopup({
        delegate: 'a.woocommerce-main-image',
        type: 'image',
        gallery: {
            enabled: true
        }
    });



    /* ---------------------------------------------
     Single Product
     --------------------------------------------- */


    function init_wc_product_carousel(){

        var $pd = $('.wc-single-product');
        if( $pd.hasClass('product-layout5')){
            return;
        }

        $('body').on('click', '.product-tool-zoom', function(e){
            e.preventDefault();
            var $this = $(this),
                $images = $this.closest('.product-images-wrap'),
                $current = $images.find('.slick-current');

            $current.trigger('click');
        });

        $('.product-main-images').slick({
            asNavFor: '.product-main-thumbnails',
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            prevArrow: $('.slick-images-prev'),
            nextArrow: $('.slick-images-next')
        });

        var options = {
            asNavFor: '.product-main-images',
            infinite: true,
            focusOnSelect: true,
            slidesToShow: 5,
            prevArrow: '',
            nextArrow: '',
            slidesToScroll: 1
        };

        if( $pd.hasClass('product-layout3') || $pd.hasClass('product-layout4') ){
            options.slidesToShow = 5;
            options.vertical = true;
            options.verticalSwiping = true;
            options.prevArrow = $('.slick-thumbs-prev');
            options.nextArrow = $('.slick-thumbs-next');
        }
        $('.product-main-thumbnails').slick(options);

    }




    /* ---------------------------------------------
     QickView
     --------------------------------------------- */
    function init_wc_quickview(){

        function init_wc_qv(){
            var $qheight = $('#quickview-images').outerHeight(),
                $qheightc = $('#quickview-summary-content').outerHeight();

            if($qheightc >  $qheight && $(window).width() > 991){
                $('#quickview-summary').css({
                    'height': $qheight,
                    'overflow' : 'auto'
                });
            }else{
                $('#quickview-summary').css({
                    'height': 'auto',
                    'overflow' : 'inherit'
                });
            }

        }

        $(window).resize(function(){

            if ($(window).width() <= 991) {
                $('#quickview-summary').css({
                    'height': 'auto',
                    'overflow' : 'inherit'
                });
            }else{
                setTimeout(function(){
                    init_wc_qv();
                }, 100);
            }
        });
        $('body').on('click', '.product-quick-view', function(e){
            e.preventDefault();
            var objProduct = $(this);
            $('i', objProduct).attr('class', 'fa fa-circle-o-notch fa-spin');
            objProduct.parent().tooltip('hide');
            var data = {
                action: 'frontend_product_quick_view',
                product_id: objProduct.data('id')
            };

            $.post(ajax_frontend.ajaxurl, data, function(response) {
                $('i', objProduct).attr('class', 'fa fa-search');
                $.magnificPopup.open({
                    mainClass : 'mfp-zoom-in',
                    showCloseBtn: false,
                    removalDelay: 500,
                    items: {
                        src: '<div class="container"><div class="themedev-product-popup woocommerce mfp-with-anim">' + response + '</div></div>',
                        type: 'inline'
                    },
                    callbacks: {
                        open: function() {
                            var $popup = $('.themedev-product-popup');
                            $popup.imagesLoaded(function(){

                                $('#quickview-images').owlCarousel({
                                    'singleItem' : true,
                                    'navigation' : true,
                                    'pagination' : false,
                                    'navigationText' : 	["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                                    'afterInit' : init_wc_qv
                                });
                            });
                            $('.cruxstore-product-popup form').wc_variation_form();
                            init_wc_swatch();
                        },
                        change: function() {
                            $('.cruxstore-product-popup form').wc_variation_form();
                            init_wc_swatch();
                        }
                    }
                });
            });
        });
        $(document).on('click', '.close-quickview', function (e) {
            e.preventDefault();
            $.magnificPopup.close();
        });
    }

    /* ---------------------------------------------
     Woocommercer Quantily
     --------------------------------------------- */
    function init_wc_quantily(){
        $('body').on('click','.qty-plus',function(e){
            e.preventDefault();
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val());
            if(isNaN(val_qty)){
                val_qty = 0;
            }
            var max_qty = parseInt(obj_qty.attr('max')),
                step_qty = parseInt(obj_qty.attr('step'));
            val_qty = val_qty + step_qty;
            if(max_qty && val_qty > max_qty){ val_qty = max_qty; }
            obj_qty.val(val_qty);
        });
        $('body').on('click','.qty-minus',function(e){
            e.preventDefault();
            var obj_qty = $(this).closest('.quantity').find('input.qty'),
                val_qty = parseInt(obj_qty.val());
            if(isNaN(val_qty)){
                val_qty = 0;
            }
            var min_qty = parseInt(obj_qty.attr('min')),
                step_qty = parseInt(obj_qty.attr('step'));
            val_qty = val_qty - step_qty;
            if(min_qty && val_qty < min_qty){ val_qty = min_qty; }
            if(!min_qty && val_qty < 0){ val_qty = 0; }
            obj_qty.val(val_qty);
        });
    }


    $( 'body' )
        .on('click', '.add_to_cart_button', function() {
            var $this = $(this).addClass('wc-loading');
            $this.parent().tooltip('hide');
            $('i', $this).attr('class', 'fa fa-circle-o-notch fa-spin');
        })
        .on('added_to_cart', function(e, data) {
            var $button_product = $('.wc-loading');
            $('i', $button_product).attr('class', 'fa fa-check');
        })
        .on('click', '.yith-wcwl-add-button', function() {
            var $this = $(this).addClass('wc-wishlist-loading');
            $this.parent().tooltip('hide');
            $('i', $this).attr('class', 'fa fa-circle-o-notch fa-spin');
        })
        .on( 'added_to_wishlist removed_from_wishlist', function() {
            var data = {action: 'fronted_get_wishlist'};
            $.post(ajax_frontend.ajaxurl, data, function(response) {
                $('.shopping-bag-wishlist').html(response.html);
            }, 'json');
        })
        .on('wc_fragments_loaded wc_fragments_refreshed added_to_cart added_to_wishlist', function (){
            //$('.shopping-bag .cart_list.product_list_widget').mCustomScrollbar();
        })
        .on( 'click', '.product a.compare:not(.added)', function(e){
            e.preventDefault();
            var $this = $(this).addClass('wc-compare-loading');
            $this.parent().tooltip('hide');
        })
        .on('yith_woocompare_open_popup', function(){
            var $button_product = $('.wc-compare-loading'),
                $parent = $button_product.closest('.compare');
            $parent.removeClass('wc-compare-loading');
        });

    /* ---------------------------------------------
     Sale Count Down
     --------------------------------------------- */
    function init_wc_saleCountDown(){
        if( typeof ( $.countdown ) !== undefined ){
            $('.woocommerce-countdown').each(function(){
                var $this = $(this),
                    finalDate = $(this).data('time'),
                    $date = new Date( finalDate );
                $this.countdown($date, function(event) {
                    $(this).html(event.strftime('<div><span>%D</span>'+cruxstore_woocommerce.day_str+'</div><div><span>%H</span>'+cruxstore_woocommerce.hour_str+'</div><div><span>%M</span>'+cruxstore_woocommerce.min_str+'</div><div><span>%S</span>'+cruxstore_woocommerce.sec_str+'</div>'));
                });
            });
        }
    }




    /* ---------------------------------------------
     Owl carousel
     --------------------------------------------- */
    function init_wc_carousel(){
        $('.wc-carousel-wrapper').each(function(){

            var wooCarousel = $(this),
                objCarousel = wooCarousel.find('ul.shop-products'),
                objParent = objCarousel.closest('.owl-carousel-kt'),
                options = $(wooCarousel).data('options') || {},
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

            if(typeof options.mobile !== "undefined"){
                options.itemsMobile = [480,options.mobile];
            }

            options.navigationText = ['', ''];


            func_cb =  window[options.callback];

            options.afterInit  = function(elem) {

                if(objParent.hasClass('navigation-top')){
                    var $buttons = elem.find('.owl-buttons');
                    $buttons.prependTo(objCarousel.closest('.owl-carousel-kt'));
                }

                if(typeof options.pagbefore !== "undefined" && options.pagination){
                    var $pagination = elem.find('.owl-pagination');
                    $pagination.prependTo(objCarousel.closest('.owl-carousel-kt'));
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

        $('.wc-products-tab-navigation').on('click', 'span', function(e){
            e.preventDefault();
            var $this = $(this),
                $vertical = $this.closest('.wc-productstab-carousel'),
                $pane = $vertical.find('.tab-pane.active'),
                $owl = $pane.find("ul.shop-products");

            if($this.hasClass('wc-products-nav-left')){
                $owl.trigger('owl.prev');
            }else{
                $owl.trigger('owl.next');
            }
        });

    }




    function init_checkout_coupon(){


        $( document.body ).on( 'click', 'input[name="apply_coupon"]', function(e){
            var $form = $( this).closest('.checkout_coupon_wrap');

            if ( $form.is( '.processing' ) ) {
                return false;
            }

            $form.addClass( 'processing' ).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            var data = {
                security:		wc_checkout_params.apply_coupon_nonce,
                coupon_code:	$form.find( 'input[name="coupon_code"]' ).val()
            };

            $.ajax({
                type:		'POST',
                url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
                data:		data,
                success:	function( code ) {
                    $( '.woocommerce-error, .woocommerce-message' ).remove();
                    $form.removeClass( 'processing' ).unblock();

                    if ( code ) {
                        $( 'form.woocommerce-checkout' ).before( code );

                        $( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );
                    }
                },
                dataType: 'html'
            });

            return false;
        } );


        $( document.body )
            .on('update_checkout', function(event, args) {

            });

    }


    function init_wc_filters( ){

        var $ajax_filter = parseInt( cruxstore_woocommerce.ajax_filter );


        if(!$ajax_filter){

            $("body").on("change",".products-shop-header-atts select.orderby",function(){
                $(this).closest("form").submit();
            });
            return;
        }

        $('#main').on('click', '.widget_cruxstore_orderby a, .wc_layered_nav_filters a, .widget_cruxstore_price_filter a, .widget_color_filter a, .widget_layered_nav a', function( e ){
            e.preventDefault();
            var $this = $(this),
                $pageUrl = $this.attr('href');
            init_wc_update_filters($pageUrl);
        });

        $('body').on('click', '.wc-pagination-outer a', function( e ){
            e.preventDefault();
            var $this = $(this),
                $pageUrl = $this.attr('href');
            init_wc_update_filters($pageUrl);
        });

        $('body').on('click', '#shop-header-categories a', function( e ){
            e.preventDefault();
            var $this = $(this),
                $pageUrl = $this.attr('href'),
                $cates = $this.closest('.shop-header-list'),
                $cate_li = $cates.find('li');

            $cate_li.removeClass('current-cat');
            $this.closest('li').addClass('current-cat');

            init_wc_update_filters($pageUrl);
        });

        $("body").on("change",".products-shop-header-atts select.orderby",function(){
            var $this = $(this),
                $pageUrl = $this.find('option:selected').attr('data-url');

            init_wc_update_filters($pageUrl);
        });

    }

    var $ajax_request;
    function init_wc_update_filters($pageUrl){

        if($ajax_request && $ajax_request.readystate != 4){
            $ajax_request.abort();
        }

        init_wc_loading(true);

        $pageUrl = $pageUrl.replace(/\/?(\?|#|$)/, '/$1');

        var $products = $('#main > .woocommerce-row'),
            $filters = $('#cruxstore-shop-filters-content'),
            $pagination = $('#main > .wc-pagination-outer'),
            $columns = $products.find('li:first').data('columns'),
            $layered = $('#wc_layered_nav_filters');

        if($('.products-shop-header-atts').length){
            $filters = $('.products-shop-header-atts');
        }


        var $data = {
            cruxstore_shop: 'full',
            cols: $columns
        };


        $ajax_request = $.ajax({
            url: $pageUrl,
            data: $data,
            dataType: 'html',
            cache: false,
            method: 'POST',
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log('AJAX error - ' + errorThrown);
                init_wc_loading(false);
            },
            success: function(response) {

                init_wc_loading(false);

                var $response_html = $(response),
                    $products_change = $('#main > .woocommerce-row', $response_html),
                    $filters_change = $('#cruxstore-shop-filters-content', $response_html),
                    $layered_change = $('#wc_layered_nav_filters', $response_html),

                    $pagination_change = $('#main > .wc-pagination-outer', $response_html),
                    $wpTitle = $($response_html).filter('title').text();


                if($('.shop-header-attrs').length){
                    $filters_change = $('.products-shop-header-atts', $response_html);
                }

                if ($wpTitle.length) {
                    document.title = $wpTitle;
                }

                $products.replaceWith($products_change);
                $filters.replaceWith($filters_change);
                $pagination.replaceWith($pagination_change);

                if ( history.pushState ) {
                    history.pushState({}, '', $pageUrl);
                }

            }
        });
    }



    function init_wc_loading($show){
        if(!$('.wc-filters-loading').length){
            $('body').append('<div class="wc-filters-loading"><span></span></div>');
        }

        var $loading = $('.wc-filters-loading');
        if($show){
            $loading.show();
        }else{
            $loading.hide();
        }
    }


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