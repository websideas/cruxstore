<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if ( has_nav_menu( 'topbar' ) ) {
    echo '<li class="header-navigation">';
    wp_nav_menu( array( 'theme_location' => 'topbar', 'container' => '', 'menu_id' => 'topbar-nav' ) );
    echo '</li>';
}