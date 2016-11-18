<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 */

$sidebar = cruxstore_get_page_sidebar();

$main_column = ($sidebar['sidebar']) ? '8' : '12';
$sidebar_class = ($sidebar['sidebar']) ? 'sidebar-'.$sidebar['sidebar'] : 'no-sidebar';
$pull_class = ($sidebar['sidebar'] == 'left') ? 'pull-right' : '';

get_header(); ?>

<div id="primary" class="content-area <?php echo esc_attr($sidebar_class); ?>">
    <div class="content-area-inner">
        <div class="container">
            <div class="row">
                <?php
                    printf(
                        '<div id="main" class="site-main col-lg-%1$s col-md-%1$s col-sm-12 col-xs-12 %2$s" role="main">',
                        esc_attr($main_column),
                        esc_attr($pull_class)
                    );
                ?>
                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php
                        do_action( 'cruxstore_page_before' ); ?>

                        <?php get_template_part( 'templates/content', 'page' ); ?>

                        <?php
                        do_action( 'cruxstore_page_after' ); ?>

                        <?php
                            if( cruxstore_option( 'show_page_comment', 1 ) ){
                                if ( comments_open() || get_comments_number() ) :
                                    comments_template();
                                endif;
                            }
                        ?>
                    <?php endwhile; // end of the loop. ?>
                <?php echo '</div><!-- .site-main -->'; ?>
                <?php if($sidebar['sidebar']){ ?>
                    <?php echo '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 side-bar main-sidebar">'; ?>
                        <div class="side-bar-inner">
                            <?php dynamic_sidebar($sidebar['sidebar_area']); ?>
                        </div>
                    <?php echo '</div><!-- .sidebar -->'; ?>
                <?php } ?>
            </div><!-- .row -->
        </div><!-- .container -->
    </div>
</div><!-- #primary -->


<?php get_footer(); ?>

