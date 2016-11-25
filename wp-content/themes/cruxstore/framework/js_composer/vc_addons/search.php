<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_CruxStore_Search extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts(array(
            'layout' => 'default',
            'css_animation' => '',
            'type' => 'all',
            'animation_delay' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts);

        extract( $atts );
        $output = '';
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'cruxstore-search', $this->settings['base'], $atts ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'layout' => 'layout'.$layout
        );
        
        $rand = rand();
        
        $type = ($type == 'products' && cruxstore_is_wc()) ? 'WC_Widget_Product_Search' : 'WP_Widget_Search';
        
        $args = array();
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
        return '<div id="cruxstore-search'.$rand.'" class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';

    }
}
// Add your Visual Composer logic here
vc_map( array(
	'name' => 'KT: ' . __( 'Search', 'cruxstore' ),
	'base' => 'cruxstore_search',
	'category' => __( 'WordPress Widgets', 'js_composer' ),
	'description' => __( 'A search form for your site', 'js_composer' ),
	'params' => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Search Form Layout', 'cruxstore' ),
            'param_name' => 'layout',
            'value' => array(
                esc_html__( 'Default', 'cruxstore' ) => 'default',
                esc_html__( 'Layout 1', 'cruxstore' ) => '1',
            ),
            'description' => esc_html__( 'Choose search form layout.', 'cruxstore' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Search Type', 'cruxstore' ),
            'param_name' => 'type',
            'value' => array(
                esc_html__( 'All', 'cruxstore' ) => 'all',
                esc_html__( 'Only products', 'cruxstore' ) => 'products',
            ),
            'description' => esc_html__( 'Choose search form layout.', 'cruxstore' ),
            "admin_label" => true,
        ),

        cruxstore_map_add_css_animation(),
        cruxstore_map_add_css_animation_delay(),
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