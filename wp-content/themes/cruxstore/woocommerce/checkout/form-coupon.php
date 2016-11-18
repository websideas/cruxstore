<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! wc_coupons_enabled() ) {
	return;
}

?>
<div class="checkout_coupon_wrap wc-cart-box">
    <?php
        printf('<h5 class="wc-cart-box-heading">%s</h5>', esc_html__('Promotional Code', 'cruxstore'));
    ?>
    <div class="wc-cart-box-inner">
        <?php printf('<p>%s</p>', esc_html__('Enter your coupon code if you have one', 'cruxstore')); ?>
        <div class="checkout_coupon form-row">
            <div class="input-group">
                <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
                <div class="input-group-btn"><input type="submit" class="btn btn-gray-b" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'woocommerce' ); ?>" /></div>
            </div>

        </div>
    </div>
</div>