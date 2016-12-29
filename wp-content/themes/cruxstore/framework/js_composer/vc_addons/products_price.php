<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );
class WPBakeryShortCode_Products_Price extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        
        $atts = shortcode_atts( array(
            'badge' => esc_html__( 'only', 'cruxstore' ),
            'price' => 245,
            'currency' => esc_html__( '$', 'cruxstore' ),
            'desktop' => 4,
            'values' => '',

            'el_class' => '',
            'css_animation' => '',
            'animation_delay' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $product_columns = 12/$desktop;
        $product_land = ($product_columns == 4) ? 4 : 6;
        
        
        $values = (array) vc_param_group_parse_atts( $values );
        $text = '';
        foreach ( $values as $data ) {
            
            $img_id = isset( $data['image'] ) ? $data['image'] : 0;
            $pd_link = isset( $data['link'] ) ? $data['link'] : '';
            $pd_title = isset( $data['label'] ) ? $data['label'] : '';
            
            $img_id = preg_replace( '/[^\d]/', '', $img_id );
            $img = wpb_getImageBySize( array(
                'attach_id' => $img_id,
                'thumb_size' => 'full',
                'class' => 'img-responsive',
            ) );
            if ( $img == null ) {
                $img['thumbnail'] = '<img class="vc_img-placeholder img-responsive" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
            }
            
            $pd_image = $img['thumbnail'];
           
           if ( ! empty( $pd_link ) ) {
                $pd_link = vc_build_link( $pd_link );
                if($pd_title){
                    $pd_title = '<a href="' . esc_attr( $pd_link['url'] ) . '"'
                                    . ( $pd_link['target'] ? ' target="' . esc_attr( $pd_link['target'] ) . '"' : '' )
                                    . ( $pd_link['title'] ? ' title="' . esc_attr( $pd_link['title'] ) . '"' : '' )
                                    . '>' . $pd_title . '</a>';
                }
                $pd_image = '<a href="' . esc_attr( $pd_link['url'] ) . '"'
                                . ( $pd_link['target'] ? ' target="' . esc_attr( $pd_link['target'] ) . '"' : '' )
                                . ( $pd_link['title'] ? ' title="' . esc_attr( $pd_link['title'] ) . '"' : '' )
                                . '>' . $img['thumbnail'] . '</a>';
                
            }
            
            $pd_image = sprintf('<div class="product-price-image">%s</div>', $pd_image);
            
            if($pd_title){
                $pd_title = sprintf('<h5>%s</h5>', $pd_title);
            }
            
            $text .= sprintf(
                '<div class="%s %s">%s</div>',
                'col-md-'.$product_columns.' col-sm-'.$product_land,
                'product-price-item',
                $pd_image.$pd_title
            );
        }
        $output = '<div class="row multi-columns-row">'.$text.'</div>';
        
        $output .= sprintf(
            '<div class="products-price-content"><div class="products-price-circle"><span class="products-price-only">%s</span><span class="products-price-currency">%s</span><span class="products-price-text">%s</span></div><span class="products-price-cart">%s</span></div>',
            $badge,
            $currency,
            $price,
            '<i class="fa fa-shopping-basket" aria-hidden="true"></i>'
        );
        
        $elementClass = array(
            'base' => apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'products-price clearfix', $this->settings['base'], $atts),
            'extra' => $this->getExtraClass($el_class),
            'css_animation' => $this->getCSSAnimation($css_animation),
            'shortcode_custom' => vc_shortcode_custom_css_class($css, ' ')
        );
        $elementClass = preg_replace(array('/\s+/', '/^\s|\s$/'), array(' ', ''), implode(' ', $elementClass));
        
        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }
        
        return '<div class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';


    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Products price", 'cruxstore'),
    "base" => "products_price",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Badge', 'cruxstore' ),
            'param_name' => 'badge',
            "admin_label" => true,
            'value' => esc_html__( 'only', 'cruxstore' ),  
        ),
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Price', 'cruxstore' ),
            'param_name' => 'price',
            "admin_label" => true,
            'value' => 245
        ),
        
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Currency', 'cruxstore' ),
            'param_name' => 'currency',
            "admin_label" => true,
            'value' => esc_html__( '$', 'cruxstore' ),
        ),
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Columns to Show?", 'cruxstore'),
            "edit_field_class" => "cruxstore_sub_heading vc_column",
            "param_name" => "items_show",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Desktop', 'cruxstore' ),
            'param_name' => 'desktop',
            'value' => array(
                esc_html__( '4 columns', 'js_composer' ) => '4',
                esc_html__( '3 columns', 'js_composer' ) => '3',
            ),
            'std' => '4',
            'description' => esc_html__('The columns attribute controls how many columns wide the products should be before wrapping.', 'cruxstore')
        ),
        array(
			'type' => 'param_group',
			'heading' => __( 'Values', 'js_composer' ),
			'param_name' => 'values',
			'description' => esc_html__( 'Enter values for product - Image, title and url.', 'js_composer' ),
			'value' => urlencode( json_encode( array(
				array(
					'label' => esc_html__( 'Product title', 'js_composer' ),
					'image' => '',
				),
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Product title', 'js_composer' ),
					'param_name' => 'label',
					'description' => esc_html__( 'Enter text used as title of product.', 'js_composer' ),
					'admin_label' => true,
				),
				array(
                    'type' => 'attach_image',
                    'heading' => esc_html__( 'Product Image', 'cruxstore' ),
                    'param_name' => 'image',
                    'dependency' => array( 'element' => 'icon_type',  'value' => array( 'image' ) ),
                    'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
                    'group' => esc_html__( 'Icon', 'cruxstore' )
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => esc_html__( 'Product Url', 'js_composer' ),
                    'param_name' => 'link',
                ),
			),
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
        
        

        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' )
        )

    )
));