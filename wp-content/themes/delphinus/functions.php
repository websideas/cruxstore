<?php
//session_start();
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


define( 'DELPHINUS_THEME_OPTIONS', 'delphinus_option' );

define( 'DELPHINUS_THEME_DIR', trailingslashit(get_template_directory()));
define( 'DELPHINUS_THEME_URL', trailingslashit(get_template_directory_uri()));
define( 'DELPHINUS_THEME_TEMP', DELPHINUS_THEME_DIR.'templates/');
define( 'DELPHINUS_THEME_DATA', DELPHINUS_THEME_URL.'dummy-data/');
define( 'DELPHINUS_THEME_DATA_DIR', DELPHINUS_THEME_DIR.'dummy-data/');

define( 'DELPHINUS_THEME_ASSETS', DELPHINUS_THEME_URL . 'assets/');
define( 'DELPHINUS_THEME_LIBS', DELPHINUS_THEME_ASSETS . 'libs/');
define( 'DELPHINUS_THEME_JS', DELPHINUS_THEME_ASSETS . 'js/');
define( 'DELPHINUS_THEME_CSS', DELPHINUS_THEME_ASSETS . 'css/');
define( 'DELPHINUS_THEME_IMG', DELPHINUS_THEME_ASSETS . 'images/');

//Include framework
require DELPHINUS_THEME_DIR .'framework/core.php';
