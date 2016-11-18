<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


if ( ! class_exists( 'DELPHINUS_config' ) ) {

    class DELPHINUS_config
    {
        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct()
        {
            if (!class_exists('ReduxFramework')) {
                return;
            }
            if (true == Redux_Helpers::isTheme(__FILE__)) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }
        }

        public function initSettings()
        {

            $this->theme = wp_get_theme();
            $this->setArguments();
            $this->setSections();
            if (!isset($this->args['opt_name'])) {
                return;
            }
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        public function setArguments()
        {

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => DELPHINUS_THEME_OPTIONS,
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $this->theme->get('Name'),
                // Name that appears at the top of your panel
                'display_version' => $this->theme->get('Version'),
                // Version that appears at the top of your panel
                'menu_type' => 'menu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true,
                // Show the sections below the admin menu item or not
                'menu_title' => esc_html__('Theme Options', 'delphinus'),

                'page_title' => $this->theme->get('Name') . ' ' . esc_html__('Theme Options', 'delphinus'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '',
                // Set it you want google fonts to update weekly. A google_api_key value is required.
                'google_update_weekly' => false,
                // Must be defined to add google fonts to the typography module
                'async_typography' => false,
                // Use a asynchronous font on the front end or font string
                //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
                'admin_bar' => true,
                // Show the panel pages on the admin bar
                'admin_bar_icon' => 'dashicons-portfolio',
                // Choose an icon for the admin bar menu
                'admin_bar_priority' => 50,
                // Choose an priority for the admin bar menu
                'global_variable' => '',
                // Set a different name for your global variable other than the opt_name
                'dev_mode' => false,
                // Show the time the page took to load, etc
                'update_notice' => false,
                // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
                'customizer' => false,
                // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

                // OPTIONAL -> Give you extra features
                'page_priority' => 61,
                // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php',
                // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options',
                // Permissions needed to access the options panel.
                'menu_icon' => 'dashicons-art',
                // Specify a custom URL to an icon
                'last_tab' => '',
                // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes',
                // Icon displayed in the admin panel next to your menu_title
                'page_slug' => 'theme_options',
                // Page slug used to denote the panel
                'save_defaults' => true,
                // On load save the defaults to DB before user clicks save or not
                'default_show' => false,
                // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '',
                // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,
                // Shows the Import/Export panel when not used as a field.

                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true,
                // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true,
                // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit'     => esc_html__('If you like Delphinus please leave us a &#9734;&#9734;&#9734;&#9734;&#9734; rating. A huge thank you from KiteThemes in advance!', 'delphinus'),

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info' => true,
                // REMOVE
            );

        }

        public function setSections()
        {

            $image_sizes = delphinus_get_image_sizes();

            $this->sections[] = array(
                'id'    => 'general',
                'title'  => esc_html__( 'General', 'delphinus' ),
                'icon'  => 'fa fa-cogs'
            );

            $this->sections[] = array(
                'id'    => 'general_layout',
                'title'  => esc_html__( 'General', 'delphinus' ),
                'subsection' => true,
                'fields' => array(
                    /*array(
                        'id'       => 'layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Site boxed mod(?)', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose page layout", 'delphinus' ),
                        'options'  => array(
                            'full' => esc_html__('Full width Layout', 'delphinus'),
                            'boxed' => esc_html__('Boxed Layout', 'delphinus'),
                        ),
                        'default'  => 'full',
                        'clear' => false
                    ),*/

                    array(
                        'id' => 'use_page_loader',
                        'type' => 'switch',
                        'title' => esc_html__('Use Page Loader?', 'delphinus'),
                        'desc' => esc_html__('', 'delphinus'),
                        'default' => 0,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),

                    array(
                        'id'       => 'archive_placeholder',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Placeholder', 'delphinus' ),
                        'subtitle'     => esc_html__( "Placeholder for none image", 'delphinus' ),
                    ),

                    array(
                        'id' => 'notfound_page_type',
                        'type' => 'select',
                        'title' => esc_html__('404 Page', 'delphinus'),
                        'desc' => '',
                        'options' => array(
                            'default' => esc_html__( 'Default', 'delphinus' ) ,
                            'home' => esc_html__( 'Redirect Home', 'delphinus' ) ,
                        ),
                        'default' => 'default',
                    ),

                )
            );
            /**
             *  Logos
             **/
            $this->sections[] = array(
                'id'            => 'logos_favicon',
                'title'         => esc_html__( 'Logos', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'logos_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Logos settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'logo',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo', 'delphinus' ),
                    ),
                    array(
                        'id'       => 'logo_retina',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo (Retina Version @2x)', 'delphinus' ),
                        'desc'     => esc_html__('Select an image file for the retina version of the logo. It should be exactly 2x the size of main logo.', 'delphinus')
                    ),



                    array(
                        'id'       => 'logo_light',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo light', 'delphinus' ),
                    ),
                    array(
                        'id'       => 'logo_light_retina',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo light(Retina Version @2x)', 'delphinus' ),
                        'desc'     => esc_html__('Select an image file for the retina version of the logo. It should be exactly 2x the size of main logo.', 'delphinus')
                    ),
                    
                    array(
                        'id'       => 'logo_footer',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo Footer', 'delphinus' ),
                    ),

                )
            );

            /**
             *  Header
             **/
            $this->sections[] = array(
                'id'            => 'Header',
                'title'         => esc_html__( 'Header', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(

                    array(
                        'id'       => 'header',
                        'type'     => 'image_select',
                        'compiler' => true,
                        'title'    => esc_html__( 'Header layout', 'delphinus' ),
                        'subtitle' => esc_html__( 'Please choose header layout', 'delphinus' ),
                        'options'  => array(
                            1 => array( 'alt' => esc_html__( 'Layout 1', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v1.jpg' ),
                            2 => array( 'alt' => esc_html__( 'Layout 2', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v2.jpg' ),
                            3 => array( 'alt' => esc_html__( 'Layout 3', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v3.jpg' ),
                            4 => array( 'alt' => esc_html__( 'Layout 4', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v4.jpg' ),
                            //5 => array( 'alt' => esc_html__( 'Layout 5', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v5.jpg' ),
                            6 => array( 'alt' => esc_html__( 'Layout 6', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v6.jpg' ),
                            7 => array( 'alt' => esc_html__( 'Layout 7', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v7.jpg' ),
                            8 => array( 'alt' => esc_html__( 'Layout 8', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v8.jpg' ),
                        ),
                        'default'  => 1
                    ),
                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id' => 'header_shadow',
                        'type' => 'switch',
                        'title' => esc_html__('Header shadow', 'delphinus'),
                        "default" => 1,
                        'on'        => esc_html__( 'Enabled', 'delphinus' ),
                        'off'       => esc_html__( 'Disabled', 'delphinus' ),
                    ),
                    array(
                        'id' => 'header_search',
                        'type' => 'switch',
                        'title' => esc_html__('Search Icon', 'delphinus'),
                        'desc' => esc_html__('Enable the search Icon in the header.', 'delphinus'),
                        "default" => 1,
                        'on'        => esc_html__( 'Enabled', 'delphinus' ),
                        'off'       => esc_html__( 'Disabled', 'delphinus' ),
                    ),
                    array(
                        'id'       => 'header_search_type',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Header search type', 'delphinus' ),
                        'options'  => array(
                            'all' => esc_html__('All', 'delphinus'),
                            'product' => esc_html__('Only Products', 'delphinus' )
                        ),
                        'default'  => 'all'
                    ),
                    array(
                        'id'   => 'header_socials',
                        'type' => 'delphinus_socials',
                        'title'    => esc_html__( 'Select your socials', 'delphinus' ),
                        'default' => 'facebook,twitter,instagram,linkedin'
                    ),
                )
            );
            /**
             *    Footer
             **/
            $this->sections[] = array(
                'id' => 'footer',
                'title' => esc_html__('Footer', 'delphinus'),
                'desc' => '',
                'subsection' => true,
                'fields' => array(
                    // Footer settings

                    array(
                        'id' => 'backtotop',
                        'type' => 'switch',
                        'title' => esc_html__('Back to top', 'delphinus'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                    ),

                    array(
                        'id' => 'footer_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer settings', 'delphinus') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer',
                        'type' => 'switch',
                        'title' => esc_html__('Footer enable', 'delphinus'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                    ),

                    array(
                        'id' => 'footer_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Footer background fullwidth', 'delphinus'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                    ),
                    // Footer Top settings
                    array(
                        'id' => 'footer_top_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer top settings', 'delphinus') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_top',
                        'type' => 'switch',
                        'title' => esc_html__('Footer top enable', 'delphinus'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                    ),

                    // Footer widgets settings
                    array(
                        'id' => 'footer_widgets_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer widgets settings', 'delphinus') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_widgets',
                        'type' => 'switch',
                        'title' => esc_html__('Footer widgets enable', 'delphinus'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                    ),
                    array(
                        'id' => 'footer_widgets_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Footer widgets layout', 'delphinus'),
                        'subtitle' => esc_html__('Select your footer widgets layout', 'delphinus'),
                        'options' => array(
                            'featured' => array('alt' => esc_html__('Layout 1', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-0.jpg'),
                            '3-3-3-3' => array('alt' => esc_html__('Layout 2', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-1.jpg'),
                            '6-3-3' => array('alt' => esc_html__('Layout 3', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-2.jpg'),
                            '3-3-6' => array('alt' => esc_html__('Layout 4', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-3.jpg'),
                            '6-6' => array('alt' => esc_html__('Layout 5', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-4.jpg'),
                            '4-4-4' => array('alt' => esc_html__('Layout 6', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-5.jpg'),
                            '8-4' => array('alt' => esc_html__('Layout 7', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-6.jpg'),
                            '4-8' => array('alt' => esc_html__('Layout 8', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-7.jpg'),
                            '3-6-3' => array('alt' => esc_html__('Layout 9', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-8.jpg'),
                            '12' => array('alt' => esc_html__('Layout 10', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-9.jpg'),
                        ),
                        'default' => 'featured'
                    ),

                    /* Footer Bottom */
                    array(
                        'id' => 'footer_bottom_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer bottom settings', 'delphinus') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_bottom',
                        'type' => 'switch',
                        'title' => esc_html__('Footer bottom enable', 'delphinus'),
                        'default' => false,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                    ),
                    array(
                        'id' => 'footer_bottom_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Footer bottom layout', 'delphinus'),
                        'subtitle' => esc_html__('Select your footer bottom layout', 'delphinus'),
                        'options' => array(
                            '1' => array('alt' => esc_html__('Layout 1', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-bottom-1.png'),
                            '2' => array('alt' => esc_html__('Layout 2', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'footer/footer-bottom-2.png'),
                        ),
                        'default' => '1'
                    ),

                    /* Footer copyright */
                    array(
                        'id' => 'footer_copyright_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer copyright settings', 'delphinus') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_copyright',
                        'type' => 'switch',
                        'title' => esc_html__('Footer copyright enable', 'delphinus'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                    ),
                    array(
                        'id'       => 'footer_copyright_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer copyright layout', 'delphinus' ),
                        'subtitle'     => esc_html__( 'Select your preferred footer layout.', 'delphinus' ),
                        'options'  => array(
                            'centered' => esc_html__('Centered', 'delphinus'),
                            'sides' => esc_html__('Sides', 'delphinus' ),
                            'columns' => esc_html__('3 columns', 'delphinus' ),
                        ),
                        'default'  => 'centered',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'footer_copyright_left',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer Copyright Left', 'delphinus' ),
                        'options'  => array(
                            '' => esc_html__('Empty', 'delphinus' ),
                            'navigation' => esc_html__('Navigation', 'delphinus' ),
                            'socials' => esc_html__('Socials', 'delphinus' ),
                            'copyright' => esc_html__('Copyright', 'delphinus' ),
                            'subscribe' => esc_html__('Subscribe', 'delphinus' ),
                            
                        ),
                        'default'  => ''
                    ),
                    array(
                        'id'       => 'footer_copyright_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer Copyright Right', 'delphinus' ),
                        'options'  => array(
                            '' => esc_html__('Empty', 'delphinus' ),
                            'navigation' => esc_html__('Navigation', 'delphinus' ),
                            'socials' => esc_html__('Socials', 'delphinus' ),
                            'copyright' => esc_html__('Copyright', 'delphinus' ),
                            'subscribe' => esc_html__('Subscribe', 'delphinus' ),
                        ),
                        'default'  => 'copyright'
                    ),
                    array(
                        'id'       => 'footer_copyright_center',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer Copyright Center', 'delphinus' ),
                        'options'  => array(
                            '' => esc_html__('Empty', 'delphinus' ),
                            'navigation' => esc_html__('Navigation', 'delphinus' ),
                            'socials' => esc_html__('Socials', 'delphinus' ),
                            'copyright' => esc_html__('Copyright', 'delphinus' ),
                            'subscribe' => esc_html__('Subscribe', 'delphinus' ),
                        ),
                        'default'  => 'copyright'
                    ),
                    array(
                        'id'   => 'footer_socials',
                        'type' => 'delphinus_socials',
                        'title'    => esc_html__( 'Select your socials', 'delphinus' ),
                        'default' => 'facebook,twitter,instagram,linkedin'
                    ),
                    array(
                        'id' => 'footer_copyright_text',
                        'type' => 'editor',
                        'title' => esc_html__('Footer Copyright Text', 'delphinus'),
                        'default' => '&copy; 2016 Delphinus'
                    ),
                )
            );


            /**
             *	Styling
             **/
            $this->sections[] = array(
                'id'			=> 'styling',
                'title'			=> esc_html__( 'Styling', 'delphinus' ),
                'desc'			=> '',
                'icon'	=> 'fa fa-pencil',
            );

            /**
             *	Styling Logo
             **/
            $this->sections[] = array(
                'id'			=> 'styling-logo',
                'title'			=> esc_html__( 'Logo', 'delphinus' ),
                'subsection' => true,
                'fields'		=> array(

                    array(
                        'id'             => 'logo_width',
                        'type'           => 'dimensions',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Logo width', 'delphinus' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 170, 'units'   => 'px' ),
                        'output'   => array( '.branding.branding-default img' ),
                    ),

                    array(
                        'id'       => 'logo_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'output'   => array( '.branding.branding-default' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Logo margin spacing Option', 'delphinus' ),
                        'default'  => array(
                            'margin-top'    => '36px',
                            'margin-right'  => '0',
                            'margin-bottom' => '36px',
                            'margin-left'   => '0'
                        )
                    ),

                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id'             => 'logo_mobile_width',
                        'type'           => 'dimensions',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Logo mobile width', 'delphinus' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 170, 'units'   => 'px' ),
                        'output'   => array( '.branding.branding-mobile img' ),
                    ),
                    array(
                        'id'       => 'logo_mobile_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Logo mobile margin spacing Option', 'delphinus' ),
                        'default'  => array(
                            'margin-top'    => '20px',
                            'margin-right'  => '0px',
                            'margin-bottom' => '20px',
                            'margin-left'   => '0px'
                        ),
                        'output'   => array( '.branding.branding-mobile' ),
                    ),

                )
            );


            /**
             *	Styling Header
             **/
            $this->sections[] = array(
				'id'			=> 'styling_header',
				'title'			=> esc_html__( 'Header', 'delphinus' ),
				'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'header_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Header background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Header background with image, color, etc.', 'delphinus' ),
                        'default'   => '',
                        'output'      => array( '.header-content' ),
                    ),
                    array(
                        'id'       => 'header_default_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header Default settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Header Border', 'delphinus' ),
                        'output'   => array( '.header-content' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'top'      => false,
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'header_light_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header Light settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_light_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Header Border', 'delphinus' ),
                        'output'   => array( '.header-light.header-transparent .header-content' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'top'      => false,
                        'default'  => array(
                            'border-bottom' => '1px',
                            'border-style' => 'solid',
                            'border-color' => 'rgba(246, 246, 246, 0.2)'
                        )
                    ),
                    array(
                        'id'       => 'header_light_spacing',
                        'type'     => 'raw',
                        'content'  => '<div style="height:150px"></div>',
                        'full_width' => true
                    ),
                )
            );

            /**
             *	Styling Header Toolbar
             **/
            $this->sections[] = array(
                'id'			=> 'styling_header_toolbar',
                'title'			=> esc_html__( 'Header Toolbar', 'delphinus' ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'header_toolbar_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header Toolbar settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_toolbar_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Header Toolbar background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Header Toolbar background with image, color, etc.', 'delphinus' ),
                        'default'   => '',
                        'output'      => array( '.topbar' ),
                    ),
                    array(
                        'id'       => 'header_toolbar_default_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header Toolbar Default', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_toolbar_default_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.top-navigation > li > a'
                        ),
                        'title'    => esc_html__( 'Default: Top Level Color', 'delphinus' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_toolbar_default_active_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.top-navigation > li:hover > a',
                            '.top-navigation > li > a:hover',
                            '.top-navigation > li > a:focus'
                        ),
                        'title'    => esc_html__( 'Default: Top Level Hover Color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_toolbar_border_color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Default: Border Color', 'delphinus' ),
                        'default'  => '#ebebeb',
                        'transparent' => true
                    ),

                    array(
                        'id'       => 'header_toolbar_light_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header Toolbar Light', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_toolbar_light_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light .top-navigation > li > a'
                        ),
                        'title'    => esc_html__( 'Light: Top Level Color', 'delphinus' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_toolbar_light_active_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light .top-navigation > li:hover > a',
                            '.header-transparent.header-light .top-navigation > li > a:hover',
                            '.header-transparent.header-light .top-navigation > li > a:focus'
                        ),
                        'title'    => esc_html__( 'Light: Top Level Hover Color', 'delphinus' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_toolbar_light_border_color',
                        'type'     => 'color_rgba',
                        'title'    => esc_html__( 'Light: Border Color', 'delphinus' ),
                        'subtitle' => esc_html__( 'Gives you the RGBA color.', 'delphinus' ),
                        'default'  => array(
                            'color' => '#f6f6f6',
                            'alpha' => '.2'
                        ),
                        'mode'     => 'background',
                    ),
                    array(
                        'id'       => 'mega_menu_spacing',
                        'type'     => 'raw',
                        'content'  => '<div style="height:150px"></div>',
                        'full_width' => true
                    ),

                )
            );


            /**
             *  Styling Sticky
             **/
            $this->sections[] = array(
                'id'            => 'styling_sticky',
                'title'         => esc_html__( 'Sticky', 'delphinus' ),
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'fixed_header',
                        'type'     => 'button_set',
                        'title'    => esc_html__( 'Sticky header', 'delphinus' ),
                        'options'  => array(
                            '1' => esc_html__('Disabled', 'delphinus'),
                            '2' => esc_html__('Fixed Sticky', 'delphinus'),
                            '3' => esc_html__('Slide Down', 'delphinus'),
                        ),
                        'default'  => '3',
                        'desc' => esc_html__('Choose your sticky effect.', 'delphinus')
                    ),
                    array(
                        'id'             => 'logo_sticky_width',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'title'          => esc_html__( 'Logo width', 'delphinus' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 170, 'units'   => 'px' ),
                        'output'   => array( '.is-sticky .apply-sticky .branding-default img' ),
                    ),

                    array(
                        'id'       => 'logo_sticky_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Logo sticky margin spacing Option', 'delphinus' ),
                        'default'  => array(
                            'margin-top'    => '15px',
                            'margin-bottom' => '15px',
                            'margin-left'   => '0',
                            'margin-right'   => '0'
                        ),
                        'output'   => array( '.is-sticky .apply-sticky .branding-default'),
                    ),

                    array(
                        'id'             => 'navigation_height_fixed',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'title'          => esc_html__( 'Main Navigation Sticky Height', 'delphinus' ),
                        'desc'          => esc_html__( 'Change height of main navigation sticky', 'delphinus' ),
                        'width'         => false,
                        'default'        => array(
                            'height'  => '60',
                            'units'  => 'px'
                        )
                    ),

                    array(
                        'id'            => 'header_sticky_opacity',
                        'type'          => 'slider',
                        'title'         => esc_html__( 'Sticky Background opacity', 'delphinus' ),
                        'default'       => .8,
                        'min'           => 0,
                        'step'          => .1,
                        'max'           => 1,
                        'resolution'    => 0.1,
                        'display_value' => 'text'
                    ),

                    array(
                        'id'       => 'header_sticky_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Header sticky background', 'delphinus' ),
                        'desc' => esc_html__( 'Header sticky with image, color, etc.', 'delphinus' ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                        'default'   => array(
                            'background-color'      => '#ffffff',
                        ),
                        'output'      => array( '.header-sticky-background' ),
                    ),


                    array(
                        'id'       => 'header_sticky_light_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Header transparent sticky background', 'delphinus' ),
                        'desc' => esc_html__( 'Header transparent sticky with image, color, etc.', 'delphinus' ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                        'default'   => array(
                            'background-color'      => '#000000',
                        ),
                        'output'      => array( '.header-transparent.header-light .header-sticky-background' ),
                    ),
                    array(
                        'id'       => 'header_sticky_spacing',
                        'type'     => 'raw',
                        'content'  => '<div style="height:150px"></div>',
                        'full_width' => true
                    ),

                )
            );

            /**
             *	Styling Footer
             **/
            $this->sections[] = array(
                'id'			=> 'styling_footer',
                'title'			=> esc_html__( 'Footer', 'delphinus' ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'footer_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer Background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Footer Background with image, color, etc.', 'delphinus' ),
                        'default'   => array( ),
                        'output'      => array( '#footer' ),
                    ),

                    array(
                        'id'       => 'footer_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Footer padding', 'delphinus' ),
                        'default'  => array( )
                    ),

                    array(
                        'id'       => 'footer_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer Border', 'delphinus' ),
                        'output'   => array( '#footer' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'bottom'      => false,
                        'default'  => array( )
                    ),

                    // Footer top settings
                    array(
                        'id'       => 'footer_top_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer top settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_top_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer top Background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Footer top Background with image, color, etc.', 'delphinus' ),
                        'default'   => array( ),
                        'output'      => array( '#footer-top' ),
                    ),
                    array(
                        'id'       => 'footer_top_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer-top' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Footer top padding', 'delphinus' ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'footer_top_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer top Border', 'delphinus' ),
                        'output'   => array( '#footer-top' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'top'      => false,
                        'default'  => array(

                        )
                    ),
                    // Footer widgets settings
                    array(
                        'id'       => 'footer_widgets_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer widgets settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_widgets_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer widgets Background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Footer widgets Background with image, color, etc.', 'delphinus' ),
                        'default'   => array(  ),
                        'output'      => array( '#footer-area' ),
                    ),

                    array(
                        'id'       => 'footer_box_widgets_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer Box widgets Background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Footer Box widgets Background with image, color, etc.', 'delphinus' ),
                        'default'   => array( ),
                        'output'      => array( '#footer-area .footer-area-right' ),
                    ),


                    array(
                        'id'       => 'footer_widgets_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer-area' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Footer widgets padding', 'delphinus' ),
                        'default'  => array( )
                    ),

                    //Footer bottom settings
                    array(
                        'id'       => 'footer_bottom_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer bottom settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_bottom_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer bottom Background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Footer  Background with image, color, etc.', 'delphinus' ),
                        'default'   => array( ),
                        'output'      => array( '#footer-bottom' ),
                    ),

                    array(
                        'id'       => 'footer_bottom_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Footer bottom padding', 'delphinus' ),
                        'default'  => array( ),
                        'subtitle' => 'Disable if you use instagram background',
                    ),
                    array(
                        'id'       => 'footer_bottom_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer bottom Border', 'delphinus' ),
                        'output'   => array( '#footer-bottom' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'bottom'   => false,
                        'color'    => false,
                        'default'  => array( )
                    ),
                    
                    array(
                        'id'       => 'footer_bottom_border_color',
                        'type'     => 'color_rgba',
                        'title'    => esc_html__( 'Footer bottom Border color', 'delphinus' ),
                        'output'   => array( '#footer-bottom' ),
                        'default'  => array(
                            'color' => '#FFFFFF',
                            'alpha' => '.2'
                        ),
                        'mode'     => 'border-color',
                    ),
                    
                    //Footer copyright settings
                    array(
                        'id'       => 'footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer copyright settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'footer_copyright_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer Copyright Border', 'delphinus' ),
                        'output'   => array( '#footer-copyright' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'bottom'      => false,
                        'default'  => array( )
                    ),

                    array(
                        'id'       => 'footer_copyright_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer Background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Footer Background with image, color, etc.', 'delphinus' ),
                        'default'   => array(
                            'background-color' => '#f6f6f6'
                        ),
                        'output'      => array( '#footer-copyright' ),
                    ),
                    array(
                        'id'       => 'footer_copyright_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '#footer-copyright' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Footer copyright padding', 'delphinus' ),
                        'default'  => array( )
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'footer_socials_style',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer socials style', 'delphinus' ),
                        'options'  => array(
                            'dark'   => esc_html__('Dark', 'delphinus' ),
                            'light'  => esc_html__('Light', 'delphinus' ),
                            'color'  => esc_html__('Color', 'delphinus' ),
                            'custom'  => esc_html__('Custom Color', 'delphinus' ),
                        ),
                        'default'  => 'custom'
                    ),
                    array(
                        'id'       => 'custom_color_social',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Footer socials Color', 'delphinus' ),
                        'default'  => '#999999',
                        'transparent' => false,
                        'required' => array('footer_socials_style','equals', array( 'custom' ) ),
                    ),
                    array(
                        'id'       => 'footer_socials_background',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer socials background', 'delphinus' ),
                        'options'  => array(
                            'empty'       => esc_html__('None', 'delphinus' ),
                            'rounded'   => esc_html__('Circle', 'delphinus' ),
                            'boxed'  => esc_html__('Square', 'delphinus' ),
                            'rounded-less'  => esc_html__('Rounded', 'delphinus' ),
                            'diamond-square'  => esc_html__('Diamond Square', 'delphinus' ),
                            'rounded-outline'  => esc_html__('Outline Circle', 'delphinus' ),
                            'boxed-outline'  => esc_html__('Outline Square', 'delphinus' ),
                            'rounded-less-outline'  => esc_html__('Outline Rounded', 'delphinus' ),
                            'diamond-square-outline'  => esc_html__('Outline Diamond Square', 'delphinus' ),
                        ),
                        'subtitle'     => esc_html__( 'Select background shape and style for social.', 'delphinus' ),
                        'default'  => 'empty'
                    ),
                    array(
                        'id'       => 'footer_socials_size',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer socials size', 'delphinus' ),
                        'options'  => array(
                            'small'       => esc_html__('Small', 'delphinus' ),
                            'standard'   => esc_html__('Standard', 'delphinus' ),
                        ),
                        'default'  => 'small'
                    ),
                    array(
                        'id'       => 'footer_socials_space_between_item',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Footer socials space between item', 'delphinus' ),
                        'default'  => '30'
                    ),
                )
            );

            /**
             *  Main Navigation
             **/
            $this->sections[] = array(
                'id'            => 'styling_navigation',
                'title'         => esc_html__( 'Main Navigation', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'styling_navigation_general',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'General', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'             => 'navigation_height',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Main Navigation Height', 'delphinus' ),
                        'subtitle'          => esc_html__( 'Change height of main navigation', 'delphinus' ),
                        'width'         => false,
                        'default'        => array(
                            'height'  => '102',
                            'units'  => 'px'
                        )
                    ),

                    array(
                        'id'       => 'navigation_box_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'MegaMenu & Dropdown Box background', 'delphinus' ),
                        'default'   => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'      => array(
                            '#nav #main-nav-wc > li ul.sub-menu-dropdown',
                            '#nav #main-nav-wc > li .navigation-submenu',
                            '#nav #main-navigation > li ul.sub-menu-dropdown',
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper',
                            '#nav #main-navigation > li .navigation-submenu',
                            '.top-navigation > li .navigation-submenu'
                        ),
                        'transparent'           => false,
                    ),

                    array(
                        'id'       => 'navigation_box_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'MegaMenu & Dropdown Box Border', 'delphinus' ),
                        'output'   => array(
                            '#nav #main-nav-wc > li .navigation-submenu',
                            '#nav #main-navigation > li ul.sub-menu-dropdown',
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper',
                            '.top-navigation > li .navigation-submenu'
                        ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'style'    => false,
                        'bottom'   => false,
                        'default'  => array(
                            'border-style'      => 'solid',
                            'border-top'        => '1',
                            'border-color'      => '#f6f6f6'
                        )
                    ),

                    array(
                        'id'       => 'styling_navigation_general',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Top Level', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'            => 'navigation_space',
                        'type'          => 'slider',
                        'title'         => esc_html__( 'Top Level space', 'delphinus' ),
                        'default'       => 20,
                        'min'           => 0,
                        'step'          => 1,
                        'max'           => 50,
                        'resolution'    => 1,
                        'display_value' => 'text',
                        'subtitle' => esc_html__( 'Margin left between top level.', 'delphinus' ),
                    ),
                    array(
                        'id'       => 'navigation_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-nav-wc > li > a',
                            '#nav #main-navigation > li > a',
                            '#nav #main-nav-socials > li > a'
                        ),
                        'title'    => esc_html__( 'Dark: Top Level Color', 'delphinus' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'navigation_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-nav-wc > li.current-menu-item > a',
                            '#nav #main-nav-wc > li > a:hover',
                            '#nav #main-nav-wc > li > a:focus',
                            '#nav #main-navigation > li.current-menu-item > a',
                            '#nav #main-navigation > li > a:hover',
                            '#nav #main-navigation > li > a:focus',
                            '#nav #main-nav-socials > li > a:hover',
                            '#nav #main-nav-socials > li > a:focus',
                        ),
                        'title'    => esc_html__( 'Dark: Top Level hover Color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),


                    array(
                        'id'       => 'navigation_color_light',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light #nav #main-nav-wc > li > a',
                            '.header-transparent.header-light #nav #main-nav-socials > li > a',
                            '.header-transparent.header-light #nav #main-navigation > li > a',
                        ),
                        'title'    => esc_html__( 'Light: Top Level Color', 'delphinus' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'navigation_color_hover_light',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light #nav #main-nav-wc > li.current-menu-item > a',
                            '.header-transparent.header-light #nav #main-nav-wc > li > a:hover',
                            '.header-transparent.header-light #nav #main-nav-wc > li > a:focus',
                            '.header-transparent.header-light #nav #main-navigation > li.current-menu-item > a',
                            '.header-transparent.header-light #nav #main-navigation > li > a:hover',
                            '.header-transparent.header-light #nav #main-navigation > li > a:focus',
                        ),
                        'title'    => esc_html__( 'Light: Top Level hover Color', 'delphinus' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),


                    array(
                        'id'       => 'styling_navigation_dropdown',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Drop down', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'             => 'navigation_dropdown',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Dropdown width', 'delphinus' ),
                        'subtitle'       => esc_html__( 'Change width of Dropdown', 'delphinus' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 308, 'units' => 'px' ),
                        'output'         => array( '#nav #main-navigation > li ul.sub-menu-dropdown' ),
                    ),
                    array(
                        'id'       => 'dropdown_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Dropdown Background Color', 'delphinus' ),
                        'default'  => array(
                            'background-color'      => '',
                        ),
                        'output'   => array(
                            '#nav #main-navigation > li ul.sub-menu-dropdown li a',
                            '.top-navigation > li .navigation-submenu > li a'
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => true,
                    ),

                    array(
                        'id'       => 'dropdown_background_hover',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Dropdown Background Hover Color', 'delphinus' ),
                        'default'  => array(
                            'background-color'      => '',
                        ),
                        'output'   => array(
                            '#nav #main-navigation > li ul.sub-menu-dropdown li.current-menu-item > a',
                            '#nav #main-navigation > li ul.sub-menu-dropdown li > a:hover',
                            '.top-navigation > li .navigation-submenu > li a:hover'
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => true,
                    ),
                    array(
                        'id'       => 'dropdown_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-navigation > li ul.sub-menu-dropdown li a',
                            '.navigation-submenu.woocommerce ul.product_list_widget li a',
                            '.shopping-bag .woocommerce.navigation-submenu .mini_cart_item .quantity',
                            '.top-navigation > li .navigation-submenu > li a',
                            '.shopping-bag .woocommerce.navigation-submenu .mini_cart_item .amount',
                        ),
                        'title'    => esc_html__( 'Dropdown Text Color', 'delphinus' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'dropdown_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-navigation > li ul.sub-menu-dropdown li.current-menu-item > a',
                            '#nav #main-navigation > li ul.sub-menu-dropdown li > a:hover',
                            '.top-navigation > li .navigation-submenu > li a:hover',
                            '.shopping-bag .navigation-submenu.woocommerce ul.product_list_widget li a'
                        ),
                        'title'    => esc_html__( 'Dropdown Text Hover Color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'dropdown_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'DropDown Border', 'delphinus' ),
                        'output'   => array(
                            '#nav #main-navigation > li ul.sub-menu-dropdown li + li',
                            '.shopping-bag .woocommerce.shopping-bag-content .mini_cart_item + .mini_cart_item',
                            '.shopping-bag .woocommerce.shopping-bag-content .total',
                            '.top-navigation > li .navigation-submenu > li + li'
                        ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'style'    => false,
                        'bottom'   => false,
                        'default'  => array(
                            'border-style'      => 'solid',
                            'border-top'        => '1',
                            'border-color'      => '#ebebeb'
                        )
                    ),
                    array(
                        'id'       => 'styling_navigation_mega',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Mega', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),


                    array(
                        'id'       => 'mega_title_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper > ul > li > a',
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper > ul > li > span',
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper > ul > li .widget-title',
                        ),
                        'title'    => esc_html__( 'MegaMenu Title color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_title_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper > ul > li > a:hover'
                        ),
                        'title'    => esc_html__( 'MegaMenu Title Hover Color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-navigation > li > .delphinus-megamenu-wrapper > .delphinus-megamenu-ul > li ul.sub-menu-megamenu a'
                        ),
                        'title'    => esc_html__( 'MegaMenu Text color', 'delphinus' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'mega_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#nav #main-navigation > li > .delphinus-megamenu-wrapper > .delphinus-megamenu-ul > li ul.sub-menu-megamenu a:hover',
                            '#nav #main-navigation > li > .delphinus-megamenu-wrapper > .delphinus-megamenu-ul > li ul.sub-menu-megamenu .current-menu-item > a',

                        ),
                        'title'    => esc_html__( 'MegaMenu Text Hover color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_border_color',
                        'title'    => esc_html__( 'MegaMenu Border color', 'delphinus' ),
                        'type'     => 'color',
                        'default'  => '#ebebeb`',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_menu_spacing',
                        'type'     => 'raw',
                        'content'  => '<div style="height:150px"></div>',
                        'full_width' => true
                    ),
                )
            );

            /**
             *  Mobile Navigation
             **/
            $this->sections[] = array(
                'id'            => 'styling_mobile_menu',
                'title'         => esc_html__( 'Mobile Menu', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'mobile_menu_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Background', 'delphinus' ),
                        'default'   => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'      => array( '#main-nav-mobile'),
                        'transparent'           => false,
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),

                    array(
                        'id'       => 'mobile_menu_color',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li > a'
                        ),
                        'title'    => esc_html__( 'Top Level Color', 'delphinus' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mobile_menu_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            'ul.navigation-mobile > li:hover > a',
                            'ul.navigation-mobile > li > a:hover'
                        ),
                        'title'    => esc_html__( 'Top Level hover Color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mobile_menu_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Top Level Background Color', 'delphinus' ),
                        'default'  => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'   => array(
                            'ul.navigation-mobile > li > a'
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                    ),

                    array(
                        'id'       => 'mobile_menu_background_hover',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Top Level Hover Color', 'delphinus' ),
                        'default'  => array(
                            'background-color'      => '#F5F5F5',
                        ),
                        'output'   => array(
                            'ul.navigation-mobile > li:hover > a',
                            'ul.navigation-mobile > li > a:hover',
                        ),
                        'background-repeat'     => false,
                        'background-attachment' => false,
                        'background-position'   => false,
                        'background-image'      => false,
                        'background-size'       => false,
                        'preview'               => false,
                        'transparent'           => false,
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'mobile_sub_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.main-nav-mobile > ul > li ul.sub-menu li a',
                            '.main-nav-mobile > ul > li ul.sub-menu-megamenu li a',
                            '.main-nav-mobile > ul > li ul.sub-menu-dropdown li a',
                            'ul.navigation-mobile > li .delphinus-megamenu-wrapper > ul.delphinus-megamenu-ul > li > .sub-menu-megamenu > li > a',
                        ),
                        'title'    => esc_html__( 'Text color', 'delphinus' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'mobile_sub_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '.main-nav-mobile > ul > li ul.sub-menu li a:hover',
                            '.main-nav-mobile > ul > li ul.sub-menu-megamenu li a:hover',
                            '.main-nav-mobile > ul > li ul.sub-menu-dropdown li a:hover',
                            'ul.navigation-mobile > li .delphinus-megamenu-wrapper > ul.delphinus-megamenu-ul > li > .sub-menu-megamenu > li > a:hover',
                        ),
                        'title'    => esc_html__( 'Text Hover color', 'delphinus' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'mobile_menu_spacing',
                        'type'     => 'raw',
                        'content'  => '<div style="height:150px"></div>',
                        'full_width' => true
                    ),
                )
            );


            /**
             *	Typography
             **/
            $this->sections[] = array(
                'id'			=> 'typography',
                'title'			=> esc_html__( 'Typography', 'delphinus' ),
                'desc'			=> '',
                'icon'	=> 'fa fa-camera-retro',
            );

            /**
             *	Typography General
             **/
            $this->sections[] = array(
                'id'			=> 'typography_general',
                'title'			=> esc_html__( 'General', 'delphinus' ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'typography_body',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Body Font', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the body font properties.', 'delphinus' ),
                        'text-align' => false,
                        'letter-spacing'  => true,
                        'output'      => array( 'body', 'button', 'input', 'textarea' ),
                        'default'  => array(
                            'font-family'     => 'Karla'
                        ),
                    ),
                    array(
                        'id'       => 'typography_pragraph',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Pragraph', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the pragraph font properties.', 'delphinus' ),
                        'output'   => array( 'p' ),
                        'default'  => array( ),
                        'color'    => false,
                        'text-align' => false,
                    ),

                    array(
                        'id'       => 'typography_blockquote',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Blockquote', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the blockquote font properties.', 'delphinus' ),
                        'output'   => array( 'blockquote' ),
                        'color'    => false,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Crete Round',
                            'font-style'      => '400italic'
                        ),
                    ),
                    /*
                    array(
                        'id'       => 'typography_button',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Button', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the button font properties.', 'delphinus' ),
                        'output'   => array(
                            '.button',
                            '.wpcf7-submit',
                            '.btn',
                            '.woocommerce #respond input#submit',
                            '.woocommerce a.button',
                            '.woocommerce button.button',
                            '.woocommerce input.button',
                            '.woocommerce #respond input#submit.alt',
                            '.woocommerce a.button.alt',
                            '.woocommerce button.button.alt',
                            '.woocommerce input.button.alt',
                            '.vc_general.vc_btn3',
                            '.delphinus-button',
                            '.readmore-link',
                            '.readmore-link-white'
                        ),
                        'default'  => array( ),
                        'color'    => false,
                        'text-align'    => false,
                        'font-size'    => false,
                        'text-transform' => true,
                        'letter-spacing'  => true,
                        'font-weight' => false
                    ),
                    */
                    array(
                        'id'       => 'typography_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Heading settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_heading1',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 1', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the heading 1 font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'text-align' => false,
                        'output'      => array( 'h1', '.h1' ),
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'text-transform'  => 'uppercase',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading2',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 2', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the heading 2 font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h2', '.h2' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading3',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 3', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the heading 3 font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h3', '.h3' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading4',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 4', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the heading 4 font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h4', '.h4' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading5',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 5', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the heading 5 font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h5', '.h5' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_heading6',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 6', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the heading 6 font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h6', '.h6' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'font-weight'     => '700'
                        ),
                    ),
                )
            );

            /**
             *  Typography header
             **/
            $this->sections[] = array(
                'id'            => 'typography_header',
                'title'         => esc_html__( 'Header', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_header_content',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Header', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the header font properties.', 'delphinus' ),
                        'google'   => true,
                        'text-align' => false,
                        'output'      => array( '#header' )
                    )
                )
            );
            /**
             *  Typography footer
             **/
            $this->sections[] = array(
                'id'            => 'typography_footer',
                'title'         => esc_html__( 'Footer', 'delphinus' ),
                'desc'          => '',
                'subsection'    => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_footer_top_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer top settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_top',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer top', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the footer top font properties.', 'delphinus' ),
                        'google'   => true,
                        'text-align'      => false,
                        'output'      => array( '#footer-top' ),
                        'default'  => array(
                            'color'       => '',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer widgets settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_widgets',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer widgets', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the footer widgets font properties.', 'delphinus' ),
                        'google'   => true,
                        'text-align'      => false,
                        'output'      => array( '#footer-area' ),
                        'default'  => array(
                            'color'       => '#999999',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_title',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer widgets title', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the footer widgets title font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-align'      => true,
                        'text-transform' => true,
                        'output'      => array( '#footer-area h3.widget-title' ),
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_link',
                        'type'     => 'link_color',
                        'title'    => esc_html__( 'Footer widgets Links Color', 'delphinus' ),
                        'output'      => array( '#footer-area a' ),
                        'default'  => array(
                            'regular' => '#666666',
                            'hover'   => '#000000',
                            'active'  => '#000000'
                        )
                    ),

                    array(
                        'id'       => 'typography_footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer Bottom settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_bottom_link',
                        'type'     => 'link_color',
                        'title'    => esc_html__( 'Footer Bottom Links Color', 'delphinus' ),
                        'output'      => array( '#footer-bottom a', '#footer-bottom button' ),
                        'default'  => array(
                            'regular' => '#666666',
                            'hover'   => '#000000',
                            'active'  => '#000000'
                        )
                    ),
                    array(
                        'id'       => 'typography_footer_bottom',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer Bottom', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the footer font properties.', 'delphinus' ),
                        'text-align'      => false,
                        'output'      => array( '#footer-bottom' ),
                        'default'  => array(
                            'color'       => '#999999',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),

                    array(
                        'id'       => 'typography_footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer copyright settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_copyright_link',
                        'type'     => 'link_color',
                        'title'    => esc_html__( 'Footer Copyright Links Color', 'delphinus' ),
                        'output'      => array( '#footer-copyright a' ),
                        'default'  => array(
                            'regular' => '#999999',
                            'hover'   => '#ffffff',
                            'active'  => '#ffffff'
                        )
                    ),
                    array(
                        'id'       => 'typography_footer_copyright',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer copyright', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the footer font properties.', 'delphinus' ),
                        'text-align'      => false,
                        'output'      => array( '#footer-copyright' ),
                        'default'  => array(
                            'color'       => '',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),
                    array(
                        'id'       => 'typography_footer_copyright_space',
                        'type'     => 'raw',
                        'content'  => '<div style="height: 120px;"></div>',
                        'full_width' => true
                    ),

                )
            );



            /**
             *  Typography sidebar
             **/
            $this->sections[] = array(
                'id'            => 'typography_sidebar',
                'title'         => esc_html__( 'Sidebar', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_sidebar',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Sidebar title', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the sidebar title font properties.', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'output'      => array(
                            '.side-bar .widget .widget-title',
                            '.wpb_widgetised_column .widget .widget-title'
                        ),
                        'default'  => array(
                            'text-transform' => 'uppercase',
                        ),
                    ),
                    array(
                        'id'       => 'typography_sidebar_content',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Sidebar text', 'delphinus' ),
                        'subtitle' => esc_html__( 'Specify the sidebar title font properties.', 'delphinus' ),
                        'text-algin' => true,
                        'output'      => array( '.side-bar', '.wpb_widgetised_column' ),
                        'default'  => array(

                        ),
                    ),
                )
            );

            /**
             *  Typography Navigation
             **/

            $this->sections[] = array(
                'id'            => 'typography_navigation',
                'title'         => esc_html__( 'Main Navigation', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography-navigation_top',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Top Menu Level', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '#nav #main-navigation > li > a',
                            '#nav #main-nav-wc > li > a'
                        )
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'typography_navigation_dropdown',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Dropdown menu', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_navigation_second',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Second Menu Level', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '#nav #main-navigation > li ul.sub-menu-dropdown li a'
                        ),
                        'default'  => array(

                        ),
                    ),
                    array(
                        'id'       => 'typography_navigation_mega',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Mega menu', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_navigation_heading',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading title', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper > ul > li > a',
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper > ul > li > span',
                            '#nav #main-navigation > li .delphinus-megamenu-wrapper > ul > li .widget-title'
                        ),
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_navigation_mega_link',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Mega menu', 'delphinus' ),
                        'google'   => true,
                        'text-align'      => false,
                        'color'           => false,
                        'text-transform' => true,
                        'line-height'     => false,
                        'output'      => array(
                            '#nav #main-navigation > li > .delphinus-megamenu-wrapper > .delphinus-megamenu-ul > li ul.sub-menu-megamenu a'
                        ),
                        'default'  => array( ),
                    )

                )
            );

            /**
             *  Typography mobile Navigation
             **/

            $this->sections[] = array(
                'id'            => 'typography_mobile_navigation',
                'title'         => esc_html__( 'Mobile Navigation', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_mobile_navigation_top',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Top Menu Level', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array( '.main-nav-mobile > ul > li > a' ),
                        'default'  => array(
                            'text-transform' => 'uppercase',
                        ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'typography_mobile_navigation_second',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Sub Menu Level', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '.main-nav-mobile > ul > li ul.sub-menu-dropdown li a',
                            '.main-nav-mobile > ul > li ul.sub-menu-megamenu li a'
                        ),
                    ),
                    array(
                        'id'       => 'typography_mobile_navigation_heading',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading title', 'delphinus' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '.main-nav-mobile > ul > li div.delphinus-megamenu-wrapper > ul > li > a',
                            '.main-nav-mobile > ul > li div.delphinus-megamenu-wrapper > ul > li > span',
                            '.main-nav-mobile > ul > li div.delphinus-megamenu-wrapper > ul > li .widget-title'
                        ),
                        'default'  => array(
                            'text-transform' => 'uppercase',
                            'font-weight'  => '700'
                        ),
                    ),
                )
            );

            /**
             *	Woocommerce
             **/
            $this->sections[] = array(
                'id'			=> 'woocommerce',
                'title'			=> esc_html__( 'Woocommerce', 'delphinus' ),
                'desc'			=> '',
                'icon'	=> 'fa fa-cart-arrow-down',
                'fields'		=> array(
                    //Slider effect: Lightbox - Zoom
                    //Product description position - Tab, Below
                    //Product reviews position - Tab,Below
                    //Social Media Sharing Buttons
                    //Single Product Gallery Type

                    array(
                        'id'       => 'shop_single_product',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Shop settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    
                    array(
                        'id' => 'catalog_mode',
                        'type' => 'switch',
                        'title' => esc_html__('Catalog Mode', 'delphinus'),
                        'desc' => esc_html__('When enabled, the feature Turns Off the shopping functionality of WooCommerce.', 'delphinus'),
                        "default" => 0,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    
                    array(
                        'id'       => 'time_product_new',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Time Product New', 'delphinus' ),
                        'default'  => '30',
                        'desc' => esc_html__('Time Product New ( unit: days ).', 'delphinus'),
                    ),
                    
                    
                    array(
                        'id'       => 'shop_products_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Shop Products settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'shop_page_header',
                        'type' => 'switch',
                        'title' => esc_html__('Show Page header', 'delphinus'),
                        'desc' => esc_html__('Show page header or?.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    
                    array(
                        'id' => 'loop_shop_quickview',
                        'type' => 'switch',
                        'title' => esc_html__('Quick View', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    
                    array(
                        'id'       => 'shop_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop: Sidebar configuration', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose sidebar for shop post", 'delphinus' ),
                        'options'  => array(
                            'full' => esc_html__('No sidebars', 'delphinus'),
                            'left' => esc_html__('Left Sidebar', 'delphinus'),
                            'right' => esc_html__('Right Layout', 'delphinus')
                        ),
                        'default'  => 'left',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'shop_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Shop: Sidebar left area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('shop_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'shop_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop: Sidebar right area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('shop_sidebar','equals','right'),
                        'clear' => false
                    ),

                    array(
                        'id'       => 'shop_products_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop: Products default Layout', 'delphinus' ),
                        'options'  => array(
                            'grid' => esc_html__('Grid', 'delphinus' ),
                            'lists' => esc_html__('Lists', 'delphinus' )
                        ),
                        'default'  => 'grid'
                    ),
                    
                    array(
                        'id'       => 'shop_gird_cols',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Number column to display width gird mod', 'delphinus' ),
                        'options'  => array(
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                        ),
                        'default'  => 3,
                    ),
                    /*
                    array(
                        'id'       => 'shop_products_effect',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop product effect', 'delphinus' ),
                        'options'  => array(
                            '1' => esc_html__('Effect 1', 'delphinus' ),
                            '2' => esc_html__('Effect 2', 'delphinus' ),
                            '3' => esc_html__('Effect 3', 'delphinus' )
                        ),
                        'default'  => '1'
                    ),
                    */
                    array(
                        'id'       => 'loop_shop_per_page',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Number of products displayed per page', 'delphinus' ),
                        'default'  => '12'
                    ),


                    // For Shop header
                    array(
                        'id'       => 'shop_header_products',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Shop Header Settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'shop_header_tool_bar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop Header', 'delphinus' ),
                        'desc'     => esc_html__('Select type shop header.', 'delphinus'),
                        'options'  => array(
                            '0' => esc_html__('Disable', 'delphinus' ),
                            '1' => esc_html__('Default', 'delphinus' ),
                            '2' => esc_html__('Categories', 'delphinus' )
                        ),
                        'default'  => '1'
                    ),
                    array(
                        'id'       => 'shop_header_orderby',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Categories - Order by', 'delphinus' ),
                        'desc'     => esc_html__('The column to use for ordering categories', 'delphinus'),
                        'options'  => array(
                            'id' => esc_html__('ID', 'delphinus' ),
                            'name' => esc_html__('Name/Menu-order', 'delphinus' ),
                            'slug' => esc_html__('Slug', 'delphinus' ),
                            'count' => esc_html__('Count', 'delphinus' ),
                            'term_group' => esc_html__('Term Group', 'delphinus' ),
                        ),
                        'default'  => 'slug',
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),

                    array(
                        'id'       => 'shop_header_order',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Categories - Order', 'delphinus' ),
                        'desc'     => esc_html__('Which direction to order categories', 'delphinus'),
                        'options'  => array(
                            'ASC' => esc_html__('ASC', 'delphinus' ),
                            'DESC' => esc_html__('DESC', 'delphinus' ),
                        ),
                        'default'  => 'ASC',
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),

                    array(
                        'id' => 'shop_header_filters',
                        'type' => 'switch',
                        'title' => esc_html__('Show Filters', 'delphinus'),
                        'desc' => esc_html__('Display filters in the shop header.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus'),
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),
                    array(
                        'id' => 'shop_header_search',
                        'type' => 'switch',
                        'title' => esc_html__('Show Search', 'delphinus'),
                        'desc' => esc_html__('Display search box in the shop header.', 'delphinus'),
                        "default" => 1,
                        'on' =>  esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),
                    array(
                        'id' => 'shop_header_ajax',
                        'type' => 'switch',
                        'title' => esc_html__('Ajax for fillter', 'delphinus'),
                        'desc' => esc_html__('Enable ajax when use filter.', 'delphinus'),
                        "default" => 1,
                        'on' =>  esc_html__('Enabled', 'delphinus'),
                        'off' => esc_html__('Disabled', 'delphinus'),
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),

                    // For Single Products
                    array(
                        'id'       => 'shop_single_product',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Single Product settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'product_detail_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Product layout', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose single product layout.", 'delphinus' ),
                        'options'  => array(
                            'layout1' => esc_html__('Layout 1', 'delphinus'),
                            'layout2' => esc_html__('Layout 2', 'delphinus'),
                            'layout3' => esc_html__('Layout 3', 'delphinus'),
                        ),
                        'default'  => 'layout1',
                    ),

                    
                )
            );

            /**
             *	Page header
             **/
            $this->sections[] = array(
                'id'			=> 'page_header_section',
                'title'			=> esc_html__( 'Page header', 'delphinus' ),
                'desc'			=> '',
                'icon'          => 'fa fa-header',
                'fields'		=> array(

                    array(
                        'id'       => 'title_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Page header settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'title_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Page header layout', 'delphinus' ),
                        'subtitle'     => esc_html__( 'Select your preferred Page header layout.', 'delphinus' ),
                        'options'  => array(
                            'sides' => esc_html__('Sides', 'delphinus'),
                            'centered' => esc_html__('Centered', 'delphinus' ),
                        ),
                        'default'  => 'centered'
                    ),
                    array(
                        'id'       => 'title_align',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Page header align', 'delphinus' ),
                        'subtitle'     => esc_html__( 'Please select page header align', 'delphinus' ),
                        'options'  => array(
                            'left' => esc_html__('Left', 'delphinus' ),
                            'center' => esc_html__('Center', 'delphinus'),
                            'right' => esc_html__('Right', 'delphinus')
                        ),
                        'default'  => 'center',
                        'desc' => esc_html__("Align Center don't support for layout Sides", 'delphinus')
                    ),

                    array(
                        'id'       => 'title_breadcrumbs',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Show breadcrumbs', 'delphinus' ),
                        'default'  => true,
                        'on'		=> esc_html__( 'Enabled', 'delphinus' ),
                        'off'		=> esc_html__( 'Disabled', 'delphinus' ),
                    ),
                    array(
                        'id'       => 'title_breadcrumbs_mobile',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Breadcrumbs on Mobile Devices', 'delphinus' ),
                        'default'  => false,
                        'on'		=> esc_html__( 'Enabled', 'delphinus' ),
                        'off'		=> esc_html__( 'Disabled', 'delphinus' ),
                    ),
                    array(
                        'id'       => 'title_style_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Page header style settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'title_padding',
                        'type'     => 'spacing',
                        'mode'     => 'padding',
                        'left'     => false,
                        'right'    => false,
                        'output'   => array( '.page-header' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Title padding', 'delphinus' ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'title_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Background', 'delphinus' ),
                        'subtitle' => esc_html__( 'Page header with image, color, etc.', 'delphinus' ),
                        'output'      => array( '.page-header' )
                    ),

                    array(
                        'id'       => 'title_typography_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Page header typography settings', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'title_typography',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Typography title', 'delphinus' ),
                        'google'   => true,
                        'text-align'      => false,
                        'line-height'     => false,
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'output'      => array( '.page-header .page-header-title' ),
                    ),
                    array(
                        'id'       => 'title_typography_subtitle',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Typography sub title', 'delphinus' ),
                        'google'   => true,
                        'text-align'      => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array( '.page-header .page-header-subtitle' )
                    ),
                    array(
                        'id'       => 'title_typography_breadcrumbs',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Typography breadcrumbs', 'delphinus' ),
                        'google'   => true,
                        'text-align'      => false,
                        'line-height'     => false,
                        'output'      => array( '.page-header .woocommerce-breadcrumb' )
                    ),
                )
            );

            /**
             * General page
             *
             */
            $this->sections[] = array(
                'title' => esc_html__('Page', 'delphinus'),
                'desc' => esc_html__('General Page Options', 'delphinus'),
                'icon' => 'fa fa-suitcase',
                'fields' => array(
                    array(
                        'id' => 'show_page_header',
                        'type' => 'switch',
                        'title' => esc_html__('Show Page header', 'delphinus'),
                        'desc' => esc_html__('Show page header or?.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    array(
                        'id'       => 'page_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar configuration', 'delphinus' ),
                        'options'  => array(
                            '' => esc_html__('No sidebars', 'delphinus'),
                            'left' => esc_html__('Left Sidebar', 'delphinus'),
                            'right' => esc_html__('Right Layout', 'delphinus')
                        ),
                        'default'  => '',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'page_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Sidebar left area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose default layout", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('page_sidebar','equals','left')
                        //'clear' => false
                    ),

                    array(
                        'id'       => 'page_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar right area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose page layout", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('page_sidebar','equals','right')
                        //'clear' => false
                    ),
                    array(
                        'id' => 'show_page_comment',
                        'type' => 'switch',
                        'title' => esc_html__('Show comments on page ?', 'delphinus'),
                        'desc' => esc_html__('Show or hide the readmore button.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                )
            );
            /**
             * General Blog
             *
             */
            $this->sections[] = array(
                'title' => esc_html__('Blog', 'delphinus'),
                'icon' => 'fa fa-pencil',
                'desc' => esc_html__('General Blog Options', 'delphinus')
            );

            /**
             *  Archive settings
             **/
            $this->sections[] = array(
                'id'            => 'archive_section',
                'title'         => esc_html__( 'Archive', 'delphinus' ),
                'desc'          => 'Archive post settings',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'archive_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Archive post general', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'archive_page_header',
                        'type' => 'switch',
                        'title' => esc_html__('Show Page header', 'delphinus'),
                        'desc' => esc_html__('Show page header or?.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    array(
                        'id'       => 'archive_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar configuration', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose archive page ", 'delphinus' ),
                        'options'  => array(

                            'left' => esc_html__('Left Sidebar', 'delphinus'),
                            'right' => esc_html__('Right Layout', 'delphinus')
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'archive_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Sidebar left area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('archive_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'archive_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar right area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('archive_sidebar','equals','right'),
                        'clear' => false
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'archive_loop_style',
                        'type' => 'select',
                        'title' => esc_html__('Loop Style', 'delphinus'),
                        'desc' => '',
                        'options' => array(
                            'classic' => esc_html__( 'Classic', 'delphinus' ),
                            'list' => esc_html__( 'List', 'delphinus' ),
                            'grid' => esc_html__( 'Grid', 'delphinus' ),
                            'masonry' => esc_html__( 'Masonry', 'delphinus' ),
                        ),
                        'default' => 'grid'
                    ),
                    array(
                        'id' => 'archive_columns',
                        'type' => 'select',
                        'title' => esc_html__('Columns on desktop', 'delphinus'),
                        'desc' => '',
                        'options' => array(
                            '2' => esc_html__( '2 columns', 'js_composer' ) ,
                            '3' => esc_html__( '3 columns', 'js_composer' ) ,
                            '4' => esc_html__( '4 columns', 'js_composer' ) ,
                        ),
                        'default' => '2',
                        'required' => array('archive_loop_style','equals', array( 'grid', 'masonry' ) ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'archive_posts_per_page',
                        'type' => 'text',
                        'title' => esc_html__('Posts per page', 'delphinus'),
                        'desc' => esc_html__("Insert the total number of pages.", 'delphinus'),
                        'default' => 14,
                    ),
                    array(
                        'id' => 'archive_excerpt_length',
                        'type' => 'text',
                        'title' => esc_html__('Excerpt Length', 'delphinus'),
                        'desc' => esc_html__("Insert the number of words you want to show in the post excerpts.", 'delphinus'),
                        'default' => 35,
                    ),

                    array(
                        'id' => 'archive_readmore',
                        'type' => 'select',
                        'title' => esc_html__('Readmore button', 'delphinus'),
                        'desc' => '',
                        'options' => array(
                            'none' => esc_html__( 'None', 'js_composer' ) ,
                            'link' => esc_html__( 'Link', 'js_composer' ) ,
                        ),
                        'default' => 'none',
                    ),

                    array(
                        'id' => 'archive_pagination',
                        'type' => 'select',
                        'title' => esc_html__('Pagination Type', 'delphinus'),
                        'desc' => esc_html__('Select the pagination type.', 'delphinus'),
                        'options' => array(
                            'normal' => esc_html__( 'Normal pagination', 'delphinus' ),
                            'button' => esc_html__( 'Next - Previous button', 'delphinus' ),
                        ),
                        'default' => 'normal'
                    ),
                )
            );

            /**
             *  Single post settings
             **/
            $this->sections[] = array(
                'id'            => 'post_single_section',
                'title'         => esc_html__( 'Single Post', 'delphinus' ),
                'desc'          => 'Single post settings',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'blog_single_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Single post general', 'delphinus' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'single_page_header',
                        'type' => 'text',
                        'title' => esc_html__('Custom Page header', 'delphinus'),
                        'subtitle' => esc_html__("Empty if you want use post title", 'delphinus'),
                        'default' => esc_html__('Welcome to our blog', 'delphinus')
                    ),
                    array(
                        'id'       => 'single_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar configuration', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose sidebar for single post", 'delphinus' ),
                        'options'  => array(
                            '' => esc_html__('No sidebars', 'delphinus'),
                            'left' => esc_html__('Left Sidebar', 'delphinus'),
                            'right' => esc_html__('Right Layout', 'delphinus')
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'single_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Single post: Sidebar left area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('single_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'single_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Single post: Sidebar right area', 'delphinus' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'delphinus' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('single_sidebar','equals','right'),
                        'clear' => false
                    ),
                    array(
                        'id'   => 'single_image_size',
                        'type' => 'select',
                        'options' => $image_sizes,
                        'title'    => esc_html__( 'Image size', 'delphinus' ),
                        'desc' => esc_html__("Select image size.", 'delphinus'),
                        'default' => 'full'
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'single_share_box',
                        'type' => 'switch',
                        'title' => esc_html__('Share box in posts', 'delphinus'),
                        'desc' => esc_html__('Show share box in blog posts.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    array(
                        'id' => 'single_next_prev',
                        'type' => 'switch',
                        'title' => esc_html__('Previous & next buttons', 'delphinus'),
                        'desc' => esc_html__('Show Previous & next buttons in blog posts.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    array(
                        'id' => 'single_author',
                        'type' => 'switch',
                        'title' => esc_html__('Author info in posts', 'delphinus'),
                        'desc' => esc_html__('Show author info in blog posts.', 'delphinus'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    array(
                        'id' => 'single_related',
                        'type' => 'switch',
                        'title' => esc_html__('Related posts', 'delphinus'),
                        'desc' => esc_html__('Show related posts in blog posts.', 'delphinus'),
                        "default" => 0,
                        'on' => esc_html__('Enabled', 'delphinus'),
                        'off' =>esc_html__('Disabled', 'delphinus')
                    ),
                    array(
                        'id'       => 'single_related_type',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Related Query Type', 'delphinus' ),
                        'options'  => array(
                            'categories' => esc_html__('Categories', 'delphinus'),
                            'tags' => esc_html__('Tags', 'delphinus'),
                            'author' => esc_html__('Author', 'delphinus')
                        ),
                        'required' => array('single_related','equals','1'),
                        'default'  => 'categories',
                    )
                )
            );



            /**
             *	Socials Link
             **/

            $this->sections[] = array(
                'id'			=> 'social',
                'title'			=> esc_html__( 'Socials', 'delphinus' ),
                'desc'			=> esc_html__('Social and share settings', 'delphinus'),
                'icon'	        => 'fa fa-facebook',
                'fields'		=> array(

                    array(
                        'id' => 'twitter',
                        'type' => 'text',
                        'title' => esc_html__('Twitter', 'delphinus'),
                        'subtitle' => esc_html__("Your Twitter username (no @).", 'delphinus'),
                        'default' => '#'
                    ),
                    array(
                        'id' => 'facebook',
                        'type' => 'text',
                        'title' => esc_html__('Facebook', 'delphinus'),
                        'subtitle' => esc_html__("Your Facebook page/profile url", 'delphinus'),
                        'default' => '#'
                    ),
                    array(
                        'id' => 'pinterest',
                        'type' => 'text',
                        'title' => esc_html__('Pinterest', 'delphinus'),
                        'subtitle' => esc_html__("Your Pinterest username", 'delphinus'),
                        'default' => '#'
                    ),
                    array(
                        'id' => 'dribbble',
                        'type' => 'text',
                        'title' => esc_html__('Dribbble', 'delphinus'),
                        'subtitle' => esc_html__("Your Dribbble username", 'delphinus'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'vimeo',
                        'type' => 'text',
                        'title' => esc_html__('Vimeo', 'delphinus'),
                        'subtitle' => esc_html__("Your Vimeo username", 'delphinus'),
                        'desc' => '',
                        'default' => '#'
                    ),
                    array(
                        'id' => 'tumblr',
                        'type' => 'text',
                        'title' => esc_html__('Tumblr', 'delphinus'),
                        'subtitle' => esc_html__("Your Tumblr username", 'delphinus'),
                        'desc' => '',
                        'default' => '#'
                    ),
                    array(
                        'id' => 'skype',
                        'type' => 'text',
                        'title' => esc_html__('Skype', 'delphinus'),
                        'subtitle' => esc_html__("Your Skype username", 'delphinus'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'linkedin',
                        'type' => 'text',
                        'title' => esc_html__('LinkedIn', 'delphinus'),
                        'subtitle' => esc_html__("Your LinkedIn page/profile url", 'delphinus'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'googleplus',
                        'type' => 'text',
                        'title' => esc_html__('Google+', 'delphinus'),
                        'subtitle' => esc_html__("Your Google+ page/profile URL", 'delphinus'),
                        'desc' => '',
                        'default' => '#'
                    ),
                    array(
                        'id' => 'youtube',
                        'type' => 'text',
                        'title' => esc_html__('YouTube', 'delphinus'),
                        'subtitle' => esc_html__("Your YouTube username", 'delphinus'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'instagram',
                        'type' => 'text',
                        'title' => esc_html__('Instagram', 'delphinus'),
                        'subtitle' => esc_html__("Your Instagram username", 'delphinus'),
                        'desc' => '',
                        'default' => ''
                    )
                )
            );

            /**
             *	Popup
             **/
            $this->sections[] = array(
                'id'			=> 'popup',
                'title'			=> esc_html__( 'Popup', 'delphinus' ),
                'icon'	=> 'fa fa-bullhorn',
                'fields'		=> array(
                    array(
                        'id'		=> 'enable_popup',
                        'type'		=> 'switch',
                        'title'		=> esc_html__( 'Enable Popup', 'delphinus' ),
                        'subtitle'	=> esc_html__( '', 'delphinus'),
                        "default"	=> false,
                        'on'		=> esc_html__( 'On', 'delphinus' ),
                        'off'		=> esc_html__( 'Off', 'delphinus' ),
                    ),
                    array(
                        'id'            => 'time_show',
                        'type'          => 'slider',
                        'title'         => esc_html__( 'Time to show', 'delphinus' ),
                        'desc'          => esc_html__( 'Delay time for show. (seconds)', 'delphinus' ),
                        'default'       => 0,
                        'min'           => 0,
                        'step'          => 1,
                        'max'           => 20,
                        'display_value' => 'text',
                        'required'      => array('enable_popup','equals', 1)
                    ),
                )
            );
            
            /**
             *	Subscribe
             **/
            $this->sections[] = array(
                'id'			=> 'subscribe',
                'title'			=> esc_html__( 'Subscribe', 'delphinus' ),
                'icon'	=> 'fa fa-envelope-o',
                'fields'		=> array(
                    array(
                        'id'       => 'popup_image',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Popup Image', 'delphinus' ),
                        'required' => array('enable_popup','equals', 1)
                    ),
                    array(
                        'id'       => 'content_popup',
                        'type'     => 'editor',
                        'title'    => esc_html__( 'Popup Content', 'delphinus' ),
                        'subtitle' => esc_html__( '', 'delphinus' ),
                        'required' => array('enable_popup','equals', 1),
                        'default'  => '<h3>NEWSLETTER</h3><p>Subscribe to the Universal mailing list to receive updates on new arrivals, offers and other discount information.</p>',
                    ),
                    array(
                        'id'       => 'popup_form',
                        'type'     => 'textarea',
                        'title'    => esc_html__( 'Popup form', 'delphinus' ),
                        'desc'     => esc_html__( 'You can use shortcode or Embed code in here.', 'delphinus' ),
                        'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                    ),
                )
            );
            
            /**
             *  Advanced
             **/
            $this->sections[] = array(
                'id'            => 'advanced',
                'title'         => esc_html__( 'Advanced', 'delphinus' ),
                'desc'          => '',
                'icon'  => 'fa fa-cog',
            );

            /**
             *  Advanced Social Share
             **/
            $this->sections[] = array(
                'id'            => 'share_section',
                'title'         => esc_html__( 'Social Share', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'social_share',
                        'type'     => 'sortable',
                        'mode'     => 'checkbox', // checkbox or text
                        'title'    => esc_html__( 'Social Share', 'delphinus' ),
                        'desc'     => esc_html__( 'Reorder and Enable/Disable Social Share Buttons.', 'delphinus' ),
                        'options'  => array(
                            'facebook' => esc_html__('Facebook', 'delphinus'),
                            'twitter' => esc_html__('Twitter', 'delphinus'),
                            'google_plus' => esc_html__('Google+', 'delphinus'),
                            'pinterest' => esc_html__('Pinterest', 'delphinus'),
                            'linkedin' => esc_html__('Linkedin', 'delphinus'),
                            'tumblr' => esc_html__('Tumblr', 'delphinus'),
                            'mail' => esc_html__('Mail', 'delphinus'),
                        ),
                        'default'  => array(
                            'facebook' => true,
                            'twitter' => true,
                            'google_plus' => false,
                            'pinterest' => true,
                            'linkedin' => false,
                            'tumblr' => false,
                            'mail' => false,
                        )
                    )
                )
            );

            /**
             *  Advanced Custom CSS
             **/
            $this->sections[] = array(
                'id'            => 'advanced_css',
                'title'         => esc_html__( 'Custom CSS', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'advanced_editor_css',
                        'type'     => 'ace_editor',
                        'title'    => esc_html__( 'CSS Code', 'delphinus' ),
                        'subtitle' => esc_html__( 'Paste your CSS code here.', 'delphinus' ),
                        'mode'     => 'css',
                        'theme'    => 'chrome',
                        'full_width' => true
                    ),
                )
            );


            /**
             *  Advanced Custom CSS
             **/
            $this->sections[] = array(
                'id'            => 'advanced_js',
                'title'         => esc_html__( 'Custom JS', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'advanced_editor_js',
                        'type'     => 'ace_editor',
                        'title'    => esc_html__( 'JS Code', 'delphinus' ),
                        'subtitle' => esc_html__( 'Paste your JS code here.', 'delphinus' ),
                        'mode'     => 'javascript',
                        'theme'    => 'chrome',
                        'default'  => "jQuery(document).ready(function(){\n\n});",
                        'full_width' => true
                    ),
                )
            );

            /**
             *  Advanced Tracking Code
             **/
            $this->sections[] = array(
                'id'            => 'advanced_tracking',
                'title'         => esc_html__( 'Tracking Code', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'advanced_tracking_code',
                        'type'     => 'textarea',
                        'title'    => esc_html__( 'Tracking Code', 'delphinus' ),
                        'desc'     => esc_html__( 'Paste your Google Analytics (or other) tracking code here. This will be added into the header template of your theme. Please put code inside script tags.', 'delphinus' ),
                    )
                )
            );

            $info_arr = array();
            $theme = wp_get_theme();

            $info_arr[] = "<li><span>".esc_html__('Theme Name:', 'delphinus')." </span>". $theme->get('Name').'</li>';
            $info_arr[] = "<li><span>".esc_html__('Theme Version:', 'delphinus')." </span>". $theme->get('Version').'</li>';
            $info_arr[] = "<li><span>".esc_html__('Theme URI:', 'delphinus')." </span>". $theme->get('ThemeURI').'</li>';
            $info_arr[] = "<li><span>".esc_html__('Author:', 'delphinus')." </span>". $theme->get('Author').'</li>';

            $system_info = sprintf("<div class='troubleshooting'><ul>%s</ul></div>", implode('', $info_arr));

            /**
             *  Advanced Troubleshooting
             **/
            $this->sections[] = array(
                'id'            => 'advanced_troubleshooting',
                'title'         => esc_html__( 'Troubleshooting', 'delphinus' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'opt-raw_info_4',
                        'type'     => 'raw',
                        'content'  => $system_info,
                        'full_width' => true
                    ),
                )
            );


        }
    }

    global $reduxConfig;
    $reduxConfig = new DELPHINUS_config();

} else {
    echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
}

