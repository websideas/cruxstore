<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_CruxStore_Instagram extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts(array(
            'username' => '',
            'number' => '9',
            'size' => 'thumbnail',
            'target' => '_self',
            'css_animation' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts);

        extract( $atts );
        $output = '';
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'cruxstore-instagram', $this->settings['base'], $atts ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
        );

        $type = 'null_instagram_widget';

        $args = array(
            'username' => $username,
            'number' => $number,
            'size' => $size,
            'target' => $target
        );
        global $wp_widget_factory;
        // to avoid unwanted warnings let's check before using widget
        if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
            ob_start();
            the_widget( $type, $atts, $args );
            $output .= ob_get_clean();
        } else {
            $output .= $this->debugComment( 'Widget ' . esc_attr( $type ) . 'Not found in : vc_wp_search' );
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}
// Add your Visual Composer logic here
vc_map( array(
    'name' => 'KT: ' . __( 'Instagram', 'cruxstore' ),
    'base' => 'cruxstore_instagram',
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => esc_html__( "Displays your latest Instagram photos", 'cruxstore'),
    'params' => array(

        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Username', 'js_composer' ),
            'value' => '',
            'param_name' => 'username',
            "admin_label" => true,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of photos', 'js_composer' ),
            'value' => '9',
            'param_name' => 'number',
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Photo size', 'js_composer' ),
            'param_name' => 'size',
            'value' => array(
                esc_html__( 'Thumbnail', 'js_composer' ) => 'thumbnail',
                esc_html__( 'Small', 'js_composer' ) => 'small',
                esc_html__( 'Large', 'js_composer' ) => 'large',
                esc_html__( 'Original', 'js_composer' ) => 'original',
            ),
            'std' => 'thumbnail',
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Open links in', 'js_composer' ),
            'param_name' => 'target',
            'value' => array(
                esc_html__( 'Current window (_self)', 'js_composer' ) => '_self',
                esc_html__( 'New window (_blank)', 'js_composer' ) => '_blank',
            ),
            'std' => 'name',
            "admin_label" => true,
        ),
        vc_map_add_css_animation(),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' )
        )
    ),
));