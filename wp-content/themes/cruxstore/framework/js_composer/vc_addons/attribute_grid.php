<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


class WPBakeryShortCode_Attribute_Grid extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        $atts = shortcode_atts( array(
            'per_page' => '12',
            'orderby' => 'name',
            'order' => 'ASC',
            'attribute' => '',
            'hide_empty' => 'true',

            'css_animation' => '',
            'animation_delay' => '',
            'el_class' => '',
            'css' => '',
        ), $atts );

        $atts['hide_empty'] = apply_filters('sanitize_boolean', $atts['hide_empty']);

        extract($atts);

        $elementClass = array(
            'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'attribute-gird', $this->settings['base'], $atts ),
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
        );

        $hide_empty = ( $atts['hide_empty'] == true || $atts['hide_empty'] == 1 ) ? 1 : 0;

        $output = '';

        $args = array(
            'orderby'    => $orderby,
            'order' => $order,
            'hide_empty' => $hide_empty,
            'number' => $per_page
        );

        $all_terms = get_terms( $attribute, apply_filters( 'woocommerce_product_attribute_terms', $args ) );

        if(is_wp_error($all_terms))
            return;




        $small_thumbnail_size = apply_filters('cruxstore_attribute_grid_size', 'cruxstore_product');
        $dimensions = wc_get_image_size($small_thumbnail_size);

        $output .= '<div class="row multi-columns-row">';

        foreach ($all_terms as $term){
            $thumbnail_id = get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true );

            if ($thumbnail_id) {
                $image = wp_get_attachment_image_src($thumbnail_id, $small_thumbnail_size);
                $image = $image[0];
            } else {
                $image = wc_placeholder_img_src();
            }

            if($animation_delay){
                $animation_delay_item = sprintf(' data-wow-delay="%sms"', $key * $animation_delay);
            }else{
                $animation_delay_item = '';
            }

            $output .= sprintf('<div class="brand-banner-wrap col-lg-%1$s col-md-%1$s col-sm-%2$s col-xs-%3$s">', 2, 3, 6);

            $output .= '<div class="brand-banner'.$css_animation.'"'.$animation_delay_item.'><div class="brand-banner-content">';

            if ($image) {
                // Prevent esc_url from breaking spaces in urls for image embeds
                // Ref: http://core.trac.wordpress.org/ticket/23605
                $image = str_replace(' ', '%20', $image);

                $output .=  '<div class="brand-image"><img src="' . esc_url($image) . '" alt="' . esc_attr($term->name) . '" width="' . esc_attr($dimensions['width']) . '" height="' . esc_attr($dimensions['height']) . '" /></div>';
            }

            $output .= sprintf('<div class="brand-count"><span>%s</span> %s</a></div>',  $term->count, esc_html__('Products', 'cruxstore'));
            $output .= sprintf('<a href="%s">%s</a>', get_term_link( $term ), $term->name);
            $output .= '</div></div>';
            $output .= '</div>';

        }

        $output .= "</div><!-- .row -->";


        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
        return $output = '<div class="'.esc_attr( $elementClass ).'">'.$output.'</div>';

    }
}

$attributes_tax = wc_get_attribute_taxonomies();
$attributes = array();
foreach ( $attributes_tax as $attribute ) {
    $attributes[ $attribute->attribute_label ] = wc_attribute_taxonomy_name( $attribute->attribute_name );
}

vc_map( array(
    "name" => esc_html__( "KT: Attribute Grid", 'cruxstore'),
    "base" => "attribute_grid",
    "category" => esc_html__('by Kite-Themes', 'cruxstore' ),
    "params" => array(

        array(
            'type' => 'dropdown',
            'heading' => __( 'Attribute', 'js_composer' ),
            'param_name' => 'attribute',
            'value' => $attributes,
            'save_always' => true,
            'description' => __( 'List of product taxonomy attribute', 'js_composer' ),
        ),
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
            'type' => 'textfield',
            'heading' => __( 'Per page', 'js_composer' ),
            'value' => 12,
            'param_name' => 'per_page',
            'save_always' => true,
            'description' => __( 'How much items per page to show', 'js_composer' ),
        ),
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Hide empty', 'cruxstore' ),
            'param_name' => 'hide_empty',
            'value' => 'true',
            "description" => esc_html__("Hide brand if empty.", 'cruxstore'),
        ),
        cruxstore_map_add_css_animation(),
        cruxstore_map_add_css_animation_delay(),
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
