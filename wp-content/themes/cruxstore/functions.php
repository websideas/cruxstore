<?php
//session_start();
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


define( 'CRUXSTORE_THEME_OPTIONS', 'cruxstore_option' );

define( 'CRUXSTORE_THEME_DIR', trailingslashit(get_template_directory()));
define( 'CRUXSTORE_THEME_URL', trailingslashit(get_template_directory_uri()));
define( 'CRUXSTORE_THEME_TEMP', CRUXSTORE_THEME_DIR.'templates/');
define( 'CRUXSTORE_THEME_DATA', CRUXSTORE_THEME_URL.'dummy-data/');
define( 'CRUXSTORE_THEME_DATA_DIR', CRUXSTORE_THEME_DIR.'dummy-data/');

define( 'CRUXSTORE_THEME_ASSETS', CRUXSTORE_THEME_URL . 'assets/');
define( 'CRUXSTORE_THEME_LIBS', CRUXSTORE_THEME_ASSETS . 'libs/');
define( 'CRUXSTORE_THEME_JS', CRUXSTORE_THEME_ASSETS . 'js/');
define( 'CRUXSTORE_THEME_CSS', CRUXSTORE_THEME_ASSETS . 'css/');
define( 'CRUXSTORE_THEME_IMG', CRUXSTORE_THEME_ASSETS . 'images/');

//Include framework
require CRUXSTORE_THEME_DIR .'framework/core.php';
