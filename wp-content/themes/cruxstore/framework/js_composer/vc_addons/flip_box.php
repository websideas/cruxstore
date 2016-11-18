<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );
class WPBakeryShortCode_Flip_Box extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {

        extract(shortcode_atts(array(
            'title' => esc_html__( 'Title', 'js_composer' ),
            'subcontent' => '',
            'use_theme_fonts' => 'true',
            'font_container' => '',
            'google_fonts' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts));

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'flip-box ', $this->settings['base'], $atts ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
        );

        $style_title = '';

        extract( $this->getAttributes( $atts ) );
        unset($font_container_data['values']['text_align']);



        $styles = array();
        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        $subsets = '';
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        }
        if ( ! empty( $google_fonts_data ) && isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }

        if ( ! empty( $styles ) ) {
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }

        $box_title = ($title) ? '<' . $font_container_data['values']['tag'] . ' class="flip-box-before" '.$style_title.'>'.$title.'</' . $font_container_data['values']['tag'] . '>' : '';

        $output = $box_title.'<div class="flip-box-after">'.$subcontent.'</div>';
        
        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';

    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Flip Box", 'cruxstore'),
    "base" => "flip_box",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => esc_html__( "", 'cruxstore'),

    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            'value' => esc_html__( 'Title', 'js_composer' ),
            "admin_label" => true,
        ),

        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Content', 'js_composer' ),
            'param_name' => 'subcontent',
            'value' => esc_html__( 'Content', 'js_composer' ),
            "admin_label" => true,
            'description' => esc_html__( 'Enter your content here.', 'js_composer' ),
        ),


        array(
            'type' => 'hidden',
            'heading' => esc_html__( 'Link Url', 'js_composer' ),
            'param_name' => 'link',
        ),


        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_size',
                    'line_height',
                    'color',
                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => esc_html__( 'Typography', 'cruxstore' )
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'js_composer' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
                    'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
            'description' => esc_html__( '', 'js_composer' ),
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