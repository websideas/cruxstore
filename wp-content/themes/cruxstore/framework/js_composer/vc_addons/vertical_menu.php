<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_Vertical_menu extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts(array(



            'css_animation' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts);

        extract( $atts );

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vertical-menu ', $this->settings['base'], $atts ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'extra' => $this->getExtraClass( $el_class ),
            'animation' =>  $this->getCSSAnimation( $css_animation )
        );

        $output = '';

        ob_start();
        if ( has_nav_menu( 'vertical' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'vertical',
                'container' => '',
                'link_before'     => '<span>',
                'link_after'      => '</span>',
                'menu_id'         => 'vertical-navigation'
            ) );
        }else{
            printf(
                '<ul id="vertical-navigation"><li><a href="%s">%s</a></li></ul>',
                admin_url( 'nav-menus.php'),
                esc_html__("Define your site vertical menu!", 'cruxstore')
            );
        }
        $output .= ob_get_clean();

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Vertical Navigation Menu", 'cruxstore'),
    "base" => "vertical_menu",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),

    "params" => array(

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