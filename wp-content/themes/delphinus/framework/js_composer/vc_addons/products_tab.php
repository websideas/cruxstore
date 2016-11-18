<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Products_Tab extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            
            'source' => 'widgets',
            'categories' => '',
            'per_page' => 8,
            'product_type' => 'classic',
            'desktop' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
            
            'active_section' => 1,
            'css_animation' => '',
            'el_class' => '',
            'css' => '',
            
            'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
        ), $atts );
        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wc-products-tab', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'product_stype' => 'delphinus-products delphinus-products-'.$product_type,
            'woocommerce' => 'woocommerce columns-' . $desktop ,
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

        $tabs = array();
        if($source == 'categories'){
            $tabs = explode(',', $categories);
            $args['order'] = $order;
            $args['orderby'] = $orderby;
        }else{
            $tabs = array('new', 'featured', 'bestselling');
        }

        $tab_heading = '<ul class="nav-style" data-count="'.count($tabs).'">';

        


        $i = 1;
        foreach($tabs as $tab){
            if($source == 'categories'){
                $term = get_term_by('slug', sanitize_title($tab), 'product_cat');
                $text = $term->name;
            }else{
                if($tab == 'featured'){
                    $text = esc_html__('Featured Products', 'delphinus');
                }elseif($tab == 'new'){
                    $text = esc_html__('Arrivals Products', 'delphinus');
                }elseif($tab == 'bestselling'){
                    $text = esc_html__('Best Sellers', 'delphinus');
                }
            }
            $class = ($active_section == $i) ? ' class="active"' : '';
            $tab_heading .= sprintf( '<li %s><h5><a href="%s" data-toggle="tab"><span data-hover="%s">%s</span></a></h5></li>', $class, '#tab-'.$tab.'-'.$uniqeID, esc_attr($text), $text );
            $i++;
        }
        $tab_heading .= "</ul>";

        global $woocommerce_loop;

        $i = 1;
        $output_content = '';

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
                }
            }

            $products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $new_args, $atts ) );
            $woocommerce_loop['columns'] = $desktop;
            $woocommerce_loop['type'] = $product_type;

            ob_start();
            if ( $products->have_posts() ) :
                woocommerce_product_loop_start();
    
                if($product_type == 'masonry'){
                    echo '<div class="clearfix product col-sm-3 grid-sizer"></div>';
                }
    
                while ( $products->have_posts() ) : $products->the_post();
                    wc_get_template_part( 'content', 'product' );
                endwhile; // end of the loop.
                woocommerce_product_loop_end();
            endif;
            wp_reset_postdata();

            $class = ($active_section == $i) ? 'fade in active' : '';
            $output_content .= sprintf('<div id="%s" class="tab-pane %s">%s</div><!-- .tab-pane -->', 'tab-'.$tab.'-'.$uniqeID, $class, ob_get_clean());

            $i++;
        }



        $output = sprintf('<div class="wc-products-tab-heading">%s</div><div class="tab-content">%s</div>', $tab_heading, $output_content);

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        $output = '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

        return $output;
    }
}





vc_map( array(
    "name" => esc_html__( "KT: Products Tab", 'delphinus'),
    "base" => "products_tab",
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    "params" => array(

        // Data setting
        array(
            "type" => "delphinus_heading",
            "heading" => esc_html__("Data settings", 'delphinus'),
            "param_name" => "data_settings",
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Data source", 'delphinus'),
            "param_name" => "source",
            "value" => array(
                esc_html__('Widgets', 'delphinus') => 'widgets',
                esc_html__('Specific Categories', 'delphinus') => 'categories',
            ),
            'std' => 'widgets',
            "admin_label" => true,
            "description" => esc_html__("Select content type for your posts.", 'delphinus'),
        ),

        array(
            "type" => "delphinus_taxonomy",
            'taxonomy' => 'product_cat',
            'heading' => esc_html__( 'Categories', 'delphinus' ),
            'param_name' => 'categories',
            'placeholder' => esc_html__( 'Select your categories', 'delphinus' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
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
            'type' => 'dropdown',
            'heading' => esc_html__( 'Product display type', 'delphinus' ),
            'param_name' => 'product_type',
            'value' => array(
                esc_html__( 'Standard', 'js_composer' ) => 'classic',
                esc_html__( 'Gallery', 'js_composer' ) => 'gallery',
                esc_html__( 'Masonry', 'js_composer' ) => 'masonry',
            ),
            'std' => 'classic',
            'description' => '',
            'admin_label' => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Columns', 'delphinus' ),
            'param_name' => 'desktop',
            'value' => array(
                esc_html__( '1 column', 'js_composer' ) => '1',
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
                esc_html__( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '4',
            'description' => esc_html__('The columns attribute controls how many columns wide the products should be before wrapping.', 'delphinus')
        ),

        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Per page', 'js_composer' ),
            'value' => 8,
            'param_name' => 'per_page',
            'description' => esc_html__( 'The "per_page" shortcode determines how many products to show on the page', 'js_composer' ),
        ),

        // Others setting
        array(
            "type" => "delphinus_heading",
            "heading" => esc_html__("Others settings", 'delphinus'),
            "param_name" => "others_settings",
        ),

        array(
            "type" => "delphinus_number",
            "heading" => esc_html__("Active section", 'delphinus'),
            "param_name" => "active_section",
            "value" => "1",
            'description' => esc_html__( "Enter active section number (Note: to have all sections closed on initial load enter non-existing number).", 'delphinus')
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
