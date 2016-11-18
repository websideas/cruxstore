<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if ( !defined('YITH_WCBR'))
    return;


class WPBakeryShortCode_Brands_Grid extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'per_page' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'ids' => '',
            'hide_empty' => 'true',
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
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'products-brands-carousel ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
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

        $product_brands = get_terms( 'yith_product_brand', $args );

        if ( '' !== $atts['parent'] ) {
            $product_brands = wp_list_filter( $product_brands, array( 'parent' => $atts['parent'] ) );
        }

        if ( $hide_empty ) {
            foreach ( $product_brands as $key => $brand ) {
                if ( $brand->count == 0 ) {
                    unset( $product_brands[ $key ] );
                }
            }
        }

        if ( $atts['number'] ) {
            $product_brands = array_slice( $product_brands, 0, $atts['number'] );
        }

        $output = '';

        ob_start();

        if ( $product_brands ) {
            global $woocommerce_carousel;
            $woocommerce_carousel = 'normal';

            $css_animation = cruxstore_getCSSAnimation( $css_animation );

            echo '<div class="row multi-columns-row">';


            foreach ( $product_brands as $key=>$term ) {

                if($animation_delay){
                    $animation_delay_item = sprintf(' data-wow-delay="%sms"', $key * $animation_delay);
                }else{
                    $animation_delay_item = '';
                }

                printf('<div class="brand-banner-wrap col-lg-%1$s col-md-%1$s col-sm-%2$s col-xs-%3$s">', 2, 3, 6);

                echo '<div class="brand-banner'.$css_animation.'"'.$animation_delay_item.'><div class="brand-banner-content">';

                $thumbnail_id = absint( yith_wcbr_get_term_meta( $term->term_id, 'thumbnail_id', true ) );
                $image_size = apply_filters('cruxstore_brand_logo', 'cruxstore_grid');
                if ( $thumbnail_id ) {
                    $image = wp_get_attachment_image_src( $thumbnail_id, $image_size );
                    printf( '<div class="brand-image"><img src="%s" width="%d" height="%d" alt="%s"/></div>', $image[0], $image[1], $image[2], $term->name );
                }else{
                    do_action( 'yith_wcbr_no_brand_logo', $term->term_id, $term, 'yith_wcbr_logo_size', false, false );
                    echo '<div class="brand-image">'.wc_placeholder_img().'</div>';
                }


                printf('<div class="brand-count"><span>%s</span> %s</a></div>',  $term->count, esc_html__('Products', 'cruxstore'));
                printf('<a href="%s">%s</a>', get_term_link( $term ), $term->name);
                echo '</div></div>';
                echo '</div>';

            }

            echo "</div><!-- .row -->";

        }

        wp_reset_postdata();
        $output .= ob_get_clean();

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return $output = '<div class="'.esc_attr( $elementClass ).'"><div class="products-brands-inner">'.$output.'</div></div>';

    }
}



vc_map( array(
    "name" => esc_html__( "KT: Brands Grid", 'cruxstore'),
    "base" => "brands_grid",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(

        array(
            "type" => "cruxstore_taxonomy",
            'taxonomy' => 'yith_product_brand',
            'heading' => esc_html__( 'Brands', 'js_composer' ),
            'param_name' => 'ids',
            'multiple' => true,
            'admin_label' => true,
            'select' => 'id',
            'description' => esc_html__('List of product brands', 'cruxstore')
        ),

        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Per Page', 'js_composer' ),
            'value' => '',
            'param_name' => 'per_page',
            'description' => esc_html__( 'The "per_page" shortcode determines how many brands to show on the page', 'js_composer' ),
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
            "description" => esc_html__("Hide brand if empty.", 'cruxstore'),
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