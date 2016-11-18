<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;



/**
 * Add custom favicon
 *
 * @since 1.0
 */
function cruxstore_add_site_icon(){
    if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
        $custom_favicon = cruxstore_option( 'custom_favicon' );
        $custom_favicon_iphone = cruxstore_option( 'custom_favicon_iphone' );
        $custom_favicon_iphone_retina = cruxstore_option( 'custom_favicon_iphone_retina' );
        $custom_favicon_ipad = cruxstore_option( 'custom_favicon_ipad' );
        $custom_favicon_ipad_retina = cruxstore_option( 'custom_favicon_ipad_retina' );
        if($custom_favicon['url']){
            printf( '<link rel="shortcut icon" href="%s"/>', esc_url($custom_favicon['url']) );
        }
        if($custom_favicon_iphone['url']) {
            printf('<link rel="apple-touch-icon" href="%s"/>', esc_url($custom_favicon_iphone['url']));
        }
        if($custom_favicon_ipad['url']) {
            printf('<link rel="apple-touch-icon" sizes="72x72" href="%s"/>', esc_url($custom_favicon_ipad['url']));
        }
        if($custom_favicon_iphone_retina['url']) {
            printf('<link rel="apple-touch-icon" sizes="114x114" href="%s"/>', esc_url($custom_favicon_iphone_retina['url']));
        }
        if($custom_favicon_ipad_retina['url']) {
            printf('<link rel="apple-touch-icon" sizes="144x144" href="%s"/>', esc_url($custom_favicon_ipad_retina['url']));
        }
    }
}
add_action( 'wp_head', 'cruxstore_add_site_icon');


/**
 * Flag boolean.
 *
 * @param $input string
 * @return boolean
 */
function cruxstore_sanitize_boolean( $input = '' ) {
    $input = (string)$input;
    return in_array($input, array('1', 'true', 'y', 'on'));
}
add_filter( 'sanitize_boolean', 'cruxstore_sanitize_boolean', 15 );



if ( ! function_exists( 'cruxstore_page_loader' ) ) :
    /**
     * Add page loader to frontend
     *
     */
    function cruxstore_page_loader(){
        $use_loader = cruxstore_option( 'use_page_loader', 0 );
        if( $use_loader ){
            $svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                        <path d="M3.7,12h10.6l15.1,54.6c0.4,1.6,1.9,2.7,3.6,2.7h46.4c1.5,0,2.8-0.9,3.4-2.2l16.9-38.8c0.5-1.2,0.4-2.5-0.3-3.5c-0.7-1-1.8-1.7-3.1-1.7H45c-2,0-3.7,1.7-3.7,3.7s1.7,3.7,3.7,3.7h45.6L76.9,62H35.8L20.7,7.3c-0.4-1.6-1.9-2.7-3.6-2.7H3.7C1.7,4.6,0,6.3,0,8.3S1.7,12,3.7,12z"/>
                        <path d="M29.5,95.4c4.6,0,8.4-3.8,8.4-8.4s-3.8-8.4-8.4-8.4s-8.4,3.8-8.4,8.4C21.1,91.6,24.8,95.4,29.5,95.4z"/>
                        <path d="M81.9,95.4c0.2,0,0.4,0,0.6,0c2.2-0.2,4.3-1.2,5.7-2.9c1.5-1.7,2.2-3.8,2-6.1c-0.3-4.6-4.3-8.1-8.9-7.8s-8.1,4.4-7.8,8.9C73.9,91.9,77.5,95.4,81.9,95.4z"/>
                    </svg>';
            ?>
            <div class="page-loading-wrapper">
                <div class="progress-bar-loading">
                    <?php printf('<div class="back-loading progress-bar-inner">%1$s</div><div class="front-loading progress-bar-inner">%1$s</div>', $svg); ?>
                    <div class="progress-bar-number">0%</div>
                </div>
            </div>
        <?php
        }
    }
    add_action( 'cruxstore_body_top', 'cruxstore_page_loader');
