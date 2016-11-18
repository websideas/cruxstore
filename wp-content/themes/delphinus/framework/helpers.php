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
function delphinus_is_wc(){
    return function_exists('is_woocommerce');
}

/**
 *  @true  if WPML installed.
 */
function  delphinus_is_wpml(){
    return class_exists('SitePress');
}

if (!function_exists('delphinus_meta')){

    function delphinus_meta( $key, $args = array(), $post_id = null ){

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

if (!function_exists('delphinus_option')){
    /**
     * Function to get options in front-end
     * @param int $option The option we need from the DB
     * @param string $default If $option doesn't exist in DB return $default value
     * @return string
     */

    function delphinus_option( $option = false, $default = false ){
        if($option === FALSE){
            return FALSE;
        }
        $delphinus_options = wp_cache_get( DELPHINUS_THEME_OPTIONS );
        if(  !$delphinus_options ){
            $delphinus_options = get_option( DELPHINUS_THEME_OPTIONS );
            wp_cache_delete( DELPHINUS_THEME_OPTIONS );
            wp_cache_add( DELPHINUS_THEME_OPTIONS, $delphinus_options );
        }

        if(isset($delphinus_options[$option]) && $delphinus_options[$option] !== ''){
            return $delphinus_options[$option];
        }else{
            return $default;
        }
    }
}




if (!function_exists('delphinus_get_image_sizes')){

    /**
     * Get image sizes
     *
     * @return array
     */
    function delphinus_get_image_sizes( $full = true, $custom = false ) {

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
                    $option_text[] = esc_html__('Crop', 'delphinus');
                }
                $sizes[ $_size ] = implode(' - ', $option_text);
            }
        }

        if($full){
            $sizes[ 'full' ] = esc_html__('Full', 'delphinus');
        }
        if($custom){
            $sizes[ 'custom' ] = esc_html__('Custom size', 'delphinus');
        }

        return $sizes;
    }

}



if (!function_exists('delphinus_sidebars')){
    /**
     * Get sidebars
     *
     * @return array
     */
    function delphinus_sidebars( ){
        $sidebars = array();
        foreach ( $GLOBALS['wp_registered_sidebars'] as $item ) {
            $sidebars[$item['id']] = $item['name'];
        }
        return $sidebars;
    }
}

if (!function_exists('delphinus_getlayout')) {
    /**
     * Get Layout of post
     *
     * @param number $post_id Optional. ID of article or page.
     * @return string
     *
     */
    function delphinus_getlayout($post_id = null){
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $layout = delphinus_meta('_delphinus_layout', array(),  $post_id);
        if($layout == 'default' || !$layout){
            $layout = delphinus_option('layout', 'full');
        }

        return $layout;
    }
}

