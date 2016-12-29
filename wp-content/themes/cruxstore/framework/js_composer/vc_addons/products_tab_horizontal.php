<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Products_Tab_Horizontal extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'source' => 'widgets',
            'categories' => '',
            'per_page' => 8,
            'orderby' => 'date',
            'order' => 'DESC',
            'style' => 1,
            'active_section' => 1,

            'product_effect' => '',
            'product_type' => 'classic',

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
            'navigation' => false,
            'pagination' => false,


            'css_animation' => '',
            'el_class' => '',
            'css' => '',

            'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
        ), $atts );

        $atts['columns'] = $atts['desktop'];

        extract($atts);


        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wc-products-horizontal wc-productstab-carousel', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'product_stype' => 'cruxstore-products cruxstore-products-classic',
            'woocommerce' => 'woocommerce columns-' . $columns ,
        );

        if(!$active_section){
            $active_section = 1;
        }

        $uniqeID = uniqid();

        $meta_query = WC()->query->get_meta_query();
        $args = array(
            'post_type'				=> 'product',
            'post_status'			=> 'publish',
            'ignore_sticky_posts'	=> 1,
            'posts_per_page' 		=> $atts['per_page'],
            'meta_query' 			=> $meta_query
        );

        if($source == 'categories'){
            $tabs = explode(',', $categories);
            $args['order'] = $order;
            $args['orderby'] = $orderby;
        }else{
            $tabs = array('onsale', 'new', 'bestselling' );
        }

        $tab_heading = '<ul class="nav-style nav-style-'.$style.'" data-count="'.count($tabs).'">';

        $i = 1;
        foreach($tabs as $tab){
            if($source == 'categories'){
                if($term = get_term_by('slug', sanitize_title($tab), 'product_cat')){
                    $text = $term->name;
                }
            }else{
                if($tab == 'new'){
                    $text = esc_html__('New Products', 'cruxstore');
                }elseif($tab == 'bestselling'){
                    $text = esc_html__('Best Sellers', 'cruxstore');
                }elseif($tab == 'onsale'){
                    $text = esc_html__('On sale', 'cruxstore');
                }
            }
            $class = ($active_section == $i) ? ' class="active"' : '';
            $tab_heading .= sprintf( '<li %s><a href="%s" data-toggle="tab"><span data-hover="%s">%s</span></a></li>', $class, '#tab-'.$tab.'-'.$uniqeID, esc_attr($text), $text );
            $i++;
        }
        $tab_heading .= "</ul>";


        if($product_type != 'classic'){
            $product_effect = '';
        }

        if($product_effect == 7){
            $elementClass[] = 'effect-7';
        }

        global $woocommerce_loop;

        $i = 1;
        $output_content = '';
        $carousel_atts = array(
            'desktop' => $columns,
            'desktopsmall' => $desktopsmall,
            'tablet' => $tablet,
            'mobile' => $mobile,
            'navigation' => false,
            'gutters' => false
        );

        $carousel_ouput = cruxstore_render_carousel(apply_filters( 'cruxstore_render_args', $carousel_atts), '', 'wc-carousel-wrapper');


        foreach($tabs as $tab){

            $new_args = $args;

            if($source == 'categories'){
                $new_args['tax_query'] = array(
                    array(
                        'taxonomy' 		=> 'product_cat',
                        'terms' 		=> sanitize_title($tab),
                        'field' 		=> 'slug',
                        'operator' 		=> $atts['operator']
                    )
                );
            }else{
                if( $tab == 'bestselling' ){
                    $new_args['meta_key'] = 'total_sales';
                    $new_args['orderby'] = 'meta_value_num';

                }elseif( $tab == 'featured' ){
                    $new_args['meta_query'][] = array(
                        'key'   => '_featured',
                        'value' => 'yes'
                    );
                }elseif( $tab == 'onsale' ){
                    $new_args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
                }
            }

            $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $new_args, $atts ) );
            $woocommerce_loop['columns'] = $columns;
            $woocommerce_loop['type'] = $product_type;
            $woocommerce_loop['effect'] = $product_effect;

            ob_start();
            if ( $products->have_posts() ) :
                woocommerce_product_loop_start();

                while ( $products->have_posts() ) : $products->the_post();
                    wc_get_template_part( 'content', 'product' );
                endwhile; // end of the loop.
                woocommerce_product_loop_end();
            endif;
            wp_reset_postdata();

            $carousel_html = ob_get_clean();
            $carousel_html = str_replace('%carousel_html%', $carousel_html, $carousel_ouput);

            $class = ($active_section == $i) ? 'fade in active' : 'fade';
            $output_content .= sprintf('<div id="%s" role="tabpanel" class="tab-pane %s">%s</div><!-- .tab-pane -->', 'tab-'.$tab.'-'.$uniqeID, $class, $carousel_html);

            $i++;
        }


        $heading = sprintf('<div class="wc-products-tab-heading">%s</div>', $tab_heading);
        $navigation = '<div class="wc-products-tab-navigation"><span class="wc-products-nav-left"><i class="fa fa-angle-left" aria-hidden="true"></i></span><span class="wc-products-nav-right"><i class="fa fa-angle-right" aria-hidden="true"></i></span></div>';
        $output = sprintf('%s<div class="tab-content">%s%s</div>', $heading, $navigation, $output_content);


        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        $output = '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

        return $output;
    }
}





