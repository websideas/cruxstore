<?php
/**
 * The template for displaying product content inside our popup
 *
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

global $post, $product, $woocommerce;

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }

    $layout = cruxstore_get_product_layout();
    $classes = array('product', 'wc-single-product-quickview', 'product-'.$layout);


?>

<a class="close-quickview" href="#"><i class="fa fa-times"></i></a>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class($classes); ?>>

    <div class="row">
        <div class="col-lg-6 col-md-6">
            <?php
            $attachment_ids = $product->get_gallery_attachment_ids();
            $attachment_count = count( $attachment_ids );
            ?>
            <div class="images product-detail-thumbarea">
                <div class="product-images-wrap">
                    <div class="product-main-images" id="quickview-images">
                        <?php
                        if ( has_post_thumbnail() ) {
                            $image_caption = get_post( get_post_thumbnail_id() )->post_excerpt;
                            $image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
                            $image         = get_the_post_thumbnail(
                                $post->ID,
                                apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ),
                                array('title'	=> get_the_title( get_post_thumbnail_id() ))
                            );
                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<span itemprop="image" class="woocommerce-main-image" title="%s">%s</span>', $image_title, $image ), $post->ID );

                        } else {
                            echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'woocommerce' ) ), $post->ID );
                        }

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

                                echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<span itemprop="image" class="woocommerce-main-image" title="%s">%s</span>', $image_title, $image ), $post->ID );
                            }

                        endif;

                        ?>
                    </div><!-- #quickview-images.single-product-main-images.owl-carousel -->

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="summary entry-summary" id="quickview-summary">
                <div id="quickview-summary-content" class="clearfix">
                    <?php
                    /**
                     * woocommerce_single_product_summary hook.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     */
                    do_action( 'woocommerce_single_product_summary' );
                    ?>
                </div>
            </div><!-- .summary -->
        </div>
    </div>
</div><!-- #product-<?php the_ID(); ?> -->