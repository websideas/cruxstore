<?php
/**
 * All helpers for theme
 *
 */
 
 
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;




add_filter( 'rwmb_meta_boxes', 'delphinus_register_meta_boxes' );
function delphinus_register_meta_boxes( $meta_boxes )
{

    $prefix = '_delphinus_';
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
        'title'  => esc_html__('Testimonial Settings','delphinus'),
        'pages'  => array( 'del_testimonial' ),
        'fields' => array(
            array(
                'name' => esc_html__( 'Company Name / Job Title', 'delphinus' ),
                'id' => $prefix . 'testimonial_company',
                'desc' => esc_html__( "Please type the text for Company Name / Job Title here.", 'delphinus' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "URL to Author's Website", 'delphinus' ),
                'id' => $prefix . 'testimonial_link',
                'desc' => esc_html__( "Please type the text for link here.", 'delphinus' ),
                'type'  => 'text',
            )
        ),
    );

    /**
     * For Employees
     *
     */

    $meta_boxes[] = array(
        'title'  => esc_html__('Employees Settings','delphinus'),
        'pages'  => array( 'del_employees' ),
        'fields' => array(

            array(
                'name' => esc_html__( 'Employee Position', 'delphinus' ),
                'id' => $prefix . 'employee_position',
                'desc' => esc_html__( "Please enter team member's Position in the company.", 'delphinus' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Email Address", 'delphinus' ),
                'id' => $prefix . 'employee_email',
                'desc' => esc_html__( "Please enter team member's email address.", 'delphinus' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Facebook", 'delphinus' ),
                'id' => $prefix . 'employee_facebook',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'delphinus' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Twitter", 'delphinus' ),
                'id' => $prefix . 'employee_twitter',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'delphinus' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Google Plus", 'delphinus' ),
                'id' => $prefix . 'employee_googleplus',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'delphinus' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Linked In", 'delphinus' ),
                'id' => $prefix . 'employee_linkedin',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'delphinus' ),
                'type'  => 'text',
            ),
            array(
                'name' => esc_html__( "Instagram", 'delphinus' ),
                'id' => $prefix . 'employee_instagram',
                'desc' => esc_html__( "Please enter full URL of this social network(include http://).", 'delphinus' ),
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
            'label' => esc_html__( 'Layout', 'delphinus' ),
            'icon'  => 'fa fa-columns',
        ),
        /*'page_background' => array(
            'label' => esc_html__( 'Background', 'delphinus' ),
            'icon'  => 'fa fa-picture-o',
        )*/

    );

    $fields = array(



        //Page layout
        array(
            'name' => esc_html__('Type Page Options', 'delphinus'),
            'id'   => "{$prefix}type_page",
            'type' => 'select',
            'options' => array(
                'bullet' => esc_html__('Row Bullet', 'delphinus'),
                //'onepage' => esc_html__('One Page Navigation', 'delphinus'),
            ),
            'std'  => '1',
            'tab'  => 'page_layout',
            'placeholder' => esc_html__('Default', 'delphinus'),
            'desc' => esc_html__('Choose type of page display.', 'delphinus'),
        ),
        array(
            'name' => esc_html__('Sidebar configuration', 'delphinus'),
            'id' => $prefix . 'sidebar',
            'desc' => wp_kses( __("Choose the sidebar configuration for the detail page.<br/><b>Note: Cart and checkout, My account page always use no sidebars.</b>", 'delphinus'), array('br' => true, 'b' => true) ),
            'type' => 'select',
            'options' => array(
                'full' => esc_html__('No sidebars', 'delphinus'),
                'left' => esc_html__('Left Sidebar', 'delphinus'),
                'right' => esc_html__('Right Sidebar', 'delphinus')
            ),
            'placeholder' => esc_html__('Default', 'delphinus'),
            'tab'  => 'page_layout',
        ),
        array(
            'name' => esc_html__('Left sidebar', 'delphinus'),
            'id' => $prefix . 'left_sidebar',
            'type' => 'select',
            'tab'  => 'page_layout',
            'options' => $sidebars,
            'desc' => esc_html__("Select your sidebar.", 'delphinus'),
            'visible' => array($prefix . 'sidebar','=', 'left' ),
        ),
        array(
            'name' => esc_html__('Right sidebar', 'delphinus'),
            'id' => $prefix . 'right_sidebar',
            'type' => 'select',
            'tab'  => 'page_layout',
            'options' => $sidebars,
            'desc' => esc_html__("Select your sidebar.", 'delphinus'),
            'visible' => array($prefix . 'sidebar','=', 'right' ),
        ),
        array(
            'name' => esc_html__('Page top spacing', 'delphinus'),
            'id' => $prefix . 'page_top_spacing',
            'desc' => esc_html__("Enter your page top spacing (Example: 100px).", 'delphinus' ),
            'type'  => 'text',
            'tab'  => 'page_layout',
        ),
        array(
            'name' => esc_html__('Page bottom spacing', 'delphinus'),
            'id' => $prefix . 'page_bottom_spacing',
            'desc' => esc_html__("Enter your page bottom spacing (Example: 100px).", 'delphinus' ),
            'type'  => 'text',
            'tab'  => 'page_layout',
        ),
        array(
            'name' => esc_html__('Extra page class', 'delphinus'),
            'id' => $prefix . 'extra_page_class',
            'desc' => esc_html__('If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.', 'delphinus' ),
            'type'  => 'text',
            'tab'  => 'page_layout',
        ),
        /*
        array(
            'name' => esc_html__('Background', 'delphinus'),
            'id' => $prefix.'background_body',
            'type'  => 'background',
            'tab'  => 'page_background',
            'desc' => esc_html__('The option that will be used as the OUTER page.', 'delphinus' ),
        ),
        array(
            'name' => esc_html__('Inner Background', 'delphinus'),
            'id' => $prefix.'background_inner',
            'type'  => 'background',
            'tab'  => 'page_background',
            'desc' => esc_html__('The option that will be used as the INNER page.', 'delphinus' ),
        )
        */
    );



    $tabs_page = array(
        'header'  => array(
            'label' => esc_html__( 'Header', 'delphinus' ),
            'icon'  => 'fa fa-desktop',
        ),
        'page_footer'  => array(
            'label' => esc_html__( 'Footer', 'delphinus' ),
            'icon'  => 'fa fa-list-alt',
        ),
        'page_header' => array(
            'label' => esc_html__( 'Page Header', 'delphinus' ),
            'icon'  => 'fa fa-bars',
        ),
    );

    $fields_page = array(
        // Page Header
        array(

            'name' => esc_html__( 'Page Header', 'delphinus' ),
            'id' => $prefix . 'page_header',
            'desc' => esc_html__( "Show Page Header.", 'delphinus' ),
            'type' => 'select',
            'options' => array(
                'off'	    => esc_html__('Hidden', 'delphinus'),
                'on'		=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'delphinus'),
            'tab'  => 'page_header',
        ),
        array(
            'name' => esc_html__( 'Page Header Custom Text', 'delphinus' ),
            'id' => $prefix . 'page_header_custom',
            'desc' => esc_html__( "Enter cstom Text for page header.", 'delphinus' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header', '!=', 'off')
        ),
        array(
            'name' => esc_html__( 'Page header subtitle', 'delphinus' ),
            'id' => $prefix . 'page_header_subtitle',
            'desc' => esc_html__( "Enter subtitle for page.", 'delphinus' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header', '!=', 'off')
        ),
        array(
            'id'       => "{$prefix}page_header_layout",
            'type'     => 'select',
            'name'    => esc_html__( 'Page header layout', 'delphinus' ),
            'desc'     => esc_html__( 'Please select Page Header align', 'delphinus' ),
            'placeholder' => esc_html__('Default', 'delphinus'),
            'options'  => array(
                'sides' => esc_html__('Sides', 'delphinus'),
                'centered' => esc_html__('Centered', 'delphinus' ),
            ),
            'std'  => '',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),

        array(
            'id'       => "{$prefix}page_header_align",
            'type'     => 'select',
            'name'    => esc_html__( 'Page Header align', 'delphinus' ),
            'desc'     => esc_html__( 'Please select Page Header align', 'delphinus' ),
            'placeholder' => esc_html__('Default', 'delphinus'),
            'options'  => array(
                'left' => esc_html__('Left', 'delphinus' ),
                'center' => esc_html__('Center', 'delphinus'),
                'right' => esc_html__('Right', 'delphinus')
            ),
            'std'  => '',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),

        array(
            'name' => esc_html__('Page header breadcrumb', 'delphinus'),
            'id'   => "{$prefix}show_breadcrumb",
            'type' => 'select',
            'options' => array(
                0		=> esc_html__('Hidden', 'delphinus'),
                1		=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'delphinus'),
            'desc' => esc_html__( "Show page breadcrumb.", 'delphinus' ),
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),

        array(
            'type'  => 'divider',
            'tab'  => 'page_header',
        ),
        array(
            'name' => esc_html__('Page header top spacing', 'delphinus'),
            'id' => $prefix . 'page_header_top',
            'desc' => esc_html__("(Example: 60px). Emtpy for use default", 'delphinus' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__('Page header bottom spacing', 'delphinus'),
            'id' => $prefix . 'page_header_bottom',
            'desc' => esc_html__("(Example: 60px). Emtpy for use default", 'delphinus' ),
            'type'  => 'text',
            'tab'  => 'page_header',
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__('Page header Background', 'delphinus'),
            'id' => $prefix.'page_header_background',
            'type'  => 'background',
            'tab'  => 'page_header',
            'desc' => esc_html__('The option that will be used as the OUTER page.', 'delphinus' ),
        ),
        array(
            'type'  => 'divider',
            'tab'  => 'page_header',
        ),
        array(
            'name' => esc_html__( 'Typography title custom color', 'delphinus' ),
            'id'   => "{$prefix}page_header_title_color",
            'type' => 'color',
            'tab'  => 'page_header',
            'desc' => esc_html__( "Choose custom color for title.", 'delphinus' ),
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__( 'Typography sub title custom color', 'delphinus' ),
            'id'   => "{$prefix}page_header_subtitle_color",
            'type' => 'color',
            'tab'  => 'page_header',
            'desc' => esc_html__( "Choose custom color for sub title.", 'delphinus' ),
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),
        array(
            'name' => esc_html__( 'Typography breadcrumbs custom color', 'delphinus' ),
            'id'   => "{$prefix}page_header_breadcrumbs_color",
            'type' => 'color',
            'tab'  => 'page_header',
            'desc' => esc_html__( "Choose custom color for breadcrumbs.", 'delphinus' ),
            'visible' => array($prefix . 'page_header','!=', '0' ),
        ),

        // Header
        array(
            'name' => esc_html__('Main Navigation Location', 'delphinus'),
            'id'   => "{$prefix}header_main_menu",
            'type' => 'select',
            'options' => $menus_arr,
            'std'  => '',
            'tab'  => 'header',
            'placeholder' => esc_html__('Default', 'delphinus'),
            'desc' => esc_html__('Choose which menu location to be used in this page. If left blank, Primary Menu will be used.', 'delphinus'),
        ),

        array(
            'name'     => esc_html__( 'Header layout', 'delphinus' ),
            'type'     => 'image_radio',
            'id'       => $prefix.'header_layout',
            'desc'     => esc_html__( "Please choose header layout", 'delphinus' ),
            'options'  => array(
                0=> array( 'alt' => esc_html__('Default', 'delphinus'), 'img' => DELPHINUS_FW_IMG . 'header/header-default.jpg', ),
                1 => array( 'alt' => esc_html__( 'Layout 1', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v1.jpg' ),
                2 => array( 'alt' => esc_html__( 'Layout 2', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v2.jpg' ),
                3 => array( 'alt' => esc_html__( 'Layout 3', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v3.jpg' ),
                4 => array( 'alt' => esc_html__( 'Layout 4', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v4.jpg' ),
                //5 => array( 'alt' => esc_html__( 'Layout 5', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v5.jpg' ),
                6 => array( 'alt' => esc_html__( 'Layout 6', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v6.jpg' ),
                7 => array( 'alt' => esc_html__( 'Layout 7', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v7.jpg' ),
                8 => array( 'alt' => esc_html__( 'Layout 8', 'delphinus' ), 'img' => DELPHINUS_FW_IMG . 'header/header-v8.jpg' ),
            ),
            'std'  => '',
            'attributes' => '',
            'tab'  => 'header',
        ),



        array(
            'name'    => esc_html__( 'Header position', 'delphinus' ),
            'type'     => 'select',
            'id'       => $prefix.'header_position',
            'desc'     => esc_html__( "Please choose header position", 'delphinus' ),
            'options'  => array(
                'below' => esc_html__('Below Slideshow', 'delphinus'),
                'transparent' => esc_html__('Transparent', 'delphinus'),
            ),
            'placeholder' => esc_html__('Default', 'delphinus'),
            'std'  => 'default',
            'tab'  => 'header',
        ),

        array(
            'name' => esc_html__('Transparent header Color Scheme', 'delphinus'),
            'id'   => "{$prefix}header_scheme",
            'type' => 'select',
            'options' => array(
                'light'		=> esc_html__('Light', 'delphinus'),
            ),
            'placeholder' => esc_html__('Dark', 'delphinus'),
            'tab'  => 'header',
            'visible' => array($prefix . 'header_position', '=' , 'transparent' ),
            'desc'     => esc_html__( "Please choose transparent color scheme", 'delphinus' ),
        ),
        array(
            'name' => esc_html__('Select Your Slideshow Type', 'delphinus'),
            'id' => $prefix . 'slideshow_type',
            'desc' => esc_html__("You can select the slideshow type using this option.", 'delphinus'),
            'type' => 'select',
            'options' => array(
                'revslider' => esc_html__('Revolution Slider', 'delphinus'),
                'layerslider' => esc_html__('Layer Slider', 'delphinus'),
                'custom' => esc_html__('Custom Slider', 'delphinus'),
            ),
            'placeholder' => esc_html__('Select Option', 'delphinus'),
            'tab'  => 'header',
        ),
        array(
            'name' => esc_html__('Select Revolution Slider', 'delphinus'),
            'id' => $prefix . 'rev_slider',
            'default' => true,
            'type' => 'select',
            'tab'  => 'header',
            'desc' => esc_html__('Select the Revolution Slider.', 'delphinus'),
            'visible' => array($prefix . 'slideshow_type', '=', 'revslider'),
            'options' => $rev_options,
            'placeholder' => esc_html__('Select Option', 'delphinus'),
        ),
        array(
            'name' => esc_html__('Select Layer Slider', 'delphinus'),
            'id' => $prefix . 'layerslider',
            'default' => true,
            'type' => 'select',
            'tab'  => 'header',
            'desc' => esc_html__('Select the Layer Slider.', 'delphinus'),
            'visible' => array($prefix . 'slideshow_type', '=', 'layerslider'),
            'options' => $ls_options,
            'placeholder' => esc_html__('Select Option', 'delphinus'),
        ),
        array(
            'name'        => esc_html__( 'Custom Slider', 'delphinus' ),
            'id'          => $prefix.'slideshow_custom',
            'type'        => 'textarea',
            'tab'  => 'header',
            'desc' => esc_html__('Put your shortcode in here.', 'delphinus'),
            'visible' => array($prefix . 'slideshow_type', '=', 'custom'),
            'rows'        => 5,
        ),
        // Footer
        array(
            'name' => esc_html__( 'Footer Top', 'delphinus' ),
            'id' => $prefix . 'footer_top',
            'desc' => esc_html__( "Show Footer Top.", 'delphinus' ),
            'type' => 'select',
            'options' => array(
                'off'	    => esc_html__('Hidden', 'delphinus'),
                'on'		=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'delphinus'),
            'tab'  => 'page_footer',
        ),
        array(
            'name' => esc_html__( 'Footer widgets', 'delphinus' ),
            'id' => $prefix . 'footer_widgets',
            'desc' => esc_html__( "Show Footer widgets.", 'delphinus' ),
            'type' => 'select',
            'options' => array(
                'off'	    => esc_html__('Hidden', 'delphinus'),
                'on'		=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'placeholder' => esc_html__('Default', 'delphinus'),
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
        'pages' => array( 'del_client' ),
        'context' => 'normal',
        'priority' => 'default',
        'fields' => array(

            array(
                'name' => esc_html__( 'Link Client', 'delphinus' ),
                'id' => $prefix . 'link_client',
                'desc' => esc_html__( "Link Client.", 'delphinus' ),
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
        'title'     => esc_html__('Page Options', 'delphinus'),
        'pages'     => array( 'page' ),
        'tabs'      => array_merge( $tabs,$tabs_page),
        'fields'    => array_merge( $fields,$fields_page),
    );


    $tabs_post = array(
        'post_general'  => array(
            'label' => esc_html__( 'General', 'delphinus' ),
            'icon'  => 'fa fa-bars',
        ),
        'post_header'  => array(
            'label' => esc_html__( 'Header', 'delphinus' ),
            'icon'  => 'fa fa-desktop',
        ),

    );

    $fields_post = array(
        //General
        array(
            'name' => esc_html__('Previous & next buttons', 'delphinus'),
            'id'   => "{$prefix}prev_next",
            'type' => 'select',
            'options' => array(
                ''    => esc_html__('Default', 'delphinus'),
                'off'		=> esc_html__('Hidden', 'delphinus'),
                'on'		=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'delphinus')
        ),
        array(
            'name' => esc_html__('Author info', 'delphinus'),
            'id'   => "{$prefix}author_info",
            'type' => 'select',
            'options' => array(
                ''    => esc_html__('Default', 'delphinus'),
                'off'		=> esc_html__('Hidden', 'delphinus'),
                'on'		=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'delphinus')
        ),
        array(
            'name' => esc_html__('Social sharing', 'delphinus'),
            'id'   => "{$prefix}social_sharing",
            'type' => 'select',
            'options' => array(
                ''    => esc_html__('Default', 'delphinus'),
                'off'		=> esc_html__('Hidden', 'delphinus'),
                'on'		=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'delphinus')
        ),
        array(
            'name' => esc_html__('Related articles', 'delphinus'),
            'id'   => "{$prefix}related_acticles",
            'type' => 'select',
            'options' => array(
                ''      => esc_html__('Default', 'delphinus'),
                'off'    => esc_html__('Hidden', 'delphinus'),
                'on'	=> esc_html__('Show', 'delphinus'),
            ),
            'std'  => '',
            'tab'  => 'post_general',
            'desc' => esc_html__('Select "Default" to use settings in Theme Options', 'delphinus')
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
        'title'  => esc_html__('Product Settings','delphinus'),
        'pages'  => array( 'product' ),
        'fields' => array(
            //General
            array(
                'name' => esc_html__('Product layout', 'delphinus'),
                'id' => $prefix . 'detail_layout',
                'desc' => esc_html__("Choose the layout for the product detail display.", 'delphinus'),
                'type' => 'select',
                'placeholder' => esc_html__('Default', 'delphinus'),
                'options' => array(
                    'layout1' => esc_html__('Layout 1', 'delphinus'),
                    'layout2' => esc_html__('Layout 2', 'delphinus'),
                    'layout3' => esc_html__('Layout 3', 'delphinus'),
                ),
            ),
            array(
                'name'        => esc_html__( 'Short Description in List view', 'delphinus' ),
                'id'          => $prefix.'short_description',
                'desc'        => esc_html__( 'You can optionally write a short description here, which shows in List view (Product Archive).', 'delphinus' ),
                'type'        => 'textarea',
                'placeholder' => esc_html__( 'Empty if you want use Product Short Description', 'delphinus' ),
                'rows'        => 5,
            ),
            array(
                'id'               => $prefix .'image',
                'name'             => esc_html__( 'Image transparent', 'delphinus' ),
                'type'             => 'image_advanced',
                'max_file_uploads' => 1,
                'desc' => esc_html__("Select image for carousel featured. It's use for transparent style", 'delphinus'),
            ),
            array(
                'id'               => $prefix .'gallery',
                'name'             => esc_html__( 'Image Gallery', 'delphinus' ),
                'type'             => 'image_advanced',
                'max_file_uploads' => 1,
                'desc' => esc_html__("It's use for gallery style.", 'delphinus'),
            ),

            array(
                'name' => esc_html__('Disposition', 'delphinus'),
                'id' => $prefix . 'box_size',
                'desc' => esc_html__('Select disposition for Packery display', 'delphinus'),
                'type'     => 'select',
                'options'  => array(
                    'landscape' => esc_html__('Landscape (2x1)', 'delphinus'),
                    'portrait' => esc_html__('Portrait (1x2)', 'delphinus'),
                    'wide' => esc_html__('Wide (2x2)', 'delphinus'),
                    'big' => esc_html__('Big (3x2)', 'delphinus'),
                ),
                'std'  => 'normal'
            ),
        ),
    );
    
    
    return $meta_boxes;
}


