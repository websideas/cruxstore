<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if(!cruxstore_option('header_search', 1)) return;

?>
<li class="search-action">
    <a href="#search-fullwidth" class="search-item">
        <i class="fa fa-search" aria-hidden="true"></i>
        <span class="text"><?php echo esc_html__('Search', 'cruxstore'); ?></span>
    </a>
</li>
