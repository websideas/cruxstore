<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


$primary = cruxstore_get_mainmenu();

if(!$primary['custom']){
    if ( has_nav_menu( $primary['menu'] ) ) {
        wp_nav_menu( array(
            'theme_location' => $primary['menu'],
            'container' => '',
            'link_before'     => '<span>',
            'link_after'      => '</span>',
            'menu_id'         => 'main-navigation',
            'menu_class' => 'hidden-xs hidden-sm',
            'walker' => new KTMegaWalker(),
        ) );
    }else{
        printf(
            '<ul id="main-navigation" class="hidden-xs hidden-sm"><li><a href="%s">%s</a></li></ul>',
            admin_url( 'nav-menus.php'),
            esc_html__("Define your site main menu!", 'cruxstore')
        );
    }
}else{
    wp_nav_menu( array(
        'menu' => $primary['menu'],
        'container' => '',
        'link_before'     => '<span>',
        'link_after'      => '</span>',
        'menu_id'         => 'main-navigation',
        'menu_class' => 'hidden-xs hidden-sm',
        'walker' => new KTMegaWalker(),
    ) );
}


