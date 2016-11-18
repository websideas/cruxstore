<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;


/**
 * Enable support for woocommerce after setip theme
 *
 */
add_action('after_setup_theme', 'cruxstore_woocommerce_theme_setup');
if (!function_exists('cruxstore_woocommerce_theme_setup')):
    function cruxstore_woocommerce_theme_setup()
    {
        /**
         * Enable support for woocommerce
         */
        add_theme_support('woocommerce');

        if (function_exists('add_image_size')) {
            add_image_size('cruxstore_product', 270, 200, true);
            add_image_size('cruxstore_landscape', 570, 200, true);
            add_image_size('cruxstore_portrait', 200, 570, true);
            add_image_size('cruxstore_wide', 570, 430, true);
            add_image_size('cruxstore_big', 870, 430, true);
            add_image_size('cruxstore_cate_carousel', 640, 800, true);
            add_image_size('cruxstore_product_slider', 265, 375, true);
        }

    }
endif;

if (!function_exists('cruxstore_get_product_layout')) {
    function cruxstore_get_product_layout()
    {
        $layout = cruxstore_meta('_cruxstore_detail_layout', array(), get_the_ID());
        if (!$layout) {
            $layout = cruxstore_option('product_detail_layout', 'layout1');
        }
        return $layout;
    }
}

add_action('woocommerce_widget_field_colors', 'cruxstore_wc_widget_field_colors', 10, 4);
function cruxstore_wc_widget_field_colors($key, $value, $setting, $instance)
{
    $terms = get_terms('pa_color', array('hide_empty' => '0'));

    printf('<label>%s</label>', esc_html__('Select color bellow', 'cruxstore'));
    if (!empty($terms) && !is_wp_error($terms)) {
        $output = '';
        foreach ($terms as $term) {

            $id = 'woocommerce_widget_field_colors' . $term->term_id.'-'.rand();
            $valuecolor = '';
            if(is_array($value)){
                $valuecolor = isset($value[$term->term_id]) ? esc_attr($value[$term->term_id]) : '';
            }
            $output .= '<tr>
							<td><label for="' . esc_attr($id) . '">' . esc_attr($term->name) . ' </label></td>
							<td><input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($setting['name']) . '[' . esc_attr($term->term_id) . ']" value="'.esc_attr($valuecolor).'" size="3" class="color-picker" /></td>
						</tr>';
        }
        printf(
            '<table class="colors-table"><tr><td><b>%1$s</b></td><td><b>%2$s</b></td></tr>%3$s</table>',
            esc_html__('Term', 'cruxstore'),
            esc_html__('Color', 'cruxstore'),
            $output
        );
    } else {
        printf('<p>%s</p>', wp_kses(__('No product attribute saved with the <strong>"color"</strong> slug yet.', 'cruxstore'), array('strong' => array())));
    }

}


/**
 * Extend the default WordPress body classes.
 *
 * @since 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function cruxstore_wc_body_classes($classes)
{
    if (is_product()) {
        $layout = cruxstore_get_product_layout();
        $classes[] = 'product-' . $layout;
    }
    return $classes;
}


/**
 * Define image sizes
 *
 *
 */
function cruxstore_woocommerce_set_option()
{

    global $pagenow;
 
	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}

    $catalog = array('width' => '500', 'height' => '500', 'crop' => 1);
    $thumbnail = array('width' => '150', 'height' => '150', 'crop' => 1);
    $single = array('width' => '1024', 'height' => '1024', 'crop' => 1);
    $woocompare = array('width' => '270', 'height' => '200', 'crop' => 1);

    // Image sizes
    update_option('shop_catalog_image_size', $catalog);        // Product category thumbs
    update_option('shop_single_image_size', $single);        // Single product image
    update_option('shop_thumbnail_image_size', $thumbnail);    // Image gallery thumbs
    update_option('yith_woocompare_image_size', $woocompare);    // Image compare thumbs

    update_option('yith_woocompare_is_button', 'link');    // Image compare thumbs
}

add_action('after_switch_theme', 'cruxstore_woocommerce_set_option', 1);

/**
 * Woocommerce wishlist in header
 *
 * @since 1.0
 */
function cruxstore_woocommerce_get_wishlist()
{
    if (cruxstore_is_wc() && defined('YITH_WCWL')) {
        cruxstore_cart_wishlist();
    }
}


/**
 * WishList Link
 * Displayed a link to the cart including the number of items present and the cart total
 * @param  array $settings Settings
 * @return array           Settings
 * @since  1.0.0
 */
