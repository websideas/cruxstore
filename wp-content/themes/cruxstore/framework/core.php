<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

define( 'CRUXSTORE_FW_VER', '1.0' );

define( 'CRUXSTORE_FW_DIR', trailingslashit(CRUXSTORE_THEME_DIR.'framework'));
define( 'CRUXSTORE_FW_URL', trailingslashit(CRUXSTORE_THEME_URL.'framework'));

define( 'CRUXSTORE_FW_EXT_DIR', trailingslashit( CRUXSTORE_FW_DIR . 'extensions' ) );
define( 'CRUXSTORE_FW_EXT_URL', trailingslashit( CRUXSTORE_FW_URL . 'extensions' ) );

define( 'CRUXSTORE_FW_PLUGINS_DIR', trailingslashit( CRUXSTORE_FW_DIR . 'plugins' ) );

define( 'CRUXSTORE_FW_WIDGETS', trailingslashit( CRUXSTORE_FW_DIR . 'widgets' ) );

define( 'CRUXSTORE_FW_ASSETS', trailingslashit( CRUXSTORE_FW_URL . 'assets' ) );
define( 'CRUXSTORE_FW_JS', trailingslashit( CRUXSTORE_FW_ASSETS . 'js' ) );
define( 'CRUXSTORE_FW_CSS', trailingslashit( CRUXSTORE_FW_ASSETS . 'css' ) );
define( 'CRUXSTORE_FW_IMG', trailingslashit( CRUXSTORE_FW_ASSETS . 'images' ) );
define( 'CRUXSTORE_FW_LIBS', trailingslashit( CRUXSTORE_FW_ASSETS . 'libs' ) );

define( 'CRUXSTORE_FW_CLASS', trailingslashit( CRUXSTORE_FW_DIR . 'class' ) );
define( 'CRUXSTORE_FW_DATA', trailingslashit( CRUXSTORE_FW_DIR . 'data' ) );


/**
 * All ajax functions
 *
 */
require_once  CRUXSTORE_FW_DIR . 'ajax.php';


/**
 * Get all functions for frontend
 *
 */
require_once CRUXSTORE_FW_DIR . 'frontend.php';

/**
 * Get functions for framework
 *
 */
require_once CRUXSTORE_FW_DIR . 'functions.php';

/**
 * Get class helpers in framework
 *
 */
require_once CRUXSTORE_FW_DIR . 'helpers.php';


/**
 * get custom walker for wp_nav_menu
 *
 */
require CRUXSTORE_FW_EXT_DIR .'nav/nav_custom_walker.php';


if ( class_exists( 'RW_Meta_Box' ) && is_admin() ) {

    // Add fields to metabox
    require CRUXSTORE_FW_EXT_DIR . 'meta-box-custom.php';

    // Add plugin meta-box-show-hide
    require CRUXSTORE_FW_EXT_DIR . 'meta-box-show-hide/meta-box-show-hide.php';

    // Add plugin meta-box-tabs
    require CRUXSTORE_FW_EXT_DIR . 'meta-box-tabs/meta-box-tabs.php';

    // Add plugin meta-box-conditional-logic
    define( 'MBC_URL', trailingslashit( CRUXSTORE_FW_EXT_URL . 'meta-box-conditional-logic' ) );
    require CRUXSTORE_FW_EXT_DIR . 'meta-box-conditional-logic/meta-box-conditional-logic.php';

    require CRUXSTORE_FW_DATA . 'data-meta-box.php';

}


/**
 * Include the redux-framework.
 * 
 */

if(!function_exists('redux_register_custom_extension_loader')) :
	function redux_register_custom_extension_loader($ReduxFramework) {
		$path = CRUXSTORE_FW_EXT_DIR . '/ReduxCoreExt/';
		$folders = scandir( $path, 1 );		   
		foreach($folders as $folder) {
			if ($folder === '.' or $folder === '..' or !is_dir($path . $folder) ) {
				continue;	
			} 
			$extension_class = 'ReduxFramework_Extension_' . $folder;
			if( !class_exists( $extension_class ) ) {
				// In case you wanted override your override, hah.
				$class_file = $path . $folder . '/extension_' . $folder . '.php';
				$class_file = apply_filters( 'redux/extension/'.$ReduxFramework->args['opt_name'].'/'.$folder, $class_file );
				if( $class_file ) {
					require $class_file;
					$extension = new $extension_class( $ReduxFramework );
				}
			}
		}
	}
	// Modify {$redux_opt_name} to match your opt_name
	add_action("redux/extensions/".CRUXSTORE_THEME_OPTIONS."/before", 'redux_register_custom_extension_loader', 0);
endif;


add_action('init', 'cruxstore_admin_options_init');
function  cruxstore_admin_options_init(){
    if (file_exists( CRUXSTORE_FW_DATA . 'data-options.php' ) ) {
        require  CRUXSTORE_FW_DATA . 'data-options.php';
    }
}



if (is_admin() ) {

	/**
	 * Get plugin require for theme
	 *
	 */
	require CRUXSTORE_FW_CLASS . 'class-tgm-plugin-activation.php';

	/**
	 * Install Plugins
     * 
	 */ 
 	require CRUXSTORE_FW_DATA . 'data-plugins.php';

    /**
     * Get Navigation nav
     *
     */
    require CRUXSTORE_FW_EXT_DIR . 'nav/nav.php';


    /**
     * Add Admin function
     *
     */
    require CRUXSTORE_FW_DIR . 'admin.php';


}

/* Insert icon to parrams icons */
require CRUXSTORE_FW_DATA . '/data-icons.php';

  
/**
 * Force Visual Composer to initialize as "built into the theme". 
 * This will hide certain tabs under the Settings->Visual Composer page
 */

add_action( 'vc_before_init', 'cruxstore_vcSetAsTheme' );
function cruxstore_vcSetAsTheme() {
    vc_set_as_theme();
}


/**
 * Initialising Visual Composer
 * 
 */ 
if ( class_exists( 'Vc_Manager', false ) ) {


    if ( ! function_exists( 'js_composer_bridge_admin' ) ) {
		function js_composer_bridge_admin( $hook ) {
			wp_enqueue_style( 'js_composer_bridge', CRUXSTORE_FW_CSS . 'js_composer_bridge.css', array(), CRUXSTORE_FW_VER );
		}
	}
    add_action( 'admin_enqueue_scripts', 'js_composer_bridge_admin', 15 );


    function cruxstore_js_composer_bridge() {
        require CRUXSTORE_FW_DIR . 'js_composer/js_composer_parrams.php';
        require CRUXSTORE_FW_DIR . 'js_composer/js_composer_bridge.php';
    }

    if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
        vc_set_shortcodes_templates_dir( CRUXSTORE_THEME_TEMP . '/vc_templates' );
    }
    add_action( 'init', 'cruxstore_js_composer_bridge', 20 );


    function rd_vc_remove_frontend_links() {
        vc_disable_frontend(); // this will disable frontend editor
    }
    add_action( 'vc_after_init', 'rd_vc_remove_frontend_links' );


    function cruxstore_vc_remove_cf7() {
        if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
            vc_remove_element( 'contact-form-7' );
            // Add other elements that should be removed here
        }
    }

    // Hook for admin editor.
    add_action( 'vc_build_admin_page', 'cruxstore_vc_remove_cf7', 20 );

    /**
     * Include js_composer update param
     *
     */
    require CRUXSTORE_FW_DIR . 'js_composer/js_composer_update.php';

    /**
     * Include js_composer functions
     *
     */
    require CRUXSTORE_FW_DIR . 'js_composer/js_composer_function.php';


}


if(cruxstore_is_wc()){
    /**
     * support for woocommerce helpers
     *
     */
    require CRUXSTORE_FW_DIR . '/woocommerce.php';

    /**
     * support for woocommerce attributes
     *
     */
    require CRUXSTORE_FW_CLASS . 'class-wc-admin-attributes.php';
}



/**
 * Include Widgets register and define all sidebars.
 *
 */
require CRUXSTORE_FW_DIR . 'widgets.php';