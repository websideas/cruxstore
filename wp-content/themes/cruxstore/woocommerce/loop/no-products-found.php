<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/no-products-found.php.
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

$filters = cruxstore_option('shop_header_filters', 1);
if($filters){
    echo '<div id="cruxstore-shop-filters" class="row multi-columns-row"><div id="cruxstore-shop-filters-content">';
    dynamic_sidebar('shop-filter-area');
    echo '</div></div>';
}
?>

<p class="woocommerce-info woocommerce-row"><?php esc_html_e( 'No products were found matching your selection.', 'woocommerce' ); ?></p>
<div class="wc-pagination-outer"></div>

