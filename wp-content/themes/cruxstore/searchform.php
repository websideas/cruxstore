<?php
/**
 * Template for displaying search forms
 *
 */
?>

<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="wrap_product_cat">
        <?php
        $args = array(
            'taxonomy' => 'category',
            'id' => 'cat_'.rand(),
            'hierarchical' => 1,
            'show_option_all' => __('All Categories', 'wingman')
        );
        $args = apply_filters( 'cruxstore_search_categories', $args );
        wp_dropdown_categories($args);
        ?>
    </div>
    <label class="screen-reader-text"><?php esc_html_e( 'Search now', 'cruxstore' ); ?></label>
    <input type="text" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'cruxstore' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'cruxstore' ); ?>" />
    <button class="submit">
        <i class="fa fa-search" aria-hidden="true"></i>
        <span><?php echo esc_html_x( 'Search', 'submit button', 'cruxstore' ); ?></span>
    </button>
</form>
