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
    init_wc_easyZoom();
    init_wc_swatch();
    init_wc_video();
    init_wc_filters();
    init_wc_product_carousel();
    init_wc_sticky();

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

        $('body').on('click', '#cruxstore-shop-filters-content .widget-title', function(){
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
                                var images = $("#quickview-images"),
                                    thumbnails = $("#quickview-thumbnails");
                                init_wc_product_carousel(images, thumbnails);
                            });
                            $('.cruxstore-product-popup form').wc_variation_form();
                        },
                        change: function() {
                            $('.cruxstore-product-popup form').wc_variation_form();
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

        $('.wc-products-vertical-navigation').on('click', 'span', function(e){
            e.preventDefault();
            var $this = $(this),
                $vertical = $this.closest('.wc-products-vertical'),
                $pane = $vertical.find('.tab-pane.active'),
                $owl = $pane.find("ul.shop-products");

            if($this.hasClass('wc-products-vertical-left')){
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
                console.log('call');

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