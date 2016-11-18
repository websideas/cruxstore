<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Delphinus_List extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts(array(

            'style' => 'check',
            'background_style' => '',

            'background_color' => 'grey',
            'custom_background_color' => '',
            'color' => 'black',
            'custom_color' => '',


            'css_animation' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts);

        extract( $atts );

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'delphinus-list ', $this->settings['base'], $atts ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'extra' => $this->getExtraClass( $el_class ),
            'style' => 'style-'.$style
        );


        $uniqid = 'fancy-list-'.uniqid();
        $custom_css = '';



        if( $color == 'custom' ){
            $color = $custom_color;
        }else{
            $color = delphinus_color2Hex($color);
        }
        $custom_css .= '#'.$uniqid.' > ul > li:before{color: '.$color.';}';


        if ( strlen( $background_style ) > 0 ) {

            if( $background_color == 'custom' ){
                $background_color = $custom_background_color;
            }else{
                $background_color = delphinus_color2Hex($background_color);
            }

            if ( false !== strpos( $background_style, 'outline' ) ) {
                $background_style .= ' list-outline'; // if we use outline style it is border in css
                $custom_css .= '#'.$uniqid.' > ul > li:before{border-color:'.$background_color.';}';
            } else {
                $background_style .= ' list-background';
                $custom_css .= '#'.$uniqid.' > ul > li:before{background:'.$background_color.';}';
            }

            $elementClass[] = 'bg-style-'.$background_style;



            $elementClass[] = 'bg-style';

        }


        if($custom_css){
            $custom_css = '<div class="delphinus_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div id="'.$uniqid.'" class="'.esc_attr( $elementClass ).'">'.$content.$custom_css.'</div>';

    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Fancy List", 'delphinus'),
    "base" => "delphinus_list",
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    "description" => esc_html__( "", 'delphinus'),

    "params" => array(
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'delphinus'),
            "param_name" => "content",
            "description" => esc_html__("", 'delphinus'),
            'holder' => 'div',
            'std' => '<ul><li>List Item</li><li>list Item</li></ul>'
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'List style', 'delphinus' ),
            'param_name' => 'style',
            'value' => array(
                esc_html__( 'Check', 'delphinus' ) => 'check',
                esc_html__( 'Numbers', 'delphinus' ) => 'numbers',
                esc_html__( 'Angle Right ', 'delphinus' ) => 'angle-right',
            ),
            'std' => 'check',
            'description' => esc_html__('select your style', 'delphinus'),
            "admin_label" => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon color', 'js_composer' ),
            'param_name' => 'color',
            'value' => array_merge( getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => esc_html__( 'Select icon color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'std' => 'black'
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Custom color', 'js_composer' ),
            'param_name' => 'custom_color',
            'description' => esc_html__( 'Select custom icon color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'color',
                'value' => 'custom',
            ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Background shape', 'js_composer' ),
            'param_name' => 'background_style',
            'value' => array(
                esc_html__( 'None', 'js_composer' ) => '',
                esc_html__( 'Circle', 'js_composer' ) => 'rounded',
                esc_html__( 'Square', 'js_composer' ) => 'boxed',
                esc_html__( 'Rounded', 'js_composer' ) => 'rounded-less',
                esc_html__( 'Outline Circle', 'js_composer' ) => 'rounded-outline',
                esc_html__( 'Outline Square', 'js_composer' ) => 'boxed-outline',
                esc_html__( 'Outline Rounded', 'js_composer' ) => 'rounded-less-outline',
            ),
            'description' => esc_html__( 'Select background shape and style for icon.', 'js_composer' ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Background color', 'js_composer' ),
            'param_name' => 'background_color',
            'value' => array_merge( getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'std' => 'grey',
            'description' => esc_html__( 'Select background color for icon.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'dependency' => array(
                'element' => 'background_style',
                'not_empty' => true,
            ),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Custom background color', 'js_composer' ),
            'param_name' => 'custom_background_color',
            'description' => esc_html__( 'Select custom icon background color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'background_color',
                'value' => 'custom',
            ),
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