(function($){
    "use strict"; // Start of use strict


    init_wc_grid_list();
    init_wc_currency();
    init_wc_quantily();
    init_wc_carousel();
    init_wc_masonry();
    init_wc_filter();
    init_wc_saleCountDown();
    init_wc_quickview();
    init_checkout_coupon();


    function init_wc_masonry(){
        $('.delphinus-products-masonry').each(function(){
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
    }


    function init_wc_filter(){
        $('.wc-header-filter').on('click', 'a', function(e){
            e.preventDefault();
            var $this = $(this);
            if($this.hasClass('active')){
                $(this).removeClass('active');
                $('#delphinus-shop-filters').slideUp('fast');
            }else{
                $(this).addClass('active');
                $('#shop-header-categories').slideUp('fast', function(){
                    $('#shop-header-categories').removeAttr('style');
                    $('.wc-header-categories a').removeClass('active');
                    $('#delphinus-shop-filters').slideDown('fast');
                });
            }
        });

        $('.wc-header-categories').on('click', 'a', function(e){
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
                $('#delphinus-shop-filters').slideUp('fast', function(){
                    $('#shop-header-categories').slideDown('fast');
                });
            }

        });

        $('#delphinus-shop-filters-content').on('click', '.widget-title', function(){
            $(this).closest('.widget-content').toggleClass('widget-active');
        });


    }


    /* ---------------------------------------------
     Grid list Toggle
     --------------------------------------------- */
    function init_wc_grid_list(){
        $('ul.gridlist-toggle a').on('click', function(e){
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

    init_wc_product_carousel($("#sync1"), $("#sync2"));

    /* ---------------------------------------------
     Single Product
     --------------------------------------------- */


    function init_wc_product_carousel(sync1, sync2){

        sync1.imagesLoaded(function(){
            sync1.owlCarousel({
                singleItem : true,
                slideSpeed : 1000,
                items : 1,
                navigation: true,
                pagination: false,
                afterAction : syncPosition,
                autoHeight: true,
                responsiveRefreshRate : 200,
                navigationText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
            });
        });


        sync2.imagesLoaded(function(){
            sync2.owlCarousel({
                theme : 'woocommerce-thumbnails',
                items : sync2.data('items'),
                itemsCustom : [[991,sync2.data('items')], [768, sync2.data('mobile')], [480, sync2.data('mobile')]],
                navigation: false,
                navigationText: false,
                pagination:false,
                responsiveRefreshRate : 100,
                afterInit : function(el){
                    el.find(".owl-item").eq(0).addClass("synced");
                }
            });
        });

        sync2.on("click", ".owl-item", function(e){
            e.preventDefault();
            var number = $(this).data("owlItem");
            sync1.trigger("owl.goTo", number);
        });

        function syncPosition(el){
            var current = this.currentItem;

            sync2
                .find(".owl-item")
                .removeClass("synced")
                .eq(current)
                .addClass("synced");
            if(sync2.data("owlCarousel") !== undefined){
                center(current)
            }
        }
        function center(number){
            var sync2visible = sync2.data("owlCarousel").owl.visibleItems;

            var num = number;
            var found = false;

            for(var i in sync2visible){
                if(num === sync2visible[i]){
                    var found = true;
                }
            }

            if(found===false){
                if(num>sync2visible[sync2visible.length-1]){
                    sync2.trigger("owl.goTo", num - sync2visible.length+2)
                }else{
                    if(num - 1 === -1){
                        num = 0;
                    }
                    sync2.trigger("owl.goTo", num);
                }
            } else if(num === sync2visible[sync2visible.length-1]){
                sync2.trigger("owl.goTo", sync2visible[1])
            } else if(num === sync2visible[0]){
                sync2.trigger("owl.goTo", num-1)
            }
        }
    }




    /* ---------------------------------------------
     QickView
     --------------------------------------------- */
    function init_wc_quickview(){
        $('body').on('click', '.product-quick-view', function(e){
            e.preventDefault();
            var objProduct = $(this);
            $('i', objProduct).attr('class', 'fa fa-circle-o-notch fa-spin');

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
                                var images = $("#quickview-images"),
                                    thumbnails = $("#quickview-thumbnails");
                                init_wc_product_carousel(images, thumbnails);
                            });
                            $('.delphinus-product-popup form').wc_variation_form();
                        },
                        change: function() {
                            $('.delphinus-product-popup form').wc_variation_form();
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
        })
        .on('added_to_cart', function(e, data) {
            var $button_product = $('.wc-loading'),
                $parent = $button_product.parent();
            $parent.tooltip('hide');
        })
        .on('click', '.yith-wcwl-add-button', function() {
            var $this = $(this).addClass('wc-wishlist-loading');
            $('i', $this).attr('class', 'fa fa-circle-o-notch fa-spin');
        })
        .on( 'added_to_wishlist removed_from_wishlist', function() {
            var $button_product = $('.wc-wishlist-loading'),
                $parent = $button_product.closest('.yith-wcwl-add-to-wishlist');
            var data = {action: 'fronted_get_wishlist'};
            $.post(ajax_frontend.ajaxurl, data, function(response) {
                $('.shopping-bag-wishlist').html(response.html);
                //$('.shopping-bag-wishlist .cart_list.product_list_widget').mCustomScrollbar();
            }, 'json');
        })
        .on('wc_fragments_loaded wc_fragments_refreshed added_to_cart added_to_wishlist', function (){
            //$('.shopping-bag .cart_list.product_list_widget').mCustomScrollbar();
        })
        .on( 'click', '.product a.compare:not(.added)', function(e){
            e.preventDefault();
            var $this = $(this).addClass('wc-compare-loading');
        })
        .on('yith_woocompare_open_popup', function(){
            var $button_product = $('.wc-compare-loading'),
                $parent = $button_product.closest('.compare');
            $parent.removeClass('wc-compare-loading');
        });

    /*

    $( 'body' ).on('click','.shopping-bag a.remove',function( e){

        e.preventDefault();

        var product_id = $(this).data('product_id'),
            remove_item = $(this).data('itemkey');

        $('.shopping_cart .shopping-bag').append('<span class="loading_overlay"><i class="fa fa-spinner fa-pulse"></i></span>');

        var data = {
            action: 'fronted_remove_product',
            security : ajax_frontend.security,
            product_id : product_id,
            remove_item : remove_item
        };

        $.get(ajax_frontend.ajaxurl, data, function(response) {
            console.log(response);
        }, 'json');

    });
    */

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
                    $(this).html(event.strftime('<div><span>%D</span>'+delphinus_woocommerce.day_str+'</div><div><span>%H</span>'+delphinus_woocommerce.hour_str+'</div><div><span>%M</span>'+delphinus_woocommerce.min_str+'</div><div><span>%S</span>'+delphinus_woocommerce.sec_str+'</div>'));
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


        var $slick_categories = $('#shop-header-categories')
            .on('setPosition', function(slick){
                if($slick_categories){
                    var $width_w = $slick_categories.width(),
                        $slides = $slick_categories.find('.slick-slide'),
                        $slides_w = 2;

                    $slides.each(function(i){
                        $slides_w += $(this).outerWidth(true);
                    });
                    if($slides_w > $width_w){
                        $slick_categories.removeClass('no-buttons');
                    }else{
                        $slick_categories.addClass('no-buttons');
                        $slick_categories.slick('slickGoTo', 0);
                    }
                }
            })
            .slick({
                dots: false,
                infinite: false,
                speed: 300,
                slidesToShow: 1,
                variableWidth: true,
                prevArrow: '<div class="slick-prev"></div>',
                nextArrow: '<div class="slick-next"></div>'
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


    init_wc_filters();




    function init_wc_filters( ){

        var $ajax_filter = parseInt( delphinus_woocommerce.ajax_filter );
        if(!$ajax_filter) return;

        $('#delphinus-shop-filters').on('click', '.widget_delphinus_orderby a, .widget_delphinus_price_filter a, .widget_color_filter a, .widget_layered_nav a', function( e ){
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

    }

    var $ajax_request;
    function init_wc_update_filters($pageUrl){

        if($ajax_request && $ajax_request.readystate != 4){
            $ajax_request.abort();
        }

        init_wc_loading(true);

        $pageUrl = $pageUrl.replace(/\/?(\?|#|$)/, '/$1');

        var $products = $('#main > .woocommerce-row'),
            $filters = $('#delphinus-shop-filters-content'),
            $pagination = $('#main > .wc-pagination-outer'),
            $columns = $products.find('li:first').data('columns');


        var $data = {
            delphinus_shop: 'full',
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
                    $filters_change = $('#delphinus-shop-filters-content', $response_html),
                    $pagination_change = $('#main > .wc-pagination-outer', $response_html),
                    $wpTitle = $($response_html).filter('title').text();

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
            $('body').append('<div class="wc-filters-loading"></div>');
        }

        var $loading = $('.wc-filters-loading');
        if($show){
            $loading.show();
        }else{
            $loading.hide();
        }
    }





})(jQuery); // End of use strict