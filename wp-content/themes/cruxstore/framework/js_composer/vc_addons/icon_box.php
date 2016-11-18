<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );
class WPBakeryShortCode_Icon_Box extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts( array(
            'title' => esc_html__( 'Title', 'js_composer' ),
            'link' => '',
            'skin' => '',
            'readmore' => '',

            'use_theme_fonts' => 'true',
            'font_container' => '',
            'google_fonts' => '',
            'letter_spacing' => '0',

            'icon_hover_div' => '',
            'icon_type' => 'icon',
            'icon_image' => '',
            'icon_icon' => 'fa fa-adjust',
            'icon_size' => 'md',
            'icon_color' => '',
            'icon_custom_color' => '',
            'icon_color_hover' => '',
            'icon_custom_color_hover' => '',
            'icon_background_style' => 'default',
            'icon_background_color' => 'grey',
            'icon_custom_background_color' => '',
            'icon_background_color_hover' => '',
            'icon_custom_background_color_hover' => '',

            'icon_box_layout' => '1',
            'boxed_background_color' => 'grey',
            'boxed_border_color' => 'grey',
            'boxed_custom_background_color' => '',
            'boxed_custom_border_color' => '',

            'el_class' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $custom_css = $output = $style_title = '';
        $uniqid = 'features-box-'.uniqid();

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'feature-box', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'layout' => 'layout-'.$icon_box_layout,
            'size' => 'size-'.$icon_size
        );

        if($icon_background_style == 'default'){
            $elementClass['style'] = 'style-'.$icon_background_style;
        }

        if($skin){
            $elementClass['skin'] = 'skin-'.$skin;
        }

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
        if($letter_spacing){
            $styles[] = 'letter-spacing: '.$letter_spacing.'px;';
        }
        if ( ! empty( $styles ) ) {
            $style_title .= 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        }

        if($icon_box_layout == '2' || $icon_box_layout == '3' || $icon_box_layout == '4'){
            $icon_align = 'center';
        }else{
            $icon_align = 'left';
        }

        if($icon_box_layout == '3' || $icon_box_layout == '4' || $icon_box_layout == '6'){
            $elementClass['boxed'] = 'background-'.$boxed_background_color;
        }
        if($icon_box_layout == '4'){
            $elementClass['border'] = 'border-'.$boxed_border_color;
        }




        $icon_box_icon = do_shortcode('[cruxstore_icon hover_div="#'.$uniqid.':hover .iconbox-ct" link="'.$link.'" type = "'.$icon_type.'" image = "'.$icon_image.'" icon = "'.$icon_icon.'" size = "'.$icon_size.'" color = "'.$icon_color.'" custom_color = "'.$icon_custom_color.'" color_hover = "'.$icon_color_hover.'" custom_color_hover = "'.$icon_custom_color_hover.'" background_style = "'.$icon_background_style.'" background_color = "'.$icon_background_color.'" custom_background_color = "'.$icon_custom_background_color.'" background_color_hover = "'.$icon_background_color_hover.'" custom_background_color_hover = "'.$icon_custom_background_color_hover.'" align="'.$icon_align.'"]');

        if($icon_box_icon){
            $icon_box_icon = '<div class="feature-box-icon">'.$icon_box_icon.'</div>';
        }

        $icon_readmore = '';

        $link = ( $link == '||' ) ? '' : $link;

        if($link){
            $link = vc_build_link( $link );
            $a_href = $link['url'];
            $a_title = $link['title'];
            $a_target = $link['target'];
            $icon_box_link = array('href="'.esc_attr( $a_href ).'"', 'title="'.esc_attr( $a_title ).'"', 'target="'.esc_attr( $a_target ).'"' );

            if($title){
                $style_link = '';
                if(isset($font_container_data['values']['color'])){
                    $style_link .= ' style="color: '.$font_container_data['values']['color'].'"';
                }
                $title = '<a '.implode(' ', $icon_box_link).$style_link.'>'.$title.'</a>';
            }
            $icon_readmore = '<div class="feature-box-readmore"><a '.implode(' ', $icon_box_link).'>'.$readmore.'</a></div>';

        }


        $icon_box_title = ($title) ? '<' . $font_container_data['values']['tag'] . ' class="feature-box-title" '.$style_title.'>'.$title.'</' . $font_container_data['values']['tag'] . '>' : '';
        $icon_box_content = ($content) ? '<div class="feature-box-content">'.$content.'</div>' : '';

        $output .= $icon_box_icon . $icon_box_title . $icon_box_content . $icon_readmore;

        if($custom_css){
            $custom_css = '<div class="cruxstore_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }

        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div id="'.$uniqid.'" class="'.esc_attr( $elementClass ).'"'.$animation_delay.'><div class="feature-box-inner">'.$output.$custom_css.'</div></div>';


    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Icon Box", 'cruxstore'),
    "base" => "icon_box",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "wrapper_class" => "clearfix",
    "params" => array_merge(
        array(
            array(
                "type" => "textfield",
                'heading' => esc_html__( 'Title', 'js_composer' ),
                'param_name' => 'title',
                "admin_label" => true,
                'value' => esc_html__( 'Title', 'js_composer' ),
            ),

            array(
                'type' => 'vc_link',
                'heading' => esc_html__( 'Link Url', 'js_composer' ),
                'param_name' => 'link',
            ),
            array(
                "type" => "textfield",
                'heading' => esc_html__( 'Link Text', 'js_composer' ),
                'param_name' => 'readmore',
            ),


            array(
                "type" => "textarea_html",
                "heading" => esc_html__("Content", 'cruxstore'),
                "param_name" => "content",
                "value" => '',
                "description" => '',
                "holder" => "div",
            ),

            //Layout settings
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Layout icon box', 'cruxstore' ),
                'param_name' => 'icon_box_layout',
                'value' => array(
                    esc_html__( 'Icon on Top of Title', 'cruxstore' ) => '1',
                    esc_html__( 'Icon on Top of Title - Center', 'cruxstore' ) => '2',
                    esc_html__( 'Icon in left Content', 'cruxstore' ) => '5',
                    esc_html__( 'Boxed - Icon on Top of Title - Center', 'cruxstore' ) => '3',
                    esc_html__( 'Border Boxed - Icon on Top of Title - Center', 'cruxstore' ) => '4',
                    esc_html__( 'Boxed - Icon in left Content', 'cruxstore' ) => '6',
                ),
                'description' => esc_html__( 'Select your layout.', 'cruxstore' ),
                "admin_label" => true,
            ),

            array(
                'type' => 'dropdown',
                'heading' => __( 'Background color', 'js_composer' ),
                'param_name' => 'boxed_background_color',
                'value' => array_merge( array( esc_html__( 'Accent', 'cruxstore' ) => 'accent' ), getVcShared( 'colors' ) ),
                'std' => 'grey',
                'description' => __( 'Select background color for boxed.', 'js_composer' ),
                'param_holder_class' => 'vc_colored-dropdown',
                'dependency' => array(
                    'element' => 'icon_box_layout',
                    'value' => array('3', '4', '6'),
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Border color', 'js_composer' ),
                'param_name' => 'boxed_border_color',
                'value' => array_merge( array( esc_html__( 'Accent', 'cruxstore' ) => 'accent' ), getVcShared( 'colors' ) ),
                'std' => 'grey',
                'description' => __( 'Select border color for boxed.', 'js_composer' ),
                'param_holder_class' => 'vc_colored-dropdown',
                'dependency' => array(
                    'element' => 'icon_box_layout',
                    'value' => array('4'),
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Skin icon box', 'cruxstore' ),
                'param_name' => 'skin',
                'value' => array(
                    esc_html__( 'Default', 'cruxstore' ) => '',
                    esc_html__( 'Light', 'cruxstore' ) => 'light'
                ),
                'description' => esc_html__( 'Select your skin.', 'cruxstore' ),
            ),
            cruxstore_map_add_css_animation(),
            cruxstore_map_add_css_animation_delay(),
            array(
                "type" => "textfield",
                "heading" => esc_html__( "Extra class name", "js_composer" ),
                "param_name" => "el_class",
                "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
            ),
        ),
        CruxStore_Icon_map(true, 'icon_'),
        array(
            //Typography settings
            array(
                "type" => "cruxstore_number",
                "heading" => esc_html__("Letter spacing", 'cruxstore'),
                "param_name" => "letter_spacing",
                "value" => 0,
                "min" => 0,
                "max" => 10,
                "suffix" => "px",
                "description" => "",
                'group' => esc_html__( 'Typography', 'cruxstore' ),
            ),
            array(
                'type' => 'font_container',
                'param_name' => 'font_container',
                'value' => '',
                'settings' => array(
                    'fields' => array(
                        'tag' => 'h4', // default value h4
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
                'type' => 'css_editor',
                'heading' => esc_html__( 'CSS box', 'js_composer' ),
                'param_name' => 'css',
                // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
                'group' => esc_html__( 'Design Options', 'js_composer' )
            )
        )
    )
));