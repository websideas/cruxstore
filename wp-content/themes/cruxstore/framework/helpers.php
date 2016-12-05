<?php

/**
 * All helpers for theme
 *
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Function check if WC Plugin installed
 */
function cruxstore_is_wc(){
    return function_exists('is_woocommerce');
}

/**
 *  @true  if WPML installed.
 */
function  cruxstore_is_wpml(){
    return class_exists('SitePress');
}

if (!function_exists('cruxstore_meta')){

    function cruxstore_meta( $key, $args = array(), $post_id = null ){

        if(function_exists('rwmb_meta')){
            /**
             * If meta boxes is registered in the backend only, we can't get field's params
             * This is for backward compatibility with version < 4.8.0
             */
            $field = RWMB_Helper::find_field( $key );
            if ( false === $field || isset( $args['type'] ) )
            {
                return apply_filters( 'rwmb_meta', RWMB_Helper::meta( $key, $args, $post_id ) );
            }
            $meta = in_array( $field['type'], array( 'oembed', 'map' ) ) ?
                rwmb_the_value( $key, $args, $post_id, false ) :
                rwmb_get_value( $key, $args, $post_id );
            return apply_filters( 'rwmb_meta', $meta, $key, $args, $post_id );
        }else{
            return null;
        }
    }
}

if (!function_exists('cruxstore_option')){
    /**
     * Function to get options in front-end
     * @param int $option The option we need from the DB
     * @param string $default If $option doesn't exist in DB return $default value
     * @return string
     */

    function cruxstore_option( $option = false, $default = false ){
        if($option === false){
            return false;
        }
        $cruxstore_options = wp_cache_get( CRUXSTORE_THEME_OPTIONS );
        if(  !$cruxstore_options ){
            $cruxstore_options = get_option( CRUXSTORE_THEME_OPTIONS );
            wp_cache_delete( CRUXSTORE_THEME_OPTIONS );
            wp_cache_add( CRUXSTORE_THEME_OPTIONS, $cruxstore_options );
        }

        if(isset($cruxstore_options[$option]) && $cruxstore_options[$option] !== ''){
            return $cruxstore_options[$option];
        }else{
            return $default;
        }
    }
}




if (!function_exists('cruxstore_get_image_sizes')){

    /**
     * Get image sizes
     *
     * @return array
     */
    function cruxstore_get_image_sizes( $full = true, $custom = false ) {

        global $_wp_additional_image_sizes;
        $get_intermediate_image_sizes = get_intermediate_image_sizes();
        $sizes = array();
        // Create the full array with sizes and crop info
        foreach( $get_intermediate_image_sizes as $_size ) {

            if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
                $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
                $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
                $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $sizes[ $_size ] = array(
                    'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                    'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                    'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
                );
            }

            $option_text = array();
            $option_text[] = ucfirst(str_replace('_', ' ', $_size));
            if( isset($sizes[ $_size ]) ){
                $option_text[] = '('.$sizes[ $_size ]['width'].' x '.$sizes[ $_size ]['height'].')';
                if($sizes[ $_size ]['crop']){
                    $option_text[] = esc_html__('Crop', 'cruxstore');
                }
                $sizes[ $_size ] = implode(' - ', $option_text);
            }
        }

        if($full){
            $sizes[ 'full' ] = esc_html__('Full', 'cruxstore');
        }
        if($custom){
            $sizes[ 'custom' ] = esc_html__('Custom size', 'cruxstore');
        }

        return $sizes;
    }

}



if (!function_exists('cruxstore_sidebars')){
    /**
     * Get sidebars
     *
     * @return array
     */
    function cruxstore_sidebars( ){
        $sidebars = array();
        foreach ( $GLOBALS['wp_registered_sidebars'] as $item ) {
            $sidebars[$item['id']] = $item['name'];
        }
        return $sidebars;
    }
}

if (!function_exists('cruxstore_getlayout')) {
    /**
     * Get Layout of post
     *
     * @param number $post_id Optional. ID of article or page.
     * @return string
     *
     */
    function cruxstore_getlayout($post_id = null){
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $layout = cruxstore_meta('_cruxstore_layout', array(),  $post_id);
        if($layout == 'default' || !$layout){
            $layout = cruxstore_option('layout', 'full');
        }

        return $layout;
    }
}

