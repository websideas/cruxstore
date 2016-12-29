<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Product_Widgets extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => esc_html__( 'Title', 'js_composer' ),
            'title_color' => 'default',
            'number' => 3,
            'widget' => 'new',

            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css' => '',

        ), $atts );

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wc-product-widgets', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );

        if($widget == 'top_rated'){
            $type = 'WC_Widget_Top_Rated_Products';
        } else{
            $type = 'WC_Widget_Products';
            $atts['show'] = $widget;

            if($widget == 'onsale'){
                $atts['orderby'] = 'sales';
                $atts['order'] = 'desc';

            }

        }

        $output = '';


        $args = array(
            'widget_id' => rand(),
            'before_title' => '<h4 class="product-widgets-title product-widgets-'.$title_color.'"><span>',
            'after_title' => '</span></h4>',
        );



        global $wp_widget_factory;
        // to avoid unwanted warnings let's check before using widget
        if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
            ob_start();
            the_widget( $type, $atts, $args );
            $output .= ob_get_clean();
        } else {
            $output .= $this->debugComment( 'Widget ' . esc_attr( $type ) . 'Not found in : '.$type );
        }

        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        $output = '<div class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';

        return $output;
    }
}


vc_map( array(
    "name" => esc_html__( "KT: Product Widgets", 'cruxstore'),
    "base" => "product_widgets",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            'value' => esc_html__( 'Title', 'js_composer' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Title Color', 'js_composer' ),
            'param_name' => 'title_color',
            'value' => array_merge(  array( esc_html__( 'Default', 'js_composer' ) => 'default' ), array( esc_html__( 'Accent color', 'cruxstore' ) => 'accent' ), getVcShared( 'colors' ) ),
            'std' => 'default',
            'param_holder_class' => 'vc_colored-dropdown',
            'description' => esc_html__( 'Choose color for title', 'cruxstore' ),
        ),

        // Data setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Data settings", 'cruxstore'),
            "param_name" => "data_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Select Widget', 'js_composer' ),
            'param_name' => 'widget',
            'value' => array(
                esc_html__( 'New Products', 'cruxstore' ) => '',
                esc_html__( 'Top Rated Products', 'cruxstore' ) => 'top_rated',
                esc_html__( 'Featured Products', 'cruxstore' ) => 'featured',
                esc_html__( 'On-sale Products', 'cruxstore' ) => 'onsale',
                esc_html__( 'Best Sellers', 'cruxstore' ) => 'bestselling',
            )
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Number of products to show', 'js_composer' ),
            'value' => 3,
            'param_name' => 'number',
        ),
        // Others setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Others settings", 'cruxstore'),
            "param_name" => "others_settings",
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
            'heading' => esc_html__( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => esc_html__( 'Design options', 'js_composer' )
        ),
    ),
));
