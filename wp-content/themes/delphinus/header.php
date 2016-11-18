<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @since 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
    <!-- W3TC-include-css -->
	<?php wp_head(); ?>
</head>
<body <?php body_class( ); ?>>
    <?php
    $header_layout = delphinus_get_header_layout();
    $header_position = delphinus_get_header_position();
    do_action( 'delphinus_body_top' );
    ?>
    <div id="page_outter">
        <div id="page" class="hfeed site">
            <div id="wrapper-content" class="content-header-<?php echo esc_attr($header_layout); ?>">

                <?php
                do_action( 'delphinus_before_header' ); ?>

                <div class="header-container header-layout<?php echo esc_attr($header_layout); ?> <?php echo esc_attr(apply_filters('delphinus_header_class', '', $header_layout, $header_position)); ?>">

                    <?php
                    get_template_part( 'templates/headers/header',  'mobile');
                    get_template_part( 'templates/headers/header',  'mobilenav');

                    if($header_position == 'below'){
                        do_action( 'delphinus_slideshows_position' );
                    }
                    ?>

                    <header id="header" class="<?php echo apply_filters('delphinus_header_content_class', 'header-content', $header_layout) ?>">
                        <?php get_template_part( 'templates/headers/header', $header_layout); ?>
                    </header><!-- #header -->

                    <?php
                    if($header_position != 'below'){
                        do_action( 'delphinus_slideshows_position' );
                    } ?>
                </div><!-- .header-container -->

                <?php
                do_action( 'delphinus_before_content' ); ?>

                <div id="content" class="<?php echo apply_filters('delphinus_content_class', 'site-content') ?>">
                    <?php
                    do_action( 'delphinus_content_top' ); ?>