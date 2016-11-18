<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$footer_socials = delphinus_option('footer_socials', 'facebook,twitter,instagram,linkedin');
$footer_socials_style = delphinus_option('footer_socials_style');
$footer_socials_background = delphinus_option('footer_socials_background');
$footer_socials_size = delphinus_option('footer_socials_size');
$footer_socials_space_between_item = delphinus_option('footer_socials_space_between_item');
$footer_custom_color = delphinus_option( 'custom_color_social' );



echo do_shortcode('[socials social="'.$footer_socials.'" space_between_item="'.$footer_socials_space_between_item.'" size="'.$footer_socials_size.'" style="'.$footer_socials_style.'" custom_color="'.$footer_custom_color.'" background_style="'.$footer_socials_background.'"]');