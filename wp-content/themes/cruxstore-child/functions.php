<?php
function cruxstore_child_scripts() {
    wp_enqueue_style( 'cruxstore-child', get_stylesheet_directory_uri() . '/style.css' );
}
add_action('wp_enqueue_scripts', 'cruxstore_child_scripts', 99);

