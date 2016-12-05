<?php
/**
 * Additional Information tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/additional-information.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional Information', 'woocommerce' ) );

?>

<?php if ( $heading ): ?>
	<h2><?php echo $heading; ?></h2>
<?php endif; ?>

<?php //$product->list_attributes(); ?>


<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 */

// Instead of showing the attributes in a left-right table,
// we show them as columns with the name above each value.


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$has_row    = false;
$attributes = $product->get_attributes();

ob_start();

?>
	<div class="shop_attributes row multi-columns-row">

		<?php foreach ( $attributes as $attribute ) :

			if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) )
				continue;

			$values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
			$att_val = apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );

			if( empty( $att_val ) )
				continue;

			$has_row = true;
			?>

			<div class="col-lg-6 col-md-6 col-sm-12">
				<div class="att_wrapper">
					<div class="att_label"><?php echo wc_attribute_label( $attribute['name'] ); ?></div>
					<div class="att_value"><?php echo $att_val; ?></div><!-- .att_value -->
				</div>
			</div><!-- .col -->
		<?php endforeach; ?>

	</div><!-- .product_attributes -->
<?php
if ( $has_row ) {
	echo ob_get_clean();
} else {
	ob_end_clean();
}