if (!function_exists('cruxstore_cart_wishlist')) {
    function cruxstore_cart_wishlist()
    {
        global $yith_wcwl;

        $args = array('is_default' => 1);
        $wishlist_items = $yith_wcwl->get_products($args);

        printf(
            '<a href="%s" class="%s" title="%s">%s %s %s</a>',
            esc_url($yith_wcwl->get_wishlist_url()),
            'wishlist-contents',
            esc_html__('View your wishlist', 'cruxstore'),
            '<i class="fa fa-heart" aria-hidden="true"></i>',
            '<span class="text">'.esc_html__('Wishlist', 'cruxstore').'</span>',
            '<span class="amount">'.count($wishlist_items).'</span>'
        );

        ?>
        <div class="navigation-submenu shopping-bag-content woocommerce widget_shopping_cart">
            <?php


            if (count($wishlist_items) > 0) {
                echo '<ul class="cart_list product_list_widget ">';
                foreach ($wishlist_items as $item) {
                    global $product;
                    if (function_exists('wc_get_product')) {
                        $product = wc_get_product($item['prod_id']);
                    } else {
                        $product = get_product($item['prod_id']);
                    }

                    if ($product !== false && $product->exists()) {
                        ?>
                        <li class="mini_cart_item">
                            <a class="minicart_product"
                               href="<?php echo esc_url(get_permalink(apply_filters('woocommerce_in_cart_product', $item['prod_id']))) ?>">
                                <?php echo $product->get_image() ?>
                                <?php echo apply_filters('woocommerce_in_cartproduct_obj_title', $product->get_title(), $product) ?>
                            </a>

                            <div class="minicart_product_infos">
                                <?php
                                if (is_a($product, 'WC_Product_Bundle')) {
                                    if ($product->min_price != $product->max_price) {
                                        echo sprintf('%s - %s', wc_price($product->min_price), wc_price($product->max_price));
                                    } else {
                                        echo wc_price($product->min_price);
                                    }
                                } elseif ($product->price != '0') {
                                    echo $product->get_price_html();
                                } else {
                                    echo apply_filters('yith_free_text', esc_html__('Free!', 'cruxstore'));
                                }
                                ?>
                            </div>
                        </li>
                        <?php
                    }
                }
                echo '</ul>';
                printf(
                    '<p class="buttons-wishlist"><span><a class="btn btn-default btn-block wc-forward" href="%s">%s</a></span></p>',
                    esc_url($yith_wcwl->get_wishlist_url()),
                    esc_html__('View Wishlist', 'cruxstore')
                );
            } else {
                printf('<p class="cart-desc empty">%s</p>', esc_html__('Your wishlist is empty.', 'cruxstore'));

            }
            ?>
        </div>
        <?php
    }
}

/**
 * Woocommerce cart in header
 *
 * @since 1.0
 */
function cruxstore_woocommerce_get_cart()
{
    if (cruxstore_is_wc()) {
        cruxstore_cart_link();
        if (!is_cart() && !is_checkout()) {
            ?>
            <div class="navigation-submenu shopping-bag-content woocommerce widget_shopping_cart">
                <?php the_widget('WC_Widget_Cart', 'title='); ?>
            </div><!-- .shopping-bag-content -->
            <?php
        }
    }
}


/**
 * Cart Link
 * Displayed a link to the cart including the number of items present and the cart total
 * @param  array $settings Settings
 * @return array           Settings
 * @since  1.0.0
 */
if (!function_exists('cruxstore_cart_link')) {
    function cruxstore_cart_link($class = 'cart-contents', $text = null)
    {
        if (!isset($text)) {
            $text = '<i class="fa fa-shopping-basket" aria-hidden="true"></i>';
        }
        printf(
            '<a href="%s" class="%s" title="%s"><span>%s<span class="text">%s</span><span class="amount">%s</span><span class="subtotal">%s</span></span></a>',
            esc_url(wc_get_page_permalink('cart')),
            $class,
            esc_html__('View your shopping cart', 'cruxstore'),
            $text,
            esc_html__('Cart', 'cruxstore'),
            WC()->cart->get_cart_contents_count(),
            WC()->cart->get_cart_subtotal()

        );
    }
}


/**
 * Cart Fragments
 * Ensure cart contents update when products are added to the cart via AJAX
 * @param  array $fragments Fragments to refresh via AJAX
 * @return array            Fragments to refresh via AJAX
 */
if (!function_exists('cruxstore_cart_link_fragment')) {
    function cruxstore_cart_link_fragment($fragments)
    {
        ob_start();
        cruxstore_cart_link();
        $fragments['a.cart-contents'] = ob_get_clean();

        ob_start();
        cruxstore_cart_link('cart-mobile');
        $fragments['a.cart-mobile'] = ob_get_clean();

        return $fragments;
    }
}
if (!function_exists('cruxstore_template_loop_category_title')) {
    /**
     * Show the subcategory title in the product loop.
     */
    function cruxstore_template_loop_category_title($category)
    {
        ?>
        <h3 class="product-title">
            <?php
            $count = ($category->count > 0) ? apply_filters('woocommerce_subcategory_count_html', ' <span class="count">(' . $category->count . ')</span>', $category) : '';
            printf('<a href="%s">%s</a>', get_term_link($category->slug, 'product_cat'), $category->name.$count);
            ?>
        </h3>
        <a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>" class="shop-now-link">
            <?php echo esc_html__('Shop now', 'cruxstore') ?>
        </a>
        <?php
    }
}


