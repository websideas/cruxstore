<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Delphinus_Heading extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => esc_html__( 'Title', 'js_composer' ),
            'link' => '',
            'button_text' => '',
            'divider' => 'true',
            'font_size' => '',
            'line_height' => '',
            'letter_spacing' => '',

            'align' => 'center',
            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',
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

        if ( isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }

        if($letter_spacing){
            if ( empty( $styles ) ) {
                $styles = array();
            }
            $styles[] = 'letter-spacing: '.$letter_spacing.'px';
        }

        if ( ! empty( $styles ) ) {
            $style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        } else {
            $style = '';
        }

        $output = '';

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'delphinus-heading ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => delphinus_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'align' => 'text-'.$align,
        );

        $readmore_text = '';

        if ( ! empty( $link ) ) {
            if($button_text){
                $link = vc_build_link( $link );
                $readmore_text = '<a href="' . esc_attr( $link['url'] ) . '"'
                                . ( $link['target'] ? ' target="' . esc_attr( $link['target'] ) . '"' : '' )
                                . ( $link['title'] ? ' title="' . esc_attr( $link['title'] ) . '"' : '' )
                                . '>' . $button_text . '</a>';
            }
        }

        $custom_css = '';
        $rand = 'delphinus_heading_'.rand();

        if($font_size){
            $custom_css .= delphinus_responsive_render( '#'.$rand.' .delphinus-heading-title', 'font-size',  $font_size);
        }

        if($line_height){
            $custom_css .= delphinus_responsive_render( '#'.$rand.' .delphinus-heading-title', 'line-height',  $line_height);
        }

        $title = sprintf('<%1$s class="delphinus-heading-title" %2$s>%3$s</%1$s>', $font_container_data['values']['tag'], $style, $title );

        $content = '';
        if($readmore_text){
            $content = sprintf('<div class="delphinus-heading-content">%s</div>', $readmore_text);
        }

        $divider = apply_filters('sanitize_boolean', $divider);
        if($divider){
            $output .= '<div class="delphinus-heading-divider"><span>
                <svg xml:space="preserve" style="enable-background:new 349 274.7 1310.8 245.3;" viewBox="349 274.7 1310.8 245.3" y="0px" x="0px" version="1.1">
                <path d="M1222,438.9c-2.7,0-5.4,0-8.1-2.7l-210.8-129.7L792.3,436.2c-5.4,2.7-10.8,2.7-13.5,0L573.3,306.5L365.2,436.2L349,411.9
                    l216.2-132.4c5.4-2.7,10.8-2.7,13.5,0l208.1,127l210.8-129.7c5.4-2.7,10.8-2.7,13.5,0L1222,409.2l208.1-129.7
                    c5.4-2.7,10.8-2.7,13.5,0l216.2,135.1l-13.5,21.7l-208.1-129.7l-208.1,129.7C1227.4,436.2,1224.7,438.9,1222,438.9L1222,438.9z"/>
                    <path d="M1222,520c-2.7,0-5.4,0-8.1-2.7l-210.8-129.7L792.3,517.3c-5.4,2.7-10.8,2.7-13.5,0L573.3,387.6L362.5,517.3L349,493
                    l216.2-132.4c5.4-2.7,10.8-2.7,13.5,0l205.4,129.7L995,360.5c5.4-2.7,10.8-2.7,13.5,0l210.8,129.7l208.1-129.7
                    c5.4-2.7,10.8-2.7,13.5,0l216.2,135.1l-13.5,21.6l-205.4-129.7l-208.1,129.8C1227.4,517.3,1224.7,520,1222,520L1222,520z"/>
                </svg></span>
            </div>';
        }

        if($custom_css){
            $custom_css = '<div class="delphinus_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }

        $output .= $title.$content.$custom_css;
        
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
    'name' => esc_html__( 'KT: Heading', 'delphinus' ),
    'base' => 'delphinus_heading',
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    'params' => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            'value' => esc_html__( 'Title', 'js_composer' ),
            "admin_label" => true,
        ),


        array(
            'type' => 'vc_link',
            'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
            'description' => esc_html__( 'Add link to title.', 'js_composer' )
        ),

        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Button text', 'js_composer' ),
            'param_name' => 'button_text',
            "admin_label" => true,
            'value' => '',
        ),

        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Divider', 'delphinus' ),
            'param_name' => 'divider',
            'value' => 'true',
            "description" => esc_html__("Show divider before heading", 'delphinus'),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Alignment', 'js_composer' ),
            'param_name' => 'align',
            'value' => array(
                esc_html__( 'Center', 'js_composer' ) => 'center',
                esc_html__( 'Left', 'js_composer' ) => 'left',
                esc_html__( 'Right', 'js_composer' ) => "right"
            ),
            'description' => esc_html__( 'Select separator alignment.', 'js_composer' )
        ),
        delphinus_map_add_css_animation(),
        delphinus_map_add_css_animation_delay(),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Extra class name', 'js_composer' ),
            'param_name' => 'el_class',
            'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
        ),

        // Typography setting
        array(
            "type" => "delphinus_heading",
            "heading" => esc_html__("Typography heading", 'delphinus'),
            "param_name" => "typography_heading",
            'group' => esc_html__( 'Typography', 'delphinus' ),
        ),
        array(
            'type' => 'delphinus_responsive',
            'param_name' => 'font_size',
            'heading' => esc_html__( 'Font size', 'delphinus' ),
            'group' => esc_html__( 'Typography', 'delphinus' ),
            'unit' =>  esc_html__( 'px', 'delphinus' ),
            'description' => esc_html__( 'Use font size for the title.', 'delphinus' ),
        ),

        array(
            'type' => 'delphinus_responsive',
            'param_name' => 'line_height',
            'heading' => esc_html__( 'Line Height', 'delphinus' ),
            'group' => esc_html__( 'Typography', 'delphinus' ),
            'unit' =>  esc_html__( 'px', 'delphinus' ),
            'description' => esc_html__( 'Use line height for the title.', 'delphinus' ),
        ),
        array(
            "type" => "delphinus_number",
            "heading" => esc_html__("Letter spacing", 'delphinus'),
            "param_name" => "letter_spacing",
            "min" => 0,
            "suffix" => "px",
            'group' => esc_html__( 'Typography', 'delphinus' )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'tag' => 'h3',
                    'color',
                    //'font_size',
                    //'line_height',
                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => esc_html__( 'Typography', 'delphinus' ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'delphinus' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => 'font_family:Oswald|font_style:700%20regular%3A400%3Anormal',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
                    'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => esc_html__( 'Typography', 'delphinus' ),
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


