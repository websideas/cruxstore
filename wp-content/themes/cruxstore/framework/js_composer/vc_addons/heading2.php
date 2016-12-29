<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_CruxStore_Heading2 extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => esc_html__( 'Title', 'js_composer' ),
            'title_color' => 'default',
            'font_size' => '',
            'line_height' => '',
            'letter_spacing' => '',
            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',
            'font_option' => '',

            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );


        // This is needed to extract $font_container_data and $google_fonts_data
        extract( $this->getAttributes( $atts ) );
        unset($font_container_data['values']['text_align']);


        $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
        extract( $atts );

        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        } else {
            $subsets = '';
        }

        if ( isset( $google_fonts_data['values'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }


        if ( empty( $styles ) ) {
            $styles = array();
        }
        $font_option = explode( ',', $font_option );
        if ( in_array( 'uppercase', $font_option ) ) {
            $styles[] = 'text-transform: uppercase;';
        }
        if ( in_array( 'underline', $font_option ) ) {
            $styles[] = 'text-decoration: underline;';
        }
        if ( in_array( 'italic', $font_option ) ) {
            $styles[] = 'font-style: italic;';
        }
        if($letter_spacing){
            $styles[] = 'letter-spacing: '.$letter_spacing.'px';
        }
        if($title_color != 'default'){
            $styles[] = 'color: ' .cruxstore_color2Hex($title_color);
        }

        if ( count( $styles ) ) {
            $style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        } else {
            $style = '';
        }

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'cruxstore-heading2 ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );


        $custom_css = '';
        $rand = 'cruxstore_heading_'.rand();

        if($font_size){
            $custom_css .= cruxstore_responsive_render( '#'.$rand.' .cruxstore-heading2-title', 'font-size',  $font_size);
        }

        if($line_height){
            $custom_css .= cruxstore_responsive_render( '#'.$rand.' .cruxstore-heading2-title', 'line-height',  $line_height);
        }


        $title = sprintf('<%1$s class="cruxstore-heading2-title" %2$s>%3$s</%1$s>', $font_container_data['values']['tag'], $style, $title );
        $content = sprintf('<div class="cruxstore-heading2-content">%s</div>', $content);

        $output = $title.$content;

        $output .= $custom_css;

        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div id="'.$rand.'"  class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';

    }


}



/* Custom Heading element
----------------------------------------------------------- */
vc_map( array(
    'name' => esc_html__( 'KT: Heading2', 'cruxstore' ),
    'base' => 'cruxstore_heading2',
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    'params' => array(
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

        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'cruxstore'),
            "param_name" => "content",
            "value" => '',
            "holder" => "div",
        ),
        // Others setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Others settings", 'cruxstore'),
            "param_name" => "others_settings",
        ),
        cruxstore_map_add_css_animation(),
        cruxstore_map_add_css_animation_delay(),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Extra class name', 'js_composer' ),
            'param_name' => 'el_class',
            'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
        ),
        array(
            'type' => 'hidden',
            'param_name' => 'link',
        ),
        // Typography setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Typography heading", 'cruxstore'),
            "param_name" => "typography_heading",
            'group' => esc_html__( 'Typography', 'cruxstore' ),
        ),
        array(
            'type' => 'cruxstore_responsive',
            'param_name' => 'font_size',
            'heading' => esc_html__( 'Font size', 'cruxstore' ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'unit' =>  esc_html__( 'px', 'cruxstore' ),
            'description' => esc_html__( 'Use font size for the title.', 'cruxstore' ),
        ),
        array(
            'type' => 'cruxstore_responsive',
            'param_name' => 'line_height',
            'heading' => esc_html__( 'Line Height', 'cruxstore' ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'unit' =>  esc_html__( 'px', 'cruxstore' ),
            'description' => esc_html__( 'Use line height for the title.', 'cruxstore' ),
        ),
        array(
            "type" => "cruxstore_number",
            "heading" => esc_html__("Letter spacing", 'cruxstore'),
            "param_name" => "letter_spacing",
            "min" => 0,
            "suffix" => "px",
            'group' => esc_html__( 'Typography', 'cruxstore' )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'tag' => 'h3',
                    //'color',
                    //'font_size',
                    //'line_height',
                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'checkbox',
            'heading' => __( 'Font Option', 'cruxstore' ),
            'param_name' => 'font_option',
            'value' => array(
                __( 'Underline', 'cruxstore' ) => 'underline',
                __( 'Italic', 'cruxstore' ) => 'italic',
            ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'description' => esc_html__( 'Select special option for font.', 'cruxstore' ),
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
        ),


        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' )
        )
    ),
) );


