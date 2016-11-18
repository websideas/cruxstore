<?php

if ( !function_exists( 'delphinus_admin_enqueue_scripts' ) ) {

    /**
     * Add stylesheet and script for admin
     *
     * @since       1.0
     * @return      void
     * @access      public
     */
    function delphinus_admin_enqueue_scripts( $hook_suffix ){
        wp_enqueue_style( 'font-awesome', DELPHINUS_THEME_LIBS.'font-awesome/css/font-awesome.min.css');
        wp_enqueue_style( 'framework-core', DELPHINUS_FW_CSS.'framework-core.css');
        wp_enqueue_style( 'jquery-chosen', DELPHINUS_FW_LIBS.'chosen/chosen.min.css');
        wp_enqueue_style( 'delphinus-delphinus', DELPHINUS_THEME_LIBS . 'delphinus/style.css', array());

        wp_enqueue_script( 'jquery-chosen', DELPHINUS_FW_LIBS.'chosen/chosen.jquery.min.js', array('jquery'), DELPHINUS_FW_VER, true);
        wp_enqueue_script( 'cookie', DELPHINUS_FW_JS.'jquery.cookie.js', array('jquery'), DELPHINUS_FW_VER, true);
        wp_enqueue_script( 'delphinus_icons', DELPHINUS_FW_JS.'delphinus_icons.js', array('jquery'), DELPHINUS_FW_VER, true);

        wp_enqueue_media();
        wp_enqueue_script( 'delphinus_image', DELPHINUS_FW_JS.'delphinus_image.js', array('jquery'), DELPHINUS_FW_VER, true);
        wp_localize_script( 'delphinus_image', 'delphinus_image_lange', array(
            'frameTitle' => esc_html__('Select your image', 'delphinus' )
        ));

        if ( 'widgets.php' === $hook_suffix ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'underscore' );
            wp_enqueue_script( 'delphinus_color', DELPHINUS_FW_JS.'delphinus_color.js', array('jquery'), DELPHINUS_FW_VER, true);
        }

        wp_enqueue_script( 'framework-core', DELPHINUS_FW_JS.'framework-core.js', array('jquery', 'jquery-ui-tabs', 'jquery-ui-sortable'), DELPHINUS_FW_VER, true);


    } // End delphinus_admin_enqueue_scripts.
    add_action( 'admin_enqueue_scripts', 'delphinus_admin_enqueue_scripts' );
}