if (!function_exists('cruxstore_get_woo_sidebar')) {
    /**
     * Get woo sidebar
     *
     * @return array
     */
    function cruxstore_get_woo_sidebar( )
    {

        $sidebar = array('sidebar' => '', 'sidebar_area' => '');

        if (isset($_REQUEST['sidebar'])) {
            $sidebar['sidebar'] = $_REQUEST['sidebar'];
            $sidebar['sidebar_area'] = 'shop-widget-area';
        } elseif (is_shop() || is_product_taxonomy() || is_product_tag()) {
            $sidebar['sidebar'] = cruxstore_option('shop_sidebar', 'left');
            if ($sidebar['sidebar'] == 'left') {
                $sidebar['sidebar_area'] = cruxstore_option('shop_sidebar_left', 'primary-widget-area');
            } elseif ($sidebar['sidebar'] == 'right') {
                $sidebar['sidebar_area'] = cruxstore_option('shop_sidebar_right', 'primary-widget-area');
            }
        }

        if ($sidebar['sidebar'] == 'full') {
            $sidebar['sidebar'] = '';
        }

        return apply_filters('cruxstore_wc_sidebar', $sidebar);
    }
}

if (!function_exists('cruxstore_wc_subcategory_thumbnail')) {

    /**
     * Show subcategory thumbnails.
     *
     * @param mixed $category
     * @subpackage    Loop
     */
    function cruxstore_wc_subcategory_thumbnail($category, $woocommerce_carousel)
    {

        if ($woocommerce_carousel == 'portrait') {
            $small_thumbnail_size = apply_filters('single_product_small_thumbnail_size', 'cruxstore_cate_carousel');
        } else {
            $small_thumbnail_size = apply_filters('single_product_small_thumbnail_size', 'shop_catalog');

        }

        $dimensions = wc_get_image_size($small_thumbnail_size);

        $thumbnail_id = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);

        if ($thumbnail_id) {
            $image = wp_get_attachment_image_src($thumbnail_id, $small_thumbnail_size);
            $image = $image[0];
        } else {
            $image = wc_placeholder_img_src();
        }

        if ($image) {
            // Prevent esc_url from breaking spaces in urls for image embeds
            // Ref: http://core.trac.wordpress.org/ticket/23605
            $image = str_replace(' ', '%20', $image);

            echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" width="' . esc_attr($dimensions['width']) . '" height="' . esc_attr($dimensions['height']) . '" />';
        }
    }
}

/**
 * Display Gird List toogle
 *
 *
 */

function cruxstore_woocommerce_gridlist_toggle()
{ ?>
    <?php $gridlist = apply_filters('woocommerce_gridlist_toggle', cruxstore_get_gridlist_toggle()) ?>
    <ul class="gridlist-toggle">
        <li>
            <a class="grid<?php if ($gridlist == 'grid') { ?> active<?php } ?>" data-toggle="tooltip" href="#"
               title="<?php esc_html_e('Grid view', 'cruxstore') ?>" data-layout="products-grid" data-remove="products-list">
                <i class="fa fa-th"></i>
            </a>
        </li>
        <li>
            <a class="list<?php if ($gridlist == 'list') { ?> active<?php } ?>" data-toggle="tooltip" href="#"
               title="<?php esc_html_e('List view', 'cruxstore') ?>" data-layout="products-list" data-remove="products-grid">
                <i class="fa fa-bars"></i>
            </a>
        </li>
    </ul>
<?php }


/**
 * Get Grid or List layout.
 *
 * Return the layout of products
 *
 * @return string layout of products.
 *
 *
 */
function cruxstore_get_gridlist_toggle($layout = 'grid')
{
    if (isset($_REQUEST['view'])) {
        return $_REQUEST['view'];
    } else {
        return cruxstore_option('shop_products_layout', $layout);
    }
}


add_filter('cruxstore_product_loop_start', 'cruxstore_wc_product_loop_start');
function cruxstore_wc_product_loop_start($classes)
{
    if (is_product_category() || is_shop() || is_product_tag()) {
        $view = cruxstore_get_gridlist_toggle();
        $classes .= ' products-' . $view;
    }
    return $classes;
}


if (!function_exists('cruxstore_product_shop_count')) {
    function cruxstore_product_shop_count()
    {
        $default_count = $products_per_page = cruxstore_option('products_per_page', 12);
        $count = isset($_GET['per_page']) ? $_GET['per_page'] : $default_count;
        if ($count === 'all') {
            $count = -1;
        } else if (!is_numeric($count)) {
            $count = $default_count;
        }
        return $count;
    }
}