if (!function_exists('cruxstore_show_slideshow')) {
    /**
     * Show slideshow of page
     *
     * @param $post_id
     *
     */
    function cruxstore_show_slideshow($post_id = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $slideshow = cruxstore_meta('_cruxstore_slideshow_type', array(), $post_id);
        $sideshow_class = array();
        $output = '';

        if ($slideshow == 'revslider' || $slideshow == 'search') {
            $revslider = cruxstore_meta('_cruxstore_rev_slider', array(), $post_id);
            if ($revslider && class_exists('RevSlider')) {
                ob_start();
                putRevSlider($revslider);
                $revslider_html = ob_get_contents();
                ob_end_clean();
                $output .= $revslider_html;
            }
            if($slideshow == 'search'){
                if(cruxstore_is_wc()){
                    $search = get_product_search_form(false);
                }else{
                    $search = get_search_form(false);
                }
                $output .= '<div class="searchform-wrap"><div class="container"><div class="searchform-inner">'.$search.'</div></div></div>';
            }

        } elseif ($slideshow == 'layerslider') {
            $layerslider = cruxstore_meta('_cruxstore_layerslider', array(), $post_id);
            if ($layerslider && is_plugin_active('LayerSlider/layerslider.php')) {
                $layerslider_html = do_shortcode('[layerslider id="' . $layerslider . '"]');
                if($layerslider_html){
                    $output .= $layerslider_html;
                }
            }
        }elseif($slideshow == 'custom'){
            $customslider = cruxstore_meta('_cruxstore_slideshow_custom', array(), $post_id);
            $output .= do_shortcode($customslider);
        }

        if($output != ''){
            printf(
                '<div id="main-slideshow" class="%s"><div id="sideshow-inner">%s</div></div>',
                esc_attr(implode(' ', $sideshow_class)),
                $output
            );
        }
    }
}


if (!function_exists('cruxstore_get_header')) {
    /**
     * Get Header
     *
     * @return string
     *
     */
    function cruxstore_get_header_position(){
        $header = 'default';
        $header_position = '';

        if(is_page()){
            $header_position = cruxstore_meta('_cruxstore_header_position');
        }

        if($header_position){
            $header = $header_position;
        }
        return $header;
    }
}


if (!function_exists('cruxstore_get_header_layout')) {
    /**
     * Get Header Layout
     *
     * @return string
     *
     */
    function cruxstore_get_header_layout(){

        $layout = null;

        if(is_page()){
            $layout = cruxstore_meta('_cruxstore_header_layout');
        }

        if(isset($_REQUEST['header_layout'])){
            $layout = $_REQUEST['header_layout'];
        }

        if(!$layout){
            $layout = cruxstore_option('header', '1');
        }

        return $layout;
    }
}


if (!function_exists('cruxstore_get_logo')){
    /**
     * Get logo of current page
     *
     * @return string
     *
     */
    function cruxstore_get_logo(){
        $cacheKey = 'cruxstore_logo';
        $logo 	= wp_cache_get( $cacheKey );

        if( false === $logo){
            $logo = array('default' => '', 'retina' => '', 'light' => '', 'light_retina' => '');
            $logo_default = cruxstore_option( 'logo' );
            $logo_retina = cruxstore_option( 'logo_retina' );
            $logo_light = cruxstore_option( 'logo_light' );
            $logo_light_retina = cruxstore_option( 'logo_light_retina' );

            if(is_array($logo_default) && $logo_default['url'] != '' ){
                $logo['default'] = $logo_default;
            }

            if(is_array($logo_retina ) && $logo_retina['url'] != '' ){
                $logo['retina'] = $logo_retina;
            }

            if(is_array($logo_light) && $logo_light['url'] != '' ){
                $logo['light'] = $logo_light;
            }

            if(is_array($logo_light_retina ) && $logo_light_retina['url'] != '' ){
                $logo['light_retina'] = $logo_light_retina;
            }

            if(!$logo['default']){
                $logo['default'] = array(
                    'url' => CRUXSTORE_THEME_IMG.'logo.png',
                    'width' => 170,
                    'height' => 20
                );
                $logo['retina'] = array(
                    'url' => CRUXSTORE_THEME_IMG.'logo-2x.png',
                    'width' => 340,
                    'height' => 40
                );
            }

            if(!$logo['light']){
                $logo['light'] = array(
                    'url' => CRUXSTORE_THEME_IMG.'logo-light.png',
                    'width' => 170,
                    'height' => 20
                );
                $logo['light_retina'] = array(
                    'url' => CRUXSTORE_THEME_IMG.'logo-light-2x.png',
                    'width' => 340,
                    'height' => 40
                );
            }

            wp_cache_add( $cacheKey, $logo );

        }

        return $logo;
    }
}



