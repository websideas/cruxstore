<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}

if(!function_exists('KT_register_menu_init')) {

    function KT_register_menu_init()
    {
        $labels = array(
            'name' => __('Mega Menu item', 'cruxstore_core'),
            'singular_name' => __('Mega Menu item', 'cruxstore_core'),
            'add_new' => __('Add New', 'cruxstore_core'),
            'all_items' => __('All Items', 'cruxstore_core'),
            'add_new_item' => __('Add New Menu item', 'cruxstore_core'),
            'edit_item' => __('Edit Menu item', 'cruxstore_core'),
            'new_item' => __('New Menu item', 'cruxstore_core'),
            'view_item' => __('View Menu item', 'cruxstore_core'),
            'search_items' => __('Search Menu item', 'cruxstore_core'),
            'not_found' => __('No Menu item found', 'cruxstore_core'),
            'not_found_in_trash' => __('No Menu item found in Trash', 'cruxstore_core'),
            'parent_item_colon' => __('Parent Menu item', 'cruxstore_core'),
            'menu_name' => __('Menu items', 'cruxstore_core')
        );
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'supports' 	=> array('title', 'editor'),
            'menu_icon' => 'dashicons-universal-access-alt',
            'exclude_from_search' => true,
        );
        register_post_type('kt_mgmenu', $args);

    }

}

add_action('init', 'KT_register_menu_init');