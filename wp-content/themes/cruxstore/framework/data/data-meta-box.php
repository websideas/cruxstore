<?php
/**
 * All helpers for theme
 *
 */
 
 
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;




add_filter( 'rwmb_meta_boxes', 'cruxstore_register_meta_boxes' );
function cruxstore_register_meta_boxes( $meta_boxes )
{

    $prefix = '_cruxstore_';
    $menus = wp_get_nav_menus();

    $menus_arr = array();
    foreach ( $menus as $menu ) {
        $menus_arr[$menu->term_id] = esc_html( $menu->name );
    }

    $rev_options = array();

    if ( class_exists( 'RevSlider' ) ) {
        $revSlider = new RevSlider();
        $arrSliders = $revSlider->getArrSliders();

        if(!empty($arrSliders)){
            foreach($arrSliders as $slider){
                $rev_options[$slider->getParam("alias")] = $slider->getParam("title");
            }
        }
    }

    $ls_options = array();
    if ( is_plugin_active( 'LayerSlider/layerslider.php' ) ) {
        global $wpdb;
        $table_name = $wpdb->prefix . "layerslider";
        $sliders = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC LIMIT 100" );
        if ( $sliders != null && !empty( $sliders ) ) {
            foreach ( $sliders as $item ) :
                $ls_options[$item->id] = $item->name;
            endforeach;
        }
    }

    /**
     * For Testimonial
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Testimonial Settings','cruxstore'),
        'pages'  => array( 'crux_testimonial' ),
        'fields' => array(
            array(
                'name' => esc_html__( 'Company Name / Job Title', 'cruxstore' ),
                'id' => $prefix . 'testimonial_company',
                'desc' => esc_html__( "Please type the text for Company Name / Job Title here.", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "URL to Author's Website", 'cruxstore' ),
                'id' => $prefix . 'testimonial_link',
                'desc' => esc_html__( "Please type the text for link here.", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__('Rate', 'cruxstore'),
                'id'   => "{$prefix}testimonial_rate",
                'type' => 'select',
                'options' => array(
                    '0'    => esc_html__('Choose star', 'cruxstore'),
                    '1'   => esc_html__('1', 'cruxstore'),
                    '2'   => esc_html__('2', 'cruxstore'),
                    '3'   => esc_html__('3', 'cruxstore'),
                    '4'   => esc_html__('4', 'cruxstore'),
                    '5'   => esc_html__('5', 'cruxstore'),
                ),
                'std'  => '0'
            ),
        ),
    );

    /**
     * For Employees
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Employees Settings','cruxstore'),
        'pages'  => array( 'crux_employees' ),
        'fields' => array(

            array(
                'name' => esc_html__( 'Employee Position', 'cruxstore' ),
                'id' => $prefix . 'employee_position',
                'desc' => esc_html__( "Please enter team member's Position in the company.", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Email Address", 'cruxstore' ),
                'id' => $prefix . 'employee_email',
                'desc' => esc_html__( "Please enter team member's email address.", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Facebook", 'cruxstore' ),
                'id' => $prefix . 'employee_facebook',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Twitter", 'cruxstore' ),
                'id' => $prefix . 'employee_twitter',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Google Plus", 'cruxstore' ),
                'id' => $prefix . 'employee_googleplus',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Linked In", 'cruxstore' ),
                'id' => $prefix . 'employee_linkedin',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Instagram", 'cruxstore' ),
                'id' => $prefix . 'employee_instagram',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'cruxstore' ),
                'type'  => 'text',
            )
        ),
    );



    $sidebars = array();

    foreach($GLOBALS['wp_registered_sidebars'] as $sidebar){
        $sidebars[$sidebar['id']] = ucwords( $sidebar['name'] );
    }


    $tabs = array(
        'page_layout' => array(
            'label' => esc_html__( 'Layout', 'cruxstore' ),
            'icon'  => 'fa fa-columns',
        ),
        /*'page_background' => array(
            'label' => esc_html__( 'Background', 'cruxstore' ),
            'icon'  => 'fa fa-picture-o',
        )*/

    );

    $fields = array(



        //Page layout
        array(
            'name' => esc_html__('Type Page Options', 'cruxstore'),
            'id'   => "{$prefix}type_page",
            'type' => 'select',
            'options' => array(
                'bullet' => esc_html__('Row Bullet', 'cruxstore'),
                //'onepage' => esc_html__('One Page Navigation', 'cruxstore'),
            ),
            'std'  => '1',
            'tab'  => 'page_layout',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'desc' => esc_html__('Choose type of page display.', 'cruxstore'),
        ),
        array(
            'name' => esc_html__('Sidebar configuration', 'cruxstore'),
            'id' => $prefix . 'sidebar',
            'desc' => wp_kses( __("Choose the sidebar configuration for the detail page.<br/><b>Note: Cart and checkout, My account page always use no sidebars.</b>", 'cruxstore'), array('br' => true, 'b' => true) ),
            'type' => 'select',
            'options' => array(
                'full' => esc_html__('No sidebars', 'cruxstore'),
                'left' => esc_html__('Left Sidebar', 'cruxstore'),
                'right' => esc_html__('Right Sidebar', 'cruxstore')
            ),
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'tab'  => 'page_layout',
        ),
        array(
            'name' => esc_html__('Left sidebar', 'cruxstore'),
            'id' => $prefix . 'left_sidebar',
            'type' => 'select',
            'tab'  => 'page_layout',
            'options' => $sidebars,
            'desc' => esc_html__("Select your sidebar.", 'cruxstore'),
            'visible' => array($prefix . 'sidebar','=', 'left' ),
        ),
        array(
            'name' => esc_html__('Right sidebar', 'cruxstore'),
            'id' => $prefix . 'right_sidebar',
            'type' => 'select',
            'tab'  => 'page_layout',
            'options' => $sidebars,
            'desc' => esc_html__("Select your sidebar.", 'cruxstore'),
            'visible' => array($prefix . 'sidebar','=', 'right' ),
        ),
        array(
            'name' => esc_html__('Page top spacing', 'cruxstore'),
            'id' => $prefix . 'page_top_spacing',
            'desc' => esc_html__("Enter your page top spacing (Example: 100px).", 'cruxstore' ),
            'type'  => 'text',
            'tab'  => 'page_layout',
        ),
        array(
            'name' => esc_html__('Page bottom spacing', 'cruxstore'),
            'id' => $prefix . 'page_bottom_spacing',
            'desc' => esc_html__("Enter your page bottom spacing (Example: 100px).", 'cruxstore' ),
            'type'  => 'text',
            'tab'  => 'page_layout',
        ),
        array(
            'name' => esc_html__('Extra page class', 'cruxstore'),
            'id' => $prefix . 'extra_page_class',
            'desc' => esc_html__('If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.', 'cruxstore' ),
            'type'  => 'text',
            'tab'  => 'page_layout',
        ),
        /*
        array(
            'name' => esc_html__('Background', 'cruxstore'),
            'id' => $prefix.'background_body',
            'type'  => 'background',
            'tab'  => 'page_background',
            'desc' => esc_html__('The option that will be used as the OUTER page.', 'cruxstore' ),
        ),
        array(
            'name' => esc_html__('Inner Background', 'cruxstore'),
            'id' => $prefix.'background_inner',
            'type'  => 'background',
            'tab'  => 'page_background',
            'desc' => esc_html__('The option that will be used as the INNER page.', 'cruxstore' ),
        )
        */
    );



    $tabs_page = array(
        'header'  => array(
            'label' => esc_html__( 'Header', 'cruxstore' ),
            'icon'  => 'fa fa-desktop',
        ),
        'slider'  => array(
            'label' => esc_html__( 'Slider', 'cruxstore' ),
            'icon'  => 'fa fa-picture-o',
        ),
        'page_footer'  => array(
            'label' => esc_html__( 'Footer', 'cruxstore' ),
            'icon'  => 'fa fa-list-alt',
        ),
        'page_header' => array(
            'label' => esc_html__( 'Page Header', 'cruxstore' ),
            'icon'  => 'fa fa-bars',
        ),
    );

    $fields_page = array(
        // Page Header
        array(

            'name' => esc_html__( 'Page Header', 'cruxstore' ),
            'id' => $prefix . 'page_header',
            'desc' => esc_html__( "Show Page Header.", 'cruxstore' ),
            'type' => 'select',
            'options' => array(
                'off'	    => esc_html__('Hidden', 'cruxstore'),
                'on'		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'tab'  => 'page_header',
        ),
        array(
            'name' => esc_html__( 'Page Header Custom Text', 'cruxstore' ),
            'id' => $prefix . 'page_header_custom',
            'desc' => esc_html__( "Enter cstom Text for page header.", 'cruxstore' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header', '!=', 'off')
        ),
        array(
            'name' => esc_html__( 'Page header subtitle', 'cruxstore' ),
            'id' => $prefix . 'page_header_subtitle',
            'desc' => esc_html__( "Enter subtitle for page.", 'cruxstore' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header', '!=', 'off')
        ),
        array(
            'id'       => "{$prefix}page_header_layout",
            'type'     => 'select',
            'name'    => esc_html__( 'Page header layout', 'cruxstore' ),
            'desc'     => esc_html__( 'Please select Page Header align', 'cruxstore' ),
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'options'  => array(
                'sides' => esc_html__('Sides', 'cruxstore'),
                'centered' => esc_html__('Centered', 'cruxstore' ),
            ),
            'std'  => '',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),

        array(
            'id'       => "{$prefix}page_header_align",
            'type'     => 'select',
            'name'    => esc_html__( 'Page Header align', 'cruxstore' ),
            'desc'     => esc_html__( 'Please select Page Header align', 'cruxstore' ),
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'options'  => array(
                'left' => esc_html__('Left', 'cruxstore' ),
                'center' => esc_html__('Center', 'cruxstore'),
                'right' => esc_html__('Right', 'cruxstore')
            ),
            'std'  => '',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__('Page header title', 'cruxstore'),
            'id'   => "{$prefix}show_title",
            'type' => 'select',
            'options' => array(
                0		=> esc_html__('Hidden', 'cruxstore'),
                1		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'desc' => esc_html__( "Show page title.", 'cruxstore' ),
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__('Page header breadcrumb', 'cruxstore'),
            'id'   => "{$prefix}show_breadcrumb",
            'type' => 'select',
            'options' => array(
                0		=> esc_html__('Hidden', 'cruxstore'),
                1		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'desc' => esc_html__( "Show page breadcrumb.", 'cruxstore' ),
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),

        array(
            'type'  => 'divider',
            'tab'  => 'page_header',
        ),
        array(
            'name' => esc_html__('Page header top spacing', 'cruxstore'),
            'id' => $prefix . 'page_header_top',
            'desc' => esc_html__("(Example: 60px). Emtpy for use default", 'cruxstore' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__('Page header bottom spacing', 'cruxstore'),
            'id' => $prefix . 'page_header_bottom',
            'desc' => esc_html__("(Example: 60px). Emtpy for use default", 'cruxstore' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__('Page header Background', 'cruxstore'),
            'id' => $prefix.'page_header_background',
            'type'  => 'background',
            'tab'  => 'page_header',
            'desc' => esc_html__('The option that will be used as the OUTER page.', 'cruxstore' ),
        ),
        array(
            'type'  => 'divider',
            'tab'  => 'page_header',
        ),
        array(
            'name' => esc_html__( 'Typography title custom color', 'cruxstore' ),
            'id'   => "{$prefix}page_header_title_color",
            'type' => 'color',
            'tab'  => 'page_header',
            'desc' => esc_html__( "Choose custom color for title.", 'cruxstore' ),
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__( 'Typography sub title custom color', 'cruxstore' ),
            'id'   => "{$prefix}page_header_subtitle_color",
            'type' => 'color',
            'tab'  => 'page_header',
            'desc' => esc_html__( "Choose custom color for sub title.", 'cruxstore' ),
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__( 'Typography breadcrumbs custom color', 'cruxstore' ),
            'id'   => "{$prefix}page_header_breadcrumbs_color",
            'type' => 'color',
            'tab'  => 'page_header',
            'desc' => esc_html__( "Choose custom color for breadcrumbs.", 'cruxstore' ),
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),

        // Header
        array(
            'name' => esc_html__('Main Navigation Location', 'cruxstore'),
            'id'   => "{$prefix}header_main_menu",
            'type' => 'select',
            'options' => $menus_arr,
            'std'  => '',
            'tab'  => 'header',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'desc' => esc_html__('Choose which menu location to be used in this page. If left blank, Primary Menu will be used.', 'cruxstore'),
        ),

        array(
            'name'     => esc_html__( 'Header layout', 'cruxstore' ),
            'type'     => 'image_radio',
            'id'       => $prefix.'header_layout',
            'desc'     => esc_html__( "Please choose header layout", 'cruxstore' ),
            'options'  => array(
                0=> array( 'alt' => esc_html__('Default', 'cruxstore'), 'img' => CRUXSTORE_FW_IMG . 'header/header-default.jpg', ),
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
            'std'  => '',
            'attributes' => '',
            'tab'  => 'header',
        ),



        array(
            'name'    => esc_html__( 'Header position', 'cruxstore' ),
            'type'     => 'select',
            'id'       => $prefix.'header_position',
            'desc'     => esc_html__( "Please choose header position", 'cruxstore' ),
            'options'  => array(
                'below' => esc_html__('Below Slideshow', 'cruxstore'),
                'transparent' => esc_html__('Transparent', 'cruxstore'),
            ),
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'std'  => 'default',
            'tab'  => 'header',
        ),

        array(
            'name' => esc_html__('Transparent header Color Scheme', 'cruxstore'),
            'id'   => "{$prefix}header_scheme",
            'type' => 'select',
            'options' => array(
                'light'		=> esc_html__('Light', 'cruxstore'),
            ),
            'placeholder' => esc_html__('Dark', 'cruxstore'),
            'tab'  => 'header',
            'visible' => array($prefix . 'header_position', '=' , 'transparent' ),
            'desc'     => esc_html__( "Please choose transparent color scheme", 'cruxstore' ),
        ),
        array(
            'name' => esc_html__('Select Your Slideshow Type', 'cruxstore'),
            'id' => $prefix . 'slideshow_type',
            'desc' => esc_html__("You can select the slideshow type using this option.", 'cruxstore'),
            'type' => 'select',
            'options' => array(
                'revslider' => esc_html__('Revolution Slider', 'cruxstore'),
                'layerslider' => esc_html__('Layer Slider', 'cruxstore'),
                'search' => esc_html__('Revolution Slider + Product Search', 'cruxstore'),
                'custom' => esc_html__('Custom Slider', 'cruxstore'),
            ),
            'placeholder' => esc_html__('Select Option', 'cruxstore'),
            'tab'  => 'slider',
        ),
        array(
            'name' => esc_html__('Select Revolution Slider', 'cruxstore'),
            'id' => $prefix . 'rev_slider',
            'default' => true,
            'type' => 'select',
            'tab'  => 'slider',
            'desc' => esc_html__('Select the Revolution Slider.', 'cruxstore'),
            'visible' => array($prefix . 'slideshow_type', 'in', array('revslider', 'search')),
            'options' => $rev_options,
            'placeholder' => esc_html__('Select Option', 'cruxstore'),
        ),
        array(
            'name' => esc_html__('Select Layer Slider', 'cruxstore'),
            'id' => $prefix . 'layerslider',
            'default' => true,
            'type' => 'select',
            'tab'  => 'slider',
            'desc' => esc_html__('Select the Layer Slider.', 'cruxstore'),
            'visible' => array($prefix . 'slideshow_type', '=', 'layerslider'),
            'options' => $ls_options,
            'placeholder' => esc_html__('Select Option', 'cruxstore'),
        ),
        array(
            'name'        => esc_html__( 'Custom Slider', 'cruxstore' ),
            'id'          => $prefix.'slideshow_custom',
            'type'        => 'textarea',
            'tab'  => 'slider',
            'desc' => esc_html__('Put your shortcode in here.', 'cruxstore'),
            'visible' => array($prefix . 'slideshow_type', '=', 'custom'),
            'rows'        => 5,
        ),
        // Footer
        array(
            'name' => esc_html__( 'Footer Top', 'cruxstore' ),
            'id' => $prefix . 'footer_top',
            'desc' => esc_html__( "Show Footer Top.", 'cruxstore' ),
            'type' => 'select',
            'options' => array(
                'off'	    => esc_html__('Hidden', 'cruxstore'),
                'on'		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'tab'  => 'page_footer',
        ),
        array(
            'name' => esc_html__( 'Footer Instagram', 'cruxstore' ),
            'id' => $prefix . 'footer_instagram',
            'desc' => esc_html__( "Show Footer Instagram.", 'cruxstore' ),
            'type' => 'select',
            'options' => array(
                'off'	    => esc_html__('Hidden', 'cruxstore'),
                'on'		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'tab'  => 'page_footer',
        ),
        array(
            'name' => esc_html__( 'Footer widgets', 'cruxstore' ),
            'id' => $prefix . 'footer_widgets',
            'desc' => esc_html__( "Show Footer widgets.", 'cruxstore' ),
            'type' => 'select',
            'options' => array(
                'off'	    => esc_html__('Hidden', 'cruxstore'),
                'on'		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'cruxstore'),
            'tab'  => 'page_footer',
        ),
    );

    /**
     * For Client
     *
     */

    $meta_boxes[] = array(
        'id' => 'client_meta_boxes',
        'title' => 'Client Options',
        'pages' => array( 'crux_client' ),
        'context' => 'normal',
        'priority' => 'default',
        'fields' => array(

            array(
                'name' => esc_html__( 'Link Client', 'cruxstore' ),
                'id' => $prefix . 'link_client',
                'desc' => esc_html__( "Link Client.", 'cruxstore' ),
                'type'  => 'text',
            ),

        )
    );

    /**
     * For Page Options
     *
     */
    $meta_boxes[] = array(
        'id'        => 'page_meta_boxes',
        'title'     => esc_html__('Page Options', 'cruxstore'),
        'pages'     => array( 'page' ),
        'tabs'      => array_merge( $tabs,$tabs_page),
        'fields'    => array_merge( $fields,$fields_page),
    );


    $tabs_post = array(
        'post_general'  => array(
            'label' => esc_html__( 'General', 'cruxstore' ),
            'icon'  => 'fa fa-bars',
        ),
        'post_header'  => array(
            'label' => esc_html__( 'Header', 'cruxstore' ),
            'icon'  => 'fa fa-desktop',
        ),

    );

    $fields_post = array(
        //General
        array(
            'name' => esc_html__('Previous & next buttons', 'cruxstore'),
            'id'   => "{$prefix}prev_next",
            'type' => 'select',
            'options' => array(
                ''    => esc_html__('Default', 'cruxstore'),
                'off'		=> esc_html__('Hidden', 'cruxstore'),
                'on'		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'cruxstore')
        ),
        array(
            'name' => esc_html__('Author info', 'cruxstore'),
            'id'   => "{$prefix}author_info",
            'type' => 'select',
            'options' => array(
                ''    => esc_html__('Default', 'cruxstore'),
                'off'		=> esc_html__('Hidden', 'cruxstore'),
                'on'		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'cruxstore')
        ),
        array(
            'name' => esc_html__('Social sharing', 'cruxstore'),
            'id'   => "{$prefix}social_sharing",
            'type' => 'select',
            'options' => array(
                ''    => esc_html__('Default', 'cruxstore'),
                'off'		=> esc_html__('Hidden', 'cruxstore'),
                'on'		=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'cruxstore')
        ),
        array(
            'name' => esc_html__('Related articles', 'cruxstore'),
            'id'   => "{$prefix}related_acticles",
            'type' => 'select',
            'options' => array(
                ''      => esc_html__('Default', 'cruxstore'),
                'off'    => esc_html__('Hidden', 'cruxstore'),
                'on'	=> esc_html__('Show', 'cruxstore'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'cruxstore')
        ),

    );

    /**
     * For Posts Options
     *
     */
    $meta_boxes[] = array(
        'id' => 'post_meta_boxes',
        'title' => 'Post Options',
        'pages' => array('post'),
        'tabs'      => array_merge( $tabs_post, $tabs ),
        'fields'    => array_merge( $fields_post, $fields),
    );


    /**
     * Product Settings
     *
     */
    $meta_boxes[] = array(
        'title'  => esc_html__('Product Settings','cruxstore'),
        'pages'  => array( 'product' ),
        'fields' => array(
            //General
            array(
                'name' => esc_html__('Product layout', 'cruxstore'),
                'id' => $prefix . 'detail_layout',
                'desc' => esc_html__("Choose the layout for the product detail display.", 'cruxstore'),
                'type' => 'select',
                'placeholder' => esc_html__('Default', 'cruxstore'),
                'options' => array(
                    'layout1' => esc_html__('Layout 1', 'cruxstore'),
                    'layout2' => esc_html__('Layout 2', 'cruxstore'),
                    'layout3' => esc_html__('Layout 3', 'cruxstore'),
                    'layout4' => esc_html__('Layout 4', 'cruxstore'),
                    'layout5' => esc_html__('Layout 5', 'cruxstore'),
                    'layout6' => esc_html__('Layout 6', 'cruxstore'),
                ),
            ),
            array(
                'name'        => esc_html__( 'Short Description in List view', 'cruxstore' ),
                'id'          => $prefix.'short_description',
                'desc'        => esc_html__( 'You can optionally write a short description here, which shows in List view (Product Archive).', 'cruxstore' ),
                'type'        => 'textarea',
                'placeholder' => esc_html__( 'Empty if you want use Product Short Description', 'cruxstore' ),
                'rows'        => 5,
            ),
            array(
                'name' => esc_html__('Video Link', 'cruxstore'),
                'id' => $prefix . 'video',
                'desc' => esc_html__("Enter your link of video ( Youtube, Vimeo).", 'cruxstore' ),
                'type'  => 'text',
            ),
            array(
                'id'               => $prefix .'image',
                'name'             => esc_html__( 'Image transparent', 'cruxstore' ),
                'type'             => 'image_advanced',
                'max_file_uploads' => 1,
                'desc' => esc_html__("Select image for carousel featured. It's use for transparent style", 'cruxstore'),
            ),
            array(
                'id'               => $prefix .'gallery',
                'name'             => esc_html__( 'Image Gallery', 'cruxstore' ),
                'type'             => 'image_advanced',
                'max_file_uploads' => 1,
                'desc' => esc_html__("It's use for gallery style.", 'cruxstore'),
            ),

            array(
                'name' => esc_html__('Disposition', 'cruxstore'),
                'id' => $prefix . 'box_size',
                'desc' => esc_html__('Select disposition for Packery display', 'cruxstore'),
                'type'     => 'select',
                'options'  => array(
                    'landscape' => esc_html__('Landscape (2x1)', 'cruxstore'),
                    'portrait' => esc_html__('Portrait (1x2)', 'cruxstore'),
                    'wide' => esc_html__('Wide (2x2)', 'cruxstore'),
                    'big' => esc_html__('Big (3x2)', 'cruxstore'),
                ),
                'std'  => 'normal'
            ),
        ),
    );
    
    
    return $meta_boxes;
}


