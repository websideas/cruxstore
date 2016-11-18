<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_CruxStore_Icon extends WPBakeryShortCode {

    protected function getFileName() {
        return 'vc_raw_html';
    }

    protected function content($atts, $content = null) {

        $atts = shortcode_atts( array(
            'hover_div' => '',
            'link' => '',
            'type' => 'icon',
            'image' => '',
            'svg' => '',
            'icon' => 'fa fa-adjust',
            'size' => 'md',
            'color' => '',
            'custom_color' => '',
            'color_hover' => '',
            'custom_color_hover' => '',
            'background_style' => 'default',
            'background_color' => 'grey',
            'custom_background_color' => '',
            'background_color_hover' => '',
            'custom_background_color_hover' => '',
            'align' => 'center',
            'el_class' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $custom_css = $output = $icon_style = '';
        $uniqid = 'icon-box-'.uniqid();

        if(!$hover_div){
            $hover_div = '#'.$uniqid.' .iconbox-ct:hover';
        }

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'iconbox', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'align' => 'text-'.$align,
        );
        $icon_class = array();


        if($type == 'image'){
            $img_id = preg_replace( '/[^\d]/', '', $image );
            $img_url = wp_get_attachment_image_src( $img_id, 'full' );
            if(array($img_url)){
                $icon = sprintf('<img src="%s" class="img-responsive" alt="" />', $img_url['0']);
            }
            $output = sprintf('<span class="%s %s" %s>%s</span>','icon-image', implode( ' ' , $icon_class), $icon_style, $icon);

        }else{
            if( ($type == 'svg' && !$svg ) || ($type == 'icon' && !$icon ) ){
                return;
            }

            $icon_class = array(
                'iconbox-ct',
                'size-'.$size,
                'style-'.$background_style
            );

            if($background_style != '' && $background_style != 'default'){
                $icon_class[] = 'style-special';
            }

            if($background_color != '' && $background_color != 'custom'){
                $icon_class[] = 'background-'.$background_color;
                $custom_background_color = cruxstore_color2Hex($background_color);
            }
            if($custom_background_color && $background_style != 'default'){
                if (strpos($background_style, 'outline') !== false) {
                    $icon_style .= 'border-color: '.$custom_background_color.';';
                }else{
                    $icon_style .= 'background: '.$custom_background_color.';';
                }
            }
            if( $background_color_hover != ''){
                $custom_background_color_hover = cruxstore_color2Hex($background_color_hover);
            }

            if($custom_background_color_hover && $background_style != 'default'){
                if (strpos($background_style, 'outline') !== false) {
                    $custom_css .= $hover_div.' {border-color:'.$custom_background_color_hover.'!important;}';
                }else{
                    $custom_css .= $hover_div.' {background:'.$custom_background_color_hover.'!important;}';
                }
            }

            if($color != '' && $color != 'custom'){
                $icon_class[] = 'color-'.$color;
                $custom_color = cruxstore_color2Hex($color);
            }
            if($custom_color){
                $icon_style .= 'color: '.$custom_color.';';
                if($type == 'svg'){
                    $custom_css .= '#'.$uniqid.' path{fill:'.$custom_color.'!important;}';
                }
            }

            if( $color_hover != ''){
                $custom_color_hover = cruxstore_color2Hex($color_hover);
            }

            if($custom_color_hover){
                if($type == 'svg'){
                    $custom_css .= $hover_div.' path{fill:'.$custom_color_hover.'!important;}';
                }else{
                    $custom_css .= $hover_div.' {color:'.$custom_color_hover.'!important;}';
                }
            }

            if($icon_style){
                $icon_style = sprintf('style="%s"', $icon_style);
            }

            if($type == 'svg'){
                $svg = '<span class="svg-content">'.rawurldecode( base64_decode( strip_tags( $svg ) ) ).'</span>';
                $icon = 'icon-svg';
            }else{
                $svg = '';
            }

            $output = sprintf('<span class="%s %s" %s>%s</span>', $icon, implode( ' ' , $icon_class), $icon_style, $svg);

        }


        $url = vc_build_link( $link );
        $rel = '';
        if ( ! empty( $url['rel'] ) ) {
            $rel = ' rel="' . esc_attr( $url['rel'] ) . '"';
        }

        if ( strlen( $link ) > 0 && strlen( $url['url'] ) > 0 ) {
            $output = '<a class="iconbox-link" href="' . esc_attr( $url['url'] ) . '" ' . $rel . ' title="' . esc_attr( $url['title'] )
                . '" target="' . ( strlen( $url['target'] ) > 0 ? esc_attr( $url['target'] ) : '_self' ) . '">'.$output.'</a>';
        }

        if($custom_css){
            $custom_css = '<div class="cruxstore_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
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
    "name" => esc_html__( "KT: Icon", 'cruxstore'),
    "base" => "cruxstore_icon",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => esc_html__( "", 'cruxstore'),
    "wrapper_class" => "clearfix",
    "params" => array_merge(
        array(
            array(
                'type' => 'hidden',
                'param_name' => 'hover_div'
            ),
            array(
                'type' => 'vc_link',
                'heading' => esc_html__( 'Link Url', 'js_composer' ),
                'param_name' => 'link',
            ),
        ),
        CruxStore_Icon_map(),
        array(
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
        )
    )
));
