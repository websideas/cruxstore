<?php
/*
Plugin Name:  CruxStore Core
Plugin URI:   http://kitethemes.com/
Description:  All function for CruxStore theme
Version:      1.0
Author:       KiteThemes
Author URI:   http://themeforest.net/user/kite-themes

Copyright (C) 2015-2016, by Cuongdv
All rights reserved.
*/
/**
 * Allow shortcodes in widgets.
 *
 */
add_filter( 'widget_text', 'do_shortcode' );


define( 'CRUX_CORE', __FILE__ );
define( 'CRUX_CORE_BASENAME', plugin_basename( CRUX_CORE ) );
define( 'CRUX_CORE_DIR', plugin_dir_path(__FILE__) );
define( 'CRUX_CORE_URL', plugin_dir_url(__FILE__) );
define( 'CRUX_EXT_DIR', CRUX_CORE_DIR.'extentions/' );
define( 'CRUX_EXT_URL', CRUX_CORE_URL.'extentions/' );
define( 'CRUX_CORE_JS', CRUX_CORE_URL.'assets/js/' );
define( 'CRUX_CORE_CSS', CRUX_CORE_URL.'assets/css/' );


/**
 * Require all plugin
 *
 */
require_once CRUX_EXT_DIR.'custompost/cp.php';
require_once CRUX_EXT_DIR.'shortcodes/shortcodes.php';
require_once CRUX_EXT_DIR.'importer/importer.php';
require_once CRUX_EXT_DIR.'mailchimp/mailchimp.php';
require_once CRUX_EXT_DIR.'meta-box/meta-box.php';
require_once CRUX_EXT_DIR.'megamenu/megamenu.php';



/**
 * Remove Rev Slider Metabox
 */
if ( is_admin() ) {

    add_action( 'do_meta_boxes', 'remove_revolution_slider_meta_boxes' );
    function remove_revolution_slider_meta_boxes() {
        remove_meta_box( 'mymetabox_revslider_0', 'crux_testimonial', 'normal' );
        remove_meta_box( 'mymetabox_revslider_0', 'crux_client', 'normal' );
        remove_meta_box( 'mymetabox_revslider_0', 'crux_employees', 'normal' );
        remove_meta_box( 'mymetabox_revslider_0', 'kt_mgmenu', 'normal' );
    }


    add_action( 'admin_init', 'cruxstore_tinymce_shortcode_button' );
    function cruxstore_tinymce_shortcode_button() {
        if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
            add_filter( 'mce_buttons', 'cruxstore_register_tinymce_button' );
            add_filter( 'mce_external_plugins', 'cruxstore_add_tinymce_shortcode_button' );
        }
    }

    function cruxstore_register_tinymce_button( $buttons ) {
        array_push( $buttons, "cruxstore_shortcode" );
        return $buttons;
    }

    function cruxstore_add_tinymce_shortcode_button( $plugin_array ) {
        $plugin_array['shortcode_button_script'] = CRUX_CORE_JS.'tinymce.editor.plugin.js';
        return $plugin_array;
    }


}