endif;


/**
 * Add class to next button
 *
 * @param string $attr
 * @return string
 */
function cruxstore_next_posts_link_attributes( $attr = '' ) {
    return "class='btn btn-default'";
}
add_filter( 'next_posts_link_attributes', 'cruxstore_next_posts_link_attributes', 15 );



function cruxstore_add_search_full(){
    if(cruxstore_option('header_search', 1)){
        $header_search_type = cruxstore_option('header_search_type', 'all');

        if( $header_search_type == 'product' && cruxstore_is_wc()){
            $search = get_product_search_form(false);
        }else{
            $search = get_search_form(false);
        }

        printf(
            '<div id="%1$s" class="%2$s">%3$s</div>',
            'search-fullwidth',
            'mfp-hide mfp-with-anim',
            $search
        );
    }
}
add_action('cruxstore_body_top', 'cruxstore_add_search_full', 999);



/**
 * Add popup
 *
 * @since 1.0
 */

function cruxstore_body_top_add_popup(){
    $popup_id = "";
    $enable_popup = cruxstore_option( 'enable_popup', 0 );
    if( $enable_popup == 1 && !isset($_COOKIE['cruxstore_popup']) ){
        $popup_id = ' id="popup-wrap"';

        $content_popup = cruxstore_option( 'content_popup' );
        $time_show = cruxstore_option( 'time_show', 0 );
        $image_popup = cruxstore_option( 'popup_image' );
        $popup_form = cruxstore_option( 'popup_form' );

        $main_class = ( $image_popup['url'] ) ? 'col-md-8 col-sm-8' : 'col-md-12 col-sm-12';

        ?>
        <div <?php echo $popup_id; ?> class="popup-wrap-newletter mfp-hide mfp-with-anim" data-timeshow="<?php echo esc_attr($time_show); ?>">
            <div class="container-fluid">
                <div class="wrapper-newletter-content">
                    <div class="row no-gutters">
                        <?php if( $image_popup['url'] ){ ?>
                            <div class="col-md-4 col-sm-4 newletter-popup-img hidden-xs">
                                <img src="<?php echo esc_attr($image_popup['url']); ?>" alt="" class="img-responsive"/>
                            </div>
                        <?php } ?>
                        <div class="<?php echo esc_attr($main_class); ?> wrapper-newletter-popup">
                            <div class="newletter-popup-content">
                                <?php
                                echo apply_filters('the_content', $content_popup);
                                if($popup_form){
                                    printf('<div class="newletter-popup-form">%s</div>', apply_filters('the_content', $popup_form));
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}

add_action( 'cruxstore_body_top', 'cruxstore_body_top_add_popup', 20 );


/**
 * Add class to prev button
 *p
 * @param string $attr
 * @return string
 */
function cruxstore_previous_posts_link_attributes( $attr = '' ) {
    return "class='btn btn-default'";
}
add_filter( 'previous_posts_link_attributes', 'cruxstore_previous_posts_link_attributes', 15 );


if(!function_exists('cruxstore_placeholder_callback')) {
    /**
     * Return PlaceHolder Image
     * @param string $size
     * @return string
     */
    function cruxstore_placeholder_callback($size = '')
    {

        $placeholder = cruxstore_option('archive_placeholder');
        if(is_array($placeholder) && $placeholder['id'] != '' ){
            $obj = cruxstore_get_thumbnail_attachment($placeholder['id'], $size);
            $imgage = $obj['url'];
        }elseif($size == 'cruxstore_grid' || $size == 'cruxstore_masonry') {
            $imgage = CRUXSTORE_THEME_IMG . 'placeholder-recent.jpg';
        }elseif ($size == 'cruxstore_classic'){
            $imgage = CRUXSTORE_THEME_IMG . 'placeholder-blogpost.jpg';
        }elseif($size == 'cruxstore_list'){
            $imgage = CRUXSTORE_THEME_IMG . 'placeholder-list.jpg';
        }elseif($size == 'cruxstore_small'){
            $imgage = CRUXSTORE_THEME_IMG . 'placeholder-small.jpg';
        }elseif($size == 'cruxstore_zigzag'){
            $imgage = CRUXSTORE_THEME_IMG . 'placeholder-zigzag.jpg';
        }elseif($size == 'cruxstore_widgets'){
            $imgage = CRUXSTORE_THEME_IMG . 'placeholder-widgets.jpg';
        }else{
            $imgage = CRUXSTORE_THEME_IMG . 'placeholder-post.jpg';
        }

        return $imgage;
    }
    add_filter('cruxstore_placeholder', 'cruxstore_placeholder_callback');
}


/**
 * Custom password form
 *
 * @return string
 */
function cruxstore_password_form() {
    global $post;
    $o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
    <p>' . esc_html__( "To view this protected post, enter the password below:", 'cruxstore' ) . '</p>
    <div class="input-group"><input name="post_password" type="password" size="20" maxlength="20" /><span class="input-group-btn"><input type="submit" class="btn btn-dark" name="Submit" value="' . esc_attr__( "Submit", 'cruxstore' ) . '" /></span></div>
    </form>
    ';
    return $o;
}
add_filter( 'the_password_form', 'cruxstore_password_form' );


/**
 * Extend the default WordPress body classes.
 *
 * @since 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function cruxstore_body_classes( $classes ) {


    $classes[] = 'appear-animate';

    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
        $classes[]	= 'no-wc-breadcrumb';
    }

    if( is_page() || is_singular('post')){

        global $post;

        $classes[] = 'layout-'.cruxstore_getlayout($post->ID);
        $classes[] = cruxstore_meta('_cruxstore_extra_page_class');

        $type = cruxstore_meta('_cruxstore_type_page');
        if($type){
            $classes[] = 'page-type-'.$type;
        }

    }else{
        $classes[] = 'layout-'.cruxstore_option('layout', 'boxed');
    }

    return $classes;
}
add_filter( 'body_class', 'cruxstore_body_classes' );


/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function cruxstore_widget_tag_cloud_args( $args ) {
    $args['largest'] = 1;
    $args['smallest'] = 1;
    $args['number']   = 15;
    $args['unit'] = 'em';
    return $args;
}
add_filter( 'widget_tag_cloud_args', 'cruxstore_widget_tag_cloud_args' );


/**
 * Add page header
 *
 * @since 1.0
 */

function cruxstore_page_header( ){

    global $post;
    $show_title = false;

    if ( is_front_page() && !is_singular('page')){
        $show_title = cruxstore_meta('_cruxstore_page_header', array(), get_option('page_on_front', true));
        if(cruxstore_is_wc()) {
            if (is_shop()) {
                $page_id = get_option('woocommerce_shop_page_id');
                $show_title = cruxstore_meta('_cruxstore_page_header', array(), $page_id);
            }
        }
        if( !$show_title ){
            $show_title = cruxstore_option('show_page_header', 1);
        }
    }elseif(is_archive()){
        $show_title = cruxstore_option('archive_page_header', 1);
    }elseif(is_search()){
        $show_title = cruxstore_option('search_page_header', 1);
    }elseif(is_404()){
        $show_title = false;
    }else{
        if(is_page()){
            $post_id = $post->ID;
            $show_title = cruxstore_meta('_cruxstore_page_header', array(), $post_id);
            if( !$show_title ){
                $show_title = cruxstore_option('show_page_header', 1);
            }
        }else{
            $show_title = cruxstore_option('show_page_header', 1);
        }

        if(cruxstore_is_wc()){
            if(is_product()){
                $layout = cruxstore_get_product_layout();
                if($layout == 'layout1' || $layout == 'layout3' || $layout == 'layout5'){
                    $show_title = false;
                }else{
                    $show_title = true;
                }

            }
        }
    }

    $show_title = apply_filters( 'cruxstore_show_title', $show_title );
    if($show_title == 'on' || $show_title == 1){

        $page_header_layout = cruxstore_get_page_layout();
        $page_header_align = cruxstore_get_page_align();

        $classes = array('page-header', 'ph-align-'.$page_header_align, 'page-header-'.$page_header_layout);

        $heading = cruxstore_get_title_heading();


        if($heading){
            $title = cruxstore_get_page_title();
            $subtitle = cruxstore_get_page_subtitle();
            $title_tag = (is_singular('post')) ? 'h3' : 'h1';
            $title = '<'.$title_tag.' class="page-header-title">'.$title.'</'.$title_tag.'>';
            if($subtitle != ''){
                $subtitle = '<div class="page-header-subtitle">'.$subtitle.'</div>';
            }
        }else{
            $title = '';
            $subtitle = '';
            $classes[] = 'page-header-noheading';
        }

        $breadcrumb_class = (!cruxstore_option('title_breadcrumbs_mobile', false)) ? 'hidden-xs hidden-sm' : '';
        $breadcrumb = cruxstore_get_breadcrumb();

        if($breadcrumb == '' || $page_header_layout == 'centered'){
            $layout = '%1$s%2$s%3$s';
        }else{
            if($breadcrumb != ''){
                if($page_header_align == 'right'){
                    $layout = '<div class="row"><div class="col-md-8 page-header-right">%1$s%2$s</div><div class="col-md-4 page-header-left %4$s">%3$s</div></div>';
                }else{
                    $layout = '<div class="row"><div class="col-md-8 page-header-left">%1$s%2$s</div><div class="col-md-4 page-header-right %4$s">%3$s</div></div>';
                }
            }else{
                $layout = '%1$s%2$s%3$s%4$s';
            }
        }

        $output = sprintf(
            $layout,
            $title,
            $subtitle,
            $breadcrumb,
            $breadcrumb_class
        );

        printf(
            '<div class="%s"><div class="container">%s</div></div>',
            esc_attr(implode(' ', $classes)),
            $output
        );

    }
}
add_action( 'cruxstore_before_content', 'cruxstore_page_header', 20 );



/**
 * Get breadcrumb
 *
 * @param string $breadcrumb
 * @return mixed|void
 */
function cruxstore_get_breadcrumb($breadcrumb = ''){
    $show = '';

    if( is_page() || is_singular() ){
        $show_option = cruxstore_meta( '_cruxstore_show_breadcrumb' );
        if($show_option != ''){
            $show = $show_option;
        }
    }elseif ( is_front_page() && !is_singular('page') ) {
        $show_option = cruxstore_meta( '_cruxstore_show_breadcrumb' );
        if($show_option != ''){
            $show = $show_option;
        }
    }elseif ( is_home() ) {
        $page_for_posts = get_option('page_for_posts', true);
        $show_option = cruxstore_meta( '_cruxstore_show_breadcrumb', array(), $page_for_posts );
        if($show_option != ''){
            $show = $show_option;
        }
    }elseif ( is_archive() ){
        if(cruxstore_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                if($shop_page_id){
                    $show = cruxstore_meta('_cruxstore_show_breadcrumb', array(), $shop_page_id);
                }
            }
        }
    }

    if($show == '' || $show == '-1'){
        $show = cruxstore_option('title_breadcrumbs');
    }

    if($show && function_exists( 'woocommerce_breadcrumb' )){
        ob_start();
        woocommerce_breadcrumb();
        $breadcrumb .= ob_get_clean();
    }
    return apply_filters( 'cruxstore_breadcrumb', $breadcrumb );
}



/**
 * Get title heading
 *
 * @return mixed|void
 */
function cruxstore_get_title_heading(){
    $show = '';
    if( is_page() || is_singular() ){
        $show_option = cruxstore_meta( '_cruxstore_show_title' );
        if($show_option != ''){
            $show = $show_option;
        }

        if(is_singular()){
            $show = '0';
        }

        if(cruxstore_is_wc()){
            if(is_product()){
                $show = '0';
            }
        }

    }elseif ( is_front_page() && !is_singular('page') ) {
        $show_option = cruxstore_meta( '_cruxstore_show_title' );
        if($show_option != ''){
            $show = $show_option;
        }
    }elseif ( is_home() ) {
        $page_for_posts = get_option('page_for_posts', true);
        $show_option = cruxstore_meta( '_cruxstore_show_title', array(), $page_for_posts );
        if($show_option != ''){
            $show = $show_option;
        }
    }elseif ( is_archive() ){
        if(cruxstore_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                if($shop_page_id){
                    $show = cruxstore_meta('_cruxstore_show_title', array(), $shop_page_id);
                }
            }
        }
    }
    if($show == '' || $show == '-1'){
        $show = cruxstore_option('title_heading');
    }

    return apply_filters( 'cruxstore_title_heading', $show );
}



