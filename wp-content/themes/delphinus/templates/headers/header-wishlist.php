<?php if ( delphinus_is_wc() && delphinus_option('header_wishlist', 1) && defined( 'YITH_WCWL' ) && !delphinus_option('catalog_mode', 0) ) { ?>
    <li class="shopping-bag shopping-bag-wishlist">
        <?php delphinus_woocommerce_get_wishlist(); ?>
    </li>
<?php } ?>