if (!function_exists('delphinus_show_slideshow')) {
    /**
     * Show slideshow of page
     *
     * @param $post_id
     *
     */
    function delphinus_show_slideshow($post_id = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $slideshow = delphinus_meta('_delphinus_slideshow_type', array(), $post_id);
        $sideshow_class = array();
        $output = '';

        if ($slideshow == 'revslider') {
            $revslider = delphinus_meta('_delphinus_rev_slider', array(), $post_id);
            if ($revslider && class_exists('RevSlider')) {
                ob_start();
                putRevSlider($revslider);
                $revslider_html = ob_get_contents();
                ob_end_clean();
                $output .= $revslider_html;
            }
        } elseif ($slideshow == 'layerslider') {
            $layerslider = delphinus_meta('_delphinus_layerslider', array(), $post_id);
            if ($layerslider && is_plugin_active('LayerSlider/layerslider.php')) {
                $layerslider_html = do_shortcode('[layerslider id="' . $layerslider . '"]');
                if($layerslider_html){
                    $output .= $layerslider_html;
                }
            }
        }elseif($slideshow == 'custom'){
            $customslider = delphinus_meta('_delphinus_slideshow_custom', array(), $post_id);
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


if (!function_exists('delphinus_get_header')) {
    /**
     * Get Header
     *
     * @return string
     *
     */
    function delphinus_get_header_position(){
        $header = 'default';
        $header_position = '';

        if(is_page()){
            $header_position = delphinus_meta('_delphinus_header_position');
        }

        if($header_position){
            $header = $header_position;
        }
        return $header;
    }
}


if (!function_exists('delphinus_get_header_layout')) {
    /**
     * Get Header Layout
     *
     * @return string
     *
     */
    function delphinus_get_header_layout(){

        $layout = null;

        if(is_page()){
            $layout = delphinus_meta('_delphinus_header_layout');
        }

        if(isset($_REQUEST['header_layout'])){
            $layout = $_REQUEST['header_layout'];
        }

        if(!$layout){
            $layout = delphinus_option('header', '1');
        }

        return $layout;
    }
}


if (!function_exists('delphinus_get_logo')){
    /**
     * Get logo of current page
     *
     * @return string
     *
     */
    function delphinus_get_logo(){
        $cacheKey = 'delphinus_logo';
        $logo 	= wp_cache_get( $cacheKey );

        if( false === $logo){
            $logo = array('default' => '', 'retina' => '', 'light' => '', 'light_retina' => '');
            $logo_default = delphinus_option( 'logo' );
            $logo_retina = delphinus_option( 'logo_retina' );
            $logo_light = delphinus_option( 'logo_light' );
            $logo_light_retina = delphinus_option( 'logo_light_retina' );

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
                    'url' => DELPHINUS_THEME_IMG.'logo.png',
                    'width' => 170,
                    'height' => 20
                );
                $logo['retina'] = array(
                    'url' => DELPHINUS_THEME_IMG.'logo-2x.png',
                    'width' => 340,
                    'height' => 40
                );
            }

            if(!$logo['light']){
                $logo['light'] = array(
                    'url' => DELPHINUS_THEME_IMG.'logo-light.png',
                    'width' => 170,
                    'height' => 20
                );
                $logo['light_retina'] = array(
                    'url' => DELPHINUS_THEME_IMG.'logo-light-2x.png',
                    'width' => 340,
                    'height' => 40
                );
            }

            wp_cache_add( $cacheKey, $logo );

        }

        return $logo;
    }
}



if (!function_exists('delphinus_get_archive_sidebar')) {
    /**
     * Get Archive sidebar
     *
     * @return array
     */
    function delphinus_get_archive_sidebar()
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
                'sidebar' => delphinus_option('search_sidebar', 'full'),
                'sidebar_area' => ''
            );
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = delphinus_option('search_sidebar_left', 'primary-widget-area');
            }elseif($sidebar['sidebar'] == 'full'){
                $sidebar['sidebar'] = '';
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = delphinus_option('search_sidebar_right', 'primary-widget-area');
            }
        }else{
            $default = false;
            if(is_home()) {
                $post_id = get_option('page_for_posts');
                $sidebar = array(
                    'sidebar' => delphinus_meta('_delphinus_sidebar', array(), $post_id),
                    'sidebar_area' => '',
                );

                if($sidebar['sidebar'] == 'left' ){
                    $sidebar['sidebar_area'] = delphinus_meta('_delphinus_left_sidebar', array(), $post_id);
                }elseif($sidebar['sidebar'] == 'right'){
                    $sidebar['sidebar_area'] = delphinus_meta('_delphinus_right_sidebar', array(), $post_id);
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
                    'sidebar' => delphinus_option('archive_sidebar', 'right'),
                    'sidebar_area' => '',
                );
                if($sidebar['sidebar'] == 'left' ){
                    $sidebar['sidebar_area'] = delphinus_option('archive_sidebar_left', 'primary-widget-area');
                }elseif($sidebar['sidebar'] == 'right'){
                    $sidebar['sidebar_area'] = delphinus_option('archive_sidebar_right', 'primary-widget-area');
                }elseif($sidebar['sidebar'] == 'full'){
                    $sidebar['sidebar'] = '';
                }

            }
        }

        return apply_filters('delphinus_archive_sidebar', $sidebar);
    }
}


