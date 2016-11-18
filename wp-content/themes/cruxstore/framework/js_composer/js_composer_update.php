<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



add_action( 'vc_after_init', 'cruxstore_add_option_to_vc' );
function cruxstore_add_option_to_vc() {


    $image_styles = WPBMap::getParam( 'vc_single_image', 'style' );
    $image_styles['value'][esc_html__( 'Border box inner 1', 'cruxstore' )] = 'border-box-1';
    $image_styles['value'][esc_html__( 'Border box inner 2', 'cruxstore' )] = 'border-box-2';
    $image_styles['value'][esc_html__( 'Zoom In', 'cruxstore' )] = 'zoomin';
    $image_styles['value'][esc_html__( 'Zoom Out', 'cruxstore' )] = 'zoomout';
    $image_styles['value'][esc_html__( 'Slide', 'cruxstore' )] = 'slide';
    $image_styles['value'][esc_html__( 'Shine', 'cruxstore' )] = 'shine';
    vc_update_shortcode_param( 'vc_single_image', $image_styles );

    $accordion_styles = WPBMap::getParam( 'vc_tta_accordion', 'style' );

    $accordion_styles['value'][esc_html__( 'Outline Wrapper', 'cruxstore' )] = 'wrapper';
    $accordion_styles['value'][esc_html__( 'Outline Wrapper Shadow', 'cruxstore' )] = 'wrapper shadow';
    vc_update_shortcode_param( 'vc_tta_accordion', $accordion_styles );


    $accordion_iconss = WPBMap::getParam( 'vc_tta_accordion', 'c_icon' );
    $accordion_iconss['value'][esc_html__( 'Arrow Circle', 'cruxstore' )] = 'arrow-circle';
    vc_update_shortcode_param( 'vc_tta_accordion', $accordion_iconss );


    $tab_styles = WPBMap::getParam( 'vc_tta_tabs', 'style' );
    $tab_styles['value'][esc_html__( 'Outline Wrapper', 'cruxstore' )] = 'wrapper';
    $tab_styles['value'][esc_html__( 'Outline Wrapper Shadow', 'cruxstore' )] = 'wrapper shadow';
    vc_update_shortcode_param( 'vc_tta_tabs', $tab_styles );

    $cta_styles = WPBMap::getParam( 'vc_cta', 'style' );
    $cta_styles['value'][esc_html__( 'Transparent', 'cruxstore' )] = 'transparent';
    vc_update_shortcode_param( 'vc_cta', $cta_styles );

    $button_colors = WPBMap::getParam( 'vc_btn', 'color' );
    $button_colors['value'][esc_html__( 'Accent color', 'cruxstore' )] = 'accent';
    vc_update_shortcode_param( 'vc_btn', $button_colors );

    $toggle_colors = WPBMap::getParam( 'vc_toggle', 'color' );
    $toggle_colors['value'][esc_html__( 'Accent color', 'cruxstore' )] = 'accent';
    vc_update_shortcode_param( 'vc_toggle', $toggle_colors );

    $toggle_styles = WPBMap::getParam( 'vc_toggle', 'style' );
    $toggle_styles['value'][esc_html__( 'Check Circle', 'cruxstore' )] = 'check-circle';
    vc_update_shortcode_param( 'vc_toggle', $toggle_styles );

}

function cruxstore_add_visibility_shortcode($class, $base, $atts){
    if(isset($atts['visibility'])){
        $class .= ' '.$atts['visibility'];
    }
    return $class;
}
add_filter('vc_shortcodes_css_class', 'cruxstore_add_visibility_shortcode', 20, 3);
