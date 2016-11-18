<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


global $woocommerce_carousel;

// Store loop count we're currently on
if ( empty( $woocommerce_carousel ) ) {
    $woocommerce_carousel = 'normal';
}


?>
<div class="category-banner">
    <?php
    /**
     * woocommerce_before_subcategory_title hook.
     *
     * @hooked cruxstore_wc_subcategory_thumbnail - 10
     */
    do_action( 'cruxstore_wc_subcategory_thumbnail', $category, $woocommerce_carousel );

    ?>
    <div class="category-banner-content">
        <?php

        //print_r($category);

        if($woocommerce_carousel == 'portrait'){
            printf('<div class="category-shop-for">%s</div>', esc_html__('shop for', 'cruxstore'));
            printf(
                '<h3><a href="%s" class="category-banner-link">%s</a></h3>',
                get_term_link( $category->slug, 'product_cat' ),

                $category->name
            );


        }elseif($woocommerce_carousel == 'modern'){
            printf(
                '<a href="%s" class="%s">%s</a>',
                get_term_link( $category->slug, 'product_cat' ),
                'btn btn-light',
                esc_html__('Shop now', 'cruxstore')
            );
        }else{
            printf('<h3>%s</h3>', $category->name);

            $args = array(
                'parent' => $category->term_id
            );
            $terms = get_terms( 'product_cat', $args );


            if(count($terms)){
                $terms_html = '';
                foreach($terms as $term){
                    $terms_html .= sprintf(
                        '<li><a href="%s">%s</a></li>',
                        get_term_link( $term->slug, 'product_cat' ),
                        $term->name
                    );
                }
                echo '<ul>'.$terms_html.'</ul>';
            }

            printf(
                '<a href="%s" class="%s">%s</a>',
                get_term_link( $category->slug, 'product_cat' ),
                'btn btn-light-b',
                esc_html__('Shop now', 'cruxstore')
            );
        }


        ?>
    </div>
</div>
