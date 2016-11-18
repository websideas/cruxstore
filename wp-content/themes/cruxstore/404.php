<?php
/**
 * The template for displaying error 404
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */

$type = cruxstore_option('notfound_page_type', 'default');
/* Redirect Home */
if( $type == 'home'){
    wp_redirect( home_url() ); exit;
}

?>
<?php get_header(); ?>

<div class="wrapper-404">
    <div id="error404">
        <h1><?php esc_html_e('404', 'cruxstore') ?></h1>
        <h4><?php esc_html_e('Oops, page not found.', 'cruxstore') ?></h4>
        <p><?php echo wp_kses(__('It looks like nothing was found at this location. <br>Click the link below to return home.', 'cruxstore'), array( 'br' => array() ) ); ?></p>

        <p class="no-margin"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Return home', 'cruxstore') ?> <i class="cruxstore-icon-Right-3"></i></a></p>

    </div><!-- #main -->
</div>

<?php get_footer(); ?>