if (!function_exists('cruxstore_get_archive_sidebar')) {
    /**
     * Get Archive sidebar
     *
     * @return array
     */
    function cruxstore_get_archive_sidebar()
    {
        if( isset($_REQUEST['sidebar'] )){
            $sidebar = array('sidebar' => $_REQUEST['sidebar'], 'sidebar_area' => '');
            if($sidebar['sidebar'] == 'full'){
                $sidebar['sidebar'] = '';
            }
            if(isset( $_REQUEST['area'])){
                $sidebar['sidebar_area'] = $_REQUEST['area'];
            }elseif(!$sidebar['sidebar_area']){
                $sidebar['sidebar_area'] = 'blog-widget-area';
            }
        }elseif(is_search()){
            $sidebar = array(
                'sidebar' => cruxstore_option('search_sidebar', 'full'),
                'sidebar_area' => ''
            );
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = cruxstore_option('search_sidebar_left', 'primary-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = cruxstore_option('search_sidebar_right', 'primary-widget-area');
            }
        }else{
            $default = false;
            if(is_home()) {
                $post_id = get_option('page_for_posts');
                $sidebar = array(
                    'sidebar' => cruxstore_meta('_cruxstore_sidebar', array(), $post_id),
                    'sidebar_area' => '',
                );

                if($sidebar['sidebar'] == 'left' ){
                    $sidebar['sidebar_area'] = cruxstore_meta('_cruxstore_left_sidebar', array(), $post_id);
                }elseif($sidebar['sidebar'] == 'right'){
                    $sidebar['sidebar_area'] = cruxstore_meta('_cruxstore_right_sidebar', array(), $post_id);
                }elseif($sidebar['sidebar'] == 'full'){
                    $sidebar['sidebar'] = '';
                }else{
                    $default = true;
                }
            }else{
                $default = true;
            }

            if($default){

                $sidebar = array(
                    'sidebar' => cruxstore_option('archive_sidebar', 'right'),
                    'sidebar_area' => '',
                );
                if($sidebar['sidebar'] == 'left' ){
                    $sidebar['sidebar_area'] = cruxstore_option('archive_sidebar_left', 'primary-widget-area');
                }elseif($sidebar['sidebar'] == 'right'){
                    $sidebar['sidebar_area'] = cruxstore_option('archive_sidebar_right', 'primary-widget-area');
                }

            }
        }

        if($sidebar['sidebar'] == 'full'){
            $sidebar['sidebar'] = '';
        }


        return apply_filters('cruxstore_archive_sidebar', $sidebar);
    }
}


if (!function_exists('cruxstore_get_archive_layout')) {
    /**
     * Get Archive layout
     *
     * @return array
     */
    function cruxstore_get_archive_layout()
    {
        $layout = array('type' => '', 'columns' => '', 'columns_tab' => 2, 'readmore' => '');

        if (isset($_REQUEST['type'])) {
            $layout['type'] = $_REQUEST['type'];
            $layout['pagination'] = 'normal';
            if(isset($_REQUEST['columns'])){
                $layout['columns'] = $_REQUEST['columns'];
            }else{
                $layout['columns'] = cruxstore_option('archive_columns', 2);
            }
            $layout['readmore'] = cruxstore_option('archive_readmore', 'none');
        } elseif (is_search()) {
            $layout['type'] = cruxstore_option('search_loop_style', 'grid');
            $layout['columns'] = cruxstore_option('search_columns', 3);
            $layout['pagination'] = cruxstore_option('search_pagination', 'normal');
            $layout['readmore'] = cruxstore_option('search_readmore', 'none');
        } else {
            $layout['type'] = cruxstore_option('archive_loop_style', 'grid');
            $layout['columns'] = cruxstore_option('archive_columns', 2);
            $layout['pagination'] = cruxstore_option('archive_pagination', 'normal');
            $layout['readmore'] = cruxstore_option('archive_readmore', 'none');
        }

        if($layout['type'] == 'classic'){
            $layout['readmore'] = '';
        }

        return apply_filters('cruxstore_archive_layout', $layout);
    }
}


