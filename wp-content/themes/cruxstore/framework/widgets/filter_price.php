<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Price Filter Widget and related functions.
 *
 * Generates a range slider to filter products by price.
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 * @extends  WC_Widget
 */
class WC_Widget_CruxStore_Price_Filter extends WC_Widget {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->widget_cssclass    = 'woocommerce widget_cruxstore_price_filter';
        $this->widget_description = esc_html__( 'Shows a price filter slider in a widget which lets you narrow down the list of shown products when viewing product categories.', 'woocommerce' );
        $this->widget_id          = 'wc_cruxstore_price_filter';
        $this->widget_name        = esc_html__( 'KT: WooCommerce Price Filter', 'woocommerce' );
        $this->settings           = array(
            'title'  => array(
                'type'  => 'text',
                'std'   => esc_html__( 'Filter by price', 'woocommerce' ),
                'label' => esc_html__( 'Title', 'woocommerce' )
            ),
            'range_size' => array(
                'type'  => 'number',
                'min'   => 1,
                'max'   => '',
                'step'  => 1,
                'std'   => 50,
                'label' => esc_html__( 'Price range size', 'cruxstore' )
            ),
            'max_ranges' => array(
                'type'  => 'number',
                'min'   => 1,
                'max'   => '',
                'step'  => 1,
                'std'   => 5,
                'label' => esc_html__( 'Max price ranges', 'cruxstore' )
            )
        );

        parent::__construct();
    }

    /**
     * Output widget.
     *
     * @see WP_Widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        global $wp, $wp_the_query;

		if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
			return;
		}
        
		if ( ! $wp_the_query->post_count ) {
			return;
		}
        
        
        // Remember current filters/search

        if ( '' == get_option( 'permalink_structure' ) ) {
            $link_url = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
        } else {
            $link_url = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
        }
        
        
        if ( get_search_query() ) {
            $link_url = add_query_arg( 's', get_search_query(), $link_url );
        }

        if ( ! empty( $_GET['post_type'] ) ) {
            $link_url = add_query_arg( 'post_type', urlencode( $_GET['post_type'] ), $link_url );
        }

        if ( ! empty ( $_GET['product_cat'] ) ) {
            $link_url = add_query_arg( 'product_cat', urlencode( $_GET['product_cat'] ), $link_url );
        }

        if ( ! empty( $_GET['product_tag'] ) ) {
            $link_url = add_query_arg( 'product_tag', urlencode( $_GET['product_tag'] ), $link_url );
        }

        if ( ! empty( $_GET['orderby'] ) ) {
            $link_url = add_query_arg( 'orderby', urlencode( $_GET['orderby'] ), $link_url );
        }
        
        
        
        if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
			foreach ( $_chosen_attributes as $attribute => $data ) {
				$taxonomy_filter = 'filter_' . str_replace( 'pa_', '', $attribute );
                
                $link_url = add_query_arg( $taxonomy_filter , urlencode( implode( ',', $data['terms'] ) ), $link_url );

				if ( 'or' == $data['query_type'] ) {
				    $link_url = add_query_arg( str_replace( 'pa_', 'query_type_', $attribute ) , 'or', $link_url );
				}
			}
		}
        
        // Find min and max price in current result set
		$prices = $this->get_filtered_price();
		$min    = floor( $prices->min_price );
		$max    = ceil( $prices->max_price );

		if ( $min === $max ) {
			return;
		}
        
        
        $this->widget_start( $args, $instance );
        
        /**
		 * Adjust max if the store taxes are not displayed how they are stored.
		 * Min is left alone because the product may not be taxable.
		 * Kicks in when prices excluding tax are displayed including tax.
		 */
		if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
			$tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
			$class_max   = $max;

			foreach ( $tax_classes as $tax_class ) {
				if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
					$class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );
				}
			}

			$max = $class_max;
		}
        
		$minprice = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
		$maxprice = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';
        
        $output = '';

        $min_price = 0;
        $range_size = intval( $instance['range_size'] );
        $max_ranges = ( intval( $instance['max_ranges'] ) - 1 );
        $count = 0;
        
        if ( strlen( $minprice ) > 0 ) {
            $output .= '<li><a href="' . esc_url( $link_url ) . '">' . esc_html__( 'All', 'cruxstore' ) . '</a></li>';
        } else {
            $output .= '<li class="selected">' . esc_html__( 'All', 'cruxstore' ) . '</li>';
        }
        
        while($count <= $max_ranges){

            $step = $min_price;
            $min_price += $range_size;

            if($count != $max_ranges ){
                if($min_price > $max){
                    $min_price = $max;
                }
                $link = add_query_arg( array( 'min_price' => $step, 'max_price' => $min_price ), $link_url );
                $price_text = wc_price($step).' - '.wc_price($min_price);
            }else{
                $link = add_query_arg( array( 'min_price' => $step, 'max_price' => $max ), $link_url );
                $price_text = wc_price($step).'+';
            }

            if($step == $minprice && $min_price == $maxprice){
                $output .= '<li class="selected">' . $price_text . '</li>';
            }else{
                $output .= '<li><a href="' . esc_url( $link ) . '">' . $price_text . '</a></li>';
            }

            $count++;
            if($min_price == $max){
                break;
            }

        }


        printf('<ul>%s</ul>', $output);
        
        
        $this->widget_end( $args );
        
        
    }
    
    /**
	 * Get filtered min price for current products.
	 * @return int
	 */
	protected function get_filtered_price() {
		global $wpdb, $wp_the_query;

		$args       = $wp_the_query->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = array(
				'taxonomy' => $args['taxonomy'],
				'terms'    => array( $args['term'] ),
				'field'    => 'slug',
			);
		}

		foreach ( $meta_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( CAST( price_meta.meta_value AS UNSIGNED ) ) as min_price, max( CAST( price_meta.meta_value AS UNSIGNED ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		return $wpdb->get_row( $sql );
	}
    
}


/**
 * Register Widget Price Filter widget
 *
 *
 */

register_widget('WC_Widget_CruxStore_Price_Filter');
