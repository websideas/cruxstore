<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Categories_Masonry extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'layout' => 1,
            'per_page' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'ids' => '',
            'hide_empty' => 'true',
            'parent'     => '',
            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );

        $atts['number'] = $atts['per_page'];

        $atts['hide_empty'] = apply_filters('sanitize_boolean', $atts['hide_empty']);

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'product-categories-masonry ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'woocommerce' => 'woocommerce' ,
            'layout' => 'product-categories-masonry-'.$layout ,
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );



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
            echo '<div class="row">';
            $i = 1;
            foreach ( $product_categories as $category ) {
                if($layout == 2){
                    $bootstrapColumnMobile = 6;
                    if(($i % 10) == 1 || ($i % 10 == 8)){
                        $bootstrapColumn = 6;
                        if($i == 1){
                            $bootstrapColumnMobile = 12;
                        }
                    }else{
                        $bootstrapColumn = 3;
                    }
                }else{
                    $bootstrapColumnMobile = 6;
                    if($i == 1 || $i == 2){
                        $bootstrapColumn = 6;
                        if($i == 1){
                            $bootstrapColumnMobile = 12;
                        }
                    }else{
                        $bootstrapColumn = 3;
                    }
                }





                echo '<div class="category-masonry-item col-md-'.$bootstrapColumn.' col-sm-'.$bootstrapColumn.' col-xs-'.$bootstrapColumnMobile.'">';
                wc_get_template( 'content-product_cat_masonry.php', array(
                    'category' => $category
                ) );
                echo '</div>';
                $i++;
            }
            echo '</div>';
        }

        wp_reset_postdata();
        $output = ob_get_clean();
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}



vc_map( array(
    "name" => esc_html__( "KT: Product Categories Masonry", 'cruxstore'),
    "base" => "categories_masonry",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Layout', 'js_composer' ),
            'param_name' => 'layout',
            'value' => array(
                esc_html__( 'Layout 1', 'js_composer' ) => '1',
                esc_html__( 'Layout 2', 'js_composer' ) => '2',
            ),
            'std' => '1',
            "admin_label" => true,
        ),

        // Data setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Data settings", 'cruxstore'),
            "param_name" => "data_settings",
        ),
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
            'type' => 'textfield',
            'heading' => esc_html__( 'Per Page', 'js_composer' ),
            'value' => '',
            'param_name' => 'per_page',
            'description' => esc_html__( 'The "per_page" shortcode determines how many categories to show on the page', 'js_composer' ),
            "admin_label" => true,
        ),
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
        // Others setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Others settings", 'cruxstore'),
            "param_name" => "others_settings",
        ),

        vc_map_add_css_animation(),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),

        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => esc_html__( 'Design options', 'js_composer' )
        ),
    ),
));
