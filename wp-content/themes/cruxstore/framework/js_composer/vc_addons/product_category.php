<?php

class WPBakeryShortCode_Crux_Product_Category extends WPBakeryShortCode
{
    protected function content($atts, $content = null)
    {
        $atts = shortcode_atts(array(
            'product_cat' => '',
            'image_option' => 'category',
            'image' => '',
            'img_size' => 'full',

            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css' => '',
        ), $atts);

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'crux-product-category ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );

        $category = get_term_by('slug', $product_cat, 'product_cat');

        if(!$category)
            return;

        if($image_option == 'custom'){
            $img_id = preg_replace( '/[^\d]/', '', $image );
            $img = wpb_getImageBySize( array(
                'attach_id' => $img_id,
                'thumb_size' => $img_size,
                'class' => 'img-responsive',
            ) );
            if ( $img == null ) {
                $img['thumbnail'] = '<img class="vc_img-placeholder img-responsive" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
            }
            $image = $img['thumbnail'];

        }else{
            $dimensions    			= wc_get_image_size( $img_size );
            $thumbnail_id  			= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

            if ( $thumbnail_id ) {
                $image = wp_get_attachment_image_src( $thumbnail_id, $img_size  );
                $image = $image[0];
            } else {
                $image = wc_placeholder_img_src();
            }

            if ( $image ) {
                // Prevent esc_url from breaking spaces in urls for image embeds
                // Ref: https://core.trac.wordpress.org/ticket/23605
                $image = str_replace( ' ', '%20', $image );

                $image = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
            }
        }

        $output = sprintf(
            '%s<div class="category-banner-content"><a href="%s" class="%s">%s %s</a></div>',
            $image,
            get_term_link( $category->slug, 'product_cat' ),
            'btn btn-light',
            esc_html__('shop', 'cruxstore'),
            $category->name
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
    "name" => esc_html__( "KT: Product Category", 'cruxstore'),
    "base" => "crux_product_category",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => '',
    "params" => array(
        array(
            "type" => "cruxstore_taxonomy",
            'taxonomy' => 'product_cat',
            'heading' => esc_html__( 'Category', 'js_composer' ),
            'param_name' => 'product_cat',
            'admin_label' => true,
            'select' => 'slug',
            'description' => esc_html__('List of product categories', 'cruxstore')
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Image Option', 'js_composer' ),
            'param_name' => 'image_option',
            'value' => array(
                esc_html__( 'Category', 'js_composer' ) => 'category',
                esc_html__( 'Custom', 'js_composer' ) => 'custom',
            ),
            'std' => 'category',
            "admin_label" => true,
        ),
        array(
            'type' => 'attach_image',
            'heading' => esc_html__( 'Image', 'cruxstore' ),
            'param_name' => 'image',
            'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
            'dependency' => array(
                'element' => 'image_option',
                'value' => array( 'custom')
            ),
        ),
        array(
            'type' => 'cruxstore_image_sizes',
            'heading' => esc_html__( 'Image size', 'js_composer' ),
            'param_name' => 'img_size',
            'value' => 'full',
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