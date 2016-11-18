<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Testimonial_Carousel extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'layout' => '1',
            'testimonial_skin' => '',

            'font_container' => '',
            'use_theme_fonts' => 'yes',
            'google_fonts' => '',
            'font_container_company' => '',
            'use_theme_fonts_company' => 'yes',
            'google_fonts_company' => '',


            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'max_items' => 10,
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',

            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,


            'desktop' => 1,
            'desktopsmall' => 1,
            'tablet' => 1,
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
            'el_class' => '',
            'css' => '',
        ), $atts);
        $atts['carousel_skin'] = $atts['testimonial_skin'];


        extract($atts);

        $args = array(
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $max_items,
            'ignore_sticky_posts' => true,
            'post_type' => 'del_testimonial'
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
                            'taxonomy' => 'testimonial-category',
                            'field' => 'id',
                            'terms' => $categories_arr
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


        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'testimonial-carousel', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'layout' => 'testimonial-layout-'.$layout
        );

        $query = new WP_Query( $args );
        $output = '';

        if ( $query->have_posts() ) :

            $carousel_ouput = delphinus_render_carousel(apply_filters( 'delphinus_render_args', $atts));
            $carousel_html ='';

            while ( $query->have_posts() ) : $query->the_post();

                $testimonial_content = '<div class="testimonial-content">'.do_shortcode(get_the_content()).'</div>';
                $link = delphinus_meta('_del_testimonial_link');
                $title = get_the_title();

                if($layout == 4){
                    $title .= ', ';
                }
                if( $link ){
                    $title = '<a target="_blank" href="'.$link.'">'.$title.'</a>';
                }

                $testimonial_image = (has_post_thumbnail()) ? '<div class="testimonial-image">'.get_the_post_thumbnail(null, 'delphinus_small').'</div>' : '';

                $company = delphinus_meta('_del_testimonial_company');
                $testimonial_company = ($company) ? sprintf('<div class="testimonial-info">%s</div>', $company) : '';
                $testimonial_title = sprintf('<h4 class="testimonial-author">%s</h4>', $title);



                $testimonial_author = $testimonial_image.'<div class="testimonial-author-content">'.$testimonial_title.$testimonial_company.'</div>';
                $carousel_html .= sprintf('<div class="testimonial-item">%s <div class="testimonial-author-infos"> %s</div></div>', $testimonial_content, $testimonial_author );

            endwhile;
            wp_reset_postdata();

            $output .= str_replace('%carousel_html%', $carousel_html, $carousel_ouput);

        endif;

        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}

vc_map( array(
    "name" => esc_html__( "KT: Testimonial Carousel", 'delphinus'),
    "base" => "testimonial_carousel",
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    "wrapper_class" => "clearfix",
    "params" => array(
        array(
            'type' => 'hidden',
            'heading' => esc_html__( 'URL (Link)', 'js_composer' ),
            'param_name' => 'link',
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Testimonial skin', 'delphinus' ),
            'param_name' => 'testimonial_skin',
            'value' => array(
                esc_html__( 'Default', 'delphinus') => '',
                esc_html__( 'White', 'delphinus') => 'white',
            ),
            'desc' => esc_html__('Select your skin', 'delphinus')
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Layout', 'delphinus' ),
            'param_name' => 'layout',
            'value' => array(
                esc_html__( 'Layout 1', 'delphinus' ) => '1',
                esc_html__( 'Layout 2', 'delphinus' ) => '2',
                esc_html__( 'Layout 3', 'delphinus' ) => '3',
                esc_html__( 'Layout 4', 'delphinus' ) => '4',
            ),
            'description' => esc_html__( 'Select your layout.', 'delphinus' ),
            "admin_label" => true,
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
            "heading" => esc_html__("Data source", 'delphinus'),
            "param_name" => "source",
            "value" => array(
                esc_html__('All', 'delphinus') => '',
                esc_html__('Specific Categories', 'delphinus') => 'categories',
                esc_html__('Specific Posts', 'delphinus') => 'posts',
            ),
            "admin_label" => true,
            'std' => 'all',
            "description" => esc_html__("Select content type for your posts.", 'delphinus'),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "delphinus_taxonomy",
            'taxonomy' => 'testimonial-category',
            'heading' => esc_html__( 'Categories', 'delphinus' ),
            'param_name' => 'categories',
            'select' => 'id',
            'placeholder' => esc_html__( 'Select your categories', 'delphinus' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "delphinus_posts",
            'args' => array('post_type' => 'del_testimonial', 'posts_per_page' => -1),
            'heading' => esc_html__( 'Specific Posts', 'js_composer' ),
            'param_name' => 'posts',
            'size' => '5',
            'placeholder' => esc_html__( 'Select your posts', 'js_composer' ),
            "dependency" => array("element" => "source","value" => array('posts')),
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

        // Carousel settings
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
            "value" => 1,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'delphinus' )
        ),

        array(
            'type' => 'delphinus_number',
            'heading' => esc_html__( 'on Tablets Landscape', 'delphinus' ),
            'param_name' => 'desktopsmall',
            "value" => 1,
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
            "value" => 1,
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
            "description" => esc_html__("Select position for navigation.", 'delphinus'),
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
                esc_html__( 'Center Bottom', 'delphinus') => 'center-bottom',
                esc_html__( 'Center Top', 'delphinus') => 'center-top',
                esc_html__( 'Bottom Left', 'delphinus') => 'bottom-left',
            ),
            'std' => 'center-bottom',
            "dependency" => array("element" => "pagination","value" => array('true')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Pagination style', 'js_composer' ),
            'param_name' => 'pagination_style',
            'group' => esc_html__( 'Carousel', 'delphinus' ),
            'value' => array(
                esc_html__( 'Dot stroke', 'delphinus' ) => 'dot-stroke',
                esc_html__( 'Fade', 'delphinus' ) => 'fade',
                esc_html__( 'Fill up', 'delphinus' ) => 'fill-up',
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