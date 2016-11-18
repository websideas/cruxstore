<?php
/**
 * The template for displaying Shop
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 */

$sidebar = cruxstore_get_woo_sidebar();


$main_column = ($sidebar['sidebar']) ? '9' : '12';
$sidebar_class = ($sidebar['sidebar']) ? 'sidebar-'.$sidebar['sidebar'] : 'no-sidebar';
$pull_class = ($sidebar['sidebar'] == 'left') ? 'pull-right' : '';

get_header(); ?>

<div id="primary" class="content-area <?php echo esc_attr($sidebar_class); ?>">
    <div class="content-area-inner">
        <?php if ( is_singular( 'product' ) ) { ?>
            <?php woocommerce_content(); ?>
        <?php }else{ ?>
            <div class="container">
                <div class="row">
                    <?php
                    printf(
                        '<div id="main" class="site-main col-lg-%1$s col-md-%1$s col-sm-12 col-xs-12 %2$s" role="main">',
                        esc_attr($main_column),
                        esc_attr($pull_class)
                    );
                    ?>
                    <?php woocommerce_content(); ?>

                </div><!-- .site-main -->

                <?php if($sidebar['sidebar']){ ?>
                    <?php echo '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 side-bar main-sidebar">'; ?>
                        <div class="side-bar-inner">
                            <?php dynamic_sidebar($sidebar['sidebar_area']); ?>
                        </div>
                    </div><!-- .sidebar -->
                <?php } ?>
                </div><!-- .row -->
            </div><!-- .container -->
        <?php } ?>

    </div><!-- .content-area-inner -->
</div><!-- #primary -->


<?php get_footer(); ?>


