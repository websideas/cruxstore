<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

//  0 - unsorted and appended to bottom Default  
//  1 - Appended to top)

vc_add_params("vc_custom_heading", array(
    array(
        "type" => "delphinus_number",
        "heading" => esc_html__("Letter spacing", 'delphinus'),
        "param_name" => "letter_spacing",
        "min" => 0,
        "suffix" => "px",
        'group' => esc_html__( 'Extra', 'delphinus' )
    )
));



/*

vc_remove_element( 'vc_cta' );
vc_remove_element( 'vc_basic_grid' );
vc_remove_element( 'vc_media_grid' );
vc_remove_element( 'vc_masonry_grid' );
vc_remove_element( 'vc_masonry_media_grid' );
vc_remove_element( 'vc_element-description' );
vc_remove_element( 'vc_line_chart' );
vc_remove_element( 'vc_round_chart' );
vc_remove_element( 'vc_tta_pageable' );
vc_remove_element( 'vc_tta_tour' );
*/



vc_remove_element( 'vc_gallery' );
vc_remove_element( 'vc_accordion' );
vc_remove_element( 'vc_tabs' );
vc_remove_element( 'vc_tour' );
vc_remove_element( 'vc_button' );
vc_remove_element( 'vc_button2' );
vc_remove_element( 'vc_cta_button' );
vc_remove_element( 'vc_cta_button2' );
vc_remove_element( 'vc_images_carousel' );
vc_remove_element( 'vc_flickr' );
vc_remove_element( 'vc_posts_slider' );
vc_remove_element( 'vc_wp_search' );
vc_remove_element( 'vc_wp_recentcomments' );
vc_remove_element( 'vc_wp_calendar' );
vc_remove_element( 'vc_wp_pages' );
vc_remove_element( 'vc_wp_tagcloud' );
vc_remove_element( 'vc_wp_custommenu' );
vc_remove_element( 'vc_wp_text' );
vc_remove_element( 'vc_wp_posts' );
vc_remove_element( 'vc_wp_categories' );
vc_remove_element( 'vc_wp_archives' );
vc_remove_element( 'vc_wp_rss' );
vc_remove_element( 'vc_wp_meta' );



$visibilities_arr = array('vc_empty_space', 'delphinus_heading');
foreach($visibilities_arr as $item){
    vc_add_param($item, array(
        "type" => "dropdown",
        "heading" => esc_html__("Visibility", 'delphinus'),
        "param_name" => "visibility",
        "value" => array(
            esc_html__('Always Visible', 'delphinus') => '',
            esc_html__('Visible on Phones', 'delphinus') => 'visible-xs-block',
            esc_html__('Visible on Tablets', 'delphinus') => 'visible-sm-block',
            esc_html__('Visible on Desktops', 'delphinus') => 'visible-md-block',
            esc_html__('Visible on Desktops Large', 'delphinus') => 'visible-lg-block',
            esc_html__('Visible on Desktops and Desktops Large', 'delphinus') => 'visible-md-block visible-lg-block',

            esc_html__('Hidden on Phones', 'delphinus') => 'hidden-xs',
            esc_html__('Hidden on Tablets', 'delphinus') => 'hidden-sm',
            esc_html__('Hidden on Desktops', 'delphinus') => 'hidden-md',
            esc_html__('Hidden on Desktops Large', 'delphinus') => 'hidden-lg',
            esc_html__('Hidden on Desktops and Desktops Large', 'delphinus') => 'hidden-md hidden-lg',
        ),
        "admin_label" => true,
    ));
}

$background_arr = array('vc_row', 'vc_column');
foreach($background_arr as $item) {
    vc_add_param($item, array(
        "type" => "dropdown",
        "class" => "",
        "heading" => "Background position",
        "param_name" => "background_position",
        "value" => array(
            'None' => '',
            "Center Center" => "center center",
            "Center Top" => "center top",
            "Center Bottom" => "center bottom",
            "Center Right" => "center right",
            "Center Left" => "center left",
        ),
        'description' => esc_html__('Select background position', 'delphinus'),
        'group' => esc_html__( 'Extra settings', 'js_composer' ),
    ));
}


vc_add_param('vc_row', array(
    "type" => "dropdown",
    "heading" => esc_html__('Row Bullet Skin', 'delphinus'),
    "param_name" => "bullet_skin",
    "value" => array(
        esc_html__('Dark', 'delphinus') => 'dark',
        esc_html__('Light', 'delphinus') => 'light'
    ),
    'std' => 'dark',
    'description' => esc_html__('Select skin for bullet', 'delphinus'),
    'group' => esc_html__( 'Extra settings', 'js_composer' ),
));


$composer_addons = array(
    'heading.php',
    'icon_box.php',
    'brands.php',
    'testimonial_carousel.php',
    'blog_posts.php',
    'banner.php',
    'clients_carousel.php',
    'wrapper.php',
    'box_colored.php',
    'flip_box.php',
    'googlemap.php',
    'socials.php',
    'contact-form7.php',
    'message.php',
    'employees.php',
    'list.php',
    'mailchimp.php'
);

if(delphinus_is_wc()){
    $composer_wc_addons = array(
        'products_carousel.php',
        'product_categories_carousel.php',
        'product_categories_grid.php',
        'products.php',
        'products_tab.php',
        'products_mini.php',
        'products_sale_countdown.php',
        'vertical_menu.php'
    );
    $composer_addons = array_merge($composer_wc_addons, $composer_addons);
}





$list = array(
    'page',
    'product',
);
vc_set_default_editor_post_types( $list );


$settings = array (
    'weight' => '98',
);
vc_map_update( 'vc_custom_heading', $settings );


foreach ( $composer_addons as $addon ) {
    require_once DELPHINUS_FW_DIR . 'js_composer/vc_addons/' . $addon;
}
