<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_Delphinus_Message extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        extract(shortcode_atts(array(
            'title' => '',
            "type" => 'normal',
            "close" => 'false',
            'style' => 'classic',
            'animation_delay' => '',
            'css_animation' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts));

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'alert ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'type' => 'alert-'.$type,
            'style' => 'alert-style-'.$style,
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'css_animation' => delphinus_getCSSAnimation( $css_animation ),
        );
        if($close == 'true'){
            $elementClass['dismissible'] = 'alert-dismissible fade in';
        }
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $output = '';
        $output .= '<div class="'.esc_attr( $elementClass ).'" role="alert"'.$animation_delay.'>';
        if($close == 'true'){
            $output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>';
        }

        if($title){
            $output .= '<h3 class="alert_title">'.$title.'</h3>';
        }
        $output .= $content;

        $output .= '</div><!-- .alert -->';

        return $output;
    }
}



// Add your Visual Composer logic here
vc_map( array(

    "name" => esc_html__( "KT: Message Box", 'delphinus'),
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    "base" => "delphinus_message",
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            "admin_label" => true,
        ),
        array(
            'type' => 'textarea_html',
            'holder' => 'div',
            'heading' => esc_html__( 'Text', 'js_composer' ),
            'param_name' => 'content',
            'value' => wp_kses( __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' ), array( 'p' => array()) )
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Type",'delphinus'),
            "param_name" => "type",
            "value" => array(
                esc_html__('Normal', 'delphinus') => 'normal',
                esc_html__('Success', 'delphinus') => 'success',
                esc_html__('Info', 'delphinus') => 'info',
                esc_html__('Warning', 'delphinus') => 'warning',
                esc_html__('Danger', 'delphinus') => 'danger',
            ),
            "admin_label" => true,
        ),

        array(
            "type" => "dropdown",
            "heading" => esc_html__("Style",'delphinus'),
            "param_name" => "style",
            "value" => array(
                esc_html__('Classic', 'delphinus') => 'classic',
                esc_html__('Modern', 'delphinus') => 'modern',
            ),
            "admin_label" => true,
        ),
        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Close button', 'delphinus' ),
            'param_name' => 'close',
            'value' => 'false',
            "description" => esc_html__("Close button in alert", 'delphinus')
        ),
        delphinus_map_add_css_animation(),
        delphinus_map_add_css_animation_delay(),
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