<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if($copyright = delphinus_option('footer_copyright_text', '&copy; 2016 Delphinus')){
    printf('<div class="footer-copyright">%s</div>', do_shortcode($copyright));
}