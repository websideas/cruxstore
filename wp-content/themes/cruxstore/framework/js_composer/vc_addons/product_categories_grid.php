<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Categories_Grid extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'per_page' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'ids' => '',
            'hide_empty' => 'true',
            'columns' => '4',
            'parent'     => '',

            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );

        $atts['number'] = $atts['per_page'];
        $atts['hide_empty'] = apply_filters('sanitize_boolean', $atts['hide_empty']);

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'products-categories-grid ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'woocommerce' => 'woocommerce columns-' . $columns ,
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
            echo '<div class="row multi-columns-row">';

            $bootstrapColumn = round( 12 / $columns);



            foreach ( $product_categories as $category ) {
                echo '<div class="col-md-'.$bootstrapColumn.' col-sm-'.$bootstrapColumn.'">';
                wc_get_template( 'content-product_cat_grid.php', array(
                    'category' => $category
                ) );
                echo '</div>';
            }
            echo '</div>';

        }

        wp_reset_postdata();

        $output = ob_get_clean();

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        if($animation_delay){
            $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
        }

        return '<div class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';

    }
}



vc_map( array(
    "name" => esc_html__( "KT: Product Categories Grid", 'cruxstore'),
    "base" => "categories_grid",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(

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
            'type' => 'textfield',
            'heading' => esc_html__( 'Columns', 'js_composer' ),
            'value' => '4',
            'param_name' => 'columns',
            'description' => esc_html__( 'How much columns grid', 'js_composer' ),
        ),

        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Hide empty', 'cruxstore' ),
            'param_name' => 'hide_empty',
            'value' => 'true',
            "description" => esc_html__("Hide category if empty.", 'cruxstore'),
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
            'heading' => esc_html__( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => esc_html__( 'Design options', 'js_composer' )
        ),
    ),
));
