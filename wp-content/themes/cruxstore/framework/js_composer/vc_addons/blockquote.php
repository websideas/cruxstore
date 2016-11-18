<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Blockquote extends WPBakeryShortCode {
    var $excerpt_length;
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'author' => '',
            'reverse' => 'false',
            'layout' => 1,
            'css' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
        ), $atts );

        extract($atts);
        $output = '';

        $reverse = apply_filters('sanitize_boolean', $reverse);

        $elementClass = array(
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'reverse' => ( $reverse) ? 'blockquote-reverse' : '',
            'layout' => 'layout-'.$layout
        );

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $output .= '<blockquote class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>';
            $output .= '<p>'. do_shortcode($content).'</p>';
            if( $author ){
                $output .= '<footer>'.$author.'</footer>';
            }
        $output .= '</blockquote>';
        
    	return $output;
    }
}

vc_map( array(
    "name" => esc_html__( "Blockquote", 'cruxstore'),
    "base" => "blockquote",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'cruxstore'),
            "param_name" => "content",

            'holder' => 'div',
        ),
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Author', 'js_composer' ),
            'param_name' => 'author',
            "admin_label" => true,
            "description" => esc_html__("Enter your author.", 'cruxstore'),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Layout', 'cruxstore' ),
            'param_name' => 'layout',
            'value' => array(
                esc_html__( 'Layout 1', 'cruxstore' ) => '1',
                esc_html__( 'Layout 2', 'cruxstore' ) => '2',
                esc_html__( 'Layout 3', 'cruxstore' ) => '3',
                esc_html__( 'Layout 4', 'cruxstore' ) => '4',
                esc_html__( 'Layout 5', 'cruxstore' ) => '5',
                esc_html__( 'Layout 6', 'cruxstore' ) => '6',
            ),
            'description' => esc_html__( 'Select your layout.', 'cruxstore' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Reverse Blockquote', 'cruxstore' ),
            'param_name' => 'reverse',
            'value' => 'false',
            "description" => esc_html__("Enable reverse", 'cruxstore'),
        ),
        cruxstore_map_add_css_animation(),
        cruxstore_map_add_css_animation_delay(),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        array(
			'type' => 'css_editor',
			'heading' => esc_html__( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => esc_html__( 'Design options', 'js_composer' )
		),
    ),
));