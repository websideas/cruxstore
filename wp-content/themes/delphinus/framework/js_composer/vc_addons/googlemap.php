<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

class WPBakeryShortCode_Googlemap extends WPBakeryShortCode {
    protected function content($atts, $content = null) {
        extract( shortcode_atts( array(
            'location' => '',
            'center' => '',
            'image' => '',
            'zoom' => '17',
            'height' => '300px',
            'type' => 'roadmap',
            'stype' => '',
            'scrollwheel' => '',
            'el_class' => '',
            'css' => '',
        ), $atts ) );

        if(!$location){return false;}

        $protocol = is_ssl() ? 'https' : 'http';
        wp_enqueue_script('google-maps-api',$protocol.'://maps.googleapis.com/maps/api/js?key=AIzaSyD9zfKFldzL98wnaWySSPYd07ylcQk3sts&callback=init_google_map', array( ), null, true);

        $img_id = preg_replace('/[^\d]/', '', $image);
        $img_thumb = '';
        if( $img_id ){
            $img_array = wp_get_attachment_image_src($img_id,'full');
            $img_thumb = $img_array[0];
        }

        $elementClass = array(
            'extra' => $this->getExtraClass( $el_class ),
            'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
            'size' => 'wrapper-googlemap',
        );
        $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );

        $location = explode("\n", strip_tags(trim($location)));
        $location = implode("||", $location);

        $output = '<div class="googlemap" data-style="'.esc_attr($stype).'" data-center="'.esc_attr($center).'" data-iconmap="'.esc_attr($img_thumb).'" data-type="'.esc_attr($type).'" data-scrollwheel="'.esc_attr($scrollwheel).'" data-location="'.esc_attr($location).'" data-zoom="'.esc_attr($zoom).'" style="height:'.$height.'"></div>';
        
        return '<div class=" '.$elementClass.'">'.$output.'</div>';
    }    
}

vc_map( array(
    "name" => esc_html__( "KT: Google map", 'delphinus'),
    "base" => "googlemap",
    "category" => esc_html__('by Kite-Themes', 'delphinus' ),
    "description" => esc_html__( "", 'delphinus'),
    "params" => array(
        array(
          "type" => "textfield",
          "heading" => esc_html__("Height", 'delphinus'),
          "param_name" => "height",
          "value" => '300px',
          "description" => esc_html__("Enter height of map,units :'px',Leave empty to use '300px'.", 'delphinus')
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__("Center Map", 'delphinus'),
            "param_name" => "center",
            "admin_label" => true,
            "description" => esc_html__("Enter Center Map", 'delphinus')
        ),
        array(
          "type" => "textarea",
          "heading" => esc_html__("Location", 'delphinus'),
          "param_name" => "location",
          "admin_label" => true,
          "description" => esc_html__("Enter location", 'delphinus')
        ),
        array(
            "type" => "dropdown",
        	"heading" => esc_html__("Map type",'delphinus'),
        	"param_name" => "type",
            'std' => 'ROADMAP',
        	"value" => array(
                esc_html__('Roadmap', 'delphinus') => 'roadmap',
                esc_html__('Satellite', 'delphinus') => 'satellite',
                esc_html__('Hybrid', 'delphinus') => 'hybrid',
                esc_html__('Terrain', 'delphinus') => 'terrain',
        	), 
            "admin_label" => true,            
        	"description" => esc_html__('','delphinus'),
        ),

        array(
            "type" => "dropdown",
            "heading" => esc_html__("Map stype",'delphinus'),
            "param_name" => "stype",
            'std' => '',
            "value" => array(
                esc_html__('None', 'delphinus') => '',
                esc_html__('Simple & Light', 'delphinus') => '1',
                esc_html__('Light Grey & Blue', 'delphinus') => '2',
                esc_html__('Dark', 'delphinus') => '3',
                esc_html__('Pinkish Gray', 'delphinus') => '4',
                esc_html__('Elevation', 'delphinus') => '5',
                esc_html__('Mostly Grayscale', 'delphinus') => '6',
                esc_html__('Red Hat Antwerp', 'delphinus') => '7',
                esc_html__('SB Greyscale Light', 'delphinus') => '8',
                esc_html__('Light Gray', 'delphinus') => '9',
            ),
            "admin_label" => true,
            "description" => esc_html__('','delphinus'),
        ),

        array(
            "type" => "checkbox",
        	"heading" => esc_html__("",'delphinus'),
        	"param_name" => "scrollwheel",
        	'value' => array( esc_html__( 'Disable map zoom on mouse wheel scroll', 'delphinus' ) => true ),
        	"description" => esc_html__('','delphinus'),
        ),
        array(
            "type" => "dropdown",
        	"heading" => esc_html__("Map zoom",'delphinus'),
        	"param_name" => "zoom",
            'std' => '17',
        	"value" => array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '11' => '11',
                '12' => '12',
                '13' => '13',
                '14' => '14',
                '15' => '15',
                '16' => '16',
                '17 - Default' => '17',
                '18' => '18', 
                '19' => '19'
        	),
        	"description" => esc_html__("1 is the smallest zoom level, 19 the greatest",'delphinus'),
            "admin_label" => true,
        ),
        array(
            "type" => "attach_image",
            "heading" => esc_html__( "Image marker", "js_composer" ),
            "param_name" => "image",
            "description" => esc_html__( "Select image show", "js_composer" ),
        ),
        array(
            "type" => "textfield",
            "heading" => esc_html__( "Extra class", "js_composer" ),
            "param_name" => "el_class",
            "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
        ),
        
        array(
			'type' => 'css_editor',
			'heading' => esc_html__( 'Css', 'js_composer' ),
			'param_name' => 'css',
			// 'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
			'group' => esc_html__( 'Design options', 'js_composer' )
		),
    ),
));