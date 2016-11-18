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

            'use_theme_fonts' => 'true',
            'font_container' => '',
            'google_fonts' => '',
            'letter_spacing' => '0',

            'icon_type' => 'icon',
            'iconbox_icon' => 'fa fa-adjust',
            'iconbox_image' => '',

            'color' => '',
            'custom_color' => '',
            'size' => 'md',

            'align' => 'center',
            'icon_box_layout' => '1',

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
            'css_animation' => delphinus_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'layout' => 'layout-'.$icon_box_layout
        );

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
        }


        $icon_box_title = ($title) ? '<' . $font_container_data['values']['tag'] . ' class="feature-box-title" '.$style_title.'>'.$title.'</' . $font_container_data['values']['tag'] . '>' : '';
        $icon_box_content = ($content) ? '<div class="feature-box-content">'.$content.'</div>' : '';


        $icon_box_icon = '';

        if($icon_type == 'image'){
            $img_lightbox_id = preg_replace( '/[^\d]/', '', $iconbox_image );
            $img_lightbox = wp_get_attachment_image_src( $img_lightbox_id, 'full' );

            if(array($img_lightbox)){
                $icon_box_icon = sprintf('<img src="%s" class="img-responsive" alt="" />', $img_lightbox['0']);
            }
        }else{

            $icon_box_class = array('vc_icon_box size-'.$size);
            if($color != 'custom'){
                $icon_box_class[] = 'color-'.$color;
                $custom_color = delphinus_color2Hex($color);
            }

            $icon_box_style = 'style="color: '.$custom_color.'"';
            $icon_box_icon = sprintf('<span class="%s %s" %s></span>', $iconbox_icon, implode( ' ' , $icon_box_class), $icon_box_style);

        }

        if($icon_box_icon){
            $icon_box_icon = '<div class="feature-box-icon">'.$icon_box_icon.'</div>';
        }

        $output .= $icon_box_icon . $icon_box_title . $icon_box_content;

        if($custom_css){
            $custom_css = '<div class="delphinus_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }

        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div id="'.$uniqid.'" class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.$custom_css.'</div>';


    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Icon Box", 'delphinus'),
    "base" => "icon_box",
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    "description" => esc_html__( "", 'delphinus'),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            "admin_label" => true,
        ),

        array(
            'type' => 'vc_link',
            'heading' => esc_html__( 'Link Url', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'delphinus'),
            "param_name" => "content",
            "value" => '',
            "description" => esc_html__("", 'delphinus'),
            "holder" => "div",
        ),

        //Layout settings
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Layout icon box', 'delphinus' ),
            'param_name' => 'icon_box_layout',
            'value' => array(
                esc_html__( 'Icon on Top of Title', 'delphinus' ) => '1',
                esc_html__( 'Icon on Top of Title - Center', 'delphinus' ) => '2'
            ),
            'description' => esc_html__( 'Select your layout.', 'delphinus' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Skin icon box', 'delphinus' ),
            'param_name' => 'skin',
            'value' => array(
                esc_html__( 'Default', 'delphinus' ) => '',
                esc_html__( 'Light', 'delphinus' ) => 'light'
            ),
            'description' => esc_html__( 'Select your skin.', 'delphinus' ),
            "admin_label" => true,
        ),
        delphinus_map_add_css_animation(),
        delphinus_map_add_css_animation_delay(),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        //Icon settings
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon to display', 'delphinus' ),
            'param_name' => 'icon_type',
            'value' => array(
                esc_html__( 'Font Icon', 'delphinus' ) => 'icon',
                esc_html__( 'Image Icon', 'delphinus' ) => 'image',
            ),
            'description' => esc_html__( 'Select your layout.', 'delphinus' ),
            'group' => esc_html__( 'Icon', 'delphinus' )
        ),

        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image Thumbnail', 'delphinus' ),
            'param_name' => 'iconbox_image',
            'dependency' => array( 'element' => 'icon_type',  'value' => array( 'image' ) ),
            'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
            'group' => esc_html__( 'Icon', 'delphinus' )
        ),

        array(
            "type" => "delphinus_icons",
            'heading' => esc_html__( 'Choose your icon', 'js_composer' ),
            'param_name' => 'iconbox_icon',
            "value" => 'fa fa-adjust',
            'description' => esc_html__( 'Use existing font icon or upload a custom image.', 'delphinus' ),
            'dependency' => array("element" => "icon_type","value" => array('icon')),
            'group' => esc_html__( 'Icon', 'delphinus' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon color', 'js_composer' ),
            'param_name' => 'color',
            'value' => array_merge( array( esc_html__( 'Default', 'js_composer' ) => 'default' ), getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => esc_html__( 'Select icon color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'group' => esc_html__( 'Icon', 'delphinus' ),
            'dependency' => array("element" => "icon_type","value" => array('icon')),
        ),
        array(
            'type' => 'colorpicker',
            'heading' => esc_html__( 'Custom Icon Color', 'js_composer' ),
            'param_name' => 'custom_color',
            'description' => esc_html__( 'Select custom icon color.', 'js_composer' ),
            'dependency' => array(
                'element' => 'color',
                'value' => 'custom',
            ),
            'group' => esc_html__( 'Icon', 'delphinus' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Size', 'js_composer' ),
            'param_name' => 'size',
            'value' => array_merge( getVcShared( 'sizes' ), array( 'Extra Large' => 'xl' ) ),
            'std' => 'md',
            'description' => esc_html__( 'Icon size.', 'js_composer' ),
            'group' => esc_html__( 'Icon', 'delphinus' )
        ),

        //Typography settings
        array(
            "type" => "delphinus_number",
            "heading" => esc_html__("Letter spacing", 'delphinus'),
            "param_name" => "letter_spacing",
            "value" => 0,
            "min" => 0,
            "max" => 10,
            "suffix" => "px",
            "description" => "",
            'group' => esc_html__( 'Typography', 'delphinus' ),
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
            'group' => esc_html__( 'Typography', 'delphinus' )
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
            'group' => esc_html__( 'Typography', 'delphinus' ),
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
));