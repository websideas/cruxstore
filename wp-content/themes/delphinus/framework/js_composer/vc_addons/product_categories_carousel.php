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


        $carousel_ouput = delphinus_render_carousel(apply_filters( 'delphinus_render_args', $atts), '', 'delphinus-owl-carousel');
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
    "name" => esc_html__( "KT: Product Categories Carousel", 'delphinus'),
    "base" => "categories_carousel",
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    "params" => array(

        array(
            "type" => "delphinus_taxonomy",
            'taxonomy' => 'product_cat',
            'heading' => esc_html__( 'Categories', 'js_composer' ),
            'param_name' => 'ids',
            'multiple' => true,
            'admin_label' => true,
            'select' => 'id',
            'description' => esc_html__('List of product categories', 'delphinus')
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Categories style', 'js_composer' ),
            'param_name' => 'categories_style',
            'value' => array(
                esc_html__( 'Normal', 'delphinus' ) => 'normal',
                esc_html__( 'Portrait', 'delphinus' ) => 'portrait'
            ),
            'std' => 'normal',
            "description" => esc_html__("Select your categories style.", 'delphinus'),
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
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Hide empty', 'delphinus' ),
            'param_name' => 'hide_empty',
            'value' => 'true',
            "description" => esc_html__("Hide category if empty.", 'delphinus'),
        ),

        vc_map_add_css_animation(),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),



        // Carousel
        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Auto Height', 'delphinus' ),
            'param_name' => 'autoheight',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 delphinus_margin_bottom",
            "description" => esc_html__("Enable auto height.", 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' ),
        ),
        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Mouse Drag', 'delphinus' ),
            'param_name' => 'mousedrag',
            'value' => 'true',
            "description" => esc_html__("Mouse drag enabled.", 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            "edit_field_class" => "vc_col-sm-4 delphinus_margin_bottom",
        ),
        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'AutoPlay', 'delphinus' ),
            'param_name' => 'autoplay',
            'value' => 'false',
            "description" => esc_html__("Enable auto play.", 'delphinus'),
            "edit_field_class" => "vc_col-sm-4 delphinus_margin_bottom",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            "type" => "delphinus_number",
            "heading" => esc_html__("AutoPlay Speed", 'delphinus'),
            "param_name" => "autoplayspeed",
            "value" => "5000",
            "suffix" => esc_html__("milliseconds", 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            "dependency" => array("element" => "autoplay","value" => array('true')),
        ),
        array(
            "type" => "delphinus_number",
            "heading" => esc_html__("Slide Speed", 'delphinus'),
            "param_name" => "slidespeed",
            "value" => "200",
            "suffix" => esc_html__("milliseconds", 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Carousel skin', 'delphinus' ),
            'param_name' => 'carousel_skin',
            'value' => array(
                esc_html__( 'Default', 'delphinus') => '',
                esc_html__( 'White', 'delphinus') => 'white',
            ),
            'std' => '',
            'desc' => esc_html__('Select carousel skin', 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            "type" => "delphinus_heading",
            "heading" => esc_html__("Items to Show?", 'delphinus'),
            "param_name" => "items_show",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            "type" => "delphinus_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 delphinus_margin_bottom",
            "heading" => esc_html__("On Desktop", 'delphinus'),
            "param_name" => "desktop",
            "value" => 4,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),

        array(
            'type' => 'delphinus_number',
            'heading' => esc_html__( 'on Tablets Landscape', 'delphinus' ),
            'param_name' => 'desktopsmall',
            "value" => 3,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            "edit_field_class" => "vc_col-sm-6 delphinus_margin_bottom",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            "type" => "delphinus_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 delphinus_margin_bottom",
            "heading" => esc_html__("On Tablet", 'delphinus'),
            "param_name" => "tablet",
            "value" => 2,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            "type" => "delphinus_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 delphinus_margin_bottom",
            "heading" => esc_html__("On Mobile", 'delphinus'),
            "param_name" => "mobile",
            "value" => 1,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            "type" => "delphinus_heading",
            "heading" => esc_html__("Navigation settings", 'delphinus'),
            "param_name" => "navigation_settings",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Navigation', 'delphinus' ),
            'param_name' => 'navigation',
            'value' => 'true',
            "description" => esc_html__("Show navigation in carousel", 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation position', 'delphinus' ),
            'param_name' => 'navigation_position',
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            'value' => array(
                esc_html__( 'Center outside', 'delphinus') => 'center-outside',
                esc_html__( 'Center inside', 'delphinus') => 'center',
                //esc_html__( 'Top', 'delphinus') => 'top',
                esc_html__( 'Bottom', 'delphinus') => 'bottom',
            ),
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Always Show Navigation', 'delphinus' ),
            'param_name' => 'navigation_always_on',
            'value' => 'false',
            "description" => esc_html__("Always show the navigation.", 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            "dependency" => array("element" => "navigation_position","value" => array('center', 'center-outside')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation style', 'js_composer' ),
            'param_name' => 'navigation_style',
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            'value' => array(
                esc_html__( 'Normal', 'delphinus' ) => 'normal',
                esc_html__( 'Circle Background', 'delphinus' ) => 'circle-background',
                esc_html__( 'Square Background', 'delphinus' ) => 'square-background',
                esc_html__( 'Round Background', 'delphinus' ) => 'round-background',
                esc_html__( 'Circle Border', 'delphinus' ) => 'circle-border',
                esc_html__( 'Square Border', 'delphinus' ) => 'square-border',
                esc_html__( 'Round Border', 'delphinus' ) => 'round-border',
            ),
            'std' => 'normal',
            "dependency" => array("element" => "navigation","value" => array('true')),
            "description" => esc_html__("Select your navigation style.", 'delphinus'),
        ),

        array(
            "type" => "delphinus_heading",
            "heading" => esc_html__("Pagination settings", 'delphinus'),
            "param_name" => "pagination_settings",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            'type' => 'delphinus_switch',
            'heading' => esc_html__( 'Pagination', 'delphinus' ),
            'param_name' => 'pagination',
            'value' => 'false',
            "description" => esc_html__("Show pagination in carousel", 'delphinus'),
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Pagination position', 'delphinus' ),
            'param_name' => 'pagination_position',
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            'value' => array(
                esc_html__( 'Center Top', 'delphinus') => 'center-top',
                esc_html__( 'Center Bottom', 'delphinus') => 'center-bottom',
                esc_html__( 'Bottom Left', 'delphinus') => 'bottom-left',
            ),
            'std' => 'center_bottom',
            "dependency" => array("element" => "pagination","value" => array('true')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Pagination style', 'js_composer' ),
            'param_name' => 'pagination_style',
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            'value' => array(
                esc_html__( 'Dot stroke', 'delphinus' ) => 'dot-stroke',
                esc_html__( 'Fill pp', 'delphinus' ) => 'fill-up',
                esc_html__( 'Circle grow', 'delphinus' ) => 'circle-grow',
            ),
            'std' => 'dot_stroke',
            "dependency" => array("element" => "pagination","value" => array('true')),
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
