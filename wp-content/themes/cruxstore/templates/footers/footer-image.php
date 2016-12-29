<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if($image = cruxstore_option('footer_copyright_image')){
    if(is_array($image) && $image['url'] != '' ){
        printf('<div class="footer-copyright-image"><img src="%s" class="img-reponsive" alt=""/></div>', $image['url']);
    }
}