function cruxstore_woocommerce_catalog_orderby()
{
    return array(
        'menu_order' => esc_html__('Default sorting', 'cruxstore'),
        'popularity' => esc_html__('Popularity', 'cruxstore'),
        'rating' => esc_html__('Average rating', 'cruxstore'),
        'date' => esc_html__('Newness', 'cruxstore'),
        'price' => esc_html__('Price: low to high', 'cruxstore'),
        'price-desc' => esc_html__('Price: high to low', 'cruxstore')
    );
}


/*
 *	Create single category list HTML
 */
function cruxstore_category_list_item($category, $current_cat)
{

    $active = ($current_cat == $category->term_id) ? 'current-cat ' : '';
    $output = sprintf('<li class="%s"><a href="%s">%s</a></li>', $active . 'cat-item-' . $category->term_id, esc_url(get_term_link((int)$category->term_id, 'product_cat')), $category->name);

    return $output;
}

/*
 *	Output product categories menu
 */
function cruxstore_category_menu()
{
    global $wp_query;


    $current_cat = (is_product_category()) ? $wp_query->queried_object->term_id : '';


    $page_id = wc_get_page_id('shop');
    $page_url = get_permalink($page_id);
    $all_categories_class = '';

    if (!is_product_category() && !is_product_tag() && !isset($_REQUEST['s'])) {
        $all_categories_class = ' class="current-cat"';
    }

    $output = '<li' . $all_categories_class . '><a href="' . esc_url($page_url) . '">' . esc_html__('All', 'cruxstore') . '</a></li>';

    $orderby = cruxstore_option('shop_header_orderby', 'slug');
    $order = cruxstore_option('shop_header_order', 'ASC');

    $categories = get_categories($args = array(
        'type' => 'post',
        'orderby' => $orderby,
        'order' => $order,
        'hide_empty' => 1,
        'taxonomy' => 'product_cat',
        'parent'  => 0
    ));

    foreach ($categories as $category) {
        $output .= cruxstore_category_list_item($category, $current_cat);
    }

    $shop_header_filters = '';


    $search = cruxstore_option('shop_header_search', 1);
    if ($search) {
        $shop_header_filters .= '<li class="wc-header-search">' . get_product_search_form(false) . '</li>';
    }

    $shop_header_filters .= sprintf('<li class="wc-header-categories"><a href="#cruxstore-shop-categories">%s</a></li>', esc_html__('Categories', 'cruxstore'));


    $filters = cruxstore_option('shop_header_filters', 1);
    $filters_html = '';
    if ($filters) {
        $shop_header_filters .= sprintf('<li class="wc-header-filter"><a href="#cruxstore-shop-filters">%s</a></li>', esc_html__('Filter', 'cruxstore'));

        ob_start();

        echo '<div class="clearfix"></div><div id="cruxstore-shop-filters" class="row multi-columns-row"><div id="cruxstore-shop-filters-content">';
        dynamic_sidebar('shop-filter-area');
        echo '</div></div>';

        $filters_html = ob_get_clean();

    }

    if ($shop_header_filters) {
        $shop_header_filters = '<div class="shop-header-right"><ul class="shop-header-list">' . $shop_header_filters . '</ul></div>';
    }

    printf('%s<div class="shop-header-left"><ul id="shop-header-categories" class="shop-header-list">%s</ul></div>%s', $shop_header_filters, $output, $filters_html);

}


function cruxstore_woocommerce_shop_loop()
{
    $shop_header_tool_bar = cruxstore_option('shop_header_tool_bar', 1);
    if ($shop_header_tool_bar) {

        if ($shop_header_tool_bar == 2) {
            echo '<div class="products-shop-header">';
            cruxstore_category_menu();
            echo '</div>';
        } else {
            echo '<div class="products-tools">';
            woocommerce_result_count();
            woocommerce_catalog_ordering();
            cruxstore_woocommerce_gridlist_toggle();
            echo '</div>';
        }
    }
}


/**
 * Change columns of shop
 *
 */

add_filter('loop_shop_columns', 'cruxstore_woo_shop_columns');
function cruxstore_woo_shop_columns($columns)
{
    $cols = cruxstore_option('shop_gird_cols', 3);
    if (isset($_REQUEST['cols'])) {
        $cols = $_REQUEST['cols'];
    }

    return $cols;
}


