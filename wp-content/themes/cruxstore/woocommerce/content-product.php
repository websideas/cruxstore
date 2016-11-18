<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
	$woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) ) {
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$woocommerce_loop['loop']++;



if ( empty( $woocommerce_loop['type'] ) ) {
    $woocommerce_loop['type'] = 'classic';
}

if($woocommerce_loop['type'] == 'classic'){
    $default = cruxstore_option('shop_products_effect', '1');
    if ( empty( $woocommerce_loop['effect'] ) ) {
        $woocommerce_loop['effect'] = $default;
    }elseif( $woocommerce_loop['effect'] == 'default'){
        $woocommerce_loop['effect'] = $default;
    }
}else{
    $woocommerce_loop['effect'] = '';
}

// Extra post classes
$classes = array(
    'product',
    'product-type-'.$woocommerce_loop['type']
);

if($woocommerce_loop['effect']){
    $classes[] = 'product-effect-'.$woocommerce_loop['effect'];
}



if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
    $classes[] = 'first';
}
if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
    $classes[] = 'last';
}

$columns_land = ($woocommerce_loop['columns'] == 4) ? 3 : $woocommerce_loop['columns'];
$columns_tab = 2;


if($woocommerce_loop['type'] == 'masonry'){
    $box_size = get_post_meta($product->id, '_cruxstore_box_size', true);
    if($box_size == 'wide' || $box_size == 'landscape'){
        $bootstrapColumn = '6';
    }elseif($box_size == 'big'){
        $bootstrapColumn = '9';
    }else{
        $bootstrapColumn = '3';
    }
    $bootstrapColumn_land = $bootstrapColumn;
}else{
    $bootstrapColumn = round( 12 / $woocommerce_loop['columns'] );
    $bootstrapColumn_land = round( 12 / $columns_land );
}

$bootstrapColumn_tab = round( 12 / $columns_tab );


$classes[] = sprintf('col-lg-%1$s col-md-%1$s col-sm-%2$s col-xs-%3$s', $bootstrapColumn,$bootstrapColumn_land, $bootstrapColumn_tab);

?>
<li <?php post_class( $classes ); ?> data-columns="<?php echo esc_attr($woocommerce_loop['columns']); ?>" data-effect="<?php echo esc_attr($woocommerce_loop['effect']); ?>">
    <div class="product-inner">
        <div class="product-content">
            <?php

            /**
             * woocommerce_before_shop_loop_item hook.
             *
             * @hooked woocommerce_template_loop_product_link_open - 10
             */
            do_action( 'woocommerce_before_shop_loop_item' );

            /**
             * woocommerce_before_shop_loop_item_title hook.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );

            /**
             * woocommerce_after_shop_loop_item hook.
             *
             * @hooked woocommerce_template_loop_product_link_close - 5
             * @hooked woocommerce_template_loop_add_to_cart - 10
             */
            do_action( 'woocommerce_after_shop_loop_item' );


            if($woocommerce_loop['type'] == 'classic' || $woocommerce_loop['type'] == 'normal'){
                do_action( 'woocommerce_shop_loop_item_content' );
            }

            ?>
        </div>
        <div class="product-details">
            <?php
            /**
             * woocommerce_shop_loop_item_title hook.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             */
            do_action( 'woocommerce_shop_loop_item_title' );

            /**
             * woocommerce_after_shop_loop_item_title hook.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );


            if($woocommerce_loop['type'] == 'classic'){
                echo '<div class="product-details-action">';
                do_action( 'woocommerce_shop_loop_item_details' );
                echo '</div>';
            }

            ?>
        </div>
    </div>
</li>