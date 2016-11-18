<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


$option = get_option( 'kt_mailchimp_option' );

$lists_arr = array(__('Select option', 'cruxstore') => '');


if ( isset ( $option['api_key'] ) && !empty ( $option['api_key'] ) && class_exists('MCAPI') ) {
    $mcapi = new MCAPI($option['api_key']);
    $lists = $mcapi->lists();
    if($lists['data']){
        foreach ($lists['data'] as $item) {
            $lists_arr[$item['name']] = $item['id'];
        }
    }
}

vc_map( array(
    "name" => __( "KT: Mailchimp", 'cruxstore'),
    "base" => "kt_mailchimp",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => __( "Mailchimp", 'cruxstore'),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            'type' => 'textfield',
            'param_name' => 'title',
            'heading' => __( 'Widget title', 'js_composer' ),
            'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Mailchimp layout', 'cruxstore' ),
            'param_name' => 'layout',
            'admin_label' => true,
            'value' => array(
                __( 'Layout 1', 'cruxstore' ) => '1',
                __( 'Layout 2', 'cruxstore' ) => '2',
                __( 'Layout 3', 'cruxstore' ) => '3',
                __( 'Layout 4', 'cruxstore' ) => '4',
                __( 'Layout 5', 'cruxstore' ) => '5',
                __( 'Layout 6', 'cruxstore' ) => '6',
                __( 'Layout 7', 'cruxstore' ) => '7',
            ),
            'description' => __( 'Select your layout', 'cruxstore' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Mailchimp List', 'cruxstore' ),
            'param_name' => 'list',
            'admin_label' => true,
            'value' => $lists_arr,
            'description' => __( 'Select your List', 'cruxstore' )
        ),
        array(
            "type" => 'checkbox',
            "heading" => __( 'Double opt-in', 'cruxstore' ),
            "param_name" => 'opt_in',
            "description" => __("", 'cruxstore'),
            "value" => array( __( 'Yes, please', 'js_composer' ) => 'yes' ),
        ),
        array(
            "type" => 'checkbox',
            "heading" => __( 'Disable names', 'cruxstore' ),
            "param_name" => 'disable_names',
            "description" => __("", 'cruxstore'),
            "value" => array( __( 'Yes, please', 'js_composer' ) => 'yes' ),
        ),

        array(
            "type" => "textarea",
            "heading" => __("Text before form", 'cruxstore'),
            "param_name" => "text_before",
            "description" => __("", 'cruxstore')
        ),
        array(
            'type' => 'textfield',
            'param_name' => 'placeholder',
            'heading' => __( 'Placeholder', 'js_composer' ),
            'vaule' => esc_html__('Enter your email', 'cruxstore')
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'CSS Animation', 'js_composer' ),
            'param_name' => 'css_animation',
            'admin_label' => true,
            'value' => array(
                __( 'No', 'js_composer' ) => '',
                __( 'Top to bottom', 'js_composer' ) => 'top-to-bottom',
                __( 'Bottom to top', 'js_composer' ) => 'bottom-to-top',
                __( 'Left to right', 'js_composer' ) => 'left-to-right',
                __( 'Right to left', 'js_composer' ) => 'right-to-left',
                __( 'Appear from center', 'js_composer' ) => "appear"
            ),
            'description' => __( 'Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'js_composer' )
        ),
        array(
            "type" => "textfield",
            "heading" => __( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        array(
            'type' => 'css_editor',
            'heading' => __( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => __( 'Design options', 'js_composer' )
        ),

    )
) );