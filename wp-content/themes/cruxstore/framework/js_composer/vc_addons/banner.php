<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );
class WPBakeryShortCode_Banner extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts(array(
            'title' => '',
            'image' => '',
            'link' => '',
            'link_text' => '',
            'link_color' => 'default',
            'img_size' => 'full',
            'align' => 'center',
            'skin' => '',
            'overlay' => '',
            'position' => 'middle',
            'style' => 1,
            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',
            'font_size' => '',
            'line_height' => '',
            'letter_spacing' => '',
            'font_option' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts);

        
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

        if ( count ( $styles ) ) {
            $style_banner = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        } else {
            $style_banner = '';
        }


        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'banner ', $this->settings['base'], $atts ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'extra' => $this->getExtraClass( $el_class ),
            'align' => 'banner-'.$align,
            'position' => 'position-'.$position,
            'style' => 'style-'.$style
        );
        if($overlay){
            $elementClass['overlay'] = 'banner-'.$overlay;
        }
        if($skin){
            $elementClass['skin'] = 'skin-'.$skin;
        }

        $rand = 'banner_id_'.rand();
        $custom_css = $banner_link = '';

        $img_id = preg_replace( '/[^\d]/', '', $image );
        $img = wpb_getImageBySize( array(
            'attach_id' => $img_id,
            'thumb_size' => $img_size,
            'class' => 'img-responsive',
        ) );
        if ( $img == null ) {
            $img['thumbnail'] = '<img class="vc_img-placeholder img-responsive" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
        }

        if($title){
            $title = sprintf('<%1$s class="banner-title" %2$s><span>%3$s</span></%1$s>', $font_container_data['values']['tag'], $style_banner, $title);

            if($font_size){
                $custom_css .= cruxstore_responsive_render( '#'.$rand.' .banner-title', 'font-size',  $font_size);
            }

            if($line_height){
                $custom_css .= cruxstore_responsive_render( '#'.$rand.' .banner-title', 'line-height',  $line_height);
            }
        }

        if($content){
            $content = sprintf('<div class="banner-inner">%s</div>', $content);
        }

        $icon = ($style == 1) ? 'fa fa-caret-right' : 'fa fa-long-arrow-right';
        $more = ($link_text) ? sprintf('<div class="banner-more banner-more-%s"><span>%s <i class="%s" aria-hidden="true"></i></span></div>', $link_color, $link_text, $icon) : '';

        $output = $img['thumbnail'];
        $output .= sprintf('<div class="banner-content">%s</div>', $title.$content.$more);


        if($link){
            $link = vc_build_link( $link );
            $a_href = $link['url'];
            $a_title = $link['title'];
            $a_target = $link['target'];
            $icon_box_link = array('href="'.esc_attr( $a_href ).'"', 'title="'.esc_attr( $a_title ).'"', 'target="'.esc_attr( $a_target ).'"' );
            $banner_link = '<a class="banner-link" '.implode(' ', $icon_box_link).'></a>';
        }

        $output .= $banner_link;
        
        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        if($custom_css){
            $custom_css = '<div class="cruxstore_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }
        
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        $elementClass = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $elementClass, $this->settings['base'], $atts );
                                
        return '<div id="'.$rand.'" class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.$custom_css.'</div>';

    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Banner", 'cruxstore'),
    "base" => "banner",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => esc_html__( "", 'cruxstore'),

    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            "admin_label" => true,
        ),
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'cruxstore'),
            "param_name" => "content",
            "description" => esc_html__("", 'cruxstore'),
            'holder' => 'div',
        ),
        array(
            'type' => 'vc_link',
            'heading' => esc_html__( 'Link Url', 'js_composer' ),
            'param_name' => 'link',
        ),
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Text Link', 'js_composer' ),
            'param_name' => 'link_text',
            'description' => esc_html__( 'Enter text for link button', 'cruxstore' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Text Link Color', 'js_composer' ),
            'param_name' => 'link_color',
            'value' => array_merge(  array( esc_html__( 'Default', 'js_composer' ) => 'default' ), array( esc_html__( 'Accent color', 'cruxstore' ) => 'accent' ), getVcShared( 'colors' ) ),
            'std' => 'default',
            'param_holder_class' => 'vc_colored-dropdown',
            'description' => esc_html__( 'Choose color for link button', 'cruxstore' ),
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'cruxstore' ),
            'param_name' => 'image',
            'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Image size', 'js_composer' ),
            'param_name' => 'img_size',
            'value' => 'full',
            'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
        ),
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Layout setting", 'cruxstore'),
            "param_name" => "layout_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Image overlay', 'cruxstore' ),
            'param_name' => 'overlay',
            'value' => array(
                esc_html__( 'Default', 'cruxstore' ) => '',
                esc_html__( 'Dark (0%)', 'cruxstore' ) => 'dark',
                esc_html__( 'Dark (20%)', 'cruxstore' ) => 'dark1',
                esc_html__( 'Dark (40%)', 'cruxstore' ) => 'dark2',
                esc_html__( 'Darker (60%)', 'cruxstore' ) => 'dark3',
            ),
            'description' => esc_html__( 'Select image overlay for banner.', 'js_composer' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Skin banner content', 'cruxstore' ),
            'param_name' => 'skin',
            'value' => array(
                esc_html__( 'Default', 'cruxstore' ) => '',
                esc_html__( 'Light', 'cruxstore' ) => 'light'
            ),
            'description' => esc_html__( 'Select your skin for banner.', 'cruxstore' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Content Alignment', 'js_composer' ),
            'param_name' => 'align',
            'value' => array(
                esc_html__( 'Center', 'js_composer' ) => 'center',
                esc_html__( 'Left', 'js_composer' ) => 'left',
                esc_html__( 'Right', 'js_composer' ) => "right"
            ),
            'std' => 'center',
            'description' => esc_html__( 'Select content alignment within banner.', 'js_composer' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Content Position', 'js_composer' ),
            'param_name' => 'position',
            'value' => array(
                esc_html__( 'Top', 'js_composer' ) => 'top',
                esc_html__( 'Middle', 'js_composer' ) => 'middle',
                esc_html__( 'Middle Half', 'js_composer' ) => 'middle_half',
                esc_html__( 'Bottom', 'js_composer' ) => "bottom",
                esc_html__( 'Modern 1', 'js_composer' ) => "modern",
                esc_html__( 'Modern 2', 'js_composer' ) => "modern2",
            ),
            'std' => 'middle',
            'description' => esc_html__( 'Select content position within banner.', 'js_composer' ),
            "admin_label" => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Style', 'cruxstore' ),
            'param_name' => 'style',
            'value' => array(
                esc_html__( 'Style 1', 'cruxstore' ) => '1',
                esc_html__( 'Style 2', 'cruxstore' ) => '2',
            ),
            'std' => 1,
            'description' => esc_html__( 'Select your style.', 'cruxstore' ),
        ),
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Extra setting", 'cruxstore'),
            "param_name" => "extra_settings",
        ),
        cruxstore_map_add_css_animation(),
        cruxstore_map_add_css_animation_delay(),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        // Typography setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Typography heading", 'cruxstore'),
            "param_name" => "typography_heading",
            'group' => esc_html__( 'Typography', 'cruxstore' ),
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
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
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
            'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' )
        )
    ),
));