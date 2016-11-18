<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


?>
<div class="category-grid">
    <?php
    /**
     * woocommerce_before_subcategory_title hook.
     *
     * @hooked woocommerce_subcategory_thumbnail - 10
     */
    do_action( 'woocommerce_before_subcategory_title', $category );

    printf(
        '<div class="category-grid-content"><a href="%s" class="%s">%s</a></div>',
        get_term_link( $category->slug, 'product_cat' ),
        'btn btn-light',
        $category->name
    );
    ?>
</div>