function cruxstore_template_loop_product_thumbnail()
{
    global $product, $woocommerce_loop;

    $image_size = 'shop_catalog';
    $type = $woocommerce_loop['type'];

    if ($type == 'masonry') {
        $box_size = get_post_meta($product->id, '_cruxstore_box_size', true);
        if ($box_size == 'wide') {
            $image_size = 'cruxstore_wide';
        } elseif ($box_size == 'landscape') {
            $image_size = 'cruxstore_landscape';
        } elseif ($box_size == 'big') {
            $image_size = 'cruxstore_big';
        } elseif ($box_size == 'portrait') {
            $image_size = 'cruxstore_portrait';
        } else {
            $image_size = 'cruxstore_product';
        }
    } elseif ($type == 'slider') {
        $image_size = 'cruxstore_product_slider';
        echo '<ul class="cd-item-wrapper">';
    } elseif ($type == 'countdown') {
        $image_size = 'cruxstore_wide';
    }

    $thumbnail = '';


    if ($woocommerce_loop['type'] == 'transparent') {
        $thumbnail_product = cruxstore_get_single_file('_cruxstore_image', $image_size, $product->id);
        if ($thumbnail_product) {
            @list($width, $height) = getimagesize($thumbnail_product['url']);
            $thumbnail = '<img src="' . $thumbnail_product['url'] . '" alt="' . esc_attr(get_the_title()) . '" width="' . esc_attr($width) . '" class="wp-post-image" height="' . esc_attr($height) . '" />';
        }
    } elseif ($woocommerce_loop['type'] == 'gallery') {
        $thumbnail_product = cruxstore_get_single_file('_cruxstore_gallery', $image_size, $product->id);
        if ($thumbnail_product) {
            @list($width, $height) = getimagesize($thumbnail_product['url']);
            $thumbnail = '<img src="' . $thumbnail_product['url'] . '" alt="' . esc_attr(get_the_title()) . '" width="' . esc_attr($width) . '" class="wp-post-image" height="' . esc_attr($height) . '" />';
        }
    }

    if (!$thumbnail) {
        if (has_post_thumbnail()) {
            $thumbnail = get_the_post_thumbnail($product->id, $image_size, array('class' => "first-img product-img"));
        } elseif (wc_placeholder_img_src()) {
            $thumbnail = wc_placeholder_img($image_size);
        }
        if ($type == 'slider') {
            $thumbnail = '<li class="selected">' . $thumbnail . '</li>';
        }
    }

    echo $thumbnail;

    if ($type == 'classic' || $type == 'slider') {
        $attachment_ids = $product->get_gallery_attachment_ids();
        if ($attachment_ids) {
            $i = 1;
            foreach ($attachment_ids as $attachment_id) {
                //$image_link = wp_get_attachment_url( $attachment_id );

                if ($type == 'classic') {
                    echo wp_get_attachment_image($attachment_id, $image_size, false, array('class' => "second-img product-img"));
                    break;
                } elseif ($type == 'slider') {
                    $class = ($i == 1) ? 'move-right' : '';
                    echo '<li class="' . $class . '">' . wp_get_attachment_image($attachment_id, $image_size, false, array('class' => "product-img")) . '</li>';
                }
                $i++;
            }
        }
    }
}

/**
 * Insert the opening anchor tag for products in the loop.
 */
function cruxstore_template_loop_product_link_open()
{
    global $product, $woocommerce_loop;
    $classes = array('product-thumbnail');


    if ($woocommerce_loop['type'] == 'classic') {
        $attachment_ids = $product->get_gallery_attachment_ids();
        if ($attachment_ids) {
            $classes[] = 'product-thumbnail-effect';
        }
    }

    if ($woocommerce_loop['type'] == 'slider') {
        return;
    }

    printf(
        '<a class="%s" href="%s">',
        implode(' ', $classes),
        get_the_permalink()
    );
}


function cruxstore_template_loop_product_link_close()
{
    global $woocommerce_loop;
    if ($woocommerce_loop['type'] != 'slider') {
        echo '</a>';
    }
}

function cruxstore_template_loop_rating()
{
    global $woocommerce_loop;
    if ($woocommerce_loop['type'] == 'transparent' || $woocommerce_loop['type'] == 'mini') {
        wc_get_template('loop/rating.php');
    }
}

