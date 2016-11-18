<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="category-masonry-inner">
    <?php
    /**
     * woocommerce_before_subcategory_title hook.
     *
     * @hooked woocommerce_subcategory_thumbnail - 10
     */
    do_action( 'woocommerce_before_subcategory_title', $category );

    printf(
        '<div class="category-masonry-content"><a href="%s" class="%s">%s %s</a></div>',
        get_term_link( $category->slug, 'product_cat' ),
        'btn btn-light',
        esc_html__('Shop', 'delphinus'),
        $category->name
    );
    ?>
</div>
