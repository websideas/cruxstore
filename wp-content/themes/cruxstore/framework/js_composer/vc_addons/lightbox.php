<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Icon_LightBox extends WPBakeryShortCode{
    protected function content($atts, $content = null) {

        $atts = shortcode_atts( array(
            'title' => '',
            'type' => 'image',
            'image' => '',
            'link' => '',
            'effect' => '',
            'max_width' => 650,

            'icon_type' => 'icon',
            'iconbox_icon' => 'fa fa-adjust',
            'iconbox_image' => '',

            'color' => '',
            'custom_color' => '',
            'size' => 'md',

            'align' => 'center',

            'el_class' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'css' => '',
        ), $atts );
        extract($atts);
        
        
        $output = $custom_css = '';
        $uniqid = uniqid();
        
        
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lightbox-content', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'align' => 'text-'.$align
        );

        
        $lightbox_icon = '';

        if($icon_type == 'image'){
            $img_lightbox_id = preg_replace( '/[^\d]/', '', $iconbox_image );
            $img_lightbox = wp_get_attachment_image_src( $img_lightbox_id, 'full' );

            if(array($img_lightbox)){
                $lightbox_icon = sprintf('<img src="%s" class="img-responsive" alt="" />', $img_lightbox['0']);
            }
        }else{

            $lightbox_class = array('vc_lightbox size-'.$size);
            if($color != 'custom'){
                $lightbox_class[] = 'color-'.$color;
                $custom_color = cruxstore_color2Hex($color);
            }

            $lightbox_style = 'style="color: '.$custom_color.'"';
            $lightbox_icon = sprintf('<span class="%s %s" %s></span>', $iconbox_icon, implode( ' ' , $lightbox_class), $lightbox_style);

        }
        
        $link_attr = array( 
            'href' => '',
            'class' => 'lightbox-link lightbox-'.$type,
        );
        
        if($type == 'inline'){
            $link_attr['href'] = '#lightbox-content'.$uniqid;
            $output .= sprintf(
                '<div id="%s" class="%s">%s</div>', 
                'lightbox-content'.$uniqid,
                'mfp-hide lightbox-popup-block mfp-with-anim',
                $content
            );
        }elseif($type == 'iframe'){
            $link_attr['href'] = $link;
        }else{
            $img_lightbox_id = preg_replace( '/[^\d]/', '', $image );
            $img_lightbox = wp_get_attachment_image_src( $img_lightbox_id, 'full' );
            if(array($img_lightbox)){
                $link_attr['href'] = $img_lightbox['0'];
            }else{
                $link_attr['href'] = vc_asset_url( 'vc/no_image.png' );
            }
        }

        $output .= sprintf(
            '<a href="%s" class="%s" data-type="%s" data-effect="%s" data-width="%s">%s</a>', 
            $link_attr['href'],
            $link_attr['class'],
            $type,
            $effect,
            $max_width,
            $lightbox_icon
        );

        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        
        return '<div id="lightbox-'.$uniqid.'" class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.$custom_css.'</div>';


    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Icon Lightbox", 'cruxstore'),
    "base" => "icon_lightbox",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Lightbox Type', 'cruxstore' ),
            'param_name' => 'type',
            'value' => array(
                esc_html__( 'Image', 'cruxstore' ) => 'image',
                esc_html__( 'Iframe - Video', 'cruxstore' ) => 'iframe',
                esc_html__( 'Inline', 'cruxstore' ) => 'inline',
            ),
            'description' => esc_html__( 'Select type of lightbox.', 'cruxstore' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'cruxstore' ),
            'param_name' => 'image',
            'dependency' => array("element" => "type","value" => array('image')),
            'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
        ),
        
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Link', 'js_composer' ),
            'param_name' => 'link',
            "admin_label" => true,
            'dependency' => array("element" => "type","value" => array('iframe')),
            'description' => esc_html__( 'Enter your link in here.', 'js_composer' ),
        ),
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'cruxstore'),
            "param_name" => "content",
            "value" => '',
            'dependency' => array("element" => "type","value" => array('inline')),
        ),
        
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Lightbox Effect', 'cruxstore' ),
            'param_name' => 'effect',
            'value' => array(
                esc_html__( 'Default', 'cruxstore' ) => '',
                esc_html__( 'Zoom In', 'cruxstore' ) => 'mfp-zoom-in',
                esc_html__( 'Newspaper', 'cruxstore' ) => 'mfp-newspaper',
                esc_html__( 'Move Horizontal', 'cruxstore' ) => 'mfp-move-horizontal',
                esc_html__( 'Move from top', 'cruxstore' ) => 'mfp-move-from-top',
                esc_html__( '3d unfold', 'cruxstore' ) => 'mfp-3d-unfold',
                esc_html__( 'Zoom out', 'cruxstore' ) => 'mfp-zoom-out',
            ),
            'description' => esc_html__( 'Select effect of lightbox.', 'cruxstore' ),
            "admin_label" => true,
        ),
        
        array(
            "type" => "cruxstore_number",
            "heading" => esc_html__("Max width", 'cruxstore'),
            "param_name" => "max_width",
            "value" => 650,
            "suffix" => esc_html__("px", 'cruxstore'),
            'description' => esc_html__( 'Select max-width for lightbox.', 'cruxstore' ),
            'dependency' => array("element" => "type","value" => array('inline', 'iframe')),
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
            "admin_label" => true,
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
        //Icon settings
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon to display', 'cruxstore' ),
            'param_name' => 'icon_type',
            'value' => array(
                esc_html__( 'Font Icon', 'cruxstore' ) => 'icon',
                esc_html__( 'Image Icon', 'cruxstore' ) => 'image',
            ),
            'description' => esc_html__( 'Select your layout.', 'cruxstore' ),
            'group' => esc_html__( 'Icon', 'cruxstore' )
        ),

        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image Thumbnail', 'cruxstore' ),
            'param_name' => 'iconbox_image',
            'dependency' => array( 'element' => 'icon_type',  'value' => array( 'image' ) ),
            'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
            'group' => esc_html__( 'Icon', 'cruxstore' )
        ),

        array(
            "type" => "cruxstore_icons",
            'heading' => esc_html__( 'Choose your icon', 'js_composer' ),
            'param_name' => 'iconbox_icon',
            "value" => 'fa fa-adjust',
            'description' => esc_html__( 'Use existing font icon or upload a custom image.', 'cruxstore' ),
            'dependency' => array("element" => "icon_type","value" => array('icon')),
            'group' => esc_html__( 'Icon', 'cruxstore' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Icon color', 'js_composer' ),
            'param_name' => 'color',
            'value' => array_merge( array( esc_html__( 'Default', 'js_composer' ) => 'default' ), getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'js_composer' ) => 'custom' ) ),
            'description' => esc_html__( 'Select icon color.', 'js_composer' ),
            'param_holder_class' => 'vc_colored-dropdown',
            'group' => esc_html__( 'Icon', 'cruxstore' ),
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
            'group' => esc_html__( 'Icon', 'cruxstore' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Size', 'js_composer' ),
            'param_name' => 'size',
            'value' => array_merge( getVcShared( 'sizes' ), array( 'Extra Large' => 'xl' ) ),
            'std' => 'md',
            'description' => esc_html__( 'Icon size.', 'js_composer' ),
            'group' => esc_html__( 'Icon', 'cruxstore' )
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