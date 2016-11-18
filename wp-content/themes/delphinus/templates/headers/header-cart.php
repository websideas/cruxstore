<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if ( delphinus_is_wc() && delphinus_option('header_cart', 1) && !delphinus_option('catalog_mode', 0)  ) { ?>
    <li class="shopping-bag shopping-bag-cart">
        <?php delphinus_woocommerce_get_cart(); ?>
    </li>
<?php } ?>