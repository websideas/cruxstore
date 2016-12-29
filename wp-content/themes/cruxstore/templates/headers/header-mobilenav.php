<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$items_wrap = '';

if(cruxstore_is_wpml()) {
    $items_wrap .= cruxstore_custom_wpml(
        '<li class="menu-item menu-item-language">',
        '</li>',
        'sub-menu-dropdown',
        esc_html__('Language', 'cruxstore').': '
    );
}


if ( cruxstore_is_wc()){

    if(class_exists('WOOCS')){

        global $WOOCS;
        $currencies=$WOOCS->get_currencies();

        $currency_html = '';
        $currency_active = '';
        foreach($currencies as $key => $currency){

            if($WOOCS->current_currency == $key){
                $selected = 'active';
                $currency_active = $key;
            }else{
                $selected = '';
            }

            $currency_html .= sprintf(
                '<li class="%s"><a href="#" data-currency="%s" class="currency-item" title="%s"><span></span>%s</a>',
                $selected,
                esc_attr($currency['name']),
                esc_attr($currency['description']),
                $currency['name']
            );
        }
        $items_wrap .=  sprintf(
            '<li class="menu-item menu-item-currency"><a href="#" class="currency-item">%s</a><ul class="sub-menu-dropdown">%s</ul></li>',
            esc_html__('Currency', 'cruxstore').': '.$currency_active,
            $currency_html
        );
    }
    
    
    if( !cruxstore_option('catalog_mode', 0)){
        $text = (is_user_logged_in ()) ? esc_html__('My Account', 'cruxstore') : esc_html__('Login', 'cruxstore');
        $items_wrap .= sprintf(
            '<li class="menu-item menu-item-myaccount"><a href="%s">%s</a>',
            get_permalink( get_option('woocommerce_myaccount_page_id') ),
            $text
        );
    
        if ( defined( 'YITH_WCWL' ) ) {
            global $yith_wcwl;
            $wishlist = sprintf(
                '<a href="%s">%s</a>',
                esc_url( $yith_wcwl->get_wishlist_url() ),
                esc_html__('wishlist', 'cruxstore')
            );
            $items_wrap .= sprintf('<li class="menu-item menu-item-wishlist">%s</li>', $wishlist);
        }
    }

}



if ( cruxstore_option('header_search', 1) ) {
    $header_search_type = cruxstore_option('header_search_type', 'all');
    if( $header_search_type == 'product' && cruxstore_is_wc()){
        $search = get_product_search_form(false);
    }else{
        $search = get_search_form(false);
    }
    $items_wrap .= sprintf('<li class="menu-item menu-item-search-form">%s</li>', $search);
}

$primary = cruxstore_get_mainmenu();
if(!$primary['custom']){
    if ( has_nav_menu( $primary['menu'] ) ) {
        wp_nav_menu( array(
            'theme_location'    => $primary['menu'],
            'container'         => 'nav',
            'container_class'   => 'main-nav-mobile',
            'container_id'      => 'main-nav-mobile',
            'menu_class'        => 'menu navigation-mobile',
            'link_before'       => '<span>',
            'link_after'        => '</span>',
            'walker'            => new KTMegaWalker(),
            'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s'.$items_wrap.'</ul>',
        ) );

    }else{
        printf(
            '<nav id="main-nav-mobile" class="main-nav-mobile"><ul class="menu navigation-mobile"><li><a href="%s">%s</a></li>%s</ul></nav>',
            admin_url( 'nav-menus.php'),
            esc_html__("Define your site main menu!", 'cruxstore'),
            $items_wrap
        );

    }
}else{
    wp_nav_menu( array(
        'menu'    => $primary['menu'],
        'container'         => 'nav',
        'container_class'   => 'main-nav-mobile',
        'container_id'      => 'main-nav-mobile',
        'menu_class'        => 'menu navigation-mobile',
        'link_before'       => '<span>',
        'link_after'        => '</span>',
        'walker'            => new KTMegaWalker(),
        'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s'.$items_wrap.'</ul>',
    ) );
}