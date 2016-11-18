<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if($copyright = cruxstore_option('footer_copyright_text', '&copy; 2016 CruxStore')){
    printf('<div class="footer-copyright">%s</div>', do_shortcode($copyright));
}