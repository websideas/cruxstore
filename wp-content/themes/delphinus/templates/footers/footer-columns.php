<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


$footer_left = delphinus_option('footer_copyright_left');
$footer_center = delphinus_option('footer_copyright_center');
$footer_right = delphinus_option('footer_copyright_right', 'copyright');

if(!$footer_left && !$footer_right && !$footer_center) return;

?>
<div class="row">
    <div class="col-md-4 footer-left">
        <?php get_template_part( 'templates/footers/footer', $footer_left ); ?>
    </div>
    <div class="col-md-4 footer-center">
        <?php get_template_part( 'templates/footers/footer', $footer_center ); ?>
    </div>
    <div class="col-md-4 footer-right">
        <?php get_template_part( 'templates/footers/footer', $footer_right ); ?>
    </div>
</div>