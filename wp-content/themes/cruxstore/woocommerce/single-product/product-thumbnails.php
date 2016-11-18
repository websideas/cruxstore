<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.3
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post, $product, $woocommerce;


$attachment_ids = $product->get_gallery_attachment_ids();
$attachment_count = count( $attachment_ids );

if(!$attachment_count) return;


$layout = cruxstore_get_product_layout();
if($layout == 'layout2'){
    $items = 6;
}elseif($layout == 'layout3'){
    $items = 5;
}else{
    $items = 3;
}
$items_mb = 3;


$items = apply_filters('cruxstore_product_thumbnail_items', $items, $layout);
$items_mb = apply_filters('cruxstore_product_thumbnail_items_mobile', $items_mb, $layout);



?>
<div class="product-main-thumbnails-wrap">
    <div class="product-main-thumbnails">
        <?php
        if ( has_post_thumbnail() ) {
            $image_caption = get_post( get_post_thumbnail_id() )->post_excerpt;
            $image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
            $image         = get_the_post_thumbnail(
                $post->ID,
                apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog' ),
                array('title'	=> get_the_title( get_post_thumbnail_id() ))
            );

            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<span><span>%s</span></span>', $image ), $post->ID );

        } else {

            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<span><span><img src="%s" alt="%s" /></span></span>', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'woocommerce' ) ), $post->ID );

        }


        // Display Attachment Images as well
        if( $attachment_count > 0 ) :

            // Loop in attachment
            foreach ( $attachment_ids as $attachment_id ) {

                // Get attachment image URL
                $image_link = wp_get_attachment_url( $attachment_id );

                $image_title = esc_attr( get_the_title( $attachment_id ) );

                // If isn't a URL we go to next attachment
                if ( !$image_link )
                    continue;

                $image = wp_get_attachment_image( $attachment_id, 'shop_catalog', array(
                    'class' => 'img-responsive'
                ) );

                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<span><span>%s</span></span>', $image ), $post->ID );
            }

        endif;
        ?>
    </div>
</div>