if (!function_exists('delphinus_get_archive_layout')) {
    /**
     * Get Archive layout
     *
     * @return array
     */
    function delphinus_get_archive_layout()
    {
        $layout = array('type' => '', 'columns' => '', 'columns_tab' => 2, 'readmore' => '');

        if (isset($_REQUEST['type'])) {
            $layout['type'] = $_REQUEST['type'];
            $layout['pagination'] = 'normal';
            if(isset($_REQUEST['columns'])){
                $layout['columns'] = $_REQUEST['columns'];
            }else{
                $layout['columns'] = delphinus_option('archive_columns', 2);
            }
            $layout['readmore'] = delphinus_option('archive_readmore', 'none');
        } elseif (is_search()) {
            $layout['type'] = delphinus_option('search_loop_style', 'grid');
            $layout['columns'] = delphinus_option('search_columns', 3);
            $layout['pagination'] = delphinus_option('search_pagination', 'normal');
            $layout['readmore'] = delphinus_option('search_readmore', 'none');
        } else {
            $layout['type'] = delphinus_option('archive_loop_style', 'grid');
            $layout['columns'] = delphinus_option('archive_columns', 2);
            $layout['pagination'] = delphinus_option('archive_pagination', 'normal');
            $layout['readmore'] = delphinus_option('archive_readmore', 'none');
        }

        if($layout['type'] == 'classic'){
            $layout['readmore'] = '';
        }

        return apply_filters('delphinus_archive_layout', $layout);
    }
}


