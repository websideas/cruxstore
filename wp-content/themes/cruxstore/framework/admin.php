<?php

if ( !function_exists( 'cruxstore_admin_enqueue_scripts' ) ) {

    /**
     * Add stylesheet and script for admin
     *
     * @since       1.0
     * @return      void
     * @access      public
     */
    function cruxstore_admin_enqueue_scripts( $hook_suffix ){
        wp_enqueue_style( 'font-awesome', CRUXSTORE_THEME_LIBS.'font-awesome/css/font-awesome.min.css');
        wp_enqueue_style( 'framework-core', CRUXSTORE_FW_CSS.'framework-core.css');
        wp_enqueue_style( 'jquery-chosen', CRUXSTORE_FW_LIBS.'chosen/chosen.min.css');
        wp_enqueue_style( 'font-kticon', CRUXSTORE_THEME_LIBS . 'kticon/style.css', array());
        wp_enqueue_style( 'font-flaticoneco', CRUXSTORE_THEME_LIBS . 'flaticoneco/flaticoneco.css', array());

        wp_enqueue_script( 'jquery-chosen', CRUXSTORE_FW_LIBS.'chosen/chosen.jquery.min.js', array('jquery'), CRUXSTORE_FW_VER, true);
        wp_enqueue_script( 'cookie', CRUXSTORE_FW_JS.'jquery.cookie.js', array('jquery'), CRUXSTORE_FW_VER, true);
        wp_enqueue_script( 'cruxstore_icons', CRUXSTORE_FW_JS.'icons.js', array('jquery'), CRUXSTORE_FW_VER, true);

        wp_enqueue_media();
        wp_enqueue_script( 'cruxstore_image', CRUXSTORE_FW_JS.'image.js', array('jquery'), CRUXSTORE_FW_VER, true);
        wp_localize_script( 'cruxstore_image', 'cruxstore_image_lange', array(
            'frameTitle' => esc_html__('Select your image', 'cruxstore' )
        ));

        if ( 'widgets.php' === $hook_suffix ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'underscore' );
            wp_enqueue_script( 'cruxstore_color', CRUXSTORE_FW_JS.'color.js', array('jquery'), CRUXSTORE_FW_VER, true);
        }

        wp_enqueue_script( 'framework-core', CRUXSTORE_FW_JS.'framework-core.js', array('jquery', 'jquery-ui-tabs', 'jquery-ui-sortable', 'jquery-ui-tooltip'), CRUXSTORE_FW_VER, true);


    } // End cruxstore_admin_enqueue_scripts.
    add_action( 'admin_enqueue_scripts', 'cruxstore_admin_enqueue_scripts' );
}


function cruxstore_get_thumbnail_callback() {
    
    $image = intval($_POST['image']);
    $img_size = $_POST['size'];
    
    $img = wpb_getImageBySize( array(
        'attach_id' => $image,
        'thumb_size' => $img_size,
        'class' => 'img-responsive',
    ) );
    if ( $img == null ) {
        $img['thumbnail'] = '<img class="vc_img-placeholder img-responsive" src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
    }
    
    echo $img['thumbnail'];
    
    wp_die();
}
add_action( 'wp_ajax_get_thumbnail', 'cruxstore_get_thumbnail_callback' );