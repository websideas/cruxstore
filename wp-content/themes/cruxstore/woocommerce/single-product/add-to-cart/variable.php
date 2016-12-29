<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(cruxstore_option('catalog_mode', 0)){
    return;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php esc_html_e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
		<div class="variations">


            <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                <div class="value variation-item">
                    <?php

                    $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );

                    $swatch = false;
                    $swatch_html = $class = '';
                    foreach($options as $option){
                        $taxonomy = get_term_by('slug', $option, $attribute_name);
                        $display_type = get_woocommerce_term_meta( $taxonomy->term_id, 'display_type', true );
                        $term_color = get_woocommerce_term_meta( $taxonomy->term_id, 'term_color', true );

                        if($display_type == 'color' && $term_color){
                            $columns = sprintf('<span class="swatch-term term_color" data-term="%s" style="background: %s;">&nbsp;</span>', $option, $term_color);
                            $swatch = true;
                        }elseif($display_type == 'image'){
                            $thumbnail_id = get_woocommerce_term_meta( $taxonomy->term_id, 'thumbnail_id', true );
                            $image = ( $thumbnail_id ) ? wp_get_attachment_thumb_url( $thumbnail_id ) : $image = wc_placeholder_img_src();
                            $image = str_replace( ' ', '%20', $image );
                            $columns = '<span class="swatch-term term_image" data-term="'.$option.'"><img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'woocommerce' ) . '" class="wp-post-image" height="48" width="48" /></span>';
                            $swatch = true;
                        }else {
                            $columns = sprintf('<span class="swatch-term term_text" data-term="%s">%s</span>', $option, $taxonomy->name);
                            if($display_type == 'text'){
                                $swatch = true;
                            }
                        }
                        $swatch_html .= sprintf('<li>%s</li>', $columns);
                    }

                    if($swatch){
                        printf('<ul data-id="%s" class="swatch-term-wrap">%s</ul>', $attribute_name, $swatch_html);
                        $class = 'swatch-select';
                    }


                    wc_dropdown_variation_attribute_options( array(
                        'options' => $options,
                        'attribute' => $attribute_name,
                        'product' => $product,
                        'selected' => $selected ,
                        'class' => $class,
                        'show_option_none' => sprintf('%s %s', esc_html__( 'Choose an ', 'woocommerce' ), wc_attribute_label( $attribute_name ))
                    ) );

                    ?>
                </div>
                <?php echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<div class="reset_variations_wrap"><a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a></div>' ) : ''; ?>
            <?php endforeach;?>
		</div>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

        <div class="single_variation_wrap">
            <?php
                /**
                 * woocommerce_before_single_variation Hook.
                 */
                do_action( 'woocommerce_before_single_variation' );

                /**
                 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
                 * @since 2.4.0
                 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                 */
                do_action( 'woocommerce_single_variation' );

                /**
                 * woocommerce_after_single_variation Hook.
                 */
                do_action( 'woocommerce_after_single_variation' );

                do_action( 'woocommerce_after_add_to_cart_button' );
            ?>
        </div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