/**
 * Get page title
 *
 * @param string $title
 * @return mixed|void
 */

function cruxstore_get_page_title( $title = '' ){
    global $post;

    if ( is_front_page() && !is_singular('page') ) {
        $title = esc_html__( 'Blog', 'cruxstore' );
        if(cruxstore_is_wc()) {
            if (is_shop()) {
                $shop_page_id = get_option('woocommerce_shop_page_id');
                $custom_text = cruxstore_meta('_cruxstore_page_header_custom', array(), $shop_page_id);
                $title = ($custom_text != '') ? $custom_text : get_the_title($shop_page_id);
                $title = apply_filters( 'the_title', $title, $shop_page_id );
            }
        }
    } elseif ( is_search() ) {
        $title = esc_html__( 'Search', 'cruxstore' );
    } elseif( is_home() ){
        $page_for_posts = get_option('page_for_posts', true);
        $custom_text = cruxstore_meta('_cruxstore_page_header_custom', array(), $page_for_posts);
        $title = ($custom_text != '') ? $custom_text : get_the_title($page_for_posts);
        $title = apply_filters( 'the_title', $title, $page_for_posts );
    } elseif( is_404() ) {
        $title = esc_html__( 'Page not found', 'cruxstore' );
    } elseif ( is_archive() ){
        $title = get_the_archive_title();
        if(cruxstore_is_wc()) {
            if (is_shop()) {
                $shop_page_id = get_option('woocommerce_shop_page_id');
                $title = get_the_title($shop_page_id);
                $title = apply_filters( 'the_title', $title, $shop_page_id );
            }
        }
    } elseif ( is_front_page() && is_singular('page') ){
        $page_on_front = get_option('page_on_front', true);
        $title = get_the_title($page_on_front) ;
        $title = apply_filters( 'the_title', $title, $page_on_front );
    } elseif( is_page() || is_singular() ){
        $post_id = $post->ID;
        if(is_singular('post')){
            $title_custom = cruxstore_option('single_page_header');
            $title = ($title_custom) ? $title_custom : get_the_title($post_id);
        }else{
            $custom_text = cruxstore_meta('_cruxstore_page_header_custom', array(), $post_id);
            $title = ($custom_text != '') ? $custom_text : get_the_title($post_id);
        }
        $title = apply_filters( 'the_title', $title, $post_id );
    }

    return apply_filters( 'cruxstore_title', $title );

}


