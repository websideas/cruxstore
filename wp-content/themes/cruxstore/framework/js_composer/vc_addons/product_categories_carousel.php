<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Categories_Carousel extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'per_page' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'ids' => '',
            'categories_style' => 'normal',
            'hide_empty' => 'true',
            'parent'     => '',

            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'carousel_skin' => '',

            'desktop' => 4,
            'desktopsmall' => 3,
            'tablet' => 2,
            'mobile' => 1,

            'gutters' => false,
            'navigation' => true,
            'navigation_always_on' => true,
            'navigation_position' => 'center-outside',
            'navigation_style' => 'normal',

            'pagination' => false,
            'pagination_position' => 'center-bottom',
            'pagination_style' => 'dot-stroke',

            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );


        if($atts['categories_style'] == 'modern'){
            $atts['gutters'] = true;
        }

        $atts['columns'] = $atts['desktop'];
        $atts['number'] = $atts['per_page'];

        $atts['hide_empty'] = apply_filters('sanitize_boolean', $atts['hide_empty']);

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'products-categories-carousel ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'woocommerce' => 'woocommerce columns-' . $desktop ,
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'style' => 'style-'.$categories_style
        );


        $carousel_ouput = cruxstore_render_carousel(apply_filters( 'cruxstore_render_args', $atts), '', 'cruxstore-owl-carousel');
        $output = $carousel_html ='';



        if ( isset( $atts['ids'] ) ) {
            $ids = explode( ',', $atts['ids'] );
            $ids = array_map( 'trim', $ids );
        } else {
            $ids = array();
        }

        $hide_empty = ( $atts['hide_empty'] == true || $atts['hide_empty'] == 1 ) ? 1 : 0;

        // get terms and workaround WP bug with parents/pad counts
        $args = array(
            'orderby'    => $atts['orderby'],
            'order'      => $atts['order'],
            'hide_empty' => $hide_empty,
            'include'    => $ids,
            'pad_counts' => true,
            'child_of'   => $atts['parent']
        );

        $product_categories = get_terms( 'product_cat', $args );

        if ( '' !== $atts['parent'] ) {
            $product_categories = wp_list_filter( $product_categories, array( 'parent' => $atts['parent'] ) );
        }

        if ( $hide_empty ) {
            foreach ( $product_categories as $key => $category ) {
                if ( $category->count == 0 ) {
                    unset( $product_categories[ $key ] );
                }
            }
        }

        if ( $atts['number'] ) {
            $product_categories = array_slice( $product_categories, 0, $atts['number'] );
        }


        ob_start();

        if ( $product_categories ) {
            global $woocommerce_carousel;
            $woocommerce_carousel = $categories_style;

            foreach ( $product_categories as $category ) {
                wc_get_template( 'content-product_cat_carousel.php', array(
                    'category' => $category
                ) );
            }

        }

        wp_reset_postdata();
        $carousel_html .= ob_get_clean();
        if($carousel_html){

            $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
            $output = '<div class="'.esc_attr( $elementClass ).'">'.str_replace('%carousel_html%', $carousel_html, $carousel_ouput).'</div>';

        }

        return $output;

    }
}



vc_map( array(
    "name" => esc_html__( "KT: Product Categories Carousel", 'cruxstore'),
    "base" => "categories_carousel",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array_merge(
        array(
            array(
                "type" => "cruxstore_taxonomy",
                'taxonomy' => 'product_cat',
                'heading' => esc_html__( 'Categories', 'js_composer' ),
                'param_name' => 'ids',
                'multiple' => true,
                'admin_label' => true,
                'select' => 'id',
                'description' => esc_html__('List of product categories', 'cruxstore')
            ),

            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Categories style', 'js_composer' ),
                'param_name' => 'categories_style',
                'value' => array(
                    esc_html__( 'Normal', 'cruxstore' ) => 'normal',
                    esc_html__( 'Portrait', 'cruxstore' ) => 'portrait',
                    esc_html__( 'Modern', 'cruxstore' ) => 'modern',
                ),
                'std' => 'normal',
                'admin_label' => true,
                "description" => esc_html__("Select your categories style.", 'cruxstore'),
            ),

            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Per Page', 'js_composer' ),
                'value' => '',
                'param_name' => 'per_page',
                'description' => esc_html__( 'The "per_page" shortcode determines how many categories to show on the page', 'js_composer' ),
            ),
            "admin_label" => true,

            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => array(
                    esc_html__( 'Name', 'js_composer' ) => 'name',
                    esc_html__( 'ID', 'js_composer' ) => 'id',
                    esc_html__( 'Count', 'js_composer' ) => 'count',
                    esc_html__( 'Slug', 'js_composer' ) => 'slug',
                    esc_html__( 'None', 'js_composer' ) => 'none',
                ),
                'std' => 'name',
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                "admin_label" => true,
            ),


            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Sorting', 'js_composer' ),
                'param_name' => 'order',
                'value' => array(
                    esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
                    esc_html__( 'Descending', 'js_composer' ) => 'DESC',
                ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                'description' => esc_html__( 'Select sorting order.', 'js_composer' ),
                "admin_label" => true,
            ),

            array(
                'type' => 'cruxstore_switch',
                'heading' => esc_html__( 'Hide empty', 'cruxstore' ),
                'param_name' => 'hide_empty',
                'value' => 'true',
                "description" => esc_html__("Hide category if empty.", 'cruxstore'),
            ),

            vc_map_add_css_animation(),
            array(
                "type" => "textfield",
                "heading" => esc_html__( "Extra class name", "js_composer" ),
                "param_name" => "el_class",
                "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
            ),

        ),
        cruxstore_map_add_carousel_parrams(),
        array(
            array(
                'type' => 'css_editor',
                'heading' => esc_html__( 'Css', 'js_composer' ),
                'param_name' => 'css',
                // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
                'group' => esc_html__( 'Design options', 'js_composer' )
            ),
        )
    ),
));
