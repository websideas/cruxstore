<?php
/**
 * Template for displaying search forms
 *
 */
?>

<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label class="screen-reader-text"><?php esc_html_e( 'Search', 'delphinus' ); ?></label>
    <input type="text" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'delphinus' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'delphinus' ); ?>" />
    <button class="submit">
        <i class="fa fa-search"></i>
        <span><?php echo esc_html_x( 'Search', 'submit button', 'delphinus' ); ?></span>
    </button>
</form>
