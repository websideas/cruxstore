<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if ( has_nav_menu( 'footer' ) ) {
    wp_nav_menu( array( 'theme_location' => 'footer', 'container' => 'nav', 'container_id' => 'bottom-nav' ) );
}