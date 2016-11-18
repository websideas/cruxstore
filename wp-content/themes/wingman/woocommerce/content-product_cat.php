<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce_loop;

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

// Increase loop count
$woocommerce_loop['loop'] ++;


// Extra post classes
$classes = array( 'clearfix' );

// Bootstrap Column
$bootstrapColumn = round( 12 / $woocommerce_loop['columns'] );
$bootstrapTabletColumn = round( 12 / $woocommerce_loop['columns_tablet'] );
$classes[] = 'col-xs-'. $bootstrapTabletColumn .' col-sm-'. $bootstrapTabletColumn .' col-md-' . $bootstrapColumn.' col-lg-' . $bootstrapColumn;


?>
<li <?php wc_product_cat_class( $classes ); ?>>
	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>
	<div class="product-image-container">
		<?php
		/**
		 * woocommerce_before_subcategory_title hook
		 *
		 * @hooked woocommerce_subcategory_thumbnail - 10
		 */
		do_action( 'woocommerce_before_subcategory_title', $category );
		?>
	</div>
	<div class="product-attr-container">
		<h3>
			<a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">
				<?php
					echo $category->name;

					if ( $category->count > 0 )
						echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
				?>
				<?php
					/**
					 * woocommerce_after_subcategory_title hook
					 */
					do_action( 'woocommerce_after_subcategory_title', $category );
				?>
			</a>
		</h3>

		<?php do_action( 'woocommerce_after_subcategory', $category ); ?>
	</div>
</li>