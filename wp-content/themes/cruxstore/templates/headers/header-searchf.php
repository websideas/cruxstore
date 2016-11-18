<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


if(cruxstore_option('header_search', 1)){
    $header_search_type = cruxstore_option('header_search_type', 'all');

    if( $header_search_type == 'product' && cruxstore_is_wc()){
        $search = get_product_search_form(false);
    }else{
        $search = get_search_form(false);
    }

    printf(
        '<div id="%1$s" class="%2$s">%3$s</div>',
        'header-searchform',
        'header-searchform',
        $search
    );
}