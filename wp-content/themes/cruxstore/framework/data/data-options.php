<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


if ( ! class_exists( 'CRUXSTORE_config' ) ) {

    class CRUXSTORE_config
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
                'opt_name' => CRUXSTORE_THEME_OPTIONS,
                // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $this->theme->get('Name'),
                // Name that appears at the top of your panel
                'display_version' => $this->theme->get('Version'),
                // Version that appears at the top of your panel
                'menu_type' => 'menu',
                //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => false,
                // Show the sections below the admin menu item or not
                'menu_title' => esc_html__('Theme Options', 'cruxstore'),

                'page_title' => $this->theme->get('Name') . ' ' . esc_html__('Theme Options', 'cruxstore'),
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
                'footer_credit'     => esc_html__('If you like CruxStore please leave us a &#9734;&#9734;&#9734;&#9734;&#9734; rating. A huge thank you from KiteThemes in advance!', 'cruxstore'),

                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '',
                // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info' => true,
                // REMOVE
            );

        }

        public function setSections()
        {

            $image_sizes = cruxstore_get_image_sizes();


            $taxonomy_names = array();
            if(cruxstore_is_wc()){

                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if ( ! empty( $attribute_taxonomies ) ) {
                    foreach ( $attribute_taxonomies as $tax ) {
                        $taxonomy_names[wc_attribute_taxonomy_name( $tax->attribute_name )] = $tax->attribute_label;
                    }
                }
            }

            $all_categories = array();
            if(cruxstore_is_wc()){
                $args = array('taxonomy' => 'product_cat' );
                $categories = get_categories( $args );
                foreach($categories as $category){
                    $all_categories[$category->term_id] = $category->name;
                }
            }

            $this->sections[] = array(
                'id'    => 'general',
                'title'  => esc_html__( 'General', 'cruxstore' ),
                'icon'  => 'fa fa-cogs'
            );

            $this->sections[] = array(
                'id'    => 'general_layout',
                'title'  => esc_html__( 'General', 'cruxstore' ),
                'subsection' => true,
                'fields' => array(
                    /*array(
                        'id'       => 'layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Site boxed mod(?)', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose page layout", 'cruxstore' ),
                        'options'  => array(
                            'full' => esc_html__('Full width Layout', 'cruxstore'),
                            'boxed' => esc_html__('Boxed Layout', 'cruxstore'),
                        ),
                        'default'  => 'full',
                        'clear' => false
                    ),*/

                    array(
                        'id' => 'use_page_loader',
                        'type' => 'switch',
                        'title' => esc_html__('Use Page Loader?', 'cruxstore'),
                        'desc' => esc_html__('', 'cruxstore'),
                        'default' => 0,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),

                    array(
                        'id'       => 'archive_placeholder',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Placeholder', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Placeholder for none image", 'cruxstore' ),
                    ),

                    array(
                        'id' => 'notfound_page_type',
                        'type' => 'select',
                        'title' => esc_html__('404 Page', 'cruxstore'),
                        'desc' => '',
                        'options' => array(
                            'default' => esc_html__( 'Default', 'cruxstore' ) ,
                            'home' => esc_html__( 'Redirect Home', 'cruxstore' ) ,
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
                'title'         => esc_html__( 'Logos', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'logos_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Logos settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'logo',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo', 'cruxstore' ),
                    ),
                    array(
                        'id'       => 'logo_retina',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo (Retina Version @2x)', 'cruxstore' ),
                        'desc'     => esc_html__('Select an image file for the retina version of the logo. It should be exactly 2x the size of main logo.', 'cruxstore')
                    ),
                    array(
                        'id'       => 'logo_light',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo light', 'cruxstore' ),
                    ),
                    array(
                        'id'       => 'logo_light_retina',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo light(Retina Version @2x)', 'cruxstore' ),
                        'desc'     => esc_html__('Select an image file for the retina version of the logo. It should be exactly 2x the size of main logo.', 'cruxstore')
                    ),
                    
                    array(
                        'id'       => 'logo_footer',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Logo Footer', 'cruxstore' ),
                    ),

                )
            );



            /**
             *  Header
             **/
            $this->sections[] = array(
                'id'            => 'Header',
                'title'         => esc_html__( 'Header', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'header',
                        'type'     => 'image_select',
                        'compiler' => true,
                        'title'    => esc_html__( 'Header layout', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Please choose header layout', 'cruxstore' ),
                        'options'  => array(
                            1 => array( 'alt' => esc_html__( 'Layout 1', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v1.jpg' ),
                            2 => array( 'alt' => esc_html__( 'Layout 2', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v2.jpg' ),
                            3 => array( 'alt' => esc_html__( 'Layout 3', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v3.jpg' ),
                            4 => array( 'alt' => esc_html__( 'Layout 4', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v4.jpg' ),
                            5 => array( 'alt' => esc_html__( 'Layout 5', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v5.jpg' ),
                            6 => array( 'alt' => esc_html__( 'Layout 6', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v6.jpg' ),
                            7 => array( 'alt' => esc_html__( 'Layout 7', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v7.jpg' ),
                            8 => array( 'alt' => esc_html__( 'Layout 8', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v8.jpg' ),
                            9 => array( 'alt' => esc_html__( 'Layout 9', 'cruxstore' ), 'img' => CRUXSTORE_FW_IMG . 'header/header-v8.jpg' ),
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
                        'title' => esc_html__('Header shadow', 'cruxstore'),
                        "default" => 1,
                        'on'        => esc_html__( 'Enabled', 'cruxstore' ),
                        'off'       => esc_html__( 'Disabled', 'cruxstore' ),
                    ),
                   array(
                        'id'            => 'header_show_menu',
                        'type'          => 'slider',
                        'title'         => esc_html__( 'Number show Menus vertical', 'cruxstore' ),
                        'default'       => 8,
                        'min'           => 0,
                        'step'          => 1,
                        'max'           => 30,
                        'resolution'    => 1,
                        'display_value' => 'text'
                    ),
                    array(
                        'id' => 'header_search',
                        'type' => 'switch',
                        'title' => esc_html__('Search Icon', 'cruxstore'),
                        'desc' => esc_html__('Enable the search Icon in the header.', 'cruxstore'),
                        "default" => 1,
                        'on'        => esc_html__( 'Enabled', 'cruxstore' ),
                        'off'       => esc_html__( 'Disabled', 'cruxstore' ),
                    ),
                    array(
                        'id'       => 'header_search_type',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Header search type', 'cruxstore' ),
                        'options'  => array(
                            'all' => esc_html__('All', 'cruxstore'),
                            'product' => esc_html__('Only Products', 'cruxstore' )
                        ),
                        'default'  => 'all'
                    ),
                    array(
                        'id' => 'header_phone',
                        'type' => 'text',
                        'title' => esc_html__('Phone', 'cruxstore'),
                        'subtitle' => esc_html__("Your phone number.", 'cruxstore'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'header_text',
                        'type' => 'text',
                        'title' => esc_html__('Text', 'cruxstore'),
                        'subtitle' => esc_html__("Your text near logo.", 'cruxstore'),
                        'default' => ''
                    ),

                    array(
                        'id' => 'topbar_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Topbar settings', 'cruxstore') . '</div>',
                        'full_width' => true
                    ),

                    array(
                        'id' => 'top_bar',
                        'type' => 'switch',
                        'title' => esc_html__('Top bar enable', 'cruxstore'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),
                    array(
                        'id'       => 'top_bar_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Top bar layout', 'cruxstore' ),
                        'subtitle'     => esc_html__( 'Select your preferred footer layout.', 'cruxstore' ),
                        'options'  => array(
                            'centered' => esc_html__('Centered', 'cruxstore'),
                            'sides' => esc_html__('Sides', 'cruxstore' ),
                        ),
                        'default'  => 'sides',
                    ),
                    array(
                        'id'       => 'top_bar_left',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Top bar Left', 'cruxstore' ),
                        'options'  => array(
                            'navigation' => esc_html__('Navigation', 'cruxstore' ),
                            'socials' => esc_html__('Socials', 'cruxstore' ),
                            'subscribe' => esc_html__('Subscribe', 'cruxstore' ),
                            'phone' => esc_html__('Phone', 'cruxstore' ),
                            'currency' => esc_html__('Currency', 'cruxstore' ),
                            'language' => esc_html__('Language', 'cruxstore' ),
                            'text' => esc_html__('Text + Shortcode', 'cruxstore' ),
                            'wishlist' => esc_html__('Wishlist', 'cruxstore' ),
                            'myaccount' => esc_html__('Account', 'cruxstore' ),
                        ),
                        'default'  => array('currency', 'language'),
                        'multi' => true
                    ),
                    array(
                        'id'       => 'top_bar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Top bar Right', 'cruxstore' ),
                        'options'  => array(
                            'navigation' => esc_html__('Navigation', 'cruxstore' ),
                            'socials' => esc_html__('Socials', 'cruxstore' ),
                            'subscribe' => esc_html__('Subscribe', 'cruxstore' ),
                            'phone' => esc_html__('Phone', 'cruxstore' ),
                            'currency' => esc_html__('Currency', 'cruxstore' ),
                            'language' => esc_html__('Language', 'cruxstore' ),
                            'text' => esc_html__('Text + Shortcode', 'cruxstore' ),
                            'wishlist' => esc_html__('Wishlist', 'cruxstore' ),
                            'myaccount' => esc_html__('Account', 'cruxstore' ),
                        ),
                        'default'  => array('phone', 'subscribe', 'socials' ),
                        'multi' => true
                    ),
                    array(
                        'id' => 'top_bar_text',
                        'type' => 'editor',
                        'title' => esc_html__('Top bar Text', 'cruxstore'),
                        'default' => ''
                    ),
                    array(
                        'id'   => 'top_bar_socials',
                        'type' => 'cruxstore_socials',
                        'title'    => esc_html__( 'Top bar Socials', 'cruxstore' ),
                        'default' => 'facebook,twitter,instagram,linkedin'
                    ),

                )
            );
            /**
             *    Footer
             **/
            $this->sections[] = array(
                'id' => 'footer',
                'title' => esc_html__('Footer', 'cruxstore'),
                'desc' => '',
                'subsection' => true,
                'fields' => array(
                    // Footer settings

                    array(
                        'id' => 'backtotop',
                        'type' => 'switch',
                        'title' => esc_html__('Back to top', 'cruxstore'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),

                    array(
                        'id' => 'footer_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer settings', 'cruxstore') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer',
                        'type' => 'switch',
                        'title' => esc_html__('Footer enable', 'cruxstore'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),

                    array(
                        'id' => 'footer_fullwidth',
                        'type' => 'switch',
                        'title' => esc_html__('Footer background fullwidth', 'cruxstore'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),
                    // Footer Top settings
                    array(
                        'id' => 'footer_top_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer top settings', 'cruxstore') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_top',
                        'type' => 'switch',
                        'title' => esc_html__('Footer top enable', 'cruxstore'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),
                    array(
                        'id' => 'footer_top_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Footer top layout', 'cruxstore'),
                        'subtitle' => esc_html__('Select your footer top layout', 'cruxstore'),
                        'options' => array(
                            '1' => array('alt' => esc_html__('Layout 1', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-top-1.png'),
                            '2' => array('alt' => esc_html__('Layout 2', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-top-2.png'),
                        ),
                        'default' => '1'
                    ),

                    // Footer Instagram settings
                    array(
                        'id' => 'footer_instagram_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer Instagram settings', 'cruxstore') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_instagram_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer Instagram layout', 'cruxstore' ),
                        'options'  => array(
                            'full' => esc_html__('Full', 'cruxstore' ),
                            'gallery' => esc_html__('Gallery', 'cruxstore' ),
                            'space' => esc_html__('Space', 'cruxstore' ),
                        ),
                        'default'  => 'full'
                    ),

                    // Footer widgets settings
                    array(
                        'id' => 'footer_widgets_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer widgets settings', 'cruxstore') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_widgets',
                        'type' => 'switch',
                        'title' => esc_html__('Footer widgets enable', 'cruxstore'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),
                    array(
                        'id' => 'footer_widgets_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Footer widgets layout', 'cruxstore'),
                        'subtitle' => esc_html__('Select your footer widgets layout', 'cruxstore'),
                        'options' => array(
                            '1' => array('alt' => esc_html__('Layout 1', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-1.jpg'),
                            '2' => array('alt' => esc_html__('Layout 2', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-2.jpg'),
                            '3' => array('alt' => esc_html__('Layout 3', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-3.jpg'),
                            '4' => array('alt' => esc_html__('Layout 4', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-4.jpg'),
                            '5' => array('alt' => esc_html__('Layout 5', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-5.jpg'),
                            '6' => array('alt' => esc_html__('Layout 6', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-6.jpg'),
                            '7' => array('alt' => esc_html__('Layout 7', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-7.jpg'),
                            '8' => array('alt' => esc_html__('Layout 8', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-8.jpg'),
                            '9' => array('alt' => esc_html__('Layout 9', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-9.jpg'),

                            '10' => array('alt' => esc_html__('Layout 10', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-10.jpg'),
                        ),
                        'default' => '1'
                    ),

                    /* Footer Bottom */
                    array(
                        'id' => 'footer_bottom_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer bottom settings', 'cruxstore') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_bottom',
                        'type' => 'switch',
                        'title' => esc_html__('Footer bottom enable', 'cruxstore'),
                        'default' => false,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),
                    array(
                        'id' => 'footer_bottom_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Footer bottom layout', 'cruxstore'),
                        'subtitle' => esc_html__('Select your footer bottom layout', 'cruxstore'),
                        'options' => array(
                            '1' => array('alt' => esc_html__('Layout 1', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-bottom-1.png'),
                            '2' => array('alt' => esc_html__('Layout 2', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-bottom-2.png'),
                        ),
                        'default' => '1'
                    ),

                    /* Footer copyright */
                    array(
                        'id' => 'footer_copyright_heading',
                        'type' => 'raw',
                        'content' => '<div class="section-heading">' . esc_html__('Footer copyright settings', 'cruxstore') . '</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'footer_copyright',
                        'type' => 'switch',
                        'title' => esc_html__('Footer copyright enable', 'cruxstore'),
                        'default' => true,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),
                    array(
                        'id'       => 'footer_copyright_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer copyright layout', 'cruxstore' ),
                        'subtitle'     => esc_html__( 'Select your preferred footer layout.', 'cruxstore' ),
                        'options'  => array(
                            'centered' => esc_html__('Centered', 'cruxstore'),
                            'sides' => esc_html__('Sides', 'cruxstore' ),
                            'columns' => esc_html__('3 columns', 'cruxstore' ),
                        ),
                        'default'  => 'centered',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'footer_copyright_left',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer Copyright Left', 'cruxstore' ),
                        'options'  => array(
                            '' => esc_html__('Empty', 'cruxstore' ),
                            'navigation' => esc_html__('Navigation', 'cruxstore' ),
                            'socials' => esc_html__('Socials', 'cruxstore' ),
                            'copyright' => esc_html__('Copyright', 'cruxstore' ),
                            'subscribe' => esc_html__('Subscribe', 'cruxstore' ),
                            'image' => esc_html__('Image', 'cruxstore' ),
                        ),
                        'default'  => ''
                    ),
                    array(
                        'id'       => 'footer_copyright_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer Copyright Right', 'cruxstore' ),
                        'options'  => array(
                            '' => esc_html__('Empty', 'cruxstore' ),
                            'navigation' => esc_html__('Navigation', 'cruxstore' ),
                            'socials' => esc_html__('Socials', 'cruxstore' ),
                            'copyright' => esc_html__('Copyright', 'cruxstore' ),
                            'subscribe' => esc_html__('Subscribe', 'cruxstore' ),
                            'image' => esc_html__('Image', 'cruxstore' ),
                            'navigation_socials' => esc_html__('Navigation + Socials', 'cruxstore' ),
                        ),
                        'default'  => 'copyright'
                    ),
                    array(
                        'id'       => 'footer_copyright_center',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer Copyright Center', 'cruxstore' ),
                        'options'  => array(
                            '' => esc_html__('Empty', 'cruxstore' ),
                            'navigation' => esc_html__('Navigation', 'cruxstore' ),
                            'socials' => esc_html__('Socials', 'cruxstore' ),
                            'copyright' => esc_html__('Copyright', 'cruxstore' ),
                            'subscribe' => esc_html__('Subscribe', 'cruxstore' ),
                        ),
                        'default'  => 'copyright'
                    ),
                    array(
                        'id'   => 'footer_socials',
                        'type' => 'cruxstore_socials',
                        'title'    => esc_html__( 'Select your socials', 'cruxstore' ),
                        'default' => 'facebook,twitter,instagram,linkedin'
                    ),
                    array(
                        'id'       => 'footer_copyright_image',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Footer Copyright Image', 'cruxstore' ),
                    ),
                    array(
                        'id' => 'footer_copyright_text',
                        'type' => 'editor',
                        'title' => esc_html__('Footer Copyright Text', 'cruxstore'),
                        'default' => 'BUILT WITH <i class="fa fa-heart color-accent"></i> WP THEME BY KITETHEMES-WP'
                    ),
                )
            );


            /**
             *    Global Header
             **/
            $this->sections[] = array(
                'id' => 'global_header_setion',
                'title' => esc_html__('Global Header Banner', 'cruxstore'),
                'desc' => '',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id' => 'global_header',
                        'type' => 'switch',
                        'title' => esc_html__('Global Header enable', 'cruxstore'),
                        'default' => false,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                    ),
                    array(
                        'id' => 'global_header_layout',
                        'type' => 'image_select',
                        'compiler' => true,
                        'title' => esc_html__('Global Header layout', 'cruxstore'),
                        'subtitle' => esc_html__('Select your Global Header layout', 'cruxstore'),
                        'options' => array(
                            '6-3-3' => array('alt' => esc_html__('Layout 3', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-2.jpg'),
                            '3-3-6' => array('alt' => esc_html__('Layout 4', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-3.jpg'),
                            '6-6' => array('alt' => esc_html__('Layout 5', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-4.jpg'),
                            '4-4-4' => array('alt' => esc_html__('Layout 6', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-5.jpg'),
                            '8-4' => array('alt' => esc_html__('Layout 7', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-6.jpg'),
                            '4-8' => array('alt' => esc_html__('Layout 8', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-7.jpg'),
                            '3-6-3' => array('alt' => esc_html__('Layout 9', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-8.jpg'),
                            '12' => array('alt' => esc_html__('Layout 10', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'footer/footer-9.jpg'),
                        ),
                        'default' => '4-4-4'
                    ),
                )
            );


            /**
             *	Styling
             **/
            $this->sections[] = array(
                'id'			=> 'styling',
                'title'			=> esc_html__( 'Styling', 'cruxstore' ),
                'desc'			=> '',
                'icon'	=> 'fa fa-pencil',
            );




            /**
             *	Styling General
             **/
            $this->sections[] = array(
                'id'			=> 'styling_general',
                'title'			=> esc_html__( 'General', 'cruxstore' ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'styling_accent',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Main Color', 'cruxstore' ),
                        'default'  => '#ed8b5c',
                        'transparent' => false,
                    ),
                    array(
                        'id'       => 'styling_link',
                        'type'     => 'link_color',
                        'title'    => esc_html__( 'Links Color', 'cruxstore' ),
                        'output'   => array( 'a' ),
                        'default'  => array(
                            'regular' => '#ed8b5c',
                            'hover' => '#f7ccb8',
                            'active' => '#f7ccb8'
                        )
                    ),
                )
            );


            /**
             *	Styling Logo
             **/
            $this->sections[] = array(
                'id'			=> 'styling-logo',
                'title'			=> esc_html__( 'Logo', 'cruxstore' ),
                'subsection' => true,
                'fields'		=> array(

                    array(
                        'id'             => 'logo_width',
                        'type'           => 'dimensions',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Logo width', 'cruxstore' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 55, 'units'   => 'px' ),
                        'output'   => array( '.branding.branding-default img' ),
                    ),

                    array(
                        'id'       => 'logo_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'output'   => array( '.branding.branding-default' ),
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Logo margin spacing Option', 'cruxstore' ),
                        'default'  => array(
                            'margin-top'    => '18px',
                            'margin-right'  => '0',
                            'margin-bottom' => '0',
                            'margin-left'   => '0'
                        )
                    ),
                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id'             => 'logo_sticky_width',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'title'          => esc_html__( 'Logo sticky width', 'cruxstore' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 45, 'units'   => 'px' ),
                        'output'   => array( '#header .is-sticky .apply-sticky .branding-default img' ),
                    ),

                    array(
                        'id'       => 'logo_sticky_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Logo sticky margin spacing Option', 'cruxstore' ),
                        'default'  => array(
                            'margin-top'    => '0',
                            'margin-bottom' => '0',
                            'margin-left'   => '0',
                            'margin-right'   => '0'
                        ),
                        'output'   => array( '#header .is-sticky .apply-sticky .branding-default'),
                    ),
                    array(
                        'id'   => 'divide_id',
                        'type' => 'divide'
                    ),
                    array(
                        'id'             => 'logo_mobile_width',
                        'type'           => 'dimensions',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Logo mobile width', 'cruxstore' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 55, 'units'   => 'px' ),
                        'output'   => array( '.branding.branding-mobile img' ),
                    ),
                    array(
                        'id'       => 'logo_mobile_margin_spacing',
                        'type'     => 'spacing',
                        'mode'     => 'margin',
                        'units'          => array( 'px' ),
                        'units_extended' => 'true',
                        'title'    => esc_html__( 'Logo mobile margin spacing Option', 'cruxstore' ),
                        'default'  => array(
                            'margin-top'    => '10px',
                            'margin-right'  => '0px',
                            'margin-bottom' => '10px',
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
				'title'			=> esc_html__( 'Header', 'cruxstore' ),
				'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'header_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Header background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Header background with image, color, etc.', 'cruxstore' ),
                        'default'   => '',
                        'output'      => array( '.header-content' ),
                    ),
                    array(
                        'id'       => 'header_default_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header Default settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Header Border', 'cruxstore' ),
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
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header Light settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_light_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Header Border', 'cruxstore' ),
                        'output'   => array( '.header-light.header-transparent .header-content' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'top'      => false,
                        'default'  => array( )
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
             *	Styling Header topbar
             **/
            $this->sections[] = array(
                'id'			=> 'styling_header_topbar',
                'title'			=> esc_html__( 'TopBar', 'cruxstore' ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'header_topbar_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header topbar settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_topbar_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Header topbar background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Header topbar background with image, color, etc.', 'cruxstore' ),
                        'default'   => '',
                        'output'      => array( '.topbar' ),
                    ),


                )
            );


            /**
             *  Styling Sticky
             **/
            $this->sections[] = array(
                'id'            => 'styling_sticky',
                'title'         => esc_html__( 'Sticky', 'cruxstore' ),
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'fixed_header',
                        'type'     => 'button_set',
                        'title'    => esc_html__( 'Sticky header', 'cruxstore' ),
                        'options'  => array(
                            '1' => esc_html__('Disabled', 'cruxstore'),
                            '2' => esc_html__('Fixed Sticky', 'cruxstore'),
                            '3' => esc_html__('Slide Down', 'cruxstore'),
                        ),
                        'default'  => '3',
                        'desc' => esc_html__('Choose your sticky effect.', 'cruxstore')
                    ),


                    array(
                        'id'             => 'navigation_height_fixed',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'title'          => esc_html__( 'Main Navigation Sticky Height', 'cruxstore' ),
                        'desc'          => esc_html__( 'Change height of main navigation sticky', 'cruxstore' ),
                        'width'         => false,
                        'default'        => array(
                            'height'  => '60',
                            'units'  => 'px'
                        )
                    ),

                    array(
                        'id'            => 'header_sticky_opacity',
                        'type'          => 'slider',
                        'title'         => esc_html__( 'Sticky Background opacity', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Header sticky background', 'cruxstore' ),
                        'desc' => esc_html__( 'Header sticky with image, color, etc.', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Header transparent sticky background', 'cruxstore' ),
                        'desc' => esc_html__( 'Header transparent sticky with image, color, etc.', 'cruxstore' ),
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
                'title'			=> esc_html__( 'Footer', 'cruxstore' ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'footer_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer Background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Footer Background with image, color, etc.', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Footer padding', 'cruxstore' ),
                        'default'  => array( )
                    ),

                    array(
                        'id'       => 'footer_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer Border', 'cruxstore' ),
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
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer top settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_top_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer top Background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Footer top Background with image, color, etc.', 'cruxstore' ),
                        'default'   => array( ),
                        'output'      => array( '#footer-top' ),
                    ),


                    array(
                        'id'       => 'footer_top_image',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer top special', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Footer top special Background with image, color, etc.', 'cruxstore' ),
                        'transparent' => false,
                        'default'   => array(
                            'background-image' => CRUXSTORE_THEME_IMG.'footer-top-bg.png'
                        ),
                        'output'      => array( '#footer-top .footer-top-bg' ),
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
                        'title'    => esc_html__( 'Footer top padding', 'cruxstore' ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'footer_top_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer top Border', 'cruxstore' ),
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
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer widgets settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_widgets_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer widgets Background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Footer widgets Background with image, color, etc.', 'cruxstore' ),
                        'default'   => array(  ),
                        'output'      => array( '#footer-area' ),
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
                        'title'    => esc_html__( 'Footer widgets padding', 'cruxstore' ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'footer_widgets_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer widgets Border', 'cruxstore' ),
                        'output'   => array( '#footer-area' ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'default'  => array(

                        )
                    ),
                    //Footer bottom settings
                    array(
                        'id'       => 'footer_bottom_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer bottom settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'footer_bottom_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Footer bottom Background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Footer  Background with image, color, etc.', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Footer bottom padding', 'cruxstore' ),
                        'default'  => array( ),
                        'subtitle' => 'Disable if you use instagram background',
                    ),
                    array(
                        'id'       => 'footer_bottom_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer bottom Border', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Footer bottom Border color', 'cruxstore' ),
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
                        'content'  => '<div class="section-heading">'.esc_html__( 'Footer copyright settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'footer_copyright_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'Footer Copyright Border', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Footer Background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Footer Background with image, color, etc.', 'cruxstore' ),
                        'default'   => array( ),
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
                        'title'    => esc_html__( 'Footer copyright padding', 'cruxstore' ),
                        'default'  => array( )
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'footer_socials_style',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer socials style', 'cruxstore' ),
                        'options'  => array(
                            'dark'   => esc_html__('Dark', 'cruxstore' ),
                            'light'  => esc_html__('Light', 'cruxstore' ),
                            'color'  => esc_html__('Color', 'cruxstore' ),
                            'custom'  => esc_html__('Custom Color', 'cruxstore' ),
                        ),
                        'default'  => 'custom'
                    ),
                    array(
                        'id'       => 'custom_color_social',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Footer socials Color', 'cruxstore' ),
                        'default'  => '#999999',
                        'transparent' => false,
                        'required' => array('footer_socials_style','equals', array( 'custom' ) ),
                    ),
                    array(
                        'id'       => 'footer_socials_background',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer socials background', 'cruxstore' ),
                        'options'  => array(
                            'empty'       => esc_html__('None', 'cruxstore' ),
                            'rounded'   => esc_html__('Circle', 'cruxstore' ),
                            'boxed'  => esc_html__('Square', 'cruxstore' ),
                            'rounded-less'  => esc_html__('Rounded', 'cruxstore' ),
                            'diamond-square'  => esc_html__('Diamond Square', 'cruxstore' ),
                            'rounded-outline'  => esc_html__('Outline Circle', 'cruxstore' ),
                            'boxed-outline'  => esc_html__('Outline Square', 'cruxstore' ),
                            'rounded-less-outline'  => esc_html__('Outline Rounded', 'cruxstore' ),
                            'diamond-square-outline'  => esc_html__('Outline Diamond Square', 'cruxstore' ),
                        ),
                        'subtitle'     => esc_html__( 'Select background shape and style for social.', 'cruxstore' ),
                        'default'  => 'empty'
                    ),
                    array(
                        'id'       => 'footer_socials_size',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Footer socials size', 'cruxstore' ),
                        'options'  => array(
                            'small'       => esc_html__('Small', 'cruxstore' ),
                            'standard'   => esc_html__('Standard', 'cruxstore' ),
                        ),
                        'default'  => 'small'
                    ),
                    array(
                        'id'       => 'footer_socials_space_between_item',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Footer socials space between item', 'cruxstore' ),
                        'default'  => '30'
                    ),
                )
            );

            /**
             *  Main Navigation
             **/
            $this->sections[] = array(
                'id'            => 'styling_navigation',
                'title'         => esc_html__( 'Main Navigation', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'styling_navigation_general',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'General', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'             => 'navigation_height',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Main Navigation Height', 'cruxstore' ),
                        'subtitle'          => esc_html__( 'Change height of main navigation', 'cruxstore' ),
                        'width'         => false,
                        'default'        => array(
                            'height'  => '102',
                            'units'  => 'px'
                        )
                    ),

                    array(
                        'id'       => 'navigation_box_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'MegaMenu & Dropdown Box background', 'cruxstore' ),
                        'default'   => array(
                            'background-color'      => '#FFFFFF',
                        ),
                        'output'      => array(
                            '#header #main-nav-wc > li ul.sub-menu-dropdown',
                            '#header #main-nav-wc > li .navigation-submenu',
                            '#header #main-nav-tool > li ul.sub-menu-dropdown',
                            '#header #main-nav-tool > li .navigation-submenu',
                            '#header #main-navigation > li ul.sub-menu-dropdown',
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper',
                            '#header #main-navigation > li .navigation-submenu',
                            '.top-navigation > li .navigation-submenu'
                        ),
                        'transparent'           => false,
                    ),

                    array(
                        'id'       => 'navigation_box_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'MegaMenu & Dropdown Box Border', 'cruxstore' ),
                        'output'   => array(
                            '#header #main-nav-wc > li .navigation-submenu',
                            '#header #main-nav-tool > li .navigation-submenu',
                            '#header #main-navigation > li ul.sub-menu-dropdown',
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper',
                            '.top-navigation > li .navigation-submenu'
                        ),
                        'all'      => false,
                        'left'     => false,
                        'right'    => false,
                        'style'    => false,
                        'bottom'   => false,
                        'default'  => array(

                        )
                    ),

                    array(
                        'id'       => 'styling_navigation_general',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Top Level', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'            => 'navigation_space',
                        'type'          => 'slider',
                        'title'         => esc_html__( 'Top Level space', 'cruxstore' ),
                        'default'       => 16,
                        'min'           => 0,
                        'step'          => 1,
                        'max'           => 50,
                        'resolution'    => 1,
                        'display_value' => 'text',
                        'subtitle' => esc_html__( 'Margin left between top level.', 'cruxstore' ),
                    ),
                    array(
                        'id'       => 'navigation_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#header #main-nav-wc > li > a',
                            '#header #main-navigation > li > a',
                        ),
                        'title'    => esc_html__( 'Dark: Top Level Color', 'cruxstore' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'navigation_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#header #main-nav-wc > li.current-menu-item > a',
                            '#header #main-nav-wc > li > a:hover',
                            '#header #main-nav-wc > li > a:focus',
                            '#header #main-navigation > li.current-menu-item > a',
                            '#header #main-navigation > li > a:hover',
                            '#header #main-navigation > li > a:focus',
                        ),
                        'title'    => esc_html__( 'Dark: Top Level hover Color', 'cruxstore' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),


                    array(
                        'id'       => 'navigation_color_light',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light #header #main-nav-wc > li > a',
                            '.header-transparent.header-light #header #main-navigation > li > a',
                        ),
                        'title'    => esc_html__( 'Light: Top Level Color', 'cruxstore' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'navigation_color_hover_light',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light #header #main-nav-wc > li.current-menu-item > a',
                            '.header-transparent.header-light #header #main-nav-wc > li > a:hover',
                            '.header-transparent.header-light #header #main-nav-wc > li > a:focus',
                            '.header-transparent.header-light #header #main-navigation > li.current-menu-item > a',
                            '.header-transparent.header-light #header #main-navigation > li > a:hover',
                            '.header-transparent.header-light #header #main-navigation > li > a:focus',
                        ),
                        'title'    => esc_html__( 'Light: Top Level hover Color', 'cruxstore' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),


                    array(
                        'id'       => 'styling_navigation_dropdown',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Drop down', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'             => 'navigation_dropdown',
                        'type'           => 'dimensions',
                        'units'          => 'px',
                        'units_extended' => 'true',
                        'title'          => esc_html__( 'Dropdown width', 'cruxstore' ),
                        'subtitle'       => esc_html__( 'Change width of Dropdown', 'cruxstore' ),
                        'height'         => false,
                        'default'        => array( 'width'  => 308, 'units' => 'px' ),
                        'output'         => array( '#header #main-navigation > li ul.sub-menu-dropdown' ),
                    ),
                    array(
                        'id'       => 'dropdown_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Dropdown Background Color', 'cruxstore' ),
                        'default'  => array(
                            'background-color'      => '',
                        ),
                        'output'   => array(
                            '#header #main-navigation > li ul.sub-menu-dropdown li a',
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
                        'title'    => esc_html__( 'Dropdown Background Hover Color', 'cruxstore' ),
                        'default'  => array(
                            'background-color'      => '',
                        ),
                        'output'   => array(
                            '#header #main-navigation > li ul.sub-menu-dropdown li.current-menu-item > a',
                            '#header #main-navigation > li ul.sub-menu-dropdown li > a:hover',
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
                            '#header #main-navigation > li ul.sub-menu-dropdown li a',
                            '.navigation-submenu.woocommerce ul.product_list_widget li a',
                            '.shopping-bag .woocommerce.navigation-submenu .mini_cart_item .quantity',
                            '.top-navigation > li .navigation-submenu > li a',
                            '.shopping-bag .woocommerce.navigation-submenu .mini_cart_item .amount',
                        ),
                        'title'    => esc_html__( 'Dropdown Text Color', 'cruxstore' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'dropdown_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#header #main-navigation > li ul.sub-menu-dropdown li.current-menu-item > a',
                            '#header #main-navigation > li ul.sub-menu-dropdown li > a:hover',
                            '.top-navigation > li .navigation-submenu > li a:hover',
                            '.shopping-bag .navigation-submenu.woocommerce ul.product_list_widget li a'
                        ),
                        'title'    => esc_html__( 'Dropdown Text Hover Color', 'cruxstore' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    /*
                    array(
                        'id'       => 'dropdown_border',
                        'type'     => 'border',
                        'title'    => esc_html__( 'DropDown Border', 'cruxstore' ),
                        'output'   => array(
                            '#header #main-navigation > li ul.sub-menu-dropdown li + li',
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
                    */
                    array(
                        'id'       => 'styling_navigation_mega',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Mega', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),


                    array(
                        'id'       => 'mega_title_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper > ul > li > a',
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper > ul > li > span',
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper > ul > li .widget-title',
                        ),
                        'title'    => esc_html__( 'MegaMenu Title color', 'cruxstore' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_title_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper > ul > li > a:hover'
                        ),
                        'title'    => esc_html__( 'MegaMenu Title Hover Color', 'cruxstore' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_color',
                        'type'     => 'color',
                        'output'   => array(
                            '#header #main-navigation > li > .cruxstore-megamenu-wrapper > .cruxstore-megamenu-ul > li ul.sub-menu-megamenu a'
                        ),
                        'title'    => esc_html__( 'MegaMenu Text color', 'cruxstore' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),

                    array(
                        'id'       => 'mega_color_hover',
                        'type'     => 'color',
                        'output'   => array(
                            '#header #main-navigation > li > .cruxstore-megamenu-wrapper > .cruxstore-megamenu-ul > li ul.sub-menu-megamenu a:hover',
                            '#header #main-navigation > li > .cruxstore-megamenu-wrapper > .cruxstore-megamenu-ul > li ul.sub-menu-megamenu .current-menu-item > a',

                        ),
                        'title'    => esc_html__( 'MegaMenu Text Hover color', 'cruxstore' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mega_border_color',
                        'title'    => esc_html__( 'MegaMenu Border color', 'cruxstore' ),
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
                'title'         => esc_html__( 'Mobile Menu', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'mobile_menu_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Background', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Top Level Color', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Top Level hover Color', 'cruxstore' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'mobile_menu_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Top Level Background Color', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Top Level Hover Color', 'cruxstore' ),
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
                            'ul.navigation-mobile > li .cruxstore-megamenu-wrapper > ul.cruxstore-megamenu-ul > li > .sub-menu-megamenu > li > a',
                        ),
                        'title'    => esc_html__( 'Text color', 'cruxstore' ),
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
                            'ul.navigation-mobile > li .cruxstore-megamenu-wrapper > ul.cruxstore-megamenu-ul > li > .sub-menu-megamenu > li > a:hover',
                        ),
                        'title'    => esc_html__( 'Text Hover color', 'cruxstore' ),
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
                'title'			=> esc_html__( 'Typography', 'cruxstore' ),
                'desc'			=> '',
                'icon'	=> 'fa fa-camera-retro',
            );

            /**
             *	Typography General
             **/
            $this->sections[] = array(
                'id'			=> 'typography_general',
                'title'			=> esc_html__( 'General', 'cruxstore' ),
                'subsection' => true,
                'fields'		=> array(
                    array(
                        'id'       => 'typography_body',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Body Font', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the body font properties.', 'cruxstore' ),
                        'text-align' => false,
                        'letter-spacing'  => true,
                        'output'      => array( 'body', 'button', 'input', 'textarea' ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'typography_pragraph',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Pragraph', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the pragraph font properties.', 'cruxstore' ),
                        'output'   => array( 'p' ),
                        'default'  => array( ),
                        'color'    => false,
                        'text-align' => false,
                    ),
                    array(
                        'id'       => 'typography_special',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Special', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify some element font properties.', 'cruxstore' ),
                        'output'   => array( '.special-font', '.counter-content' ),
                        'color'    => false,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'font-style'      => '700'
                        ),
                    ),
                    array(
                        'id'       => 'typography_blockquote',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Blockquote', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the blockquote font properties.', 'cruxstore' ),
                        'output'   => array( 'blockquote' ),
                        'color'    => false,
                        'text-align' => false,
                        'default'  => array(
                            'font-family'     => 'Roboto Slab',
                            'font-style'      => '400'
                        ),
                    ),
                    /*
                    array(
                        'id'       => 'typography_button',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Button', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the button font properties.', 'cruxstore' ),
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
                            '.cruxstore-button',
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
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Heading settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_heading1',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 1', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the heading 1 font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'text-align' => false,
                        'output'      => array( 'h1', '.h1' ),
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_heading2',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 2', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the heading 2 font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h2', '.h2' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_heading3',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 3', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the heading 3 font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h3', '.h3' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_heading4',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 4', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the heading 4 font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h4', '.h4' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_heading5',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 5', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the heading 5 font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h5', '.h5' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_heading6',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading 6', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the heading 6 font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'output'      => array( 'h6', '.h6' ),
                        'text-transform' => true,
                        'text-align' => false,
                        'default'  => array( ),
                    ),
                )
            );

            /**
             *  Typography topbar
             **/
            $this->sections[] = array(
                'id'            => 'typography_header_topbar',
                'title'         => esc_html__( 'TopBar', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_header_topbar_content',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Topbar', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the topbar font properties.', 'cruxstore' ),
                        'google'   => true,
                        'text-align' => false,
                        'color'    => false,
                        'output'   => array( '.topbar' )
                    ),

                    array(
                        'id'       => 'header_topbar_default_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header topbar Default', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_topbar_default_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.top-navigation > li > a',
                            '#topbar-nav > li > a',
                            '.topbar .header-text',
                            '.topbar .header-text a',
                            '.topbar .header-phone',
                            '.topbar .header-phone a',
                            '.topbar .main-nav-socials a',
                        ),
                        'title'    => esc_html__( 'Default: Top Level Color', 'cruxstore' ),
                        'default'  => '#999999',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_topbar_default_active_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.top-navigation > li > a:hover',
                            '.top-navigation > li > a:focus',
                            '#topbar-nav > li > a:hover',
                            '#topbar-nav > li > a:focus',
                            '.topbar .header-text a:hover',
                            '.topbar .header-text a:focus',
                            '.topbar .header-phone a:hover',
                            '.topbar .header-phone a:focus',
                            '.topbar .main-nav-socials a:hover',
                            '.topbar .main-nav-socials a:focus',
                        ),
                        'title'    => esc_html__( 'Default: Top Level Hover Color', 'cruxstore' ),
                        'default'  => '#000000',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_topbar_border_color',
                        'type'     => 'color',
                        'title'    => esc_html__( 'Default: Border Color', 'cruxstore' ),
                        'default'  => '#ebebeb',
                        'transparent' => true
                    ),

                    array(
                        'id'       => 'header_topbar_light_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Header topbar Light', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'header_topbar_light_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light .top-navigation > li > a',
                            '.header-transparent.header-light #topbar-nav > li > a',
                            '.header-transparent.header-light .topbar .header-text',
                            '.header-transparent.header-light .topbar .header-phone',
                            '.header-transparent.header-light .topbar .header-phone a',
                            '.header-transparent.header-light .topbar .header-text a',
                            '.header-transparent.header-light .topbar .main-nav-socials a',
                        ),
                        'title'    => esc_html__( 'Light: Top Level Color', 'cruxstore' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_topbar_light_active_color',
                        'type'     => 'color',
                        'output'   => array(
                            '.header-transparent.header-light .top-navigation > li > a:hover',
                            '.header-transparent.header-light .top-navigation > li > a:focus',
                            '.header-transparent.header-light #topbar-nav > li > a:hover',
                            '.header-transparent.header-light #topbar-nav > li > a:focus',
                            '.header-transparent.header-light .topbar .header-text a:hover',
                            '.header-transparent.header-light .topbar .header-text a:focus',
                            '.header-transparent.header-light .topbar .header-phone a:hover',
                            '.header-transparent.header-light .topbar .header-phone a:focus',
                            '.header-transparent.header-light .topbar .main-nav-socials a:hover',
                            '.header-transparent.header-light .topbar .main-nav-socials a:focus',
                        ),
                        'title'    => esc_html__( 'Light: Top Level Hover Color', 'cruxstore' ),
                        'default'  => '#FFFFFF',
                        'transparent' => false
                    ),
                    array(
                        'id'       => 'header_topbar_light_border_color',
                        'type'     => 'color_rgba',
                        'title'    => esc_html__( 'Light: Border Color', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Gives you the RGBA color.', 'cruxstore' ),
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
             *  Typography header
             **/
            $this->sections[] = array(
                'id'            => 'typography_header',
                'title'         => esc_html__( 'Header', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_header_content',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Header', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the header font properties.', 'cruxstore' ),
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
                'title'         => esc_html__( 'Footer', 'cruxstore' ),
                'desc'          => '',
                'subsection'    => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_footer_top_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer top settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_top',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer top', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the footer top font properties.', 'cruxstore' ),
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
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer widgets settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_widgets',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer widgets', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the footer widgets font properties.', 'cruxstore' ),
                        'google'   => true,
                        'text-align'      => false,
                        'output'      => array( '#footer-area' ),
                        'default'  => array(
                            'color'       => '#bbbbbb'
                        ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_title',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer widgets title', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the footer widgets title font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'text-align'      => true,
                        'text-transform' => true,
                        'output'      => array( '#footer-area .widget .widget-title' ),
                        'default'  => array(
                            'color'       => '#ffffff'
                        ),
                    ),
                    array(
                        'id'       => 'typography_footer_widgets_link',
                        'type'     => 'link_color',
                        'title'    => esc_html__( 'Footer widgets Links Color', 'cruxstore' ),
                        'output'      => array( '#footer-area a' ),
                        'default'  => array(
                            'regular' => '#bbbbbb',
                            'hover'   => '#ffffff',
                            'active'  => '#ffffff'
                        )
                    ),

                    array(
                        'id'       => 'typography_footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer Bottom settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_bottom_link',
                        'type'     => 'link_color',
                        'title'    => esc_html__( 'Footer Bottom Links Color', 'cruxstore' ),
                        'output'      => array( '#footer-bottom a', '#footer-bottom button' ),
                        'default'  => array(
                            'regular' => '#bbbbbb',
                            'hover'   => '#ffffff',
                            'active'  => '#ffffff'
                        )
                    ),
                    array(
                        'id'       => 'typography_footer_bottom',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer Bottom', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the footer font properties.', 'cruxstore' ),
                        'text-align'      => false,
                        'output'      => array( '#footer-bottom' ),
                        'default'  => array(
                            'color'       => '#bbbbbb',
                            'font-size'   => '',
                            'font-weight' => '',
                            'line-height' => ''
                        ),
                    ),

                    array(
                        'id'       => 'typography_footer_copyright_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Typography Footer copyright settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_footer_copyright_link',
                        'type'     => 'link_color',
                        'title'    => esc_html__( 'Footer Copyright Links Color', 'cruxstore' ),
                        'output'      => array( '#footer-copyright a' ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'typography_footer_copyright',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Footer copyright', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the footer font properties.', 'cruxstore' ),
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
                'title'         => esc_html__( 'Sidebar', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_sidebar',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Sidebar title', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the sidebar title font properties.', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'text-transform' => true,
                        'output'      => array(
                            '.side-bar .widget .widget-title',
                            '.wpb_widgetised_column .widget .widget-title'
                        ),
                        'default'  => array(
                        ),
                    ),
                    array(
                        'id'       => 'typography_sidebar_content',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Sidebar text', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Specify the sidebar title font properties.', 'cruxstore' ),
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
                'title'         => esc_html__( 'Main Navigation', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography-navigation_top',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Top Menu Level', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '#header #main-navigation > li > a',
                            '#header #main-nav-wc > li > a'
                        ),
                        'default'  => array( ),
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id'       => 'typography_navigation_dropdown',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Dropdown menu', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_navigation_second',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Second Menu Level', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '#header #main-navigation > li ul.sub-menu-dropdown li a'
                        ),
                        'default'  => array(

                        ),
                    ),
                    array(
                        'id'       => 'typography_navigation_mega',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Mega menu', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'typography_navigation_heading',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Heading title', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper > ul > li > a',
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper > ul > li > span',
                            '#header #main-navigation > li .cruxstore-megamenu-wrapper > ul > li .widget-title'
                        ),
                        'default'  => array( ),
                    ),
                    array(
                        'id'       => 'typography_navigation_mega_link',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Mega menu', 'cruxstore' ),
                        'google'   => true,
                        'text-align'      => false,
                        'color'           => false,
                        'text-transform' => true,
                        'line-height'     => false,
                        'output'      => array(
                            '#header #main-navigation > li > .cruxstore-megamenu-wrapper > .cruxstore-megamenu-ul > li ul.sub-menu-megamenu a'
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
                'title'         => esc_html__( 'Mobile Navigation', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'typography_mobile_navigation_top',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Top Menu Level', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Sub Menu Level', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Heading title', 'cruxstore' ),
                        'letter-spacing'  => true,
                        'text-align'      => false,
                        'color'           => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array(
                            '.main-nav-mobile > ul > li div.cruxstore-megamenu-wrapper > ul > li > a',
                            '.main-nav-mobile > ul > li div.cruxstore-megamenu-wrapper > ul > li > span',
                            '.main-nav-mobile > ul > li div.cruxstore-megamenu-wrapper > ul > li .widget-title'
                        ),
                        'default'  => array(
                            'text-transform' => 'uppercase'
                        ),
                    ),
                )
            );

            /**
             *	Woocommerce
             **/
            $this->sections[] = array(
                'id'			=> 'woocommerce',
                'title'			=> esc_html__( 'Woocommerce', 'cruxstore' ),
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
                        'content'  => '<div class="section-heading">'.esc_html__( 'Shop settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    
                    array(
                        'id' => 'catalog_mode',
                        'type' => 'switch',
                        'title' => esc_html__('Catalog Mode', 'cruxstore'),
                        'desc' => esc_html__('When enabled, the feature Turns Off the shopping functionality of WooCommerce.', 'cruxstore'),
                        "default" => 0,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    
                    array(
                        'id'       => 'time_product_new',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Time Product New', 'cruxstore' ),
                        'default'  => '30',
                        'desc' => esc_html__('Time Product New ( unit: days ).', 'cruxstore'),
                    ),
                    
                    
                    array(
                        'id'       => 'shop_products_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Shop Products settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'shop_page_header',
                        'type' => 'switch',
                        'title' => esc_html__('Show Page header', 'cruxstore'),
                        'desc' => esc_html__('Show page header or?.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    
                    array(
                        'id' => 'loop_shop_quickview',
                        'type' => 'switch',
                        'title' => esc_html__('Quick View', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    
                    array(
                        'id'       => 'shop_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop: Sidebar configuration', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose sidebar for shop post", 'cruxstore' ),
                        'options'  => array(
                            'full' => esc_html__('No sidebars', 'cruxstore'),
                            'left' => esc_html__('Left Sidebar', 'cruxstore'),
                            'right' => esc_html__('Right Layout', 'cruxstore')
                        ),
                        'default'  => 'left',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'shop_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Shop: Sidebar left area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'cruxstore' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('shop_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'shop_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop: Sidebar right area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'cruxstore' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('shop_sidebar','equals','right'),
                        'clear' => false
                    ),

                    array(
                        'id'       => 'shop_products_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop: Products default Layout', 'cruxstore' ),
                        'options'  => array(
                            'grid' => esc_html__('Grid', 'cruxstore' ),
                            'lists' => esc_html__('Lists', 'cruxstore' )
                        ),
                        'default'  => 'grid'
                    ),
                    
                    array(
                        'id'       => 'shop_gird_cols',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Number column to display width gird mod', 'cruxstore' ),
                        'options'  => array(
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                        ),
                        'default'  => 3,
                    ),
                    array(
                        'id'       => 'shop_products_effect',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop product effect', 'cruxstore' ),
                        'options'  => array(
                            '1' => esc_html__('Effect 1', 'cruxstore' ),
                            '2' => esc_html__('Effect 2', 'cruxstore' ),
                            '3' => esc_html__('Effect 3', 'cruxstore' ),
                            '4' => esc_html__('Effect 4', 'cruxstore' ),
                            '5' => esc_html__('Effect 5', 'cruxstore' ),
                        ),
                        'default'  => '1'
                    ),
                    array(
                        'id'       => 'loop_shop_per_page',
                        'type'     => 'text',
                        'title'    => esc_html__( 'Number of products displayed per page', 'cruxstore' ),
                        'default'  => '12'
                    ),

                    // For Shop header
                    array(
                        'id'       => 'shop_header_products',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Shop Header Settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'shop_header_tool_bar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop Header', 'cruxstore' ),
                        'desc'     => esc_html__('Select type shop header.', 'cruxstore'),
                        'options'  => array(
                            '0' => esc_html__('Disable', 'cruxstore' ),
                            '1' => esc_html__('Default', 'cruxstore' ),
                            '2' => esc_html__('Categories', 'cruxstore' ),
                            '3' => esc_html__('Attributes', 'cruxstore' )
                        ),
                        'default'  => '1'
                    ),
                    array(
                        'id'       => 'shop_header_categories',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop Header - Categories', 'cruxstore' ),
                        'desc'     => esc_html__('Empty for show all categories', 'cruxstore'),
                        'options'  => $all_categories,
                        'default'  => '',
                        'multi'    => true,
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),
                    array(
                        'id'       => 'shop_header_orderby',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Categories - Order by', 'cruxstore' ),
                        'desc'     => esc_html__('The column to use for ordering categories', 'cruxstore'),
                        'options'  => array(
                            'id' => esc_html__('ID', 'cruxstore' ),
                            'name' => esc_html__('Name/Menu-order', 'cruxstore' ),
                            'slug' => esc_html__('Slug', 'cruxstore' ),
                            'count' => esc_html__('Count', 'cruxstore' ),
                            'term_group' => esc_html__('Term Group', 'cruxstore' ),
                        ),
                        'default'  => 'slug',
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),
                    array(
                        'id'       => 'shop_header_order',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Categories - Order', 'cruxstore' ),
                        'desc'     => esc_html__('Which direction to order categories', 'cruxstore'),
                        'options'  => array(
                            'ASC' => esc_html__('ASC', 'cruxstore' ),
                            'DESC' => esc_html__('DESC', 'cruxstore' ),
                        ),
                        'default'  => 'ASC',
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),

                    array(
                        'id' => 'shop_header_filters',
                        'type' => 'switch',
                        'title' => esc_html__('Shop Header - Filters', 'cruxstore'),
                        'desc' => esc_html__('Display filters in the shop header.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore'),
                        'required' => array('shop_header_tool_bar','equals','2'),
                    ),

                    array(
                        'id'       => 'shop_header_attributes',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Shop Header - Attributes', 'cruxstore' ),
                        'desc'     => esc_html__('Empty for show all Attributes', 'cruxstore'),
                        'options'  => $taxonomy_names,
                        'default'  => '',
                        'multi'    => true,
                        'required' => array('shop_header_tool_bar','equals','3'),
                    ),

                    array(
                        'id' => 'shop_header_ajax',
                        'type' => 'switch',
                        'title' => esc_html__('Shop Header - Ajax for fillter', 'cruxstore'),
                        'desc' => esc_html__('Enable ajax when use filter.', 'cruxstore'),
                        "default" => 1,
                        'on' =>  esc_html__('Enabled', 'cruxstore'),
                        'off' => esc_html__('Disabled', 'cruxstore'),
                        'required' => array(
                            array('shop_header_tool_bar','!=','1'),
                            array('shop_header_tool_bar','!=','0'),
                        ),
                    ),

                    // For Attribute Swatches
                    array(
                        'id'       => 'shop_attribute_swatches',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Attribute swatches settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'product_attribute_swatche',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Grid swatche attribute to display', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Choose attribute that will be show in products grid.", 'cruxstore' ),
                        'options'  => $taxonomy_names,
                        'default'  => 'pa_color',
                    ),

                    // For Single Products
                    array(
                        'id'       => 'shop_single_product',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Single Product settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'product_detail_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Product layout', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose single product layout.", 'cruxstore' ),
                        'options'  => array(
                            'layout1' => esc_html__('Layout 1', 'cruxstore'),
                            'layout2' => esc_html__('Layout 2', 'cruxstore'),
                            'layout3' => esc_html__('Layout 3', 'cruxstore'),
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
                'title'			=> esc_html__( 'Page header', 'cruxstore' ),
                'desc'			=> '',
                'icon'          => 'fa fa-header',
                'fields'		=> array(

                    array(
                        'id'       => 'title_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Page header settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),

                    array(
                        'id'       => 'title_layout',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Page header layout', 'cruxstore' ),
                        'subtitle'     => esc_html__( 'Select your preferred Page header layout.', 'cruxstore' ),
                        'options'  => array(
                            'sides' => esc_html__('Sides', 'cruxstore'),
                            'centered' => esc_html__('Centered', 'cruxstore' ),
                        ),
                        'default'  => 'centered'
                    ),
                    array(
                        'id'       => 'title_align',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Page header align', 'cruxstore' ),
                        'subtitle'     => esc_html__( 'Please select page header align', 'cruxstore' ),
                        'options'  => array(
                            'left' => esc_html__('Left', 'cruxstore' ),
                            'center' => esc_html__('Center', 'cruxstore'),
                            'right' => esc_html__('Right', 'cruxstore')
                        ),
                        'default'  => 'center',
                        'desc' => esc_html__("Align Center don't support for layout Sides", 'cruxstore')
                    ),
                    array(
                        'id'       => 'title_heading',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Show title', 'cruxstore' ),
                        'default'  => true,
                        'on'		=> esc_html__( 'Enabled', 'cruxstore' ),
                        'off'		=> esc_html__( 'Disabled', 'cruxstore' ),
                    ),
                    array(
                        'id'       => 'title_breadcrumbs',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Show breadcrumbs', 'cruxstore' ),
                        'default'  => true,
                        'on'		=> esc_html__( 'Enabled', 'cruxstore' ),
                        'off'		=> esc_html__( 'Disabled', 'cruxstore' ),
                    ),
                    array(
                        'id'       => 'title_breadcrumbs_mobile',
                        'type'     => 'switch',
                        'title'    => esc_html__( 'Breadcrumbs on Mobile Devices', 'cruxstore' ),
                        'default'  => false,
                        'on'		=> esc_html__( 'Enabled', 'cruxstore' ),
                        'off'		=> esc_html__( 'Disabled', 'cruxstore' ),
                    ),
                    array(
                        'id'       => 'title_style_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Page header style settings', 'cruxstore' ).'</div>',
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
                        'title'    => esc_html__( 'Title padding', 'cruxstore' ),
                        'default'  => array( )
                    ),
                    array(
                        'id'       => 'title_background',
                        'type'     => 'background',
                        'title'    => esc_html__( 'Background', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Page header with image, color, etc.', 'cruxstore' ),
                        'output'      => array( '.page-header' )
                    ),

                    array(
                        'id'       => 'title_typography_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Page header typography settings', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id'       => 'title_typography',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Typography title', 'cruxstore' ),
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
                        'title'    => esc_html__( 'Typography sub title', 'cruxstore' ),
                        'google'   => true,
                        'text-align'      => false,
                        'line-height'     => false,
                        'text-transform' => true,
                        'output'      => array( '.page-header .page-header-subtitle' )
                    ),
                    array(
                        'id'       => 'title_typography_breadcrumbs',
                        'type'     => 'typography',
                        'title'    => esc_html__( 'Typography breadcrumbs', 'cruxstore' ),
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
                'title' => esc_html__('Page', 'cruxstore'),
                'desc' => esc_html__('General Page Options', 'cruxstore'),
                'icon' => 'fa fa-suitcase',
                'fields' => array(
                    array(
                        'id' => 'show_page_header',
                        'type' => 'switch',
                        'title' => esc_html__('Show Page header', 'cruxstore'),
                        'desc' => esc_html__('Show page header or?.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    array(
                        'id'       => 'page_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar configuration', 'cruxstore' ),
                        'options'  => array(
                            'full' => esc_html__('No sidebars', 'cruxstore'),
                            'left' => esc_html__('Left Sidebar', 'cruxstore'),
                            'right' => esc_html__('Right Layout', 'cruxstore')
                        ),
                        'default'  => '',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'page_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Sidebar left area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose default layout", 'cruxstore' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('page_sidebar','equals','left')
                        //'clear' => false
                    ),

                    array(
                        'id'       => 'page_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar right area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose page layout", 'cruxstore' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('page_sidebar','equals','right')
                        //'clear' => false
                    ),
                    array(
                        'id' => 'show_page_comment',
                        'type' => 'switch',
                        'title' => esc_html__('Show comments on page ?', 'cruxstore'),
                        'desc' => esc_html__('Show or hide the readmore button.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                )
            );
            /**
             * General Blog
             *
             */
            $this->sections[] = array(
                'title' => esc_html__('Blog', 'cruxstore'),
                'icon' => 'fa fa-pencil',
                'desc' => esc_html__('General Blog Options', 'cruxstore')
            );

            /**
             *  Archive settings
             **/
            $this->sections[] = array(
                'id'            => 'archive_section',
                'title'         => esc_html__( 'Archive', 'cruxstore' ),
                'desc'          => 'Archive post settings',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'archive_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Archive post general', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'archive_page_header',
                        'type' => 'switch',
                        'title' => esc_html__('Show Page header', 'cruxstore'),
                        'desc' => esc_html__('Show page header or?.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    array(
                        'id'       => 'archive_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar configuration', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose archive page ", 'cruxstore' ),
                        'options'  => array(
                            'full' => esc_html__('No sidebars', 'cruxstore'),
                            'left' => esc_html__('Left Sidebar', 'cruxstore'),
                            'right' => esc_html__('Right Layout', 'cruxstore')
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'archive_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Sidebar left area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'cruxstore' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('archive_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'archive_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar right area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'cruxstore' ),
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
                        'title' => esc_html__('Loop Style', 'cruxstore'),
                        'desc' => '',
                        'options' => array(
                            'classic' => esc_html__( 'Classic', 'cruxstore' ),
                            'list' => esc_html__( 'List', 'cruxstore' ),
                            'grid' => esc_html__( 'Grid', 'cruxstore' ),
                            'masonry' => esc_html__( 'Masonry', 'cruxstore' ),
                            'medium' => esc_html__( 'Medium', 'cruxstore' ),
                        ),
                        'default' => 'grid'
                    ),
                    array(
                        'id' => 'archive_columns',
                        'type' => 'select',
                        'title' => esc_html__('Columns on desktop', 'cruxstore'),
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
                        'title' => esc_html__('Posts per page', 'cruxstore'),
                        'desc' => esc_html__("Insert the total number of pages.", 'cruxstore'),
                        'default' => 14,
                    ),
                    array(
                        'id' => 'archive_excerpt_length',
                        'type' => 'text',
                        'title' => esc_html__('Excerpt Length', 'cruxstore'),
                        'desc' => esc_html__("Insert the number of words you want to show in the post excerpts.", 'cruxstore'),
                        'default' => 35,
                    ),

                    array(
                        'id' => 'archive_readmore',
                        'type' => 'select',
                        'title' => esc_html__('Readmore button', 'cruxstore'),
                        'desc' => '',
                        'options' => array(
                            'none' => esc_html__( 'None', 'js_composer' ) ,
                            'link' => esc_html__( 'Link', 'js_composer' ) ,
                        ),
                        'default' => 'none',
                        'required' => array('archive_loop_style','!=', 'medium' ),
                    ),

                    array(
                        'id' => 'archive_pagination',
                        'type' => 'select',
                        'title' => esc_html__('Pagination Type', 'cruxstore'),
                        'desc' => esc_html__('Select the pagination type.', 'cruxstore'),
                        'options' => array(
                            'normal' => esc_html__( 'Normal pagination', 'cruxstore' ),
                            'button' => esc_html__( 'Next - Previous button', 'cruxstore' ),
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
                'title'         => esc_html__( 'Single Post', 'cruxstore' ),
                'desc'          => 'Single post settings',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'blog_single_heading',
                        'type'     => 'raw',
                        'content'  => '<div class="section-heading">'.esc_html__( 'Single post general', 'cruxstore' ).'</div>',
                        'full_width' => true
                    ),
                    array(
                        'id' => 'single_page_header',
                        'type' => 'text',
                        'title' => esc_html__('Custom Page header', 'cruxstore'),
                        'subtitle' => esc_html__("Empty if you want use post title", 'cruxstore'),
                        'default' => esc_html__('Welcome to our blog', 'cruxstore')
                    ),
                    array(
                        'id'       => 'single_sidebar',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Sidebar configuration', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose sidebar for single post", 'cruxstore' ),
                        'options'  => array(
                            'full' => esc_html__('No sidebars', 'cruxstore'),
                            'left' => esc_html__('Left Sidebar', 'cruxstore'),
                            'right' => esc_html__('Right Layout', 'cruxstore')
                        ),
                        'default'  => 'right',
                        'clear' => false
                    ),
                    array(
                        'id'       => 'single_sidebar_left',
                        'type' => 'select',
                        'title'    => esc_html__( 'Single post: Sidebar left area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'cruxstore' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('single_sidebar','equals','left'),
                        'clear' => false
                    ),
                    array(
                        'id'       => 'single_sidebar_right',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Single post: Sidebar right area', 'cruxstore' ),
                        'subtitle'     => esc_html__( "Please choose left sidebar ", 'cruxstore' ),
                        'data'     => 'sidebars',
                        'default'  => 'primary-widget-area',
                        'required' => array('single_sidebar','equals','right'),
                        'clear' => false
                    ),
                    array(
                        'id'   => 'single_image_size',
                        'type' => 'select',
                        'options' => $image_sizes,
                        'title'    => esc_html__( 'Image size', 'cruxstore' ),
                        'desc' => esc_html__("Select image size.", 'cruxstore'),
                        'default' => 'full'
                    ),
                    array(
                        'type' => 'divide',
                        'id' => 'divide_fake',
                    ),
                    array(
                        'id' => 'single_share_box',
                        'type' => 'switch',
                        'title' => esc_html__('Share box in posts', 'cruxstore'),
                        'desc' => esc_html__('Show share box in blog posts.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    array(
                        'id' => 'single_next_prev',
                        'type' => 'switch',
                        'title' => esc_html__('Previous & next buttons', 'cruxstore'),
                        'desc' => esc_html__('Show Previous & next buttons in blog posts.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    array(
                        'id' => 'single_author',
                        'type' => 'switch',
                        'title' => esc_html__('Author info in posts', 'cruxstore'),
                        'desc' => esc_html__('Show author info in blog posts.', 'cruxstore'),
                        "default" => 1,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    array(
                        'id' => 'single_related',
                        'type' => 'switch',
                        'title' => esc_html__('Related posts', 'cruxstore'),
                        'desc' => esc_html__('Show related posts in blog posts.', 'cruxstore'),
                        "default" => 0,
                        'on' => esc_html__('Enabled', 'cruxstore'),
                        'off' =>esc_html__('Disabled', 'cruxstore')
                    ),
                    array(
                        'id'       => 'single_related_type',
                        'type'     => 'select',
                        'title'    => esc_html__( 'Related Query Type', 'cruxstore' ),
                        'options'  => array(
                            'categories' => esc_html__('Categories', 'cruxstore'),
                            'tags' => esc_html__('Tags', 'cruxstore'),
                            'author' => esc_html__('Author', 'cruxstore')
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
                'title'			=> esc_html__( 'Socials', 'cruxstore' ),
                'desc'			=> esc_html__('Social and share settings', 'cruxstore'),
                'icon'	        => 'fa fa-facebook',
                'fields'		=> array(

                    array(
                        'id' => 'twitter',
                        'type' => 'text',
                        'title' => esc_html__('Twitter', 'cruxstore'),
                        'subtitle' => esc_html__("Your Twitter username (no @).", 'cruxstore'),
                        'default' => '#'
                    ),
                    array(
                        'id' => 'facebook',
                        'type' => 'text',
                        'title' => esc_html__('Facebook', 'cruxstore'),
                        'subtitle' => esc_html__("Your Facebook page/profile url", 'cruxstore'),
                        'default' => '#'
                    ),
                    array(
                        'id' => 'pinterest',
                        'type' => 'text',
                        'title' => esc_html__('Pinterest', 'cruxstore'),
                        'subtitle' => esc_html__("Your Pinterest username", 'cruxstore'),
                        'default' => '#'
                    ),
                    array(
                        'id' => 'dribbble',
                        'type' => 'text',
                        'title' => esc_html__('Dribbble', 'cruxstore'),
                        'subtitle' => esc_html__("Your Dribbble username", 'cruxstore'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'vimeo',
                        'type' => 'text',
                        'title' => esc_html__('Vimeo', 'cruxstore'),
                        'subtitle' => esc_html__("Your Vimeo username", 'cruxstore'),
                        'desc' => '',
                        'default' => '#'
                    ),
                    array(
                        'id' => 'tumblr',
                        'type' => 'text',
                        'title' => esc_html__('Tumblr', 'cruxstore'),
                        'subtitle' => esc_html__("Your Tumblr username", 'cruxstore'),
                        'desc' => '',
                        'default' => '#'
                    ),
                    array(
                        'id' => 'skype',
                        'type' => 'text',
                        'title' => esc_html__('Skype', 'cruxstore'),
                        'subtitle' => esc_html__("Your Skype username", 'cruxstore'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'linkedin',
                        'type' => 'text',
                        'title' => esc_html__('LinkedIn', 'cruxstore'),
                        'subtitle' => esc_html__("Your LinkedIn page/profile url", 'cruxstore'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'googleplus',
                        'type' => 'text',
                        'title' => esc_html__('Google+', 'cruxstore'),
                        'subtitle' => esc_html__("Your Google+ page/profile URL", 'cruxstore'),
                        'desc' => '',
                        'default' => '#'
                    ),
                    array(
                        'id' => 'youtube',
                        'type' => 'text',
                        'title' => esc_html__('YouTube', 'cruxstore'),
                        'subtitle' => esc_html__("Your YouTube username", 'cruxstore'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'instagram',
                        'type' => 'text',
                        'title' => esc_html__('Instagram', 'cruxstore'),
                        'subtitle' => esc_html__("Your Instagram username", 'cruxstore'),
                        'desc' => '',
                        'default' => ''
                    ),
                    array(
                        'id' => 'behance',
                        'type' => 'text',
                        'title' => esc_html__('Behance', 'cruxstore'),
                        'subtitle' => esc_html__("Your behance username", 'cruxstore'),
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
                'title'			=> esc_html__( 'Popup', 'cruxstore' ),
                'icon'	=> 'fa fa-bullhorn',
                'fields'		=> array(
                    array(
                        'id'		=> 'enable_popup',
                        'type'		=> 'switch',
                        'title'		=> esc_html__( 'Enable Popup', 'cruxstore' ),
                        'subtitle'	=> esc_html__( '', 'cruxstore'),
                        "default"	=> false,
                        'on'		=> esc_html__( 'On', 'cruxstore' ),
                        'off'		=> esc_html__( 'Off', 'cruxstore' ),
                    ),
                    array(
                        'id'            => 'time_show',
                        'type'          => 'slider',
                        'title'         => esc_html__( 'Time to show', 'cruxstore' ),
                        'desc'          => esc_html__( 'Delay time for show. (seconds)', 'cruxstore' ),
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
                'title'			=> esc_html__( 'Subscribe', 'cruxstore' ),
                'icon'	=> 'fa fa-envelope-o',
                'fields'		=> array(
                    array(
                        'id'       => 'popup_image',
                        'type'     => 'media',
                        'url'      => true,
                        'compiler' => true,
                        'title'    => esc_html__( 'Popup Image', 'cruxstore' ),
                        'required' => array('enable_popup','equals', 1)
                    ),
                    array(
                        'id'       => 'content_popup',
                        'type'     => 'editor',
                        'title'    => esc_html__( 'Popup Content', 'cruxstore' ),
                        'subtitle' => esc_html__( '', 'cruxstore' ),
                        'required' => array('enable_popup','equals', 1),
                        'default'  => '<h3>NEWSLETTER</h3><p>Subscribe to the Universal mailing list to receive updates on new arrivals, offers and other discount information.</p>',
                    ),
                    array(
                        'id'       => 'popup_form',
                        'type'     => 'textarea',
                        'title'    => esc_html__( 'Popup form', 'cruxstore' ),
                        'desc'     => esc_html__( 'You can use shortcode or Embed code in here.', 'cruxstore' ),
                        'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                    ),
                )
            );
            
            /**
             *  Advanced
             **/
            $this->sections[] = array(
                'id'            => 'advanced',
                'title'         => esc_html__( 'Advanced', 'cruxstore' ),
                'desc'          => '',
                'icon'  => 'fa fa-cog',
            );

            /**
             *  Advanced Social Share
             **/
            $this->sections[] = array(
                'id'            => 'share_section',
                'title'         => esc_html__( 'Social Share', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'social_share',
                        'type'     => 'sortable',
                        'mode'     => 'checkbox', // checkbox or text
                        'title'    => esc_html__( 'Social Share', 'cruxstore' ),
                        'desc'     => esc_html__( 'Reorder and Enable/Disable Social Share Buttons.', 'cruxstore' ),
                        'options'  => array(
                            'facebook' => esc_html__('Facebook', 'cruxstore'),
                            'twitter' => esc_html__('Twitter', 'cruxstore'),
                            'google_plus' => esc_html__('Google+', 'cruxstore'),
                            'pinterest' => esc_html__('Pinterest', 'cruxstore'),
                            'linkedin' => esc_html__('Linkedin', 'cruxstore'),
                            'tumblr' => esc_html__('Tumblr', 'cruxstore'),
                            'mail' => esc_html__('Mail', 'cruxstore'),
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
                'title'         => esc_html__( 'Custom CSS', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'advanced_editor_css',
                        'type'     => 'ace_editor',
                        'title'    => esc_html__( 'CSS Code', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Paste your CSS code here.', 'cruxstore' ),
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
                'title'         => esc_html__( 'Custom JS', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'advanced_editor_js',
                        'type'     => 'ace_editor',
                        'title'    => esc_html__( 'JS Code', 'cruxstore' ),
                        'subtitle' => esc_html__( 'Paste your JS code here.', 'cruxstore' ),
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
                'title'         => esc_html__( 'Tracking Code', 'cruxstore' ),
                'desc'          => '',
                'subsection' => true,
                'fields'        => array(
                    array(
                        'id'       => 'advanced_tracking_code',
                        'type'     => 'textarea',
                        'title'    => esc_html__( 'Tracking Code', 'cruxstore' ),
                        'desc'     => esc_html__( 'Paste your Google Analytics (or other) tracking code here. This will be added into the header template of your theme. Please put code inside script tags.', 'cruxstore' ),
                    )
                )
            );

            $info_arr = array();
            $theme = wp_get_theme();

            $info_arr[] = "<li><span>".esc_html__('Theme Name:', 'cruxstore')." </span>". $theme->get('Name').'</li>';
            $info_arr[] = "<li><span>".esc_html__('Theme Version:', 'cruxstore')." </span>". $theme->get('Version').'</li>';
            $info_arr[] = "<li><span>".esc_html__('Theme URI:', 'cruxstore')." </span>". $theme->get('ThemeURI').'</li>';
            $info_arr[] = "<li><span>".esc_html__('Author:', 'cruxstore')." </span>". $theme->get('Author').'</li>';

            $system_info = sprintf("<div class='troubleshooting'><ul>%s</ul></div>", implode('', $info_arr));

            /**
             *  Advanced Troubleshooting
             **/
            $this->sections[] = array(
                'id'            => 'advanced_troubleshooting',
                'title'         => esc_html__( 'Troubleshooting', 'cruxstore' ),
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
    $reduxConfig = new CRUXSTORE_config();

} else {
    echo "The class named Redux_Framework_sample_config has already been called. <strong>Developers, you need to prefix this class with your company name or you'll run into problems!</strong>";
}