function cruxstore_product_attribute_swatche(){
    $swatche_term = cruxstore_option('product_attribute_swatche');
    if($swatche_term){
        $swatche_html = '';
        $post_id = get_the_ID();
        $args = array(
            'orderby'    => 'name',
            'hide_empty' => 0
        );
        $all_terms = get_terms( $swatche_term, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
        if ( $all_terms ) {
            foreach ( $all_terms as $term ) {
                if( has_term( absint( $term->term_id ), $swatche_term, $post_id )){
                    $display_type = get_woocommerce_term_meta( $term->term_id, 'display_type', true );
                    $term_color = get_woocommerce_term_meta( $term->term_id, 'term_color', true );

                    if($display_type == 'color' && $term_color){
                        $swatche_html .= sprintf('<span class="product-swatche-item swatche_color" style="background: %s;" title="%s" data-toggle="tooltip" data-placement="top">&nbsp;</span>', $term_color, $term->name);
                    }elseif($display_type == 'text'){
                        $swatche_html .= sprintf('<span class="product-swatche-item swatche_text" title="%1$s" data-toggle="tooltip" data-placement="top">%1$s</span>', $term->name);
                    }else{
                        $thumbnail_id = get_woocommerce_term_meta( $term->id, 'thumbnail_id', true );
                        $image = wp_get_attachment_thumb_url( $thumbnail_id );
                        if($thumbnail_id){
                            $image = str_replace( ' ', '%20', $image );
                            $swatche_html .= '<span class="product-swatche-item swatche_image" title="'.esc_attr($term->name).'" data-toggle="tooltip" data-placement="top"><img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'woocommerce' ) . '" class="wp-post-image" height="48" width="48" /></span>';
                        }
                    }
                }
            }
        }
        if($swatche_html){
            printf('<div class="product-swatche">%s</div>',$swatche_html);
        }
    }
}

function cruxstore_woocommerce_show_product_badge(){
    
    global $product, $post, $woocommerce_loop;

    if ( empty( $woocommerce_loop['type'] ) ) {
        $woocommerce_loop['type'] = 'classic';
    }

    if ($woocommerce_loop['type'] == 'transparent' || $woocommerce_loop['type'] == 'mini') {
        return;
    }

    $time_new = cruxstore_option('time_product_new', 30);
    $now = strtotime(date("Y-m-d H:i:s"));
    $post_date = strtotime($post->post_date);
    $num_day = (int)(($now - $post_date) / (3600 * 24));
    $badge = '';

    if (!$product->is_in_stock()) {
        $badge = sprintf('<span class="wc-out-of-stock">%s</span>', esc_html__('Sold out', 'cruxstore'));
    } elseif ($product->is_on_sale()) {
        $badge = apply_filters('woocommerce_sale_flash', '<span class="wc-onsale-badge">' . esc_html__('Sale!', 'cruxstore') . '</span>', $post, $product);
    } elseif ($num_day < $time_new) {
        $badge = "<span class='wc-new-badge'>" . esc_html__('New', 'cruxstore') . "</span>";
    }

    if ($badge) {
        echo '<div class="product-badge">' . $badge . '</div>';
    }

}

if (!function_exists('cruxstore_template_loop_product_title')) {

    /**
     * Show the product title in the product loop. By default this is an H3.
     */
    function cruxstore_template_loop_product_title(){
        printf('<h3 class="product-title"><a href="%s">%s</a></h3>', get_the_permalink(), get_the_title());
    }
}


function cruxstore_template_loop_product_actions()
{

    echo "<div class='product-actions'>";
    
    if(!cruxstore_option('catalog_mode', 0)){
        if (class_exists('YITH_WCWL_UI')) {
            echo do_shortcode('[yith_wcwl_add_to_wishlist]');
        }
        if (defined('YITH_WOOCOMPARE')) {
            printf(
                '<div data-toggle="tooltip" class="yith-compare" title="%s" >%s</div>',
                esc_html__('Compare', 'cruxstore'),
                do_shortcode('[yith_compare_button container="no" type="link"]&nbsp;[/yith_compare_button]')
            );
        }
    }
    
    if(cruxstore_option('loop_shop_quickview', 1)){
        printf(
            '<div data-toggle="tooltip" data-placement="top" title="' . esc_html__('Quick View', 'cruxstore') . '"><a href="#" class="product-quick-view" data-id="%s">%s</a></div>',
            get_the_ID(),
            '<i class="fa fa-search"></i>'
        );
    }
    if(!cruxstore_option('catalog_mode', 0)){
        woocommerce_template_loop_add_to_cart();
    }
    echo "</div>";
}


function cruxstore_loop_add_to_cart_args($args, $product)
{
    $args['class'] = implode(' ', array_filter(array(
        'product_type_' . $product->product_type,
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
        $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : ''
    )));
    return $args;
}


function cruxstore_fronted_fronted_get_wishlist()
{
    ob_start();
    cruxstore_cart_wishlist();
    $data['html'] = ob_get_clean();
    wp_send_json($data);
}


function cruxstore_woocommerce_template_single_excerpt()
{

    global $post;

    $post_custom_excerpt = get_post_meta($post->ID, '_cruxstore_short_description', true);

    if ($post_custom_excerpt) {
        $post_excerpt = $post_custom_excerpt;
    } else {
        $post_excerpt = $post->post_excerpt;
    }

    if (!$post_excerpt) {
        return;
    }

    echo apply_filters('woocommerce_short_description', $post_excerpt);

}


function cruxstore_template_product_actions()
{

    echo "<div class='product-actions'>";

    if (class_exists('YITH_WCWL_UI')) {
        printf(
            '<div class="wishlist-action">%s</div>',
            do_shortcode('[yith_wcwl_add_to_wishlist]')
        );
    }

    if (defined('YITH_WOOCOMPARE')) {
        printf(
            '<div class="compare-action"><div data-toggle="tooltip" class="yith-compare" title="%s" >%s</div></div>',
            esc_html__('Compare', 'cruxstore'),
            do_shortcode('[yith_compare_button container="no" type="link"]')
        );
    }

    echo "</div>";
}

function cruxstore_remove_yith_wcwl_positions($positions)
{
    $positions['add-to-cart']['hook'] = '';
    return $positions;
}


function cruxstore_change_breadcrumb_delimiter($defaults)
{
    $defaults['delimiter'] = '<span class="delimiter">-</span>';
    return $defaults;
}




function woocommerce_after_shop_loop_item_sale_sale_price($product = false, $post = false)
{

    if (is_object($product)) {
        $product_id = $product->id;
    } elseif (is_object($post)) {
        $product_id = $post->ID;
    } else {
        global $post;
        $product_id = $post->ID;
    }

    if (!$product_id) {
        return;
    }

    $cache_key = 'time_sale_price_' . $product_id;
    $cache = wp_cache_get($cache_key);
    if ($cache) {
        echo $cache;
        return;
    }
    // Get variations
    $args = array(
        'post_type' => 'product_variation',
        'post_status' => array('private', 'publish'),
        'numberposts' => -1,
        'orderby' => 'menu_order',
        'order' => 'asc',
        'post_parent' => $product_id
    );
    $variations = get_posts($args);
    $variation_ids = array();
    if ($variations) {
        foreach ($variations as $variation) {
            $variation_ids[] = $variation->ID;
        }
    }
    $sale_price_dates_to = false;

    if (!empty($variation_ids)) {
        global $wpdb;
        $sale_price_dates_to = $wpdb->get_var("
            SELECT
            meta_value
            FROM $wpdb->postmeta
            WHERE meta_key = '_sale_price_dates_to' and post_id IN(" . join(',', $variation_ids) . ")
            ORDER BY meta_value DESC
            LIMIT 1
        ");

        if ($sale_price_dates_to != '') {
            $sale_price_dates_to = date('Y-m-d', $sale_price_dates_to);
        }
    }

    if (!$sale_price_dates_to) {
        $sale_price_dates_to = ($date = get_post_meta($product_id, '_sale_price_dates_to', true)) ? date_i18n('Y-m-d', $date) : '';
    }

    if ($sale_price_dates_to) {
        $cache = '<div class="woocommerce-countdown" data-time="' . $sale_price_dates_to . '"></div>';
        wp_cache_add($cache_key, $cache);
        echo $cache;
    } else {
        wp_cache_delete($cache_key);
    }
}

function cruxstore_output_product_description(){
    $layout = cruxstore_get_product_layout();
    if($layout == 'layout6'){
        echo '<div class="product-description-content">';
        woocommerce_product_description_tab();
        echo '</div>';
    }
}

function cruxstore_template_single_excerpt(){
    $layout = cruxstore_get_product_layout();
    if($layout == 'layout6'){
        return;
    }else{
        woocommerce_template_single_excerpt();
    }
}

function cruxstore_woocommerce_product_tabs($tabs){
    $layout = cruxstore_get_product_layout();
    if($layout == 'layout6') {
        $tabs['description']['callback'] = 'woocommerce_template_single_excerpt';
    }
    return $tabs;
}

function cruxstore_output_product_data_tabs_after(){
    $layout = cruxstore_get_product_layout();
    if($layout != 'layout6'){
        woocommerce_output_product_data_tabs();
    }
}


function woocommerce_output_product_data_accordion(){
    wc_get_template( 'single-product/accordions/accordions.php' );
}


function cruxstore_output_product_data_accordion(){
    $layout = cruxstore_get_product_layout();
    if($layout == 'layout6'){
        woocommerce_output_product_data_accordion();
    }
}


/**
 * Product Quick View callback AJAX request
 *
 */

function cruxstore_frontend_product_quick_view_callback()
{
    global $product, $post;
    $product_id = intval($_POST["product_id"]);
    $post = get_post($product_id);
    $product = wc_get_product($product_id);
    wc_get_template('content-single-product-quick-view.php');
    die();
}


function cruxstore_woocommerce_close_quickview()
{
    echo '<a class="close-quickview" href="#"><i class="fa fa-times"></i></a>';
}

function cruxstore_wc_breadcrumb(){
    $layout = cruxstore_get_product_layout();
    if($layout == 'layout1' || $layout == 'layout3' || $layout == 'layout5'){
        woocommerce_breadcrumb();
    }
}


/**
 * KT WooCommerce hooks
 *
 * @package cruxstore
 */

add_action('wp_ajax_fronted_get_wishlist', 'cruxstore_fronted_fronted_get_wishlist');
add_action('wp_ajax_nopriv_fronted_get_wishlist', 'cruxstore_fronted_fronted_get_wishlist');


if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
    add_filter('woocommerce_add_to_cart_fragments', 'cruxstore_cart_link_fragment');
} else {
    add_filter('add_to_cart_fragments', 'cruxstore_cart_link_fragment');
}



add_filter('body_class', 'cruxstore_wc_body_classes');
add_filter('loop_shop_columns', 'cruxstore_woo_shop_columns');
add_filter('loop_shop_per_page', 'cruxstore_product_shop_count');
add_filter('woocommerce_catalog_orderby', 'cruxstore_woocommerce_catalog_orderby');
add_filter('woocommerce_show_page_title', '__return_false');
add_filter('woocommerce_product_loop_start', 'cruxstore_woocommerce_product_loop_start_callback');
add_filter('woocommerce_breadcrumb_defaults', 'cruxstore_change_breadcrumb_delimiter');




/**
 * KT WooCommerce Products hooks
 *
 * @package cruxstore
 */

add_filter('woocommerce_loop_add_to_cart_args', 'cruxstore_loop_add_to_cart_args', 10, 2);


remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10);

remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);


add_action('cruxstore_wc_subcategory_thumbnail', 'cruxstore_wc_subcategory_thumbnail', 10, 2);

add_action('woocommerce_before_shop_loop', 'cruxstore_woocommerce_shop_loop');

add_action('woocommerce_shop_loop_subcategory_title', 'cruxstore_template_loop_category_title', 10);

add_action('woocommerce_shop_loop_item_title', 'cruxstore_template_loop_product_title', 20);
add_action('woocommerce_before_shop_loop_item', 'cruxstore_template_loop_product_link_open');
add_action('woocommerce_shop_loop_item_content', 'cruxstore_template_loop_product_actions', 5);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 20);
add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 15);
add_action('woocommerce_before_shop_loop_item_title', 'cruxstore_template_loop_product_thumbnail');
add_action('woocommerce_before_shop_loop_item', 'cruxstore_woocommerce_show_product_badge', 5);

add_action('woocommerce_after_shop_loop_item', 'cruxstore_template_loop_product_link_close');

add_action('woocommerce_shop_loop_item_details', 'cruxstore_woocommerce_template_single_excerpt', 5);
add_action('woocommerce_shop_loop_item_details', 'woocommerce_template_loop_add_to_cart', 10);
add_action('woocommerce_shop_loop_item_details', 'cruxstore_template_loop_product_actions', 15);

