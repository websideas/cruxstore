<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/** 
 * Widget content
 * 
 */

if ( function_exists('register_sidebar')) {

    function delphinus_register_sidebars(){

        register_sidebar( array(
            'name' => esc_html__( 'Primary Widget Area', 'delphinus'),
            'id' => 'primary-widget-area',
            'description' => esc_html__( 'The primary widget area', 'delphinus'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Shop Widget Area', 'delphinus'),
            'id' => 'shop-widget-area',
            'description' => esc_html__( 'The shop widget area', 'delphinus'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Shop filter Area', 'delphinus'),
            'id' => 'shop-filter-area',
            'description' => esc_html__( 'The shop filter area', 'delphinus'),
            'before_widget' => '<div id="%1$s" class="widget col-lg-3 col-md-3 %2$s"><div class="widget-content">',
            'after_widget' => '</div></div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Blog Widget Area', 'delphinus'),
            'id' => 'blog-widget-area',
            'description' => esc_html__( 'The blog widget area', 'delphinus'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        $count = 5;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(esc_html__( 'Sidebar %s', 'delphinus'), $i) ,
                'id' => 'sidebar-column-'.$i,
                'description' => sprintf(esc_html__( 'The sidebar column %s widget area', 'delphinus'),$i),
                'before_widget' => '<section class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }


        register_sidebar( array(
            'name' => esc_html__( 'Footer top', 'delphinus'),
            'id' => 'footer-top',
            'description' => esc_html__( 'The footer top widget area', 'delphinus'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h4 class="widget-title">',
            'after_title' => '</h4>',
        ) );


        $count = 4;

        for($i=1; $i<=$count;$i++){
            register_sidebar( array(
                'name' => sprintf(esc_html__( 'Footer column %s', 'delphinus'), $i) ,
                'id' => 'footer-column-'.$i,
                'description' => sprintf(esc_html__( 'The footer column %s widget area', 'delphinus'),$i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }

        register_sidebar( array(
            'name' => esc_html__( 'Footer bottom column 1', 'delphinus'),
            'id' => 'footer-bottom-1',
            'description' => esc_html__( 'The footer bottom widget area', 'delphinus'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        register_sidebar( array(
            'name' => esc_html__( 'Footer bottom column 2', 'delphinus'),
            'id' => 'footer-bottom-2',
            'description' => esc_html__( 'The footer bottom widget area', 'delphinus'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );
        register_sidebar( array(
            'name' => esc_html__( 'Footer bottom column 3', 'delphinus'),
            'id' => 'footer-bottom-3',
            'description' => esc_html__( 'The footer bottom widget area', 'delphinus'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );


        $sidebars =  delphinus_option('custom_sidebars');
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
                        'before_title' => '<h3 class="widget-title">',
                        'after_title' => '</h3>',
                    ) );

                }
            }
        }

    }

    add_action( 'widgets_init', 'delphinus_register_sidebars' );

}



/**
 * This code filters the categories widget to include the post count inside the link
 */

add_filter('wp_list_categories', 'delphinus_cat_count_span');
function delphinus_cat_count_span($links) {

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

add_filter('get_archives_link', 'delphinus_archive_count_span');
function delphinus_archive_count_span($links) {
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
$delphinus_widgets = array(
    'delphinus_article.php',
    'delphinus_article_carousel.php',
    'delphinus_socials.php',
    'delphinus_fadein.php',
);

if(delphinus_is_wc()){
    $delphinus_wc_widgets = array(
        'delphinus_filter_color.php',
        'delphinus_filter_price.php',
        'delphinus_orderby.php',
    );
    $delphinus_widgets = array_merge($delphinus_widgets, $delphinus_wc_widgets);
}


foreach ( $delphinus_widgets as $widget ) {
	require_once  DELPHINUS_FW_WIDGETS . $widget;
}