if (!function_exists('delphinus_get_thumbnail_attachment')){
    /**
     * Get link attach from thumbnail_id.
     *
     * @param number $thumbnail_id ID of thumbnail.
     * @param string|array $size Optional. Image size. Defaults to 'post-thumbnail'
     * @return array
     */

    function delphinus_get_thumbnail_attachment($thumbnail_id ,$size = 'post-thumbnail'){
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


if (!function_exists('delphinus_get_page_sidebar')) {
    /**
     * Get page sidebar
     *
     * @param null $post_id
     * @return mixed|void
     */
    function delphinus_get_page_sidebar( $post_id = null )
    {
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $sidebar = array(
            'sidebar' => delphinus_meta('_delphinus_sidebar', array(), $post_id),
            'sidebar_area' => '',
        );

        if(isset($_REQUEST['sidebar'])){
            $sidebar['sidebar'] = $_REQUEST['sidebar'];
        }

        if(delphinus_is_wc()){
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
            $sidebar['sidebar'] = delphinus_option('page_sidebar', '');
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = delphinus_option('page_sidebar_left', 'primary-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = delphinus_option('page_sidebar_right', 'primary-widget-area');
            }
        }elseif($sidebar['sidebar'] == 'left'){
            $sidebar['sidebar_area'] = delphinus_meta('_delphinus_left_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = delphinus_meta('_delphinus_right_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'full'){
            $sidebar['sidebar'] = '';
        }

        return apply_filters('delphinus_page_sidebar', $sidebar);
    }
}


if (!function_exists('delphinus_get_post_sidebar')) {
    /**
     * Get post sidebar
     *
     * @param null $post_id
     * @return mixed|void
     */
    function delphinus_get_post_sidebar( $post_id = null )
    {
        global $post;
        if(!$post_id) $post_id = $post->ID;

        $sidebar = array(
            'sidebar' => delphinus_meta('_delphinus_sidebar', array(), $post_id),
            'sidebar_area' => '',
        );

        if(isset($_REQUEST['sidebar'])){
            $sidebar['sidebar'] = $_REQUEST['sidebar'];
        }

        if($sidebar['sidebar'] == '' || $sidebar['sidebar'] == 'default' || $sidebar['sidebar'] == '0' ){
            $sidebar['sidebar'] = delphinus_option('single_sidebar');
            if($sidebar['sidebar'] == 'left' ){
                $sidebar['sidebar_area'] = delphinus_option('single_sidebar_left', 'primary-widget-area');
            }elseif($sidebar['sidebar'] == 'right'){
                $sidebar['sidebar_area'] = delphinus_option('single_sidebar_right', 'primary-widget-area');
            }
        }elseif($sidebar['sidebar'] == 'left'){
            $sidebar['sidebar_area'] = delphinus_meta('_delphinus_left_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'right'){
            $sidebar['sidebar_area'] = delphinus_meta('_delphinus_right_sidebar', array(), $post_id);
        }elseif($sidebar['sidebar'] == 'full'){
            $sidebar['sidebar'] = '';
        }

        return apply_filters('delphinus_single_sidebar', $sidebar);
    }
}


if( !function_exists('delphinus_get_mainmenu')){
    function delphinus_get_mainmenu( ){
        $primary = array( 'menu' => 'primary', 'custom' => 0 );
        if(is_page()){
            if($primary_page = delphinus_meta('_delphinus_header_main_menu')){

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


if (!function_exists('delphinus_custom_wpml')){
    /**
     * Custom wpml
     *
     */

    function delphinus_custom_wpml($before = '', $after = '', $ul = 'list-lang navigation-submenu', $active = ''){

        if(delphinus_is_wpml()){

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

if (!function_exists('delphinus_render_carousel')) {
    /**
     * Render Carousel
     *
     * @param $data array, All option for carousel
     * @param $class string, Default class for carousel
     *
     * @return mixed
     */

    function delphinus_render_carousel($data, $extra = '', $class = 'owl-carousel delphinus-owl-carousel')
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


if(!function_exists('delphinus_color2hecxa')){
    /**
     * Convert color to hex
     *
     * @param $color
     * @return string
     */
    function delphinus_color2Hex($color){
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
            case 'accent': $color = delphinus_option('styling_accent', '#82c14f'); break;

        }
        return $color;
    }
}
if(!function_exists('delphinus_hex2rgba')) {

    /**
     * Convert Hex to RGBA
     * @param $hex
     * @param string $alpha
     * @return string
     */
    function delphinus_hex2rgba($hex, $alpha = ''){
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

if (!function_exists('delphinus_get_single_file')) {
    /**
     * Get Single file form meta box.
     *
     * @param string $meta . meta id of article.
     * @param string|array $size Optional. Image size. Defaults to 'screen'.
     * @param array $post_id Optional. ID of article.
     * @return array
     */
    function delphinus_get_single_file($meta, $size = 'thumbnail' ,$post_id = null)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;

        $medias = delphinus_meta($meta, 'type=image&size='.$size, $post_id);
        if (count($medias)) {
            foreach ($medias as $media) {
                return $media;
            }
        }
        return false;
    }
}


if (!function_exists('delphinus_post_option')) {
    /**
     * Check option for in article
     *
     * @param number $post_id Optional. ID of article.
     * @param string $meta Optional. meta oftion in article
     * @param string $option Optional. if meta is Global, Check option in theme option.
     * @param string $default Optional. Default vaule if theme option don't have data
     * @return boolean
     */
    function delphinus_post_option($post_id = null, $meta = '', $option = '', $default = null, $boolean = true)
    {
        global $post;
        if (!$post_id) $post_id = $post->ID;
        $meta_v = get_post_meta($post_id, $meta, true);

        if ($meta_v == '' || $meta_v == 0) {
            $meta_v = delphinus_option($option, $default);
        }
        $ouput = ($boolean) ? apply_filters('delphinus_sanitize_boolean', $meta_v) : $meta_v;
        return $ouput;
    }
}

if (!function_exists('delphinus_footer_top')) {

    /**
     * Get Footer top show or hidden.
     */
    function delphinus_footer_top(){

        $footer_top = '';

        if(is_page()){
            $footer_top = delphinus_meta('_delphinus_footer_top');
        }

        if($footer_top == 'on'){
            $footer_top = true;
        }elseif($footer_top == 'off'){
            $footer_top = false;
        }else{
            $footer_top = delphinus_option('footer_top', true);
        }




        if(!is_active_sidebar( 'footer-top' )){
            $footer_top = false;
        }
        return $footer_top;
    }
}


if (!function_exists('delphinus_footer_widgets')) {

    /**
     * Get Footer top show or hidden.
     */
    function delphinus_footer_widgets(){

        $footer_widgets = '';

        if(is_page()){
            $footer_widgets = delphinus_meta('_delphinus_footer_widgets');
        }

        if($footer_widgets == 'on'){
            $footer_widgets = true;
        }elseif($footer_widgets == 'off'){
            $footer_widgets = false;
        }else{
            $footer_widgets = delphinus_option('footer_widgets', true);
        }

        $layouts = explode('-', delphinus_option('footer_widgets_layout', '4-4-4'));

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


if (!function_exists('delphinus_render_custom_css')) {
    /**
     * Render custom css
     *
     * @param $meta
     * @param $selector
     * @param null $post_id
     */

    function delphinus_render_custom_css($meta , $selector, $post_id = null)
    {

        $ouput = '';
        if(!$post_id){
            global $post;
            $post_id = $post->ID;
        }

        $page_bg = delphinus_meta($meta, array(), $post_id);
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



function delphinus_responsive_render($element, $type, $css){

    $output = '';

    $arr = explode(';', $css);
    if(count($arr)){
        foreach($arr as $item){
            if($item){
                $arr_i = explode(':', $item);
                if(count($arr_i) == 2 && $arr_i[1]){
                    $output .= delphinus_breakpoint_css($element, $type, $arr_i[1], $arr_i[0]);
                }
            }
        }
    }
    return $output;
}


function delphinus_breakpoint_css($element, $key, $style, $type = 'desktop'){

    $media = '';
    if($type == 'desktop'){
        $media = '@media (min-width: 992px) {%s}';
    }elseif($type == 'tablet'){
        $media = '@media (min-width: 768px) and (max-width: 991px) {%s}';
    }elseif($type == 'mobile'){
        $media = '@media (max-width: 767px) {%s}';
    }

    $css = sprintf('%s{%s: %s;}', $element, $key, $style);
    $ouput = '';

    if($media && $css){
        $ouput = sprintf($media, $css);
    }

    return $ouput;
}


function delphinus_map_add_css_animation( $label = true ) {
    
	$data = array(
		'type' => 'dropdown',
		'heading' => __( 'CSS Animation', 'js_composer' ),
		'param_name' => 'css_animation',
		'admin_label' => $label,
		'value' => array(
			__( 'No', 'delphinus' ) => '',
            __( 'fadeIn', 'delphinus' ) => 'fadeIn',
            __( 'fadeInLeft', 'delphinus' ) => 'fadeInLeft',
            __( 'fadeInRight', 'delphinus' ) => 'fadeInRight',
            __( 'fadeInUp', 'delphinus' ) => 'fadeInUp',
            __( 'fadeInDown', 'delphinus' ) => 'fadeInDown',
            __( 'bounce', 'delphinus' ) => 'bounce',
            __( 'flash', 'delphinus' ) => 'flash',
            __( 'pulse', 'delphinus' ) => 'pulse',
            __( 'shake', 'delphinus' ) => 'shake',
            __( 'swing', 'delphinus' ) => 'swing',
            __( 'tada', 'delphinus' ) => 'tada',
            __( 'wobble', 'delphinus' ) => 'wobble',
            __( 'bounceIn', 'delphinus' ) => 'bounceIn',
            __( 'bounceInLeft', 'delphinus' ) => 'bounceInLeft',
            __( 'bounceInRight', 'delphinus' ) => 'bounceInRight',
            __( 'bounceInUp', 'delphinus' ) => 'bounceInUp',
            __( 'bounceInDown', 'delphinus' ) => 'bounceInDown',
		),
		'description' => __( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'js_composer' ),
	);

	return apply_filters( 'delphinus_map_add_css_animation', $data, $label );
}

function delphinus_map_add_css_animation_delay( $label = true ) {
    $vc_map_animation_delay = array(
        "type" => "delphinus_number",
        'heading' => esc_html__( 'Animation Delay', 'delphinus' ),
        "suffix" => esc_html__("milliseconds", 'delphinus'),
        'param_name' => 'animation_delay',
        "admin_label" => true,
        'dependency' => array( 
            'element' => 'css_animation', 
            'not_empty' => true 
        ),
    );
    
    return apply_filters( 'delphinus_map_add_css_animation_delay', $vc_map_animation_delay );
}


function delphinus_getCSSAnimation( $css_animation ) {
	$output = '';
	if ( '' !== $css_animation ) {
        $output = ' wow ' . $css_animation;
	}

	return $output;
}







    