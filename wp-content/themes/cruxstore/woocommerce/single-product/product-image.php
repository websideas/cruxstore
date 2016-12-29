<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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

global $post, $woocommerce, $product;

$layout = cruxstore_get_product_layout();




ob_start();

$attachment_ids = $product->get_gallery_attachment_ids();
$attachment_count = count( $attachment_ids );

$count_class = ( $attachment_count > 0 ) ? 'multi' : 'single';

?>

<div class="product-images-wrap product-images-<?php echo esc_attr($count_class); ?>">
    <?php do_action( 'woocommerce_product_images' ); ?>
    <div class="product-main-images clearfix" id="sync1">
        <?php

        //<div class="easyzoom easyzoom--overlay"></div>

        if ( has_post_thumbnail() ) {
            $image_caption = get_post( get_post_thumbnail_id() )->post_excerpt;
            $image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
            $image         = get_the_post_thumbnail(
                $post->ID,
                apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ),
                array('title'	=> get_the_title( get_post_thumbnail_id() ))
            );
            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image" title="%s">%s</a>', $image_link, $image_caption, $image ), $post->ID );
        } else {
            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'woocommerce' ) ), $post->ID );
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
                $image = wp_get_attachment_image( $attachment_id, 'shop_single', array(
                    'data-zoom-image' => $image_link,
                    'class' => 'img-responsive'
                ) );
                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image" title="%s">%s</a>', $image_link, $image_title, $image ), $post->ID );
            }
        endif;
        ?>
    </div><!-- #sync1.single-product-main-images.owl-carousel -->
    <?php if( $layout != 'layout5' ){ ?>
    <div class="product-main-tool">
        <?php
        if($attachment_count){
            echo '<span class="slick-images-arrow slick-images-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></span>';
            echo '<span class="slick-images-arrow slick-images-next"><i class="fa fa-angle-right" aria-hidden="true"></i></span>';
        }
        ?>
        <span class="product-tool-zoom"></span>

    </div>
    <?php } ?>
    <?php
    $video = cruxstore_meta('_cruxstore_video');
    if($video){
        printf('<a href="%s" class="product-tool-play"><i class="fa fa-play" aria-hidden="true"></i></a>', $video);
    }
    ?>
</div>


<?php
$images = ob_get_clean();


ob_start();
do_action( 'woocommerce_product_thumbnails' );




if($attachment_count){
    echo '<span class="slick-thumbs-arrow slick-thumbs-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></span>';
    echo '<span class="slick-thumbs-arrow slick-thumbs-next"><i class="fa fa-angle-right" aria-hidden="true"></i></span>';
}
$thumbnails = ob_get_clean();

?>

<div class="images product-detail-thumbarea">
    <?php
    if($layout == 'layout1'){
        echo $images.$thumbnails;
    }elseif($layout == 'layout5'){
        echo $images;
    }else{
        echo $images.$thumbnails;
    }
    ?>
</div>