if (!function_exists('cruxstore_get_thumbnail_attachment')){
    /**
     * Get link attach from thumbnail_id.
     *
     * @param number $thumbnail_id ID of thumbnail.
     * @param string|array $size Optional. Image size. Defaults to 'post-thumbnail'
     * @return array
     */

    function cruxstore_get_thumbnail_attachment($thumbnail_id ,$size = 'post-thumbnail'){
        if(!$thumbnail_id) return false;

        $attachment = get_post( $thumbnail_id );
        if(!$attachment) return false;

        $image = wp_get_attachment_image_src($thumbnail_id, $size);
        return array(
            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'src' => $attachment->guid,
            'url' => $image[0],
            'title' => $attachment->post_title
        );
    }
}


if (!function_exists('cruxstore_get_page_sidebar')) {
    /**
     * Get page sidebar
     *
     * @param null $post_id
     * @return mixed|void
     */
    function cruxstore_get_page_sidebar( $post_id = null )
    {
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $sidebar = array(
            'sidebar' => cruxstore_meta('_cruxstore_sidebar', array(), $post_id),
            'sidebar_area' => '',
        );

        if(isset($_REQUEST['sidebar'])){
            $sidebar['sidebar'] = $_REQUEST['sidebar'];
        }

        if(cruxstore_is_wc()){
            if(is_cart() || is_checkout() || is_account_page()){
                $sidebar['sidebar'] = 'full';
            }elseif(defined( 'YITH_WCWL' )){
                $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
                if($post_id == $wishlist_page_id){
                    $sidebar['sidebar'] = 'full';
                }
            }
        }

        if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' ){
            $sidebar['sidebar'] = cruxstore_option('page_sidebar', '');
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = cruxstore_option('page_sidebar_left', 'primary-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = cruxstore_option('page_sidebar_right', 'primary-widget-area');
            }
        }elseif($sidebar['sidebar'] == 'left'){
            $sidebar['sidebar_area'] = cruxstore_meta('_cruxstore_left_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = cruxstore_meta('_cruxstore_right_sidebar', array(), $post_id);
        }

        if($sidebar['sidebar'] == 'full'){
            $sidebar['sidebar'] = '';
        }

        return apply_filters('cruxstore_page_sidebar', $sidebar);
    }
}


if (!function_exists('cruxstore_get_post_sidebar')) {
    /**
     * Get post sidebar
     *
     * @param null $post_id
     * @return mixed|void
     */
    function cruxstore_get_post_sidebar( $post_id = null )
    {
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $sidebar = array(
            'sidebar' => cruxstore_meta('_cruxstore_sidebar', array(), $post_id),
            'sidebar_area' => '',
        );

        if(isset($_REQUEST['sidebar'])){
            $sidebar['sidebar'] = $_REQUEST['sidebar'];
        }

        if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' || $sidebar['sidebar'] == '0' ){
            $sidebar['sidebar'] = cruxstore_option('single_sidebar');
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = cruxstore_option('single_sidebar_left', 'primary-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = cruxstore_option('single_sidebar_right', 'primary-widget-area');
            }
        }elseif($sidebar['sidebar'] == 'left'){
            $sidebar['sidebar_area'] = cruxstore_meta('_cruxstore_left_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = cruxstore_meta('_cruxstore_right_sidebar', array(), $post_id);
        }

        if($sidebar['sidebar'] == 'full'){
            $sidebar['sidebar'] = '';
        }

        return apply_filters('cruxstore_single_sidebar', $sidebar);
    }
}


