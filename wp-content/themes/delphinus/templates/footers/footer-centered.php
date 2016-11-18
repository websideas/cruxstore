<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


$footer_left = delphinus_option('footer_copyright_left');
$footer_right = delphinus_option('footer_copyright_right', 'copyright');

if(!$footer_left && !$footer_right) return;

?>
<div class="footer-centered">
    <?php get_template_part( 'templates/footers/footer', $footer_left ); ?>
    <?php get_template_part( 'templates/footers/footer', $footer_right ); ?>
</div>