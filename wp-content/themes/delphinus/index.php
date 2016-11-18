<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 */

$sidebar = delphinus_get_archive_sidebar();

$main_column = ($sidebar['sidebar']) ? '9' : '12';
$sidebar_class = ($sidebar['sidebar']) ? 'sidebar-'.$sidebar['sidebar'] : 'no-sidebar';
$pull_class = ($sidebar['sidebar'] == 'left') ? 'pull-right' : '';

get_header(); ?>

<div id="primary" class="content-area <?php echo esc_attr($sidebar_class); ?>">
    <div class="content-area-inner">
        <div class="container">
            <div class="row">
                <?php
                    printf(
                        '<div id="main" class="site-main col-sm-12 col-xs-12 col-md-%s %s" role="main">',
                        esc_attr($main_column),
                        esc_attr($pull_class)
                    );
                ?>
                <?php if ( have_posts() ) : ?>
                    <?php get_template_part( 'loop' ); ?>
                <?php else : ?>
                    <?php get_template_part( 'content', 'none' ); ?>
                <?php endif; ?>
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
    </div><!-- .content-area-inner -->
</div><!-- #primary -->
<?php get_footer(); ?>