add_action('woocommerce_after_shop_loop_item_title', 'cruxstore_template_loop_rating', 15);
add_action('woocommerce_after_shop_loop_item_title', 'cruxstore_product_attribute_swatche', 20);


add_action('woocommerce_after_shop_loop_item_sale', 'woocommerce_template_loop_rating', 5);
add_action('woocommerce_after_shop_loop_item_sale', 'woocommerce_after_shop_loop_item_sale_sale_price', 10, 2);


add_action('wp_ajax_frontend_product_quick_view', 'cruxstore_frontend_product_quick_view_callback');
add_action('wp_ajax_nopriv_frontend_product_quick_view', 'cruxstore_frontend_product_quick_view_callback');


/**
 * KT WooCommerce Product detail
 *
 * @package cruxstore
 */

// Remove compare product
if (defined('YITH_WOOCOMPARE')) {
    global $yith_woocompare;
    remove_action('woocommerce_single_product_summary', array($yith_woocompare->obj, 'add_compare_link'), 35);
}
// Remove wishlist product
add_filter('yith_wcwl_positions', 'cruxstore_remove_yith_wcwl_positions');

remove_action('woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);

add_filter('woocommerce_product_description_heading', '__return_false');
add_filter('woocommerce_product_additional_information_heading', '__return_false');
add_action('woocommerce_product_images', 'cruxstore_woocommerce_show_product_badge', 10);
add_filter('woocommerce_product_tabs', 'cruxstore_woocommerce_product_tabs');

add_action('woocommerce_single_product_summary', 'cruxstore_wc_breadcrumb', 2);
add_action('woocommerce_single_product_summary', 'cruxstore_woocommerce_close_quickview', 2);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 13);

add_action('woocommerce_single_product_summary', 'cruxstore_template_single_excerpt', 10);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 20);
add_action('woocommerce_single_product_summary', 'cruxstore_template_product_actions', 35);
add_action('woocommerce_single_product_summary', 'cruxstore_output_product_data_accordion', 37);


add_action('woocommerce_before_add_to_cart_button_simple', 'woocommerce_template_single_price');

add_action('woocommerce_share', 'cruxstore_share_box');


add_action('woocommerce_after_single_product_summary', 'cruxstore_output_product_data_tabs_after', 10);
add_action('woocommerce_after_single_product_summary', 'cruxstore_output_product_description', 10);



/**
 * KT WooCommerce Cart & Checkout
 *
 * @package cruxstore
 */
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 10);
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);


