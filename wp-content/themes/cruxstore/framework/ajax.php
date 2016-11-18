<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Disable popup
 *
 */

function cruxstore_fronted_popup_callback() {

    $day = apply_filters('cruxstore_popup_time', 1);
    setcookie('cruxstore_popup', 1, time() + (86400*$day), '/');
    die();

}
add_action( 'wp_ajax_fronted_popup', 'cruxstore_fronted_popup_callback' );
add_action( 'wp_ajax_nopriv_fronted_popup', 'cruxstore_fronted_popup_callback' );





