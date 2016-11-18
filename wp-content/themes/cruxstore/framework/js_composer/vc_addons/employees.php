<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-custom-heading.php' );
class WPBakeryShortCode_Employees extends WPBakeryShortCode_VC_Custom_heading {
    protected function content($atts, $content = null) {

        $atts = shortcode_atts(array(

            'title' => '',
            'layout' => 'square',
            'columns_gap' => '15',
            'employees_columns' => 3,
            'employees_columns_tab' => 2,

            'source' => 'all',
            'categories' => '',
            'posts' => '',
            'max_items' => 10,
            'orderby' => 'date',
            'meta_key' => '',
            'order' => 'DESC',

            'css_animation' => '',
            'el_class' => '',
            'css'      => '',
        ), $atts);

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'employees-wrapper', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'css_animation' => $this->getCSSAnimation( $css_animation ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'layout' => 'layout-'.$layout,
        );

        $output = '';

        $args = array(
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $max_items,
            'ignore_sticky_posts' => true,
            'post_type' => 'crux_employees'
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
                            'taxonomy' => 'employees-category',
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

        $query = new WP_Query( $args );
        if ( $query->have_posts() ) :

            $article_columns = 12/$employees_columns;
            $article_columns_tab = 12/$employees_columns_tab;

            while ( $query->have_posts() ) : $query->the_post();

                $title = sprintf('<h4 class="employees-author">%s</h4>', get_the_title());
                $subtitle = cruxstore_meta('_cruxstore_employee_position');
                $subtitle = ($subtitle) ? sprintf('<div class="employees-info">%s</div>', $subtitle) : '';

                $socials = '';

                if($email = cruxstore_meta('_cruxstore_employee_email')){
                    $socials .= sprintf('<a href="%s" target="_blank">%s</a>', 'mailto:'.$email, '<i class="fa fa-envelope-o" aria-hidden="true"></i>');
                }
                if($facebook = cruxstore_meta('_cruxstore_employee_facebook')){
                    $socials .= sprintf('<a href="%s" target="_blank">%s</a>', $facebook, '<i class="fa fa-facebook" aria-hidden="true"></i>');
                }
                if($twitter = cruxstore_meta('_cruxstore_employee_twitter')){
                    $socials .= sprintf('<a href="%s" target="_blank">%s</a>', $twitter, '<i class="fa fa-twitter" aria-hidden="true"></i>');
                }
                if($googleplus = cruxstore_meta('_cruxstore_employee_googleplus')){
                    $socials .= sprintf('<a href="%s" target="_blank">%s</a>', $googleplus, '<i class="fa fa-google-plus" aria-hidden="true"></i>');
                }
                if($linkedin = cruxstore_meta('_cruxstore_employee_linkedin')){
                    $socials .= sprintf('<a href="%s" target="_blank">%s</a>', $linkedin, '<i class="fa fa-linkedin" aria-hidden="true"></i>');
                }
                if($instagram = cruxstore_meta('_cruxstore_employee_instagram')){
                    $socials .= sprintf('<a href="%s" target="_blank">%s</a>', $instagram, '<i class="fa fa-instagram" aria-hidden="true"></i>');
                }
                if($socials){
                    $socials = '<div class="employees-socials">'.$socials.'</div>';
                }

                $image = get_the_post_thumbnail(get_the_ID(), 'cruxstore_square');
                if(!$image){
                    $image = '<img class="vc_img-placeholder img-responsive" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
                }

                if($layout == 'gallery'){
                    $image = '<div class="employees-img">'.$image.' </div>';
                    $employees = sprintf('%s <div class="employees-infos">%s %s</div>', $image, $title, $subtitle.$socials );
                }else{
                    $image = '<div class="employees-img">'.$image.$socials.' </div>';
                    $employees = sprintf('%s <div class="employees-infos">%s %s</div>', $image, $title, $subtitle );
                }

                $output .= sprintf('<div class="employees-content col-lg-%1$s col-md-%1$s col-sm-%2$s col-xs-%2$s"><div class="employees-inner">%3$s</div></div>', $article_columns, $article_columns_tab, $employees);

            endwhile;
            wp_reset_postdata();

        endif;

        $rowclass = 'row multi-columns-row';
        if($columns_gap == '' || $columns_gap == '0'){
            $rowclass .= ' no-gutters';
        }
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return '<div class="'.esc_attr( $elementClass ).'"><div class="'.$rowclass.'">'.$output.'</div></div>';

    }
}



// Add your Visual Composer logic here
vc_map( array(
    "name" => esc_html__( "KT: Employees", 'cruxstore'),
    "base" => "employees",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "description" => esc_html__( "", 'cruxstore'),

    "params" => array(
        array(
            "type" => "textfield",
            'heading' => esc_html__( 'Title', 'js_composer' ),
            'param_name' => 'title',
            'value' => '',
            "admin_label" => true,
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Layout", 'cruxstore'),
            "param_name" => "layout",
            "value" => array(
                esc_html__('Square', 'cruxstore') => 'square',
                esc_html__('Circle', 'cruxstore') => 'circle',
                esc_html__('Gallery', 'cruxstore') => 'gallery',
            ),
            "admin_label" => true,
            'std' => 'square',
            "description" => esc_html__("Select your layout.", 'cruxstore'),
        ),
        array(
            "type" => "dropdown",
            "heading" => esc_html__("Columns gap", 'cruxstore'),
            "param_name" => "columns_gap",
            "value" => array(
                esc_html__('0px', 'cruxstore') => '0',
                esc_html__('15px', 'cruxstore') => '15',
            ),
            'std' => '15',
            "description" => esc_html__(" Select gap between columns in row.", 'cruxstore'),
        ),



        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Columns to Show?", 'cruxstore'),
            "edit_field_class" => "cruxstore_sub_heading vc_column",
            "param_name" => "items_show",
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Desktop', 'cruxstore' ),
            'param_name' => 'employees_columns',
            'value' => array(
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
                esc_html__( '6 columns', 'js_composer' ) => '6',
            ),
            'std' => '3',
            "admin_label" => true,
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'on Tablet', 'cruxstore' ),
            'param_name' => 'employees_columns_tab',
            'value' => array(
                esc_html__( '2 columns', 'js_composer' ) => '2',
                esc_html__( '3 columns', 'js_composer' ) => '3',
                esc_html__( '4 columns', 'js_composer' ) => '4',
            ),
            'std' => '2',
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
            "type" => "dropdown",
            "heading" => esc_html__("Data source", 'cruxstore'),
            "param_name" => "source",
            "value" => array(
                esc_html__('All', 'cruxstore') => '',
                esc_html__('Specific Categories', 'cruxstore') => 'categories',
                esc_html__('Specific Posts', 'cruxstore') => 'posts',
            ),
            "admin_label" => true,
            'std' => 'all',
            "description" => esc_html__("Select content type for your posts.", 'cruxstore'),
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "cruxstore_taxonomy",
            'taxonomy' => 'employees-category',
            'heading' => esc_html__( 'Categories', 'cruxstore' ),
            'param_name' => 'categories',
            'select' => 'id',
            'placeholder' => esc_html__( 'Select your categories', 'cruxstore' ),
            "dependency" => array("element" => "source","value" => array('categories')),
            'multiple' => true,
            'group' => esc_html__( 'Data settings', 'js_composer' ),
        ),
        array(
            "type" => "cruxstore_posts",
            'args' => array('post_type' => 'crux_employees', 'posts_per_page' => -1),
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

        array(
            'type' => 'css_editor',
            'heading' => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' )
        )
    ),
));