if( !function_exists('cruxstore_get_mainmenu')){
    function cruxstore_get_mainmenu( ){
        $primary = array( 'menu' => 'primary', 'custom' => 0 );
        if(is_page()){
            if($primary_page = cruxstore_meta('_cruxstore_header_main_menu')){

                $nav_menu = ! empty( $primary_page ) ? wp_get_nav_menu_object( $primary_page ) : false;

                $primary = array(
                    'menu' => $nav_menu ,
                    'custom' => 1
                );
            }
        }
        return $primary;
    }
}


if (!function_exists('cruxstore_custom_wpml')){
    /**
     * Custom wpml
     *
     */

    function cruxstore_custom_wpml($before = '', $after = '', $ul = 'list-lang navigation-submenu', $active = ''){

        if(cruxstore_is_wpml()){

            $output = $language_html = '';

            //$languages = apply_filters( 'wpml_active_languages', null, null );
            $languages = icl_get_languages();
            
            
            if(!empty($languages)) {
                foreach ($languages as $l) {
                    if($l['active']){
                        $selected = 'current';
                        $currency_lang = $l['language_code'];
                    }else{
                        $selected = '';
                    }

                    $language_html .= '<li class="'.$selected.'">';
                    $language_html .= '<a href="' . esc_url($l['url']) . '">';
                    $language_html .= "<span>" . strtoupper($l['language_code']) . "</span>";
                    $language_html .= '</a>';
                    $language_html .= '</li>';
                }

                if ($language_html != '') {
                    $language_html = '<a href="#">'.$active.$currency_lang.'</a><ul class="'.$ul.'">' . $language_html . '</ul>';
                }

                $output .= $language_html;
            }

            return $before.$output.$after;

        }
        return false;

    }
}

if (!function_exists('cruxstore_render_carousel')) {
    /**
     * Render Carousel
     *
     * @param $data array, All option for carousel
     * @param $class string, Default class for carousel
     *
     * @return mixed
     */

    function cruxstore_render_carousel($data, $extra = '', $class = 'owl-carousel cruxstore-owl-carousel')
    {
        $data = shortcode_atts(array(
            'gutters' => true,
            'autoheight' => true,
            'autoplay' => false,
            'mousedrag' => true,
            'autoplayspeed' => 5000,
            'slidespeed' => 200,
            'carousel_skin' => '',

            'desktop' => 4,
            'desktopsmall' => '',
            'tablet' => 2,
            'mobile' => 1,

            'navigation' => true,
            'navigation_always_on' => false,
            'navigation_position' => 'center-outside',
            'navigation_style' => 'normal',

            'pagination' => false,
            'pagination_position' => 'center-bottom',
            'pagination_style' => 'dot-stroke',

            'callback' => ''

        ), $data);

        if (!$data['desktopsmall']) {
            $data['desktopsmall'] = $data['desktop'];
        }

        extract($data);


        $autoheight = apply_filters('sanitize_boolean', $autoheight);
        $autoplay = apply_filters('sanitize_boolean', $autoplay);
        $mousedrag = apply_filters('sanitize_boolean', $mousedrag);
        $navigation = apply_filters('sanitize_boolean', $navigation);
        $navigation_always_on = apply_filters('sanitize_boolean', $navigation_always_on);
        $pagination = apply_filters('sanitize_boolean', $pagination);

        $output = '';

        $owl_carousel_class = array(
            'owl-carousel-kt',
            'navigation-' . $navigation_position,
            $extra
        );

        if ($carousel_skin) {
            $owl_carousel_class[] = 'carousel-skin-' . $carousel_skin;
        }

        if ($gutters) {
            $owl_carousel_class[] = 'carousel-gutters';
        }

        if ($navigation) {
            if ($navigation_always_on) {
                $owl_carousel_class[] = 'visiable-navigation';
            }
            $owl_carousel_class[] = 'navigation-' . $navigation_style;
        }

        if ($pagination) {
            $owl_carousel_class[] = 'pagination-' . $pagination_position;
            $owl_carousel_class[] = 'pagination-' . $pagination_style;
        }


        $autoplay = ($autoplay) ? $autoplayspeed : $autoplay;

        $data_carousel = array(
            'mouseDrag' => $mousedrag,
            "autoHeight" => $autoheight,
            "autoPlay" => $autoplay,
            "navigation" => $navigation,
            'navigation_pos' => $navigation_position,
            'pagination' => $pagination,
            'pagination_pos' => $pagination_position,
            "slideSpeed" => $slidespeed,
            'desktop' => $desktop,
            'desktopsmall' => $desktopsmall,
            'tablet' => $tablet,
            'mobile' => $mobile,
            'callback' => $callback

        );


        $output .= '<div class="' . esc_attr(implode(' ', $owl_carousel_class)) . '">';
        $output .= '<div class=" ' . $class . '" ' . render_data_carousel($data_carousel) . '>%carousel_html%</div>';
        $output .= '</div>';

        return $output;
    }
}

if (!function_exists('render_data_carousel')) {

    /*
     * Render data option for carousel
     * @param $data
     * @return string
     */
    function render_data_carousel($data)
    {
        $output = "";
        $array = array();
        foreach ($data as $key => $val) {
            if (is_bool($val) === true) {
                $val = ($val) ? 'true': 'false';
                $array[$key]= '"'.$key.'": '.$val;
            }else{
                $array[$key]= '"'.$key.'": "'.$val.'"';
            }
        }

        if(count($array)){
            $output = " data-options='{".implode(',', $array)."}'";
        }

        return $output;
    }
}


if(!function_exists('cruxstore_color2hecxa')){
    /**
     * Convert color to hex
     *
     * @param $color
     * @return string
     */
    function cruxstore_color2Hex($color){
        switch ($color) {
            case 'mulled_wine': $color = '#50485b'; break;
            case 'vista_blue': $color = '#75d69c'; break;
            case 'juicy_pink': $color = '#f4524d'; break;
            case 'sandy_brown': $color = '#f79468'; break;
            case 'purple': $color = '#b97ebb'; break;
            case 'pink': $color = '#fe6c61'; break;
            case 'violet': $color = '#8d6dc4'; break;
            case 'peacoc': $color = '#4cadc9'; break;
            case 'chino': $color = '#cec2ab'; break;
            case 'grey': $color = '#ebebeb'; break;
            case 'orange': $color = '#f7be68'; break;
            case 'sky': $color = '#5aa1e3'; break;
            case 'green': $color = '#6dab3c'; break;
            case 'white': $color = '#FFFFFF'; break;
            case 'accent': $color = cruxstore_option('styling_accent', '#ed8b5c'); break;

        }
        return $color;
    }
}
if(!function_exists('cruxstore_hex2rgba')) {

    /**
     * Convert Hex to RGBA
     * @param $hex
     * @param string $alpha
     * @return string
     */
    function cruxstore_hex2rgba($hex, $alpha = ''){
        $hex = str_replace("#", "", $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = $r . ',' . $g . ',' . $b;

        if ('' == $alpha) {
            return $rgb;
        } else {
            $alpha = floatval($alpha);

            return 'rgba(' . $rgb . ',' . $alpha . ')';
        }
    }
}
if(!function_exists('cruxstore_color_luminance')) {
    /**
     * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
     * @param str $hex Colour as hexadecimal (with or without hash);
     * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
     * @return str Lightened/Darkend colour as hexadecimal (with hash);
     */
    function cruxstore_color_luminance($hex, $percent)
    {

        // validate hex string

        $hex = preg_replace('/[^0-9a-f]/i', '', $hex);
        $new_hex = '#';

        if (strlen($hex) < 6) {
            $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
        }

        // convert to decimal and change luminosity
        for ($i = 0; $i < 3; $i++) {
            $dec = hexdec(substr($hex, $i * 2, 2));
            $dec = min(max(0, $dec + $dec * $percent), 255);
            $new_hex .= str_pad(dechex($dec), 2, 0, STR_PAD_LEFT);
        }

        return $new_hex;
    }
}


if (!function_exists('cruxstore_get_single_file')) {
    /**
     * Get Single file form meta box.
     *
     * @param string $meta . meta id of article.
     * @param string|array $size Optional. Image size. Defaults to 'screen'.
     * @param array $post_id Optional. ID of article.
     * @return array
     */
    function cruxstore_get_single_file($meta, $size = 'thumbnail' ,$post_id = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $medias = cruxstore_meta($meta, 'type=image&size='.$size, $post_id);
        if (count($medias)) {
            foreach ($medias as $media) {
                return $media;
            }
        }
        return false;
    }
}


if (!function_exists('cruxstore_post_option')) {
    /**
     * Check option for in article
     *
     * @param number $post_id Optional. ID of article.
     * @param string $meta Optional. meta oftion in article
     * @param string $option Optional. if meta is Global, Check option in theme option.
     * @param string $default Optional. Default vaule if theme option don't have data
     * @return boolean
     */
    function cruxstore_post_option($post_id = null, $meta = '', $option = '', $default = null, $boolean = true)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;
        $meta_v = get_post_meta($post_id, $meta, true);

        if ($meta_v == '' || $meta_v == 0) {
            $meta_v = cruxstore_option($option, $default);
        }
        $ouput = ($boolean) ? apply_filters('cruxstore_sanitize_boolean', $meta_v) : $meta_v;
        return $ouput;
    }
}


