<?php
/**
 * Extension socials
 *
 *
 * KT socials - Modified For ReduxFramework
 *
 * @package     DELPHINUS_Socials
 * @author      Cuongdv
 * @version     1.0
 */
 
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_extension_delphinus_socials' ) ) {

    class ReduxFramework_extension_delphinus_socials {
        
        public static $instance;
        
        public $extension_dir;

        static $version = "1.0";

        protected $parent;
        
        public $extension_url;
        
        /**
         * Class Constructor
         *
         * @since       1.0
         * @access      public
         * @return      void
         */
        public function __construct( $parent ) {
            $this->parent = $parent;

            if ( !is_admin() ) return;


            $this->field_name = 'delphinus_socials';

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array( &$this,
                    'overload_field_path'
                ) );


        }
        
        public static function get_instance() {
            return self::$instance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path( $field ) {
            return DELPHINUS_FW_EXT_DIR . 'ReduxCoreExt/' .$this->field_name.'/'. $this->field_name . '/field_' . $this->field_name . '.php';
        }
        
    }
}