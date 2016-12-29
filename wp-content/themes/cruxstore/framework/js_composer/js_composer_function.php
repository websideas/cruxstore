<?php

/**
 * All functions for js composer
 *
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

function cruxstore_responsive_render($element, $type, $css){

    $output = '';

    $arr = explode(';', $css);
    if(count($arr)){
        foreach($arr as $item){
            if($item){
                $arr_i = explode(':', $item);
                if(count($arr_i) == 2 && $arr_i[1]){
                    $output .= cruxstore_breakpoint_css($element, $type, $arr_i[1], $arr_i[0]);
                }
            }
        }
    }
    return $output;
}


function cruxstore_breakpoint_css($element, $key, $style, $type = 'desktop'){

    $media = '';
    if($type == 'desktop'){
        $media = '@media (min-width: 992px) {%s}';
    }elseif($type == 'tablet'){
        $media = '@media (min-width: 768px) and (max-width: 991px) {%s}';
    }elseif($type == 'mobile'){
        $media = '@media (max-width: 767px) {%s}';
    }

    $css = sprintf('%s{%s: %s;}', $element, $key, $style);
    $output = '';

    if($media && $css){
        $output = sprintf($media, $css);
    }

    return $output;
}


function cruxstore_map_add_css_animation( $label = true ) {

    $data = array(
        'type' => 'dropdown',
        'heading' => __( 'CSS Animation', 'js_composer' ),
        'param_name' => 'css_animation',
        'admin_label' => $label,
        'value' => array(
            __( 'No', 'cruxstore' ) => '',
            __( 'fadeIn', 'cruxstore' ) => 'fadeIn',
            __( 'fadeInLeft', 'cruxstore' ) => 'fadeInLeft',
            __( 'fadeInRight', 'cruxstore' ) => 'fadeInRight',
            __( 'fadeInUp', 'cruxstore' ) => 'fadeInUp',
            __( 'fadeInDown', 'cruxstore' ) => 'fadeInDown',
            __( 'bounce', 'cruxstore' ) => 'bounce',
            __( 'flash', 'cruxstore' ) => 'flash',
            __( 'pulse', 'cruxstore' ) => 'pulse',
            __( 'shake', 'cruxstore' ) => 'shake',
            __( 'swing', 'cruxstore' ) => 'swing',
            __( 'tada', 'cruxstore' ) => 'tada',
            __( 'wobble', 'cruxstore' ) => 'wobble',
            __( 'bounceIn', 'cruxstore' ) => 'bounceIn',
            __( 'bounceInLeft', 'cruxstore' ) => 'bounceInLeft',
            __( 'bounceInRight', 'cruxstore' ) => 'bounceInRight',
            __( 'bounceInUp', 'cruxstore' ) => 'bounceInUp',
            __( 'bounceInDown', 'cruxstore' ) => 'bounceInDown',
        ),
        'description' => __( 'Select type of animation for element to be animated when it "enters" the browsers viewport (Note: works only in modern browsers).', 'js_composer' ),
    );

    return apply_filters( 'cruxstore_map_add_css_animation', $data, $label );
}

function cruxstore_map_add_css_animation_delay( $label = true ) {
    $vc_map_animation_delay = array(
        "type" => "cruxstore_number",
        'heading' => esc_html__( 'Animation Delay', 'cruxstore' ),
        "suffix" => esc_html__("milliseconds", 'cruxstore'),
        'param_name' => 'animation_delay',
        "admin_label" => true,
        'dependency' => array(
            'element' => 'css_animation',
            'not_empty' => true
        ),
    );

    return apply_filters( 'cruxstore_map_add_css_animation_delay', $vc_map_animation_delay );
}


function cruxstore_getCSSAnimation( $css_animation ) {
    $output = '';
    if ( '' !== $css_animation ) {
        $output = ' wow ' . $css_animation;
    }

    return $output;
}

function cruxstore_map_add_carousel_parrams( $selects = array('other', 'columns', 'navigation', 'pagination') ) {

    $carousel['other'] = array(
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Auto Height', 'cruxstore' ),
            'param_name' => 'autoheight',
            'value' => 'true',
            "edit_field_class" => "vc_col-sm-4 cruxstore_margin_bottom",
            "description" => esc_html__("Enable auto height.", 'cruxstore'),
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
        ),
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Mouse Drag', 'cruxstore' ),
            'param_name' => 'mousedrag',
            'value' => 'true',
            "description" => esc_html__("Mouse drag enabled.", 'cruxstore'),
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
            "edit_field_class" => "vc_col-sm-4 cruxstore_margin_bottom",
        ),
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'AutoPlay', 'cruxstore' ),
            'param_name' => 'autoplay',
            'value' => 'false',
            "description" => esc_html__("Enable auto play.", 'cruxstore'),
            "edit_field_class" => "vc_col-sm-4 cruxstore_margin_bottom",
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),
        array(
            "type" => "cruxstore_number",
            "heading" => esc_html__("AutoPlay Speed", 'cruxstore'),
            "param_name" => "autoplayspeed",
            "value" => "5000",
            "suffix" => esc_html__("milliseconds", 'cruxstore'),
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
            "dependency" => array("element" => "autoplay","value" => array('true')),
        ),
        array(
            "type" => "cruxstore_number",
            "heading" => esc_html__("Slide Speed", 'cruxstore'),
            "param_name" => "slidespeed",
            "value" => "200",
            "suffix" => esc_html__("milliseconds", 'cruxstore'),
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),

    );

    $carousel['columns'] = array(
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Items to Show?", 'cruxstore'),
            "param_name" => "items_show",
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
        ),
        array(
            "type" => "cruxstore_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 cruxstore_margin_bottom",
            "heading" => esc_html__("On Desktop", 'cruxstore'),
            "param_name" => "desktop",
            "value" => 4,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),

        array(
            'type' => 'cruxstore_number',
            'heading' => esc_html__( 'on Tablets Landscape', 'cruxstore' ),
            'param_name' => 'desktopsmall',
            "value" => 3,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            "edit_field_class" => "vc_col-sm-6 cruxstore_margin_bottom",
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),
        array(
            "type" => "cruxstore_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 cruxstore_margin_bottom",
            "heading" => esc_html__("On Tablet", 'cruxstore'),
            "param_name" => "tablet",
            "value" => 2,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),
        array(
            "type" => "cruxstore_number",
            "class" => "",
            "edit_field_class" => "vc_col-sm-6 cruxstore_margin_bottom",
            "heading" => esc_html__("On Mobile", 'cruxstore'),
            "param_name" => "mobile",
            "value" => 1,
            "min" => "1",
            "max" => "5",
            "step" => "1",
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),
    );

    $carousel['navigation'] = array(
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Navigation settings", 'cruxstore'),
            "param_name" => "navigation_settings",
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Navigation', 'cruxstore' ),
            'param_name' => 'navigation',
            'value' => 'true',
            "description" => esc_html__("Show navigation in carousel", 'cruxstore'),
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),

        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation position', 'cruxstore' ),
            'param_name' => 'navigation_position',
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
            'value' => array(
                esc_html__( 'Center outside', 'cruxstore') => 'center-outside',
                esc_html__( 'Center inside', 'cruxstore') => 'center',
                //esc_html__( 'Top', 'cruxstore') => 'top',
                esc_html__( 'Bottom', 'cruxstore') => 'bottom',
            ),
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Always Show Navigation', 'cruxstore' ),
            'param_name' => 'navigation_always_on',
            'value' => 'false',
            "description" => esc_html__("Always show the navigation.", 'cruxstore'),
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
            "dependency" => array("element" => "navigation_position","value" => array('center', 'center-outside')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Navigation style', 'js_composer' ),
            'param_name' => 'navigation_style',
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
            'value' => array(
                esc_html__( 'Normal', 'cruxstore' ) => 'normal',
                esc_html__( 'Normal light', 'cruxstore' ) => 'normal-light',
                esc_html__( 'Circle Background', 'cruxstore' ) => 'circle-background',
                esc_html__( 'Square Background', 'cruxstore' ) => 'square-background',
                esc_html__( 'Round Background', 'cruxstore' ) => 'round-background',
                esc_html__( 'Circle Border', 'cruxstore' ) => 'circle-border',
                esc_html__( 'Square Border', 'cruxstore' ) => 'square-border',
                esc_html__( 'Round Border', 'cruxstore' ) => 'round-border',
            ),
            'std' => 'normal',
            "dependency" => array("element" => "navigation","value" => array('true')),
        ),
    );

    $carousel['pagination'] = array(
        array(
            "type" => "cruxstore_heading",
            "heading" => esc_html__("Pagination settings", 'cruxstore'),
            "param_name" => "pagination_settings",
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),
        array(
            'type' => 'cruxstore_switch',
            'heading' => esc_html__( 'Pagination', 'cruxstore' ),
            'param_name' => 'pagination',
            'value' => 'false',
            "description" => esc_html__("Show pagination in carousel", 'cruxstore'),
            'group' => esc_html__( 'Carousel', 'cruxstore' )
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Pagination position', 'cruxstore' ),
            'param_name' => 'pagination_position',
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
            'value' => array(
                esc_html__( 'Center Top', 'cruxstore') => 'center-top',
                esc_html__( 'Center Bottom', 'cruxstore') => 'center-bottom',
                esc_html__( 'Bottom Left', 'cruxstore') => 'bottom-left',
            ),
            'std' => 'center_bottom',
            "dependency" => array("element" => "pagination","value" => array('true')),
        ),
        array(
            'type' => 'dropdown',
            'heading' => esc_html__( 'Pagination style', 'js_composer' ),
            'param_name' => 'pagination_style',
            'group' => esc_html__( 'Carousel', 'cruxstore' ),
            'value' => array(
                esc_html__( 'Dot stroke', 'cruxstore' ) => 'dot-stroke',
                esc_html__( 'Fill Up', 'cruxstore' ) => 'fill-up',
                esc_html__( 'Circle grow', 'cruxstore' ) => 'circle-grow',
                esc_html__( 'Flip', 'cruxstore' ) => 'flip',
            ),
            'std' => 'dot_stroke',
            "dependency" => array("element" => "pagination","value" => array('true')),
            "description" => esc_html__("Choose pagination style in carousel", 'cruxstore'),
        ),
    );


    if(count($selects)){
        $output = array();
        foreach($selects as $select){
            $output = array_merge($output, $carousel[$select]);
        };
        return $output;
    }
    return false;
}




function CruxStore_Icon_map($group = false, $prefix = '', $default = 'fa fa-adjust', $remove = false){

    if($remove){
        $shape_arr = array();
    }else{
        $shape_arr = array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Background shape', 'js_composer' ),
                'param_name' => 'background_style',
                'value' => array(
                    __( 'None', 'js_composer' ) => '',
                    __( 'Circle', 'js_composer' ) => 'rounded',
                    __( 'Square', 'js_composer' ) => 'boxed',
                    __( 'Rounded', 'js_composer' ) => 'rounded-less',
                    __( 'Outline Circle', 'js_composer' ) => 'rounded-outline',
                    __( 'Outline Square', 'js_composer' ) => 'boxed-outline',
                    __( 'Outline Rounded', 'js_composer' ) => 'rounded-less-outline',
                ),
                'description' => __( 'Select background shape and style for icon.', 'js_composer' ),
                'dependency' => array("element" => "type","value" => array('icon', 'svg')),
                "admin_label" => true,
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Background color', 'js_composer' ),
                'param_name' => 'background_color',
                'value' => array_merge( array( esc_html__( 'Accent color', 'cruxstore' ) => 'accent' ), getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
                'std' => 'grey',
                'description' => __( 'Select background color for icon.', 'js_composer' ),
                'param_holder_class' => 'vc_colored-dropdown',
                'dependency' => array(
                    'element' => 'background_style',
                    'not_empty' => true,
                ),
            ),
            array(
                'type' => 'colorpicker',
                'heading' => __( 'Custom background color', 'js_composer' ),
                'param_name' => 'custom_background_color',
                'description' => __( 'Select custom icon background color.', 'js_composer' ),
                'dependency' => array(
                    'element' => 'background_color',
                    'value' => 'custom',
                ),
            ),

            array(
                'type' => 'dropdown',
                'heading' => __( 'Background color on hover', 'js_composer' ),
                'param_name' => 'background_color_hover',
                'value' => array_merge(  array( esc_html__( 'Default', 'js_composer' ) => 'default' ), array( esc_html__( 'Accent color', 'cruxstore' ) => 'accent' ), getVcShared( 'colors' ), array( __( 'Custom color', 'js_composer' ) => 'custom' ) ),
                'std' => '',
                'description' => __( 'Select background color for icon.', 'js_composer' ),
                'param_holder_class' => 'vc_colored-dropdown',
                'dependency' => array(
                    'element' => 'background_style',
                    'not_empty' => true,
                ),
            ),
            array(
                'type' => 'colorpicker',
                'heading' => __( 'Custom background color on hover', 'js_composer' ),
                'param_name' => 'custom_background_color_hover',
                'description' => __( 'Select custom icon background color.', 'js_composer' ),
                'dependency' => array(
                    'element' => 'background_color_hover',
                    'value' => 'custom',
                ),
            ),
        );
    }
    $arr = array_merge(
        array(
            //Icon settings
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Icon to display', 'cruxstore' ),
                'param_name' => 'type',
                'value' => array(
                    esc_html__( 'Font Icon', 'cruxstore' ) => 'icon',
                    esc_html__( 'Svg Icon', 'cruxstore' ) => 'svg',
                    esc_html__( 'Image Icon', 'cruxstore' ) => 'image',
                ),
                'description' => esc_html__( 'Select your layout.', 'cruxstore' ),
                "admin_label" => true,
            ),
            array(
                'type' => 'attach_image',
                'heading' => esc_html__( 'Image Thumbnail', 'cruxstore' ),
                'param_name' => 'image',
                'dependency' => array( 'element' => 'type',  'value' => array( 'image' ) ),
                'description' => esc_html__( 'Select image from media library.', 'js_composer' ),
            ),
            array(
                'type' => 'textarea_raw_html',
                'heading' => __( 'SVG HTML', 'js_composer' ),
                'param_name' => 'svg',
                'description' => __( 'Enter your SVG content.', 'cruxstore' ),
                'dependency' => array( 'element' => 'type',  'value' => array( 'svg' ) ),
                'value' => base64_encode( '' ),
            ),
            array(
                "type" => "cruxstore_icons",
                'heading' => esc_html__( 'Choose your icon', 'js_composer' ),
                'param_name' => 'icon',
                "value" => $default,
                'description' => esc_html__( 'Use existing font icon or upload a custom image.', 'cruxstore' ),
                'dependency' => array("element" => "type","value" => array('icon')),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Icon color', 'js_composer' ),
                'param_name' => 'color',
                'value' => array_merge( array( esc_html__( 'Default', 'js_composer' ) => 'default' ), array( esc_html__( 'Accent color', 'cruxstore' ) => 'accent' ),  getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'js_composer' ) => 'custom' ) ),
                'description' => esc_html__( 'Select icon color.', 'js_composer' ),
                'param_holder_class' => 'vc_colored-dropdown',
                'dependency' => array("element" => "type","value" => array('icon', 'svg')),
                "admin_label" => true,
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__( 'Custom Icon Color', 'js_composer' ),
                'param_name' => 'custom_color',
                'description' => esc_html__( 'Select custom icon color.', 'js_composer' ),
                'dependency' => array(
                    'element' => 'color',
                    'value' => 'custom',
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Icon color on Hover', 'js_composer' ),
                'param_name' => 'color_hover',
                'value' => array_merge( array( esc_html__( 'Default', 'js_composer' ) => 'default' ), array( esc_html__( 'Accent color', 'cruxstore' ) => 'accent' ), getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'js_composer' ) => 'custom' ) ),
                'description' => esc_html__( 'Select icon color hover.', 'js_composer' ),
                'param_holder_class' => 'vc_colored-dropdown',
                'dependency' => array("element" => "type","value" => array('icon', 'svg')),
                "admin_label" => true,
            ),
            array(
                'type' => 'colorpicker',
                'heading' => esc_html__( 'Custom Icon Color on hover', 'js_composer' ),
                'param_name' => 'custom_color_hover',
                'description' => esc_html__( 'Select custom icon color hover.', 'js_composer' ),
                'dependency' => array(
                    'element' => 'color_hover',
                    'value' => 'custom',
                ),
            ),
        ),
        $shape_arr,
        array(
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Size', 'js_composer' ),
                'param_name' => 'size',
                'value' => array_merge( getVcShared( 'sizes' ), array( 'Extra Large' => 'xl' ) ),
                'std' => 'md',
                'description' => esc_html__( 'Icon size.', 'js_composer' ),
                "admin_label" => true,
            ),
        )
    );


    if($group){
        foreach($arr as &$item){
            $item['group'] = esc_html__( 'Icon', 'cruxstore' );
            if($prefix){
                $item['param_name'] = $prefix.$item['param_name'];
                if(isset($item['dependency'])){
                    $item['dependency']['element'] = $prefix.$item['dependency']['element'];
                }
            }
        }
    }

    return $arr;
}


