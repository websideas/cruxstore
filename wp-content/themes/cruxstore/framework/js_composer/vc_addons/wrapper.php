<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_CruxStore_Wrapper extends WPBakeryShortCodesContainer {
    protected function content($atts, $content = null)
    {
        $atts = shortcode_atts( array(
            'max_width' => 950,
            'css_animation' => '',
            'align' => 'center',
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'cruxstore-wrapper', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'align' => 'cruxstore-wrapper-'.$align
        );


        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div class="'.esc_attr( $elementClass ).'" style="max-width: '.$max_width.'px;">'. do_shortcode($content) .'</div>';

    }
}


//Register "container" content element. It will hold all your inner (child) content elements
vc_map( array(
    "name" => esc_html__("KT: Wrapper", 'cruxstore'),
    "base" => "cruxstore_wrapper",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    'is_container' => true,
    "content_element" => true,
    "show_settings_on_create" => true,
    "js_view" => 'VcColumnView',
    "params" => array(
        array(
            "type" => "cruxstore_number",
            "heading" => esc_html__("Max width", 'cruxstore'),
            "param_name" => "max_width",
            "value" => 950,
            "suffix" => esc_html__("px", 'cruxstore'),
            'description' => esc_html__( 'Select max-width for element.', 'cruxstore' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Align', 'js_composer' ),
            'param_name' => 'align',
            'admin_label' => true,
            'value' => array(
                esc_html__( 'Center', 'js_composer' ) => '',
                esc_html__( 'Left', 'js_composer' ) => 'left',
                esc_html__( 'Right', 'js_composer' ) => 'right'
            ),
            'description' => esc_html__( 'Select wrapper alignment.', 'cruxstore' )
        ),
        vc_map_add_css_animation(),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer")
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