/**
 * Get page tagline
 *
 * @return mixed|void
 */

function cruxstore_get_page_subtitle(){
    global $post;
    $tagline = '';
    if ( is_front_page() && !is_singular('page') ) {
        $tagline =  esc_html__('Lastest posts', 'cruxstore');
        if(cruxstore_is_wc()) {
            if (is_shop()) {
                $shop_page_id = get_option('woocommerce_shop_page_id');
                $tagline = cruxstore_meta('_cruxstore_page_header_subtitle', array(), $shop_page_id);
            }
        }
    }elseif( is_home() ){
        $page_for_posts = get_option('page_for_posts', true);
        $tagline = nl2br(cruxstore_meta('_cruxstore_page_header_subtitle', array(), $page_for_posts))  ;
    }elseif ( is_front_page() && is_singular('page') ){
        $tagline =  cruxstore_meta('_cruxstore_page_header_subtitle');
    }elseif ( is_archive() ){
        $tagline = get_the_archive_description( );
        if(cruxstore_is_wc()){
            if(is_shop()){
                if(!is_search()){
                    $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                    $tagline = cruxstore_meta('_cruxstore_page_header_subtitle', array(), $shop_page_id);
                }
            }
            if( is_product_category() || is_product_tag() ){
                $tagline = '';
            }
        }
    }elseif(is_search()){
        $tagline = '';
    }elseif( $post ){
        $post_id = $post->ID;
        $tagline = nl2br(cruxstore_meta('_cruxstore_page_header_subtitle', array(), $post_id));
    }

    return apply_filters( 'cruxstore_subtitle', $tagline );
}

