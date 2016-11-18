<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}

add_action( 'init', 'cruxstore_register_client_init' );
function cruxstore_register_client_init(){
    $labels = array( 
        'name' => __( 'Client', 'cruxstore_core'),
        'singular_name' => __( 'Client', 'cruxstore_core'),
        'add_new' => __( 'Add New', 'cruxstore_core'),
        'all_items' => __( 'All Clients', 'cruxstore_core'),
        'add_new_item' => __( 'Add New Client', 'cruxstore_core'),
        'edit_item' => __( 'Edit Client', 'cruxstore_core'),
        'new_item' => __( 'New Client', 'cruxstore_core'),
        'view_item' => __( 'View Client', 'cruxstore_core'),
        'search_items' => __( 'Search Client', 'cruxstore_core'),
        'not_found' => __( 'No Client found', 'cruxstore_core'),
        'not_found_in_trash' => __( 'No Client found in Trash', 'cruxstore_core'),
        'parent_item_colon' => __( 'Parent Client', 'cruxstore_core'),
        'menu_name' => __( 'Clients', 'cruxstore_core')
    );
    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'supports' 	=> array('title', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-universal-access-alt',
    );
    register_post_type( 'crux_client', $args );
    
    register_taxonomy('client-category',array('crux_client'), array(
        "label" 						=> __("Client Categories", 'cruxstore_core'),
        "singular_label" 				=> __("Client Category", 'cruxstore_core'),
        'public'                        => false,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => false,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite'                       => false,
        'query_var'                     => true,
        'show_admin_column'             => true
    ));
}


add_action( 'init', 'cruxstore_register_testimonial_init' );
function cruxstore_register_testimonial_init(){
    $labels = array(
        'name' => __( 'Testimonial', 'cruxstore_core'),
        'singular_name' => __( 'Testimonial', 'cruxstore_core'),
        'add_new' => __( 'Add New', 'cruxstore_core'),
        'all_items' => __( 'Testimonials', 'cruxstore_core'),
        'add_new_item' => __( 'Add New testimonial', 'cruxstore_core'),
        'edit_item' => __( 'Edit testimonial', 'cruxstore_core'),
        'new_item' => __( 'New testimonial', 'cruxstore_core'),
        'view_item' => __( 'View testimonial', 'cruxstore_core'),
        'search_items' => __( 'Search testimonial', 'cruxstore_core'),
        'not_found' => __( 'No testimonial found', 'cruxstore_core'),
        'not_found_in_trash' => __( 'No testimonial found in Trash', 'cruxstore_core'),
        'parent_item_colon' => __( 'Parent testimonial', 'cruxstore_core'),
        'menu_name' => __( 'Testimonials', 'cruxstore_core')
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'menu_icon' => 'dashicons-format-chat',
        'supports' 	=> array('title', 'editor', 'thumbnail', 'page-attributes'),
    );
    register_post_type( 'crux_testimonial', $args );
    
    register_taxonomy('testimonial-category',array('crux_testimonial'), array(
        "label" 						=> __("Testimonial Categories", 'cruxstore_core'), 
        "singular_label" 				=> __("Testimonial Category", 'cruxstore_core'), 
        'public'                        => false,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => false,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite'                       => false,
        'query_var'                     => true,
        'show_admin_column'             => true
    ));
}

add_action( 'init', 'cruxstore_register_employees_init' );
function cruxstore_register_employees_init(){
    $labels = array(
        'name' => __( 'Employees', 'cruxstore_core'),
        'singular_name' => __( 'Employees', 'cruxstore_core'),
        'add_new' => __( 'Add New', 'cruxstore_core'),
        'all_items' => __( 'All Employees', 'cruxstore_core'),
        'add_new_item' => __( 'Add New Employees', 'cruxstore_core'),
        'edit_item' => __( 'Edit employees', 'cruxstore_core'),
        'new_item' => __( 'New employees', 'cruxstore_core'),
        'view_item' => __( 'View employees', 'cruxstore_core'),
        'search_items' => __( 'Search employees', 'cruxstore_core'),
        'not_found' => __( 'No employees found', 'cruxstore_core'),
        'not_found_in_trash' => __( 'No employees found in Trash', 'cruxstore_core'),
        'parent_item_colon' => __( 'Parent employees', 'cruxstore_core'),
        'menu_name' => __( 'Employees', 'cruxstore_core')
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'supports' 	=> array('title', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-groups'
    );
    register_post_type( 'crux_employees', $args );

    register_taxonomy('employees-category',array('crux_employees'), array(
        "label" 						=> __("Employees Categories", 'cruxstore_core'),
        "singular_label" 				=> __("Employees Category", 'cruxstore_core'),
        'public'                        => false,
        'hierarchical'                  => true,
        'show_ui'                       => true,
        'show_in_nav_menus'             => false,
        'args'                          => array( 'orderby' => 'term_order' ),
        'rewrite'                       => false,
        'query_var'                     => true,
        'show_admin_column'             => true
    ));
}
