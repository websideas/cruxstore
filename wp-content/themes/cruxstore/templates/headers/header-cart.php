<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if ( cruxstore_is_wc() && cruxstore_option('header_cart', 1) && !cruxstore_option('catalog_mode', 0)  ) { ?>
    <li class="shopping-bag shopping-bag-cart">
        <?php cruxstore_woocommerce_get_cart(); ?>
    </li>
<?php } ?>