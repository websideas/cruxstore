<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Clients_Carousel extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'img_size' => 'thumbnail',
            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'target_link' => '_self',
            'image_overlay' => '',

            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,

            'desktop' => 4,
            'desktopsmall' => 3,
            'tablet' => 2,
            'mobile' => 1,

            'gutters' => true,
            'navigation' => true,
            'navigation_always_on' => false,
            'navigation_position' => 'center-outside',
            'navigation_style' => 'normal',

            'pagination' => false,
            'pagination_position' => 'center-bottom',
            'pagination_style' => 'dot-stroke',


            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css' => '',

        ), $atts );

        extract($atts);

        if($image_overlay == 'white-boxed'){
            $atts['gutters'] = false;
        }




        $args = array(
            'post_type' => 'crux_client',
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => -1,
            'ignore_sticky_posts' => true
        );

        if($orderby == 'meta_value' || $orderby == 'meta_value_num'){
            $args['meta_key'] = $meta_key;
        }
        if($source == 'categories'){
            if($categories){
                $categories_arr = array_filter(explode( ',', $categories));
                if(count($categories_arr)){
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'client-category',
                            'field' => 'slug',
                            'terms' => $categories
                        )
                    );
                }
            }
        }elseif($source == 'posts'){
            if($posts){
                $posts_arr = array_filter(explode( ',', $posts));
                if(count($posts_arr)){
                    $args['post__in'] = $posts_arr;
                }
            }
        }

        $client_carousel_html = $post_thumbnail = '';
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) :
        
            $css_animation = cruxstore_getCSSAnimation( $css_animation );
            $i = 1;
            while ( $query->have_posts() ) : $query->the_post();
                $link = cruxstore_meta('_cruxstore_link_client');
                if($animation_delay){
                    $animation_delay_item = sprintf(' data-wow-delay="%sms"', $i * $animation_delay);
                }else{
                    $animation_delay_item = '';
                }
                
                if( $link ){
                    $post_thumbnail = '<a target="'.$target_link.'" href="'.$link.'">'.get_the_post_thumbnail(get_the_ID(),$img_size).'</a>';
                }else{
                    $post_thumbnail = get_the_post_thumbnail(get_the_ID(), $img_size, '');
                }

                $client_carousel_html .= sprintf(
                    '<div class="%s" %s>%s</div>',
                    'clients-carousel-item'.$css_animation,
                    $animation_delay_item,
                    $post_thumbnail
                );
                $i++;
            endwhile; wp_reset_postdata();
        endif;

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'clients-carousel ', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );
        if($image_overlay){
            $elementClass['overlay'] = 'overlay-'.$image_overlay;
        }


        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        $output = '';
        $output .= '<div class="'.esc_attr( $elementClass ).'">';

        $carousel_ouput = cruxstore_render_carousel(apply_filters( 'cruxstore_render_args', $atts));
        $output .= str_replace('%carousel_html%', $client_carousel_html, $carousel_ouput);

        $output .= '</div>';

        return $output;
    }
}

vc_map( array(
    "name" => esc_html__( "KT: Clients Carousel", 'cruxstore'),
    "base" => "clients_carousel",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "wrapper_class" => "clearfix",
    "params" => array_merge(
        array(
            array(
                "type" => "cruxstore_image_sizes",
                "heading" => esc_html__( "Select image sizes", 'cruxstore' ),
                "param_name" => "img_size",
                'description' => esc_html__( 'Select size of image', 'cruxstore')
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Target Link', 'cruxstore' ),
                'param_name' => 'target_link',
                'value' => array(
                    esc_html__( 'Self', 'cruxstore' ) => '_self',
                    esc_html__( 'Blank', 'cruxstore' ) => '_blank',
                    esc_html__( 'Parent', 'cruxstore' ) => '_parent',
                    esc_html__( 'Top', 'cruxstore' ) => '_top',
                ),
                'description' => esc_html__( 'Select target link.', 'cruxstore' ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Image Overlay', 'cruxstore' ),
                'param_name' => 'image_overlay',
                'value' => array(
                    esc_html__( 'Default', 'cruxstore' ) => '',
                    esc_html__( 'Grayscale', 'cruxstore' ) => 'grayscale',
                    esc_html__( 'White', 'cruxstore' ) => 'white',
                    esc_html__( 'White Boxed', 'cruxstore' ) => 'white-boxed',
                ),
                'description' => esc_html__( 'Select image overlay for image.', 'cruxstore' ),
            ),

            // Data settings
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Data source", 'cruxstore'),
                "param_name" => "source",
                "value" => array(
                    esc_html__('All', 'cruxstore') => '',
                    esc_html__('Specific Categories', 'cruxstore') => 'categories',
                    esc_html__('Specific Client', 'cruxstore') => 'posts',
                ),
                "admin_label" => true,
                'std' => 'all',
                "description" => esc_html__("Select content type for your posts.", 'cruxstore'),
                'group' => esc_html__( 'Data settings', 'js_composer' ),
            ),
            array(
                "type" => "cruxstore_taxonomy",
                'taxonomy' => 'client-category',
                'heading' => esc_html__( 'Categories', 'cruxstore' ),
                'param_name' => 'categories',
                'placeholder' => esc_html__( 'Select your categories', 'cruxstore' ),
                "dependency" => array("element" => "source","value" => array('categories')),
                'multiple' => true,
                'group' => esc_html__( 'Data settings', 'js_composer' ),
                'select' => 'slug'
            ),
            array(
                "type" => "cruxstore_posts",
                'args' => array('post_type' => 'crux_client', 'posts_per_page' => -1),
                'heading' => esc_html__( 'Specific Client', 'js_composer' ),
                'param_name' => 'posts',
                'placeholder' => esc_html__( 'Select your posts', 'js_composer' ),
                "dependency" => array("element" => "source","value" => array('posts')),
                'multiple' => true,
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
                'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'js_composer' ),
                'group' => esc_html__( 'Data settings', 'js_composer' ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                "admin_label" => true,
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Meta key', 'js_composer' ),
                'param_name' => 'meta_key',
                'description' => esc_html__( 'Input meta key for grid ordering.', 'js_composer' ),
                'group' => esc_html__( 'Data settings', 'js_composer' ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
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
                'group' => esc_html__( 'Data settings', 'js_composer' ),
                'value' => array(
                    esc_html__( 'Descending', 'js_composer' ) => 'DESC',
                    esc_html__( 'Ascending', 'js_composer' ) => 'ASC',
                ),
                'param_holder_class' => 'vc_grid-data-type-not-ids',
                'description' => esc_html__( 'Select sorting order.', 'js_composer' ),
                "admin_label" => true,
            ),
            cruxstore_map_add_css_animation(),
            cruxstore_map_add_css_animation_delay(),
            array(
                "type" => "textfield",
                "heading" => esc_html__( "Extra class name", "js_composer"),
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