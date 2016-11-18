<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Image_Tooltip extends WPBakeryShortCode {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts(array(
            'image' => '',
            'img_size' => 'thumbnail',
            'values' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts);
        
        extract( $atts );
        
        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'image-tooltip', $this->settings['base'], $atts ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
        );
        
        
        $img_id = preg_replace( '/[^\d]/', '', $image );
        $img = wpb_getImageBySize( array(
            'attach_id' => $img_id,
            'thumb_size' => $img_size,
            'class' => 'img-responsive',
        ) );
        if ( $img == null ) {
            $img['thumbnail'] = '<img class="vc_img-placeholder img-responsive" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
        }
        
        $output = '';
        
        
        
        $values = (array) vc_param_group_parse_atts( $values );
        $text = '';
        $left_text = '';
        $left_right = '';
        
        foreach ( $values as $key => $data ) {
            $new_line = $data;
            
            $new_line['left'] = isset( $data['left'] ) ? $data['left'] : 0;
            $new_line['top'] = isset( $data['top'] ) ? $data['top'] : 0;
            $new_line['label'] = isset( $data['label'] ) ? $data['label'] : '';
            $new_line['color'] = isset( $data['color'] ) ? $data['color'] : 'default';
            $new_line['color'] = isset( $data['color'] ) ? $data['color'] : 'default';
            $new_line['content'] = isset( $data['content'] ) ? $data['content'] : '';
            
            $active = ($key == 0) ? ' active' : '';
            
            $text .= sprintf(
                '<div class="image-tooltip-item %s" style="top: %s; left: %s;"><div class="image-tooltip-content" data-count="%s" title="%s"></div></div>',
                'tooltip-'.$new_line['color'].$active,
                $new_line['top'].'%',
                $new_line['left'].'%',
                $key,
                $new_line['label']
            );
            
            $tooltip = sprintf(
                '<div class="image-tooltip-element %s" data-count="%s"><h4>%s</h4><div>%s</div></div>',
                $active,
                $key,
                $new_line['label'],
                $data['content']
            );
            
            if($new_line['align'] == 'right'){
                $left_right .= $tooltip;
            }else{
                $left_text .= $tooltip;
            }
        }
        
        $output = sprintf(
            '<div class="row"><div class="image-tooltip-left col-md-4">%s</div><div class="image-tooltip-center col-md-4">%s %s</div><div class="image-tooltip-right col-md-4">%s</div></div>',
            $left_text,
            '<div class="image-tooltip-image">'.$img['thumbnail'].'</div>',
            '<div class="image-tooltip-text">'.$text.'</div>',
            $left_right
        );
        
        
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        
        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }
        
        return '<div class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';
        

    }
}

// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Image with Tooltip", 'cruxstore'),
    "base" => "image_tooltip",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(
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
            'value' => 'thumbnail',
            'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'js_composer' ),
        ),
        array(
			'type' => 'param_group',
			'heading' => __( 'Values', 'js_composer' ),
			'param_name' => 'values',
			'description' => __( 'Enter values for tooltip - Position, title and color.', 'js_composer' ),
			'value' => urlencode( json_encode( array(
				array(
					'label' => __( 'Tooltip content', 'js_composer' ),
					'top' => '10',
                    'left' => '10',
				),
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Label', 'js_composer' ),
					'param_name' => 'label',
					'description' => __( 'Enter text used as title of tooltip.', 'js_composer' ),
					'admin_label' => true,
				),
				array(
					'type' => 'cruxstore_number',
                    "suffix" => esc_html__("Percent", 'cruxstore'),
					'heading' => __( 'Position top', 'js_composer' ),
					'param_name' => 'top',
					'description' => __( 'Enter top position of tooltip.', 'js_composer' ),
					'admin_label' => true,
				),
                array(
					'type' => 'cruxstore_number',
                    "suffix" => esc_html__("Percent", 'cruxstore'),
					'heading' => __( 'Position left', 'js_composer' ),
					'param_name' => 'left',
					'description' => __( 'Enter left position of tooltip.', 'js_composer' ),
					'admin_label' => true,
				),
                array(
                    'type' => 'textarea',
                    'holder' => 'div',
                    'heading' => esc_html__( 'Enter text used as content of tooltip.', 'js_composer' ),
                    'param_name' => 'content'
                ),
				array(
					'type' => 'dropdown',
					'heading' => __( 'Color', 'js_composer' ),
					'param_name' => 'color',
					'value' => 
                        array(
						  __( 'Default', 'js_composer' ) => '',
						) + getVcShared( 'colors-dashed' ),
					'description' => __( 'Select single tooltip background color.', 'js_composer' ),
					'admin_label' => true,
					'param_holder_class' => 'vc_colored-dropdown',
				),
                array(
					'type' => 'dropdown',
					'heading' => __( 'Align', 'js_composer' ),
					'param_name' => 'align',
					'value' => array(
						  __( 'Left', 'js_composer' ) => 'left',
                          __( 'Right', 'js_composer' ) => 'right',
					),
					'description' => __( 'Select align for item.', 'js_composer' ),
					'admin_label' => true,
				),
			),
		),
        array(
            "type" => "cruxstore_preview",
            "heading" => esc_html__( "Preview tooltip", "cruxstore" ),
            "param_name" => "preview_tooltip",
            "description" => esc_html__( "Please update it after you upload image and values.", "cruxstore" ),
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