vc_map( array(
    "name" => esc_html__( "KT: Products Tab Horizontal", 'cruxstore'),
    "base" => "products_tab_horizontal",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array_merge(
        array(

            // Data setting
            array(
                "type" => "cruxstore_heading",
                "heading" => esc_html__("Data settings", 'cruxstore'),
                "param_name" => "data_settings",
            ),
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Data source", 'cruxstore'),
                "param_name" => "source",
                "value" => array(
                    esc_html__('Widgets', 'cruxstore') => 'widgets',
                    esc_html__('Specific Categories', 'cruxstore') => 'categories',
                ),
                'std' => 'widgets',
                "admin_label" => true,
                "description" => esc_html__("Select content type for your posts.", 'cruxstore'),
            ),

            array(
                "type" => "cruxstore_taxonomy",
                'taxonomy' => 'product_cat',
                'select' => 'slug',
                'heading' => esc_html__( 'Categories', 'cruxstore' ),
                'param_name' => 'categories',
                'placeholder' => esc_html__( 'Select your categories', 'cruxstore' ),
                "dependency" => array("element" => "source","value" => array('categories')),
                'multiple' => true,
            ),
            array(
                'type' => 'dropdown',
                'param_name' => 'product_effect',
                'heading' => esc_html__( 'Product effect', 'cruxstore' ),
                'value' => array(
                    esc_html__('Default', 'cruxstore' ) => '',
                    esc_html__('Effect 1', 'cruxstore' ) => '1',
                    esc_html__('Effect 2', 'cruxstore' ) => '2',
                    esc_html__('Effect 3', 'cruxstore' ) => '3',
                    esc_html__('Effect 4', 'cruxstore' ) => '4',
                    esc_html__('Effect 5', 'cruxstore' ) => '5',
                    esc_html__('Effect 6', 'cruxstore' ) => '6',
                    esc_html__('Effect 7', 'cruxstore' ) => '7',
                ),
                'admin_label' => true,
                'description' => esc_html__( 'select effect of product', 'cruxstore' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Order by', 'js_composer' ),
                'param_name' => 'orderby',
                'value' => array(
                    '',
                    esc_html__( 'Date', 'js_composer' ) => 'date',
                    esc_html__( 'ID', 'js_composer' ) => 'ID',
                    esc_html__( 'Author', 'js_composer' ) => 'author',
                    esc_html__( 'Title', 'js_composer' ) => 'title',
                    esc_html__( 'Modified', 'js_composer' ) => 'modified',
                    esc_html__( 'Random', 'js_composer' ) => 'rand',
                    esc_html__( 'Comment count', 'js_composer' ) => 'comment_count',
                    esc_html__( 'Menu order', 'js_composer' ) => 'menu_order',
                ),
                'save_always' => true,
                "dependency" => array( "element" => "source","value" => 'categories' ),
                'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Sort order', 'js_composer' ),
                'param_name' => 'order',
                'value' => array(
                    '',
                    esc_html__( 'Descending', 'js_composer' ) => 'DESC',
                    esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
                ),
                'save_always' => true,
                "dependency" => array( "element" => "source","value" => 'categories' ),
                'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'js_composer' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
            ),

            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Per page', 'js_composer' ),
                'value' => 8,
                'param_name' => 'per_page',
                'description' => esc_html__( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
            ),


            array(
                "type" => "cruxstore_number",
                "heading" => esc_html__("Active section", 'cruxstore'),
                "param_name" => "active_section",
                "value" => "1",
                'description' => esc_html__( "Enter active section number (Note: to have all sections closed on initial load enter non-existing number).", 'cruxstore')
            ),
        ),
        cruxstore_map_add_carousel_parrams(array('columns')),
        // Others setting
        array(
            array(
                "type" => "cruxstore_heading",
                "heading" => esc_html__("Others settings", 'cruxstore'),
                "param_name" => "others_settings",
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Style Nav', 'cruxstore' ),
                'param_name' => 'style',
                'value' => array(
                    esc_html__( 'Style 1', 'cruxstore' ) => '1',
                    esc_html__( 'Style 2', 'cruxstore' ) => '2',
                    esc_html__( 'Style 3', 'cruxstore' ) => '3',
                    esc_html__( 'Style 4', 'cruxstore' ) => '4',
                    esc_html__( 'Style 5', 'cruxstore' ) => '5',
                ),
                'std' => 1,
                'description' => esc_html__( 'Select your style.', 'cruxstore' ),
                "admin_label" => true,
            ),
            array(
                "type" => "cruxstore_number",
                "heading" => esc_html__("Active section", 'cruxstore'),
                "param_name" => "active_section",
                "value" => "1",
                'description' => esc_html__( "Enter active section number (Note: to have all sections closed on initial load enter non-existing number).", 'cruxstore')
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
        )
    ),
));
