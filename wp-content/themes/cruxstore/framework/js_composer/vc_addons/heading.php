<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_CruxStore_Heading extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => esc_html__( 'Title', 'js_composer' ),
            'title_style' => '',
            'line_color' => '#333333',
            'border_width' => '1',
            'line_width' => '',
            'line_style' => '',
            'size' => 'md',
            'color' => 'grey',
            'custom_color' => '',
            'icon' => '',
            'image' => '',
            'spacer_position' => 'middle',
            'spacer' => '',
            'align' => 'center',
            'skin' => '',

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

        if ( count( $styles ) ) {
            $style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        } else {
            $style = '';
        }

        $output = '';

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'cruxstore-heading ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'align' => 'text-'.$align,
        );

        if($skin){
            $elementClass['skin'] = 'skin-'.$skin;
        }

        $custom_css = '';
        $rand = 'cruxstore_heading_'.rand();

        if($font_size){
            $custom_css .= cruxstore_responsive_render( '#'.$rand.' .cruxstore-heading-title', 'font-size',  $font_size);
        }

        if($line_height){
            $custom_css .= cruxstore_responsive_render( '#'.$rand.' .cruxstore-heading-title', 'line-height',  $line_height);
        }

        if($title_style == 'word'){
            $title_arr = preg_split("/\s+/", $title);
            $title_arr[0] = "<span class='first'>" . $title_arr[0] . "</span>";
            $title = join(" ", $title_arr);
        }elseif($title_style == 'border'){
            $title = sprintf("<span class='tborder'>%s</span>", $title);
        }
        
        $title = sprintf('<%1$s class="cruxstore-heading-title" %2$s>%3$s</%1$s>', $font_container_data['values']['tag'], $style, $title );
        $content = sprintf('<div class="cruxstore-heading-content">%s</div>', $content);

        $divider = '';

        if($spacer){
            $elementClass['spacer'] = 'cruxstore-heading-'.$spacer_position;
            $divider_content = '';
            $divider_class = array(
                'cruxstore-heading-spacer',
                'cruxstore-heading-'.$spacer
            );
            $styles_divider = array();
            if(!$line_style) $line_style = 'solid';
            if(!$border_width) $border_width = 1;
            if($spacer == 'line'){
                $styles_divider[] = 'border-color: '.$line_color;
                $styles_divider[] = 'border-top-width: '.intval($border_width).'px';
                $styles_divider[] = 'border-top-style: '.$line_style;
                if($line_width){
                    $styles_diver[] = 'width: '.intval($line_width).'px';
                }
            }elseif($spacer == 'icon'){
                if($icon){
                    $icon_style = '';
                    $icon_class = array(
                        'iconbox-ct',
                        'size-'.$size
                    );
                    if($color != '' && $color != 'custom'){
                        $icon_class[] = 'color-'.$color;
                        $custom_color = cruxstore_color2Hex($color);
                    }
                    if($custom_color){
                        $icon_style .= 'color: '.$custom_color.';';
                    }
                    if($icon_style){
                        $icon_style = sprintf('style="%s"', $icon_style);
                    }
                    $divider_content = sprintf('<span class="%s %s" %s></span>', $icon, implode( ' ' , $icon_class), $icon_style);
                }
            }elseif($spacer == 'image'){
                $img_id = preg_replace( '/[^\d]/', '', $image );
                $img_url = wp_get_attachment_image_src( $img_id, 'full' );
                if(array($img_url)){
                    $icon = sprintf('<img src="%s" class="img-responsive" alt="" />', $img_url['0']);
                }
                $divider_content = sprintf('<span class="%s">%s</span>', 'icon-image', $icon);
            }
            $styles_divider = 'style="' . esc_attr( implode( ';', $styles_divider ) ) . '"';
            $divider = '<div class="'.implode( ' ', $divider_class ).'" '.$styles_divider.'>'.$divider_content.'</div>';
        }

        if($custom_css){
            $custom_css = '<div class="cruxstore_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }

        if($spacer_position == 'bottom'){
            $output .= $title.$content.$divider;
        }elseif($spacer_position == 'top'){
            $output .= $divider.$title.$content;
        }else{
            $output .= $title.$divider.$content;
        }

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
    'name' => esc_html__( 'KT: Heading', 'cruxstore' ),
    'base' => 'cruxstore_heading',
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
            'heading' => esc_html__( 'Title style', 'js_composer' ),
            'param_name' => 'title_style',
            'value' => array(
                esc_html__( 'Default', 'js_composer' ) => "",
                esc_html__( 'Special first word', 'js_composer' ) => 'word',
                esc_html__( 'Border word', 'js_composer' ) => 'border',
            ),
            'description' => esc_html__( 'Select style effect for title.', 'js_composer' )
        ),
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'cruxstore'),
            "param_name" => "content",
            "value" => '',
            "holder" => "div",
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

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Skin', 'cruxstore' ),
            'param_name' => 'skin',
            'value' => array(
                esc_html__( 'Default', 'cruxstore' ) => '',
                esc_html__( 'Light', 'cruxstore' ) => 'light'
            ),
            'description' => esc_html__( 'Select your skin for heading.', 'cruxstore' ),
        ),
        // Seperator setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Seperator settings", 'cruxstore'),
            "param_name" => "spacer_settings",
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __("Seperator", "cruxstore"),
            "param_name" => "spacer",
            "value" => array(
                __("No Seperator","cruxstore")	=>	"",
                __("Line","cruxstore")			=>	"line",
                __("Icon","cruxstore")			=>	"icon",
                __("Image","cruxstore") 			=> "image"
            ),
            "description" => __("Horizontal line, icon or image to divide sections", "cruxstore"),
            "admin_label" => true,
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => __("Seperator Position", "ultimate_vc"),
            "param_name" => "spacer_position",
            "value" => array(
                __("Between Heading & Content","cruxstore")	=>	"middle",
                __("Top","cruxstore")		=>	"top",
                __("Bottom","cruxstore")	=>	"bottom"
            ),
            "dependency" => Array("element" => "spacer", "value" => array("line","icon","image")),
            'description' => esc_html__( 'Select seperator position.', 'js_composer' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image Thumbnail', 'cruxstore' ),
            'param_name' => 'image',
            "dependency" => Array("element" => "spacer", "value" => array("image")),
            'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
        ),
        array(
            "type" => "cruxstore_icons",
            'heading' => esc_html__( 'Choose your icon', 'js_composer' ),
            'param_name' => 'icon',
            "value" => '',
            'description' => esc_html__( 'Use existing font icon or upload a custom image.', 'cruxstore' ),
            'dependency' => array("element" => "spacer","value" => array('icon')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon color', 'js_composer' ),
            'param_name' => 'color',
            'value' => array_merge( array( esc_html__( 'Default', 'js_composer' ) => 'default' ), array( esc_html__( 'Accent color', 'cruxstore' ) => 'accent' ),  getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => esc_html__( 'Select icon color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'dependency' => array("element" => "spacer","value" => array('icon')),
            "admin_label" => true,
            'std' => 'grey',
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
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Size', 'js_composer' ),
            'param_name' => 'size',
            'value' => array_merge( getVcShared( 'sizes' ), array( 'Extra Large' => 'xl' ) ),
            'std' => 'md',
            'description' => esc_html__( 'Icon size.', 'js_composer' ),
            "admin_label" => true,
            'dependency' => array("element" => "spacer","value" => array('icon')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Line Style', 'js_composer' ),
            "param_name" => "line_style",
            'value' => getVcShared( 'separator styles' ),
            'std' => '',
            'description' => __( 'Separator display style.', 'js_composer' ),
            "dependency" => Array("element" => "spacer", "value" => array("line")),
        ),
        array(
            "type" => "cruxstore_number",
            "class" => "",
            "heading" => __("Line Width (optional)", "cruxstore"),
            "param_name" => "line_width",
            "suffix" => "px",
            "dependency" => Array("element" => "spacer", "value" => array("line")),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Border width', 'js_composer' ),
            'param_name' => 'border_width',
            'value' => getVcShared( 'separator border widths' ),
            'description' => __( 'Select border width (pixels).', 'js_composer' ),
            "dependency" => Array("element" => "spacer", "value" => array("line")),
        ),
        array(
            "type" => "colorpicker",
            "class" => "",
            "heading" => __("Line Color", "cruxstore"),
            "param_name" => "line_color",
            "value" => "#333333",
            "dependency" => Array("element" => "spacer", "value" => array("line")),
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
                __( 'Uppercase', 'cruxstore' ) => 'uppercase',
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