add_action('cruxstore_loop_after', 'cruxstore_paging_nav');

/**
 * Get page layout
 *
 * @return mixed
 *
 */
function cruxstore_get_page_layout(){

    $page_header_layout = '';
    if ( is_front_page() && is_singular('page') ){
        $page_header_layout =  cruxstore_meta('_cruxstore_page_header_layout');
    }elseif(is_page() || is_singular()){
        $page_header_layout =  cruxstore_meta('_cruxstore_page_header_layout');
        if(is_singular()){
            $page_header_layout = 'centered';
        }
        if(cruxstore_is_wc()){
            if(is_product()){
                $page_header_layout = 'centered';
            }
        }
    }elseif ( is_home() ) {
        $page_for_posts = get_option('page_for_posts', true);
        $page_header_layout = cruxstore_meta( '_cruxstore_page_header_layout', array(), $page_for_posts );
    }elseif(is_archive()){
        if(cruxstore_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                $page_header_layout = cruxstore_meta('_cruxstore_page_header_align', array(), $shop_page_id);
            }
        }
    }

    if($page_header_layout == ''){
        $page_header_layout = cruxstore_option('title_layout', 'centered');
    }

    return $page_header_layout;
}



/**
 * Get page align
 *
 * @return mixed
 *
 */
function cruxstore_get_page_align(){

    $page_header_align = '';
    if ( is_front_page() && is_singular('page') ){
        $page_header_align =  cruxstore_meta('_cruxstore_page_header_align');
    }elseif(is_page() || is_singular()){
        $page_header_align =  cruxstore_meta('_cruxstore_page_header_align');
        if(is_singular()){
            $page_header_align = 'left';
        }
        if(cruxstore_is_wc()){
            if(is_product()){
                $page_header_align = 'left';
            }
        }
    }elseif ( is_home() ) {
        $page_for_posts = get_option('page_for_posts', true);
        $page_header_align = cruxstore_meta( '_cruxstore_page_header_align', array(), $page_for_posts );
    }elseif(is_archive()){
        if(cruxstore_is_wc()){
            if(is_shop()){
                $shop_page_id = get_option( 'woocommerce_shop_page_id' );
                $page_header_align = cruxstore_meta('_cruxstore_page_header_align', array(), $shop_page_id);
            }
        }
    }
    if($page_header_align == ''){
        $page_header_align = cruxstore_option('title_align', 'center');
    }
    return $page_header_align;
}


