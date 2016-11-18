<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wp_query;

if ( ! woocommerce_products_will_display() )
	return;


$products_per_page = cruxstore_option('products_per_page', 12);

$paged    = max( 1, $wp_query->get( 'paged' ) );
$per_page = $wp_query->get( 'posts_per_page' );
$total    = $wp_query->found_posts;
$first    = ( $per_page * $paged ) - $per_page + 1;
$last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );


?>
<p class="woocommerce-result-count">
	<?php

	if ( 1 === $total ) {
		_e( 'Showing the single result', 'woocommerce' );
	} elseif ( $total <= $per_page || -1 === $per_page ) {
		printf( esc_html__( 'Showing all %d results', 'woocommerce' ), $total );
	} else {
		printf( _x( 'Showing %1$d&ndash;%2$d of %3$d results', '%1$d = first, %2$d = last, %3$d = total', 'woocommerce' ), $first, $last, $total );
	}
	?>
</p>

<form class="woocommerce-per-page" method="get">
    <label for="products_per_page"><?php esc_html_e( "Show by :", "cruxstore" ); ?> </label>
    <div class="select-icon">
        <select name="per_page" class="per_page" id="products_per_page" onchange="this.form.submit()">
            <option <?php selected( $products_per_page, $per_page); ?> value="<?php echo esc_attr($products_per_page); ?>"><?php echo esc_html($products_per_page); ?></option>
            <option <?php selected( $products_per_page * 2, $per_page); ?> value="<?php echo esc_attr($products_per_page * 2); ?>"><?php echo esc_html($products_per_page * 2); ?></option>
            <option <?php selected( $total, $per_page); ?> value="<?php echo esc_attr($total); ?>"><?php esc_html_e( "All", "cruxstore" ); ?></option>
        </select>
    </div>
    <?php
    // Keep query string vars intact
    foreach ( $_GET as $key => $val ) {
        if ( 'per_page' === $key || 'submit' === $key ) {
            continue;
        }
        if ( is_array( $val ) ) {
            foreach( $val as $innerVal ) {
                echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
            }
        } else {
            echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
        }
    }
    ?>
</form>