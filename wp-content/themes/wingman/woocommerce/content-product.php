<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
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

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns_tablet'] ) )
    $woocommerce_loop['columns_tablet'] = apply_filters( 'loop_shop_columns_tablet', 2 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
    return;
}

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array( 'product wow fadeInUp' );

// Bootstrap Column
$bootstrapColumn = round( 12 / $woocommerce_loop['columns'] );
$bootstrapTabletColumn = round( 12 / $woocommerce_loop['columns_tablet'] );
$classes[] = 'col-xs-'. $bootstrapTabletColumn .' col-sm-'. $bootstrapTabletColumn .' col-md-' . $bootstrapColumn.' col-lg-' . $bootstrapColumn;


?>
<li <?php post_class( $classes ); ?>>
    <div class="product-item-container clearfix">

        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

        <div class="product-image-container">
            <div class="product-image-content">

                <?php
                /**
                 * woocommerce_shop_loop_item_before_image hook
                 *
                 * @hooked woocommerce_show_product_loop_new_flash - 5
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 */
                do_action( 'woocommerce_shop_loop_item_before_image' );
                ?>

                <?php
                $attachment_ids = $product->get_gallery_attachment_ids();
                $attachment = '';
                if($attachment_ids){
                    foreach ( $attachment_ids as $attachment_id ) {
                        $image_link = wp_get_attachment_url( $attachment_id );
                        if ( $image_link ){
                            $attachment = wp_get_attachment_image( $attachment_id, 'shop_catalog', false, array('class'=>"second-img product-img"));
                            break;
                        }
                    }
                }
                if ( has_post_thumbnail() ) {
                    $image_thumb =  get_the_post_thumbnail( $post->ID, 'shop_catalog', array('class'=>"first-img product-img"));
                } elseif ( wc_placeholder_img_src() ) {
                    $image_thumb = wc_placeholder_img( 'shop_catalog' );
                }
                ?>
                <a href="<?php the_permalink(); ?>" class="product-thumbnail <?php if($attachment) echo "product-thumbnail-effect"; ?>">
                    <?php echo $image_thumb; ?>
                    <?php echo $attachment; ?>
                </a>
                <?php
                /**
                 * woocommerce_shop_loop_item_after_image hook
                 *
                 * @hooked woocommerce_template_loop_add_to_cart - 5
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 * @hooked woocommerce_shop_loop_item_action_action_add - 15
                 */
                do_action( 'woocommerce_shop_loop_item_after_image' );
                ?>


            </div>
        </div>
        <div class="product-attr-container">
            <?php
            /**
             * woocommerce_before_shop_loop_item_title hook
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            ?>
    
            <h3>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
    
            <?php
            /**
             * woocommerce_after_shop_loop_item_title hook
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
            
            <div class="kt-woocommerce-tool-list">
                <?php do_action( 'woocommerce_shop_tool_list_before' ); ?>
                <?php
                    /**
                     * woocommerce_shop_loop_item_after_image hook
                     *
                     * @hooked woocommerce_template_loop_add_to_cart - 5
                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                     * @hooked woocommerce_shop_loop_item_action_action_add - 15
                     */
                    do_action( 'woocommerce_shop_tool_list' );
                ?>
            </div>
            
            <?php
    
            /**
             * woocommerce_after_shop_loop_item hook
             *
             * @hooked woocommerce_template_loop_add_to_cart - 10
             */
            do_action( 'woocommerce_after_shop_loop_item' );
    
            ?>
        </div>
    </div>
</li>
