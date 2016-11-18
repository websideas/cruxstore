<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Counter extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => __( 'Title', 'js_composer' ),
            'layout' => '1',
            'from' => '0',
            'to' => '100',
            'speed' => '2000',
            'prefix' => '',
            'suffix' => '',
            'skin' => '',

            'use_theme_fonts' => 'yes',
            'font_container' => '',
            'google_fonts' => '',

            'use_theme_fonts_value' => 'yes',
            'font_container_value' => '',
            'google_fonts_value' => '',

            'icon_hover_div' => '',
            'icon_type' => 'icon',
            'icon_image' => '',
            'icon_icon' => '',
            'icon_size' => 'md',
            'icon_color' => '',
            'icon_custom_color' => '',
            'icon_color_hover' => '',
            'icon_custom_color_hover' => '',

            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'counter-wrapper ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'layout' => 'layout-'.$layout,
            'size' => 'size-'.$icon_size
        );

        if($skin){
            $elementClass['skin'] = 'skin-'.$skin;
        }

        $uniqid = 'counter-'.uniqid();

        $style_title = $output = '';

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
        $counter_text = '<div class="counter-text" '.$style_title.'>'.$title.'</div>';

        $style_value = '';
        $atts['font_container'] = $font_container_value;
        $atts['google_fonts'] = $google_fonts_value;
        $atts['use_theme_fonts'] = $use_theme_fonts_value;


        extract($this->getAttributes($atts));
        unset($font_container_data['values']['text_align']);


        extract($this->getStyles($el_class, $css, $google_fonts_data, $font_container_data, $atts));

        $settings = get_option('wpb_js_google_fonts_subsets');
        $subsets = '';
        if (is_array($settings) && !empty($settings)) {
            $subsets = '&subset=' . implode(',', $settings);
        }
        if (!empty($google_fonts_data) && isset($google_fonts_data['values']['font_family'])) {
            wp_enqueue_style('vc_google_fonts_' . vc_build_safe_css_class($google_fonts_data['values']['font_family']), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets);
        }

        if (!empty($styles)) {
            $style_value .= 'style="' . esc_attr(implode(';', $styles)) . '"';
        }



        $decimals = explode('.', $to);
        $decimals_html = '';
        if(count($decimals) > 1){
            $decimals_html = 'data-decimals="'.esc_attr(strlen($decimals[1])).'"';
        }
        $from_attr = ($from) ? 'data-from="'.$from.'"' : 'data-from="0"';

        $speed = ($speed) ? $speed : 1;

        $counter_content = '<'.$font_container_data['values']['tag'].' class="counter-content" '.$style_value.'>'.$prefix.'<span class="counter" '.$from_attr.' data-speed="'.intval($speed).'"  '.$decimals_html.' data-to="'.esc_attr($to).'">'.$from.'</span>'.$suffix.'</'.$font_container_data['values']['tag'].'>';

        if($layout == '4'){
            $algin = 'left';
        }else{
            $algin = 'center';
        }

        $counter_icon = do_shortcode('[cruxstore_icon hover_div="#'.$uniqid.':hover .iconbox-ct" type = "'.$icon_type.'" image = "'.$icon_image.'" icon = "'.$icon_icon.'" size = "'.$icon_size.'" color = "'.$icon_color.'" custom_color = "'.$icon_custom_color.'" color_hover = "'.$icon_color_hover.'" custom_color_hover = "'.$icon_custom_color_hover.'" align="'.$algin.'"]');

        if($layout == '3'){
            $output = $counter_content.$counter_icon.$counter_text;
        }else{
            $output = $counter_icon.$counter_content.$counter_text;
        }





        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Counter", 'cruxstore'),
    "base" => "counter",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array_merge(
        array(
            array(
                "type" => "textfield",
                'heading' => esc_html__( 'Title', 'js_composer' ),
                'param_name' => 'title',
                'value' => esc_html__( 'Title', 'js_composer' ),
                "admin_label" => true,
                'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
            ),
            //Layout settings
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Layout icon box', 'cruxstore' ),
                'param_name' => 'layout',
                'value' => array(
                    esc_html__( 'Icon on Top of Number', 'cruxstore' ) => '1',
                    esc_html__( 'Icon on Top of Number + Divider', 'cruxstore' ) => '2',
                    esc_html__( 'Icon on bettween of Number and Title', 'cruxstore' ) => '3',
                    esc_html__( 'Icon beside Number', 'cruxstore' ) => '4',
                ),
                'std' => 1,
                'description' => esc_html__( 'Select your layout.', 'cruxstore' ),
                "admin_label" => true,
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__("Counter from", "js_composer"),
                "param_name" => "from",
                "value" => 0,
                "description" => esc_html__( "The number to start counting from. Enter number for counter without any special character. You may enter a decimal number. Eg 10.17", 'cruxstore' ),
            ),

            array(
                "type" => "textfield",
                "heading" => __("Counter to", "js_composer"),
                "param_name" => "to",
                "admin_label" => true,
                "value" => 100,
                "description" => esc_html__( "The number to stop counting at. Enter number for counter without any special character. You may enter a decimal number. Eg 10.17", 'cruxstore' ),
            ),

            array(
                "type" => "textfield",
                'heading' => esc_html__( 'Counter Value prefix', 'cruxstore' ),
                'param_name' => 'prefix',
                "description" => esc_html__( "Enter prefix for counter value" , 'cruxstore'),
            ),
            array(
                "type" => "textfield",
                'heading' => esc_html__( 'Counter Value suffix', 'cruxstore' ),
                'param_name' => 'suffix',
                "description" => esc_html__( "Enter suffix for counter value" , 'cruxstore'),
            ),
            array(
                "type" => "cruxstore_number",
                "heading" => esc_html__("Speed", 'cruxstore'),
                "param_name" => "speed",
                "value" => "2000",
                "suffix" => esc_html__("milliseconds", 'cruxstore'),
                "description" => esc_html__( "The number of milliseconds it should take to finish counting", 'cruxstore' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Skin counter', 'cruxstore' ),
                'param_name' => 'skin',
                'value' => array(
                    esc_html__( 'Default', 'cruxstore' ) => '',
                    esc_html__( 'Light', 'cruxstore' ) => 'light'
                ),
                'description' => esc_html__( 'Select your skin.', 'cruxstore' ),
            ),
            array(
                "type" => "textfield",
                "heading" => esc_html__( "Extra class name", "js_composer" ),
                "param_name" => "el_class",
                "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
            ),
            array(
                'type' => 'hidden',
                'param_name' => 'link',
            ),

        ),
        CruxStore_Icon_map(true, 'icon_', '', true),
        array(
            //Typography settings
            array(
                "type" => "cruxstore_heading",
                "heading" => __("Title typography", 'cruxstore'),
                "param_name" => "title_typography",
                'group' => __( 'Typography', 'cruxstore' )
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
            array(
                "type" => "cruxstore_heading",
                "heading" => __("Value typography", 'cruxstore'),
                "param_name" => "value_typography",
                'group' => __( 'Typography', 'cruxstore' )
            ),
            array(
                'type' => 'font_container',
                'param_name' => 'font_container_value',
                'value' => '',
                'settings' => array(
                    'fields' => array(
                        'tag' => 'h3', // default value h4
                        'font_size',
                        'line_height',
                        'color',
                        'tag_description' => __( 'Select element tag.', 'js_composer' ),
                        'text_align_description' => __( 'Select text alignment.', 'js_composer' ),
                        'font_size_description' => __( 'Enter font size.', 'js_composer' ),
                        'line_height_description' => __( 'Enter line height.', 'js_composer' ),
                        'color_description' => __( 'Select heading color.', 'js_composer' ),
                    ),
                ),
                'group' => __( 'Typography', 'cruxstore' )
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Use theme default font family?', 'js_composer' ),
                'param_name' => 'use_theme_fonts_value',
                'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
                'description' => __( 'Use font family from the theme.', 'js_composer' ),
                'group' => __( 'Typography', 'cruxstore' ),
                'std' => 'yes'
            ),
            array(
                'type' => 'google_fonts',
                'param_name' => 'google_fonts_value',
                'value' => 'font_family:Montserrat|font_style:400%20regular%3A400%3Anormal',
                'settings' => array(
                    'fields' => array(
                        'font_family_description' => __( 'Select font family.', 'js_composer' ),
                        'font_style_description' => __( 'Select font styling.', 'js_composer' )
                    )
                ),
                'group' => __( 'Typography', 'cruxstore' ),
                'dependency' => array(
                    'element' => 'use_theme_fonts_value',
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

        )


    ),
));