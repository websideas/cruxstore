<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );

class WPBakeryShortCode_Products_Tab_Vertical extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'title' => esc_html__( 'Title', 'js_composer' ),
            'source' => 'widgets',
            'categories' => '',
            'per_page' => 8,
            'orderby' => 'date',
            'order' => 'DESC',
            'style' => 1,
            'active_section' => 1,

            'font_size' => '',
            'line_height' => '',
            'letter_spacing' => '',
            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',

            'css_animation' => '',
            'el_class' => '',
            'css' => '',

            'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
        ), $atts );

        $columns = 3;
        $product_type = 'classic';


        // This is needed to extract $font_container_data and $google_fonts_data
        extract( $this->getAttributes( $atts ) );
        unset($font_container_data['values']['text_align']);

        $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
        extract( $atts );

        extract( $this->getStyles( $el_class, $css, $google_fonts_data, $font_container_data, $atts ) );

        $settings = get_option( 'wpb_js_google_fonts_subsets' );
        if ( is_array( $settings ) && ! empty( $settings ) ) {
            $subsets = '&subset=' . implode( ',', $settings );
        } else {
            $subsets = '';
        }

        if ( isset( $google_fonts_data['values']['font_family'] ) ) {
            wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
        }

        if($letter_spacing){
            if ( empty( $styles ) ) {
                $styles = array();
            }
            $styles[] = 'letter-spacing: '.$letter_spacing.'px';
        }

        if ( ! empty( $styles ) ) {
            $style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
        } else {
            $style = '';
        }


        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wc-products-vertical', $this->settings['base'], $atts ),
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

        $tab_heading = '<ul class="nav-style" data-count="'.count($tabs).'">';

        $i = 1;
        foreach($tabs as $tab){
            if($source == 'categories'){
                $term = get_term_by('slug', sanitize_title($tab), 'product_cat');
                $text = $term->name;
            }else{
                if($tab == 'new'){
                    $text = esc_html__('Arrivals Products', 'cruxstore');
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

        global $woocommerce_loop;

        $i = 1;
        $output_content = '';
        $carousel_atts = array(
            'desktop' => $columns,
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
            $output_content .= sprintf('<div id="%s" class="tab-pane %s">%s</div><!-- .tab-pane -->', 'tab-'.$tab.'-'.$uniqeID, $class, $carousel_html);

            $i++;
        }


        $custom_css = '';
        $rand = 'wc-products-vertical-'.rand();

        if($font_size){
            $custom_css .= cruxstore_responsive_render( '#'.$rand.' .wc-products-vertical-title', 'font-size',  $font_size);
        }

        if($line_height){
            $custom_css .= cruxstore_responsive_render( '#'.$rand.' .wc-products-vertical-title', 'line-height',  $line_height);
        }

        $title = sprintf('<%1$s class="wc-products-vertical-title" %2$s>%3$s</%1$s>', $font_container_data['values']['tag'], $style, $title );
        $heading = sprintf('<div class="wc-products-vertical-heading">%s</div>', $tab_heading);
        $navigation = '<div class="wc-products-vertical-navigation"><span class="wc-products-vertical-left"><i class="fa fa-angle-left" aria-hidden="true"></i></span><span class="wc-products-vertical-right"><i class="fa fa-angle-right" aria-hidden="true"></i></span></div>';
        $content = ($content) ? sprintf('<div class="wc-products-vertical-content">%s</div>', $content) : '';


        $output = sprintf('<div class="row"><div class="col-md-3">%s</div><div class="col-md-9"><div class="tab-content">%s</div></div></div>', $title.$content.$heading.$navigation, $output_content);


        if($custom_css){
            $custom_css = '<div class="cruxstore_custom_css" data-css="'.esc_attr($custom_css).'"></div>';
        }
        $output .= $custom_css;

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        $output = '<div id="'.$rand.'" class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

        return $output;
    }
}





vc_map( array(
    "name" => esc_html__( "KT: Products Tab Vertical", 'cruxstore'),
    "base" => "products_tab_vertical",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            'value' => esc_html__( 'Title', 'js_composer' ),
            "admin_label" => true,
        ),
        array(
            "type" => "textarea_html",
            "heading" => esc_html__("Content", 'cruxstore'),
            "param_name" => "content",
            "value" => '',
            "holder" => "div",
        ),
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
            'heading' => esc_html__( 'Categories', 'cruxstore' ),
            'param_name' => 'categories',
            'placeholder' => esc_html__( 'Select your categories', 'cruxstore' ),
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
            'type' => 'hidden',
            'param_name' => 'link',
        ),
        // Typography setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Typography heading", 'cruxstore'),
            "param_name" => "typography_heading",
            'group' => esc_html__( 'Typography', 'cruxstore' ),
        ),
        array(
            'type' => 'cruxstore_responsive',
            'param_name' => 'font_size',
            'heading' => esc_html__( 'Font size', 'cruxstore' ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'unit' =>  esc_html__( 'px', 'cruxstore' ),
            'description' => esc_html__( 'Use font size for the title.', 'cruxstore' ),
        ),
        array(
            'type' => 'cruxstore_responsive',
            'param_name' => 'line_height',
            'heading' => esc_html__( 'Line Height', 'cruxstore' ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'unit' =>  esc_html__( 'px', 'cruxstore' ),
            'description' => esc_html__( 'Use line height for the title.', 'cruxstore' ),
        ),
        array(
            "type" => "cruxstore_number",
            "heading" => esc_html__("Letter spacing", 'cruxstore'),
            "param_name" => "letter_spacing",
            "min" => 0,
            "suffix" => "px",
            'group' => esc_html__( 'Typography', 'cruxstore' )
        ),
        array(
            'type' => 'font_container',
            'param_name' => 'font_container',
            'value' => '',
            'settings' => array(
                'fields' => array(
                    'tag' => 'h3',
                    'color',
                    //'font_size',
                    //'line_height',
                    'tag_description' => esc_html__( 'Select element tag.', 'js_composer' ),
                    'text_align_description' => esc_html__( 'Select text alignment.', 'js_composer' ),
                    'font_size_description' => esc_html__( 'Enter font size.', 'js_composer' ),
                    'line_height_description' => esc_html__( 'Enter line height.', 'js_composer' ),
                    'color_description' => esc_html__( 'Select heading color.', 'js_composer' ),
                ),
            ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
        ),
        array(
            'type' => 'checkbox',
            'heading' => esc_html__( 'Use theme default font family?', 'js_composer' ),
            'param_name' => 'use_theme_fonts',
            'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
            'description' => esc_html__( 'Use font family from the theme.', 'js_composer' ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'std' => 'yes'
        ),
        array(
            'type' => 'google_fonts',
            'param_name' => 'google_fonts',
            'value' => 'font_family:Oswald|font_style:700%20regular%3A400%3Anormal',
            'settings' => array(
                'fields' => array(
                    'font_family_description' => esc_html__( 'Select font family.', 'js_composer' ),
                    'font_style_description' => esc_html__( 'Select font styling.', 'js_composer' )
                )
            ),
            'group' => esc_html__( 'Typography', 'cruxstore' ),
            'dependency' => array(
                'element' => 'use_theme_fonts',
                'value_not_equal_to' => 'yes',
            ),
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
