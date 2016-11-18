<?php if ( cruxstore_is_wc() && cruxstore_option('header_wishlist', 1) && defined( 'YITH_WCWL' ) && !cruxstore_option('catalog_mode', 0) ) { ?>
    <li class="shopping-bag shopping-bag-wishlist">
        <?php cruxstore_woocommerce_get_wishlist(); ?>
    </li>
<?php } ?>