if (!function_exists('cruxstore_footer_instagram')) {
    function cruxstore_footer_instagram(){

        $footer_instagram = '';
        if(is_page()){
            $footer_instagram = cruxstore_meta('_cruxstore_footer_instagram');
        }
        if($footer_instagram == 'on'){
            $footer_instagram = true;
        }elseif($footer_instagram == 'off'){
            $footer_instagram = false;
        }else{
            $footer_instagram = cruxstore_option('footer_widgets', true);
        }
        if(!is_active_sidebar( 'instagram-footer' )){
            $footer_instagram = false;
        }

        return $footer_instagram;

    }
}

if (!function_exists('cruxstore_footer_widgets')) {

    /**
     * Get Footer top show or hidden.
     */
    function cruxstore_footer_widgets(){

        $footer_widgets = '';

        if(is_page()){
            $footer_widgets = cruxstore_meta('_cruxstore_footer_widgets');
        }

        if($footer_widgets == 'on'){
            $footer_widgets = true;
        }elseif($footer_widgets == 'off'){
            $footer_widgets = false;
        }else{
            $footer_widgets = cruxstore_option('footer_widgets', true);
        }

        $layouts = explode('-', cruxstore_option('footer_widgets_layout', '4-4-4'));

        $sidebar_widgets = false;
        foreach($layouts as $i => $layout){
            if(is_active_sidebar('footer-column-'.($i+1))){
                $sidebar_widgets = true;
                break;
            }
        }

        if(!$sidebar_widgets){
            $footer_widgets = false;
        };

        return $footer_widgets;
    }
}


if (!function_exists('cruxstore_render_custom_css')) {
    /**
     * Render custom css
     *
     * @param $meta
     * @param $selector
     * @param null $post_id
     */

    function cruxstore_render_custom_css($meta , $selector, $post_id = null)
    {

        $ouput = '';
        if(!$post_id){
            global $post;
            $post_id = $post->ID;
        }

        $page_bg = cruxstore_meta($meta, array(), $post_id);
        if(is_array($page_bg)){
            $page_arr = array();

            $page_color = $page_bg['color'];
            if( $page_color != '' && $page_color != '#'){
                $page_arr[] = 'background-color: '.$page_color;
            }
            if($page_url = $page_bg['url']){
                $page_arr[] = 'background-image: url('.$page_url.')';
            }
            if($page_repeat = $page_bg['repeat']){
                $page_arr[] = 'background-repeat: '.$page_repeat;
            }
            if($page_size = $page_bg['size']){
                $page_arr[] = 'background-size: '.$page_size;
            }
            if($page_attachment = $page_bg['attachment']){
                $page_arr[] = 'background-attachment: '.$page_attachment;
            }
            if($page_position = $page_bg['position']){
                $page_arr[] = 'background-position: '.$page_position;
            }
            if(count($page_arr)){
                $ouput = $selector.'{'.implode(';', $page_arr).'}';
            }
        }
        return $ouput;
    }
}