if(!function_exists('cruxstore_contactmethods')){

    /**
     * Add social media to author
     *
     * @param $contactmethods
     * @return array
     */
    function cruxstore_contactmethods( $contactmethods ) {

        $contactmethods['facebook'] = esc_html__('Facebook page/profile url', 'cruxstore');
        $contactmethods['twitter'] = esc_html__('Twitter username (without @)', 'cruxstore');
        $contactmethods['pinterest'] = esc_html__('Pinterest username', 'cruxstore');
        $contactmethods['googleplus'] = esc_html__('Google+ page/profile URL', 'cruxstore');
        $contactmethods['instagram'] = esc_html__('Instagram username', 'cruxstore');
        $contactmethods['linkedin'] = esc_html__('LinkedIn page/profile url', 'cruxstore');
        $contactmethods['behance'] = esc_html__('Behance username', 'cruxstore');
        $contactmethods['tumblr'] = esc_html__('Tumblr username', 'cruxstore');
        $contactmethods['dribbble'] = esc_html__('Dribbble username', 'cruxstore');

        return $contactmethods;
    }
    add_filter( 'user_contactmethods','cruxstore_contactmethods', 10, 1 );
}
/**
 * Add slideshow header
 *
 * @since 1.0
 */
add_action( 'cruxstore_slideshows_position', 'cruxstore_slideshows_position_callback' );
function cruxstore_slideshows_position_callback(){
    if(is_page()){
        $page_id = get_the_ID();
        if(cruxstore_is_wc()) {
            if (is_shop()) {
                $page_id = get_option('woocommerce_shop_page_id');
            }
        }
        cruxstore_show_slideshow($page_id);
    }
}

