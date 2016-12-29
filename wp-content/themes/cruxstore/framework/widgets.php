<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/** 
 * Widget content
 * 
 */

if ( function_exists('register_sidebar')) {

    function cruxstore_register_sidebars(){

        register_sidebar( array(
            'name' => esc_html__( 'Primary Widget Area', 'cruxstore'),
            'id' => 'primary-widget-area',
            'description' => esc_html__( 'The primary widget area', 'cruxstore'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Shop filter Area', 'cruxstore'),
            'id' => 'shop-filter-area',
            'description' => esc_html__( 'The shop filter area', 'cruxstore'),
            'before_widget' => '<div id="%1$s" class="widget col-lg-3 col-md-3 %2$s"><div class="widget-content">',
            'after_widget' => '</div></div>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ) );


        register_sidebar( array(
            'name' => esc_html__( 'Shop Widget Area', 'cruxstore'),
            'id' => 'shop-widget-area',
            'description' => esc_html__( 'The shop widget area', 'cruxstore'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Blog Widget Area', 'cruxstore'),
            'id' => 'blog-widget-area',
            'description' => esc_html__( 'The blog widget area', 'cruxstore'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
        ) );

        $count = 3;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(esc_html__( 'Global Banner Column %s', 'cruxstore'), $i) ,
                'id' => 'global-banner-'.$i,
                'description' => esc_html__( 'This widget area is used to display widgets in the global banner. You can select the amount of columns in the theme options panel.', 'cruxstore'),
                'before_widget' => '<section class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<!--',
                'after_title' => '-->',
            ) );
        }

        $count = 5;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(esc_html__( 'Sidebar %s', 'cruxstore'), $i) ,
                'id' => 'sidebar-column-'.$i,
                'description' => sprintf(esc_html__( 'The sidebar column %s widget area', 'cruxstore'),$i),
                'before_widget' => '<section class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title"><span>',
                'after_title' => '</span></h3>',
            ) );
        }

        register_sidebar(array(
            'name' => esc_html__( 'Instagram Footer', 'cruxstore'),
            'id' => 'instagram-footer',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title"><span>',
            'after_title' => '</span></h3>',
            'description' => esc_html__('Use the Instagram widget here. IMPORTANT: For best result select "Small" under "Photo Size" and set number of photos to 6.', 'cruxstore'),
        ));

        $count = 3;
        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(esc_html__( 'Footer Top Column %s', 'cruxstore'), $i) ,
                'id' => 'footer-top-'.$i,
                'description' => sprintf(esc_html__( 'The footer top column %s widget area', 'cruxstore'),$i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<!--',
                'after_title' => '-->',
            ) );
        }
        $count = 5;
        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(esc_html__( 'Footer Column %s', 'cruxstore'), $i) ,
                'id' => 'footer-column-'.$i,
                'description' => sprintf(esc_html__( 'The footer column %s widget area', 'cruxstore'),$i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title"><span>',
                'after_title' => '</span></h3>',
            ) );
        }
        $count = 3;
        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(esc_html__( 'Footer Bottom Column %s', 'cruxstore'), $i) ,
                'id' => 'footer-bottom-'.$i,
                'description' => sprintf(esc_html__( 'The footer bottom column %s widget area', 'cruxstore'),$i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title"><span>',
                'after_title' => '</span></h3>',
            ) );
        }

        $sidebars =  cruxstore_option('custom_sidebars');
        if( !empty( $sidebars ) && is_array( $sidebars ) ){
            foreach( $sidebars as $sidebar ){
                $sidebar =  wp_parse_args($sidebar, array('title'=>'','description'=>''));
                if(  $sidebar['title'] !='' ){
                    $id = sanitize_title( $sidebar['title'] );
                    register_sidebar( array(
                        'name' => $sidebar['title'],
                        'id' => $id,
                        'description' => $sidebar['description'],
                        'before_widget' => '<div id="%1$s" class="widget %2$s">',
                        'after_widget' => '</div>',
                        'before_title' => '<h3 class="widget-title"><span>',
                        'after_title' => '</span></h3>',
                    ) );
                }
            }
        }

    }

    add_action( 'widgets_init', 'cruxstore_register_sidebars' );

}



/**
 * This code filters the categories widget to include the post count inside the link
 */

add_filter('wp_list_categories', 'cruxstore_cat_count_span');
function cruxstore_cat_count_span($links) {

    if (strpos($links, '</a>') !== false) {
        $links = str_replace('</a> (', ' <span class="count">(', $links);
        $links = str_replace('</a> <', ' <', $links);
        $links = str_replace(')', ')</span></a>', $links);
        $links = str_replace('</a></span>', '</a>', $links);
    }


    return $links;
}

/**
 * This code filters the Archive widget to include the post count inside the link
 */

add_filter('get_archives_link', 'cruxstore_archive_count_span');
function cruxstore_archive_count_span($links) {
    if ( strpos($links, '</a>') !== false ) {
        $links = str_replace('</a>&nbsp;(', ' <span class="count">(', $links);
        $links = str_replace(')', ')</span></a>', $links);
    }
    return $links;
}


/**
 * Include widgets.
 *
 */

// Widgets list
$cruxstore_widgets = array(
    'article.php',
    'article_carousel.php',
    'socials.php',
    'menu.php'
);

if(cruxstore_is_wc()){
    $cruxstore_wc_widgets = array(
        'filter_color.php',
        'filter_price.php',
        'orderby.php',
    );
    $cruxstore_widgets = array_merge($cruxstore_widgets, $cruxstore_wc_widgets);
}


foreach ( $cruxstore_widgets as $widget ) {
	require_once  CRUXSTORE_FW_WIDGETS . $widget;
}