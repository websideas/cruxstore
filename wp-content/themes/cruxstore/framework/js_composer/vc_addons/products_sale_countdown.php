<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



class WPBakeryShortCode_Products_Sale_Countdown extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(

            'desktop' => 3,

            'per_page' => 10,
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',

            'source' => 'all',
            'categories' => '',
            'products' => '',
            'operator' => 'IN',


            'css_animation' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );
        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'cruxstore-products-countdown', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'woocommerce' => 'woocommerce columns-' . $desktop ,
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' )
        );


        $meta_query = WC()->query->get_meta_query();

        $product_ids_on_sale = wc_get_product_ids_on_sale();
        $args = array(
            'post_type'				=> 'product',
            'post_status'			=> 'publish',
            'ignore_sticky_posts'	=> 1,
            'posts_per_page' 		=> $atts['per_page'],
            'meta_query' 			=> $meta_query,
            'order'                 => $order,
            'orderby'               => $orderby,
            'meta_key'              => $meta_key,
            'post__in'              => array_merge( array( 0 ), $product_ids_on_sale )
        );

        if($source == 'categories'){
            if ( ! empty( $categories ) ) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms'    => array_map( 'sanitize_title', explode( ',', $categories ) ),
                        'field'    => 'slug',
                        'operator' => $operator
                    )
                );
            }
        }elseif($source == 'products'){
            if ( ! empty( $atts['products'] ) ) {
                $args['post__in'] = array_map( 'trim', explode( ',', $atts['products'] ) );
            }
        }


        $output = '';

        ob_start();
        global $woocommerce_loop;
        $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

        $woocommerce_loop['columns'] = $desktop;
        $woocommerce_loop['type'] = 'countdown';


        if ( $products->have_posts() ) :
            woocommerce_product_loop_start();

            while ( $products->have_posts() ) : $products->the_post();
                wc_get_template_part( 'content', 'product_sale' );
            endwhile; // end of the loop.
            woocommerce_product_loop_end();
        endif;
        wp_reset_postdata();


        $output .= ob_get_clean();

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }


}



vc_map( array(
    "name" => esc_html__( "KT: Products Sale CountDown", 'cruxstore'),
    "base" => "products_sale_countdown",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(
        // Layout setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Layout setting", 'cruxstore'),
            "param_name" => "layout_settings",
        ),

        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Columns to Show?", 'cruxstore'),
            "edit_field_class" => "cruxstore_sub_heading vc_column",
            "param_name" => "items_show",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'classic', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Desktop', 'cruxstore' ),
            'param_name' => 'desktop',
            'value' => array(
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '1 columns', 'js_composer' ) => '1',
            ),
            'std' => '3'
        ),

        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Extra setting", 'cruxstore'),
            "param_name" => "extra_settings",
        ),
        vc_map_add_css_animation(),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class name", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),




        // Data settings

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Source', 'cruxstore' ),
            'param_name' => 'source',
            'value' => array(
                esc_html__( 'All Product', 'woocommerce' ) => 'all',
                esc_html__( 'Specific Categories', 'js_composer' ) => 'categories',
                esc_html__( 'Specific Products', 'js_composer' ) => 'products',
            ),
            'std' => '',
            'group' => esc_html__( 'Data settings', 'cruxstore' ),
            'description' => esc_html__('Select content type for your posts.', 'cruxstore'),
            "admin_label" => true,
        ),
        array(
            "type" => "cruxstore_taxonomy",
            'taxonomy' => 'product_cat',
            'heading' => esc_html__( 'Categories', 'cruxstore' ),
            'param_name' => 'categories',
            'placeholder' => esc_html__( 'Select your categories', 'cruxstore' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "cruxstore_posts",
            'args' => array('post_type' => 'product', 'posts_per_page' => -1, 'post__in' => wc_get_product_ids_on_sale()),
            'heading' => esc_html__( 'Specific Products', 'js_composer' ),
            'param_name' => 'products',
            'size' => '5',
            'placeholder' => esc_html__( 'Select your posts', 'js_composer' ),
            "dependency" => array( "element" => "source", "value" => array( 'products' ) ),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Total items', 'cruxstore' ),
            'value' => 10,
            'param_name' => 'per_page',
            'description' => esc_html__( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Order by', 'js_composer' ),
            'param_name' => 'orderby',
            'value' => array(
                esc_html__( 'Date', 'js_composer' ) => 'date',
                esc_html__( 'Order by post ID', 'js_composer' ) => 'ID',
                esc_html__( 'Author', 'js_composer' ) => 'author',
                esc_html__( 'Title', 'js_composer' ) => 'title',
                esc_html__( 'Last modified date', 'js_composer' ) => 'modified',
                esc_html__( 'Post/page parent ID', 'js_composer' ) => 'parent',
                esc_html__( 'Number of comments', 'js_composer' ) => 'comment_count',
                esc_html__( 'Menu order/Page Order', 'js_composer' ) => 'menu_order',
                esc_html__( 'Meta value', 'js_composer' ) => 'meta_value',
                esc_html__( 'Meta value number', 'js_composer' ) => 'meta_value_num',
                esc_html__( 'Random order', 'js_composer' ) => 'rand',
            ),
            "dependency" => array( "element" => "source", "value_not_equal_to" => array('best-sellers') ),
            'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
            "admin_label" => true,
        ),
        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Meta key', 'js_composer' ),
            'param_name' => 'meta_key',
            'group' => esc_html__( 'Data settings', 'js_composer' ),
            'dependency' => array(
                'element' => 'orderby',
                'value' => array( 'meta_value', 'meta_value_num' ),
            ),
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Sorting', 'js_composer' ),
            'param_name' => 'order',
            'value' => array(
                esc_html__( 'Descending', 'js_composer' ) => 'DESC',
                esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
            ),
            "dependency" => array( "element" => "source", "value_not_equal_to" => array('best-sellers') ),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
            'description' => esc_html__( 'Select sorting order.', 'js_composer' ),
            "admin_label" => true,
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
