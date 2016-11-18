<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_Blog_Posts_Carousel extends WPBakeryShortCode {

    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(

            'loop_stype' => 'grid',

            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'authors' => '',
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'max_items' => 10,
            "excerpt_length" => 20,

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

            'css' => '',
            'css_animation' => '',
            'el_class' => '',

        ), $atts );

        extract($atts);

        $output = '';

        $excerpt_length =  intval( $excerpt_length );
        $exl_function = create_function('$n', 'return '.$excerpt_length.';');
        add_filter( 'excerpt_length', $exl_function , 999 );

        $args = array(
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $max_items,
            'ignore_sticky_posts' => true,
        );

        if($orderby == 'meta_value' || $orderby == 'meta_value_num'){
            $args['meta_key'] = $meta_key;
        }

        if($source == 'categories'){
            if($categories){
                $categories_arr = array_filter(explode( ',', $categories));
                if(count($categories_arr)){
                    $args['category__in'] = $categories_arr;
                }
            }
        }elseif($source == 'posts'){
            if($posts){
                $posts_arr = array_filter(explode( ',', $posts));
                if(count($posts_arr)){
                    $args['post__in'] = $posts_arr;
                }
            }
        }elseif($source == 'authors'){
            if($authors){
                $authors_arr = array_filter(explode( ',', $authors));
                if(count($authors_arr)){
                    $args['author__in'] = $authors_arr;
                }
            }
        }

        ob_start();

        query_posts($args);
        if ( have_posts() ) :

            while ( have_posts() ) : the_post();
                get_template_part( 'templates/blog/carousel/content', $loop_stype );
            endwhile;

            echo "</div><!-- .blog-posts -->";

        endif;
        wp_reset_query();

        remove_filter('excerpt_length', $exl_function, 999 );

        $post_carousel_html = ob_get_clean();

        $elementClass = array(
            'base' => apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'blog-posts-carousel-wrapper ', $this->settings['base'], $atts),
            'extra' => $this->getExtraClass($el_class),
            'css_animation' => $this->getCSSAnimation($css_animation),
            'shortcode_custom' => vc_shortcode_custom_css_class($css, ' ')
        );
        $elementClass = preg_replace(array('/\s+/', '/^\s|\s$/'), array(' ', ''), implode(' ', $elementClass));

        $output = '';
        $carousel_ouput = cruxstore_render_carousel(apply_filters( 'cruxstore_render_args', $atts));
        $output .= str_replace('%carousel_html%', $post_carousel_html, $carousel_ouput);


        return '<div class="' . esc_attr($elementClass) . '"><div class="blog-posts blog-posts-carousel">' . $output . '</div></div>';

    }

}

// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Blog Posts Carousel", 'cruxstore'),
    "base" => "blog_posts_carousel",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array_merge(
        array(

            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Loop Style', 'cruxstore' ),
                'param_name' => 'loop_stype',
                'value' => array(
                    esc_html__( 'Style 1', 'js_composer' ) => 'grid',
                    esc_html__( 'Style 2', 'js_composer' ) => 'carousel',
                ),
                'std' => 'grid',
                'description' => '',
                'admin_label' => true,
            ),
            // Layout setting
            array(
                "type" => "cruxstore_heading",
                "heading" => esc_html__("Layout setting", 'cruxstore'),
                "param_name" => "layout_settings",
            ),

            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Excerpt length', 'js_composer' ),
                'value' => 15,
                'param_name' => 'excerpt_length',
            ),

            array(
                "type" => "cruxstore_heading",
                "heading" => esc_html__("Extra setting", 'cruxstore'),
                "param_name" => "extra_settings",
            ),

            vc_map_add_css_animation(),
            array(
                "type" => "textfield",
                "heading" => esc_html__( "Extra class name", "js_composer"),
                "param_name" => "el_class",
                "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
            ),
            // Data settings
            array(
                "type" => "dropdown",
                "heading" => esc_html__("Data source", 'cruxstore'),
                "param_name" => "source",
                "value" => array(
                    esc_html__('All', 'cruxstore') => '',
                    esc_html__('Specific Categories', 'cruxstore') => 'categories',
                    esc_html__('Specific Posts', 'cruxstore') => 'posts',
                    esc_html__('Specific Authors', 'cruxstore') => 'authors'
                ),
                "admin_label" => true,
                'std' => '',
                "description" => esc_html__("Select content type for your posts.", 'cruxstore'),
                'group' => esc_html__( 'Data settings', 'js_composer' ),
            ),
            array(
                "type" => "cruxstore_taxonomy",
                'taxonomy' => 'category',
                'heading' => esc_html__( 'Categories', 'cruxstore' ),
                'param_name' => 'categories',
                'placeholder' => esc_html__( 'Select your categories', 'cruxstore' ),
                "dependency" => array("element" => "source","value" => array('categories')),
                'multiple' => true,
                'select' => 'id',
                'group' => esc_html__( 'Data settings', 'js_composer' ),
            ),

            array(
                "type" => "cruxstore_posts",
                'args' => array('post_type' => 'post', 'posts_per_page' => -1),
                'heading' => esc_html__( 'Specific Posts', 'js_composer' ),
                'param_name' => 'posts',
                'size' => '5',
                'placeholder' => esc_html__( 'Select your posts', 'js_composer' ),
                "dependency" => array("element" => "source","value" => array('posts')),
                'multiple' => true,
                'group' => esc_html__( 'Data settings', 'js_composer' ),
            ),
            array(
                "type" => "cruxstore_authors",
                'post_type' => 'post',
                'heading' => esc_html__( 'Specific Authors', 'js_composer' ),
                'param_name' => 'authors',
                'size' => '5',
                'placeholder' => esc_html__( 'Select your authors', 'js_composer' ),
                "dependency" => array("element" => "source","value" => array('authors')),
                'multiple' => true,
                'group' => esc_html__( 'Data settings', 'js_composer' ),
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__( 'Total items', 'js_composer' ),
                'param_name' => 'max_items',
                'value' => 10, // default value
                'param_holder_class' => 'vc_not-for-custom',
                'description' => esc_html__( 'Set max limit for items in grid or enter -1 to display all (limited to 1000).', 'js_composer' ),
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
        ),
        cruxstore_map_add_carousel_parrams(),
        array(
            array(
                'type' => 'css_editor',
                'heading' => esc_html__( 'Css', 'js_composer' ),
                'param_name' => 'css',
                // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
                'group' => esc_html__( 'Design options', 'js_composer' )
            )
        )
    ),
));