add_filter( 'get_the_archive_title', 'cruxstore_get_the_archive_title');
/**
 * Remove text Category and Archives in get_the_archive_title
 *
 * @param $title
 * @return null|string
 */
function cruxstore_get_the_archive_title($title) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title =  single_term_title( '', false );
    }

    return $title;

}


add_filter('wpb_widget_title', 'cruxstore_widget_title', 10, 2);
function cruxstore_widget_title($output = '', $params = array('')) {
    if ( '' === $params['title'] ) {
        return '';
    }
    $extraclass = ( isset( $params['extraclass'] ) ) ? ' ' . $params['extraclass'] : '';
    return '<h4 class="wpb_heading'.$extraclass.'">'.$params['title'].'</h4>';
}

add_filter('cruxstore_header_class', 'cruxstore_header_add_class', 10, 3);
function cruxstore_header_add_class($classes, $header_layout, $header_position){

    $header_scheme = '';
    if(is_page()){
        $header_scheme = cruxstore_meta('_cruxstore_header_scheme', array());
    }

    if(!$header_scheme){
        $header_scheme = 'dark';
    }

    $classes .= ' header-'.$header_scheme;

    if($header_position == 'transparent'){
        $classes .= ' header-transparent';
    }


    return $classes;
}


function cruxstore_header_content_class($classes, $header_layout){

    if($header_shadow = cruxstore_option( 'header_shadow', true )){
        $classes .= ' header-shadow';
    }
    return $classes;
}
add_filter('cruxstore_header_content_class', 'cruxstore_header_content_class', 10, 2);


function cruxstore_navbar_container_sticky($classes){
    $fixed_header = cruxstore_option('fixed_header', 3);
    if($fixed_header == 2 || $fixed_header == 3 ){
        $classes .= ' sticky-header';
        if($fixed_header == 3){
            $classes .= ' sticky-header-down';
        }
    }

    return $classes;
}
add_filter('cruxstore_navbar_container', 'cruxstore_navbar_container_sticky');


if(!function_exists('cruxstore_setting_script_footer')){
    /**
     * Add advanced js to footer
     *
     */
    function cruxstore_setting_script_footer() {
        $advanced_js = cruxstore_option('advanced_editor_js');
        $advanced_tracking_code = cruxstore_option('advanced_tracking_code');

        if($advanced_js || $advanced_tracking_code){
            echo '<div class="footer-advanced-js">';
            if($advanced_js){
                printf('<script type="text/javascript">%s</script>', $advanced_js);
            }
            if($advanced_tracking_code){
                echo cruxstore_option('advanced_tracking_code');
            }
            echo "</div>";
        }
    }
    add_action('wp_footer', 'cruxstore_setting_script_footer', 100);
}
/**
 * Back To Top Function
 *
 */
function cruxstore_backtotop(){
    if(cruxstore_option('backtotop', true)){
        echo '<div id="back-to-top"><i class="fa fa-angle-up"></i></div>';
    }
}
add_action('wp_footer', 'cruxstore_backtotop');
