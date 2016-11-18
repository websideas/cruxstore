<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_List_Blog_Posts extends WPBakeryShortCode {

    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'readmore' => '',
            'blog_pagination' => 'none',
            'blog_type' => 'grid',
            'blog_columns' => 3,
            'blog_columns_tab' => 2,
            'show_excerpt' => 'true',

            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'authors' => '',
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',
            'max_items' => 10,
            "excerpt_length" => 20,

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
            'ignore_sticky_posts' => true
        );

        if($blog_pagination != 'none'){
            if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
            elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
            else { $paged = 1; }

            $args['paged'] = $paged;
        }

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

        $layout = array('type' => $blog_type, 'columns'=> $blog_columns, 'columns_tab' => $blog_columns_tab, 'pagination' => $blog_pagination);
        ob_start();

        query_posts($args);
        if ( have_posts() ) :

            if($blog_type == 'classic'){
                $readmore = '';
            }

            $readmore_class = (!$readmore || $readmore == 'none' ) ? ' no-readmore' : '';


            echo '<div class="blog-posts blog-posts-'.esc_attr($layout['type']).$readmore_class.'">';

            if($layout['type'] == 'grid'|| $layout['type'] == 'masonry'){
                echo '<div class="row multi-columns-row">';
                $article_columns = 12/$layout['columns'];
                $article_columns_tab = 12/$layout['columns_tab'];
            }
            if($layout['type'] == 'masonry') {
                printf('<div class="blog-post-sizer col-lg-%1$s col-md-%1$s col-sm-%2$s col-xs-%2$s"></div>', $article_columns, $article_columns_tab);
            }


            $i = 1;
            while ( have_posts() ) : the_post();
                /* Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */

                $format = get_post_format();

                if($layout['type'] == 'grid' || $layout['type'] == 'masonry') {
                    printf('<div class="blog-post-wrap col-lg-%1$s col-md-%1$s col-sm-%2$s col-xs-%2$s">', $article_columns, $article_columns_tab);
                }

                if($layout['type'] == 'zigzag'){
                    $format = ($i % 2 )? '' : 'even';
                }
                get_template_part( 'templates/blog/'.$layout['type'].'/content', $format );

                if($layout['type'] == 'grid' || $layout['type'] == 'masonry') {
                    echo '</div>';
                }
                $i++;
            endwhile;


            if($layout['type'] == 'grid' || $layout['type'] == 'masonry') {
                echo "</div><!-- .row -->";
            }
            echo "</div><!-- .blog-posts -->";


            /**
             * @hooked cruxstore_paging_nav
             */

            do_action( 'cruxstore_loop_after',  $layout);

        endif;
        wp_reset_query();

        remove_filter('excerpt_length', $exl_function, 999 );

        $output .= ob_get_clean();

        $elementClass = array(
            'base' => apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'blog-posts-wrapper ', $this->settings['base'], $atts),
            'extra' => $this->getExtraClass($el_class),
            'css_animation' => cruxstore_getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class($css, ' ')
        );
        $elementClass = preg_replace(array('/\s+/', '/^\s|\s$/'), array(' ', ''), implode(' ', $elementClass));

        return '<div class="' . esc_attr($elementClass) . '">' . $output . '</div>';

    }

}

// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Blog Posts", 'cruxstore'),
    "base" => "list_blog_posts",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => esc_html__( "Display blog posts", 'cruxstore'),
    "params" => array(
        // Layout setting
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Layout setting", 'cruxstore'),
            "param_name" => "layout_settings",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Loop Style', 'cruxstore' ),
            'param_name' => 'blog_type',
            'value' => array(
                esc_html__( 'Standard', 'js_composer' ) => 'classic',
                esc_html__( 'Grid', 'js_composer' ) => 'grid',
                esc_html__( 'Masonry', 'js_composer' ) => 'masonry',
                esc_html__( 'Widget', 'js_composer' ) => 'widget',
                esc_html__( 'Medium', 'js_composer' ) => 'medium',
            ),
            'std' => 'grid',
            'description' => '',
            'admin_label' => true,
        ),

        array(
            'type' => 'textfield',
            'heading' => esc_html__( 'Excerpt length', 'js_composer' ),
            'value' => 20,
            'param_name' => 'excerpt_length',
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Columns to Show?", 'cruxstore'),
            "edit_field_class" => "cruxstore_sub_heading vc_column",
            "param_name" => "items_show",
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Desktop', 'cruxstore' ),
            'param_name' => 'blog_columns',
            'value' => array(
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
            ),
            'std' => '3',
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Tablet', 'cruxstore' ),
            'param_name' => 'blog_columns_tab',
            'value' => array(
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
            ),
            'std' => '2',
            'dependency' => array(
                'element' => 'blog_type',
                'value' => array( 'grid', 'masonry' )
            ),
        ),

        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Extra setting", 'cruxstore'),
            "param_name" => "extra_settings",
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Readmore button', 'cruxstore' ),
            'param_name' => 'readmore',
            'value' => array(
                esc_html__('None', 'cruxstore') => '',
                esc_html__( 'Link', 'cruxstore' ) => 'link',
            ),
            "description" => esc_html__("Show or hide the readmore button.", 'cruxstore'),
            'dependency' => array(
                'element' => 'blog_type',
                'value_not_equal_to' => array( 'classic', 'widget')
            ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation type', 'js_composer' ),
            'param_name' => 'blog_pagination',
            'admin_label' => true,
            'value' => array(
                esc_html__( 'None', 'cruxstore' ) => 'none',
                esc_html__( 'Classic pagination', 'cruxstore' ) => 'classic',
                esc_html__( 'Load More button', 'cruxstore' ) => 'loadmore',
                esc_html__( 'Normal pagination', 'cruxstore' ) => 'normal',
            ),
            'description' => esc_html__( 'Select the navigation type', 'js_composer' ),
            'dependency' => array(
                'element' => 'blog_type',
                'value_not_equal_to' => array( 'widget')
            ),
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

        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'Css', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
            'group' => esc_html__( 'Design options', 'js_composer' )
        ),
    ),
));


