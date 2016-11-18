<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$footer_socials = cruxstore_option('footer_socials', 'facebook,twitter,instagram,linkedin');
$footer_socials_style = cruxstore_option('footer_socials_style');
$footer_socials_background = cruxstore_option('footer_socials_background');
$footer_socials_size = cruxstore_option('footer_socials_size');
$footer_socials_space_between_item = cruxstore_option('footer_socials_space_between_item');
$footer_custom_color = cruxstore_option( 'custom_color_social' );



echo do_shortcode('[socials social="'.$footer_socials.'" space_between_item="'.$footer_socials_space_between_item.'" size="'.$footer_socials_size.'" style="'.$footer_socials_style.'" custom_color="'.$footer_custom_color.'" background_style="'.$footer_socials_background.'"]');