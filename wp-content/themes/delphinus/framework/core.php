<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

define( 'DELPHINUS_FW_VER', '1.0' );

define( 'DELPHINUS_FW_DIR', trailingslashit(DELPHINUS_THEME_DIR.'framework'));
define( 'DELPHINUS_FW_URL', trailingslashit(DELPHINUS_THEME_URL.'framework'));

define( 'DELPHINUS_FW_EXT_DIR', trailingslashit( DELPHINUS_FW_DIR . 'extensions' ) );
define( 'DELPHINUS_FW_EXT_URL', trailingslashit( DELPHINUS_FW_URL . 'extensions' ) );

define( 'DELPHINUS_FW_PLUGINS_DIR', trailingslashit( DELPHINUS_FW_DIR . 'plugins' ) );

define( 'DELPHINUS_FW_WIDGETS', trailingslashit( DELPHINUS_FW_DIR . 'widgets' ) );

define( 'DELPHINUS_FW_ASSETS', trailingslashit( DELPHINUS_FW_URL . 'assets' ) );
define( 'DELPHINUS_FW_JS', trailingslashit( DELPHINUS_FW_ASSETS . 'js' ) );
define( 'DELPHINUS_FW_CSS', trailingslashit( DELPHINUS_FW_ASSETS . 'css' ) );
define( 'DELPHINUS_FW_IMG', trailingslashit( DELPHINUS_FW_ASSETS . 'images' ) );
define( 'DELPHINUS_FW_LIBS', trailingslashit( DELPHINUS_FW_ASSETS . 'libs' ) );

define( 'DELPHINUS_FW_CLASS', trailingslashit( DELPHINUS_FW_DIR . 'class' ) );
define( 'DELPHINUS_FW_DATA', trailingslashit( DELPHINUS_FW_DIR . 'data' ) );


/**
 * All ajax functions
 *
 */
require_once  DELPHINUS_FW_DIR . 'ajax.php';


/**
 * Get all functions for frontend
 *
 */
require_once DELPHINUS_FW_DIR . 'frontend.php';

/**
 * Get functions for framework
 *
 */
require_once DELPHINUS_FW_DIR . 'functions.php';

/**
 * Get class helpers in framework
 *
 */
require_once DELPHINUS_FW_DIR . 'helpers.php';


/**
 * get custom walker for wp_nav_menu
 *
 */
require DELPHINUS_FW_EXT_DIR .'nav/nav_custom_walker.php';


if ( class_exists( 'RW_Meta_Box' ) && is_admin() ) {

    // Add fields to metabox
    require DELPHINUS_FW_EXT_DIR . 'meta-box-custom.php';

    // Add plugin meta-box-show-hide
    require DELPHINUS_FW_EXT_DIR . 'meta-box-show-hide/meta-box-show-hide.php';

    // Add plugin meta-box-tabs
    require DELPHINUS_FW_EXT_DIR . 'meta-box-tabs/meta-box-tabs.php';

    // Add plugin meta-box-conditional-logic
    define( 'MBC_URL', trailingslashit( DELPHINUS_FW_EXT_URL . 'meta-box-conditional-logic' ) );
    require DELPHINUS_FW_EXT_DIR . 'meta-box-conditional-logic/meta-box-conditional-logic.php';

    require DELPHINUS_FW_DATA . 'data-meta-box.php';

}


/**
 * Include the redux-framework.
 * 
 */

if(!function_exists('redux_register_custom_extension_loader')) :
	function redux_register_custom_extension_loader($ReduxFramework) {
		$path = DELPHINUS_FW_EXT_DIR . '/ReduxCoreExt/';
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
	add_action("redux/extensions/".DELPHINUS_THEME_OPTIONS."/before", 'redux_register_custom_extension_loader', 0);
endif;


add_action('init', 'delphinus_admin_options_init');
function  delphinus_admin_options_init(){
    if (file_exists( DELPHINUS_FW_DATA . 'data-options.php' ) ) {
        require  DELPHINUS_FW_DATA . 'data-options.php';
    }
}



if (is_admin() ) {

	/**
	 * Get plugin require for theme
	 *
	 */
	require DELPHINUS_FW_CLASS . 'class-tgm-plugin-activation.php';

	/**
	 * Install Plugins
     * 
	 */ 
 	require DELPHINUS_FW_DATA . 'data-plugins.php';

    /**
     * Get Navigation nav
     *
     */
    require DELPHINUS_FW_EXT_DIR . 'nav/nav.php';


    /**
     * Add Admin function
     *
     */
    require DELPHINUS_FW_DIR . 'admin.php';


}

/* Insert icon to parrams icons */
require DELPHINUS_FW_DATA . '/data-icons.php';

  
/**
 * Force Visual Composer to initialize as "built into the theme". 
 * This will hide certain tabs under the Settings->Visual Composer page
 */

add_action( 'vc_before_init', 'delphinus_vcSetAsTheme' );
function delphinus_vcSetAsTheme() {
    vc_set_as_theme();
}


/**
 * Initialising Visual Composer
 * 
 */ 
if ( class_exists( 'Vc_Manager', false ) ) {


    if ( ! function_exists( 'js_composer_bridge_admin' ) ) {
		function js_composer_bridge_admin( $hook ) {
			wp_enqueue_style( 'js_composer_bridge', DELPHINUS_FW_CSS . 'js_composer_bridge.css', array(), DELPHINUS_FW_VER );
		}
	}
    add_action( 'admin_enqueue_scripts', 'js_composer_bridge_admin', 15 );


    function delphinus_js_composer_bridge() {
        require DELPHINUS_FW_DIR . 'js_composer/js_composer_parrams.php';
        require DELPHINUS_FW_DIR . 'js_composer/js_composer_bridge.php';
    }

    if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
        vc_set_shortcodes_templates_dir( DELPHINUS_THEME_TEMP . '/vc_templates' );
    }
    add_action( 'init', 'delphinus_js_composer_bridge', 20 );


    function rd_vc_remove_frontend_links() {
        vc_disable_frontend(); // this will disable frontend editor
    }
    add_action( 'vc_after_init', 'rd_vc_remove_frontend_links' );


    function delphinus_vc_remove_cf7() {
        if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
            vc_remove_element( 'contact-form-7' );
            // Add other elements that should be removed here
        }
    }

    // Hook for admin editor.
    add_action( 'vc_build_admin_page', 'delphinus_vc_remove_cf7', 20 );

    /**
     * Include js_composer update param
     *
     */
    require DELPHINUS_FW_DIR . 'js_composer/js_composer_update.php';



}


if(delphinus_is_wc()){
    /**
     * support for woocommerce helpers
     *
     */
    require DELPHINUS_FW_DIR . '/woocommerce.php';
}



/**
 * Include Widgets register and define all sidebars.
 *
 */
require DELPHINUS_FW_DIR . 'widgets.php';