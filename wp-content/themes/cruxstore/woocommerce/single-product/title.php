<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

echo '<div class="product-title-wrap">';

the_title( '<h1 itemprop="name" class="product_title entry-title">', '</h1>' );

$has_cat = get_the_terms( $product->id, 'product_cat' );
if ($has_cat != 0) { ?>
    <div class="product-navigation">
        <div class="nav-previous">
            <?php
            $icon = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
            $prev = get_previous_post_link( '%link', $icon, true, '', 'product_cat' );
            echo ($prev) ? $prev : sprintf('<span>%s</span>', $icon);
            ?>
        </div>
        <div class="nav-next">
            <?php
            $icon = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
            $next = get_next_post_link( '%link', $icon, true, '', 'product_cat' );
            echo ($next) ? $next : sprintf('<span>%s</span>', $icon);
            ?>
        </div>
    </div>
<?php }

echo '</div>';