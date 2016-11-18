<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;




if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) || defined( 'WPCF7_PLUGIN' ) ) {

    class WPBakeryShortCode_Delphinus_Contact_Form7 extends WPBakeryShortCode {
        protected function content($atts, $content = null) {

            $atts = shortcode_atts(array(
                'layout' => '',
                'id' => '',
                'css_animation' => '',
                'animation_delay' => '',
                'el_class' => '',
                'css'      => '',
            ), $atts);

            extract( $atts );

            $elementClass = array(
                'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'delphinus-contact-form7', $this->settings['base'], $atts ),
                'shortcode_custom' => vc_shortcode_custom_css_class( $css, ' ' ),
                'extra' => $this->getExtraClass( $el_class ),
                'css_animation' => delphinus_getCSSAnimation( $css_animation ),
            );
            if($layout){
                $elementClass[] = 'layout'.$layout;
            }
            $rand = rand();

            $output = do_shortcode('[contact-form-7 id="'.$id.'" ]');
            
            if($animation_delay){
                $animation_delay = sprintf(' data-wow-delay="%sms"', $animation_delay);
            }
            
            $elementClass = preg_replace( array( '/\s+/', '/^\s|\s$/' ), array( ' ', '' ), implode( ' ', $elementClass ) );
            return '<div id="delphinus-contact-form7'.$rand.'" class="'.esc_attr( $elementClass ).'"'.$animation_delay.'>'.$output.'</div>';

        }
    }

    $cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );



    $contact_forms = array();
    if ( $cf7 ) {
        foreach ( $cf7 as $cform ) {
            $contact_forms[ $cform->post_title ] = $cform->ID;
        }
    } else {
        $contact_forms[ esc_html__( 'No contact forms found', 'js_composer' ) ] = 0;
    }


    // Add your Visual Composer logic here
    vc_map( array(
        "base" => "delphinus_contact_form7",
        'name' => esc_html__( 'KT: Contact Form 7', 'delphinus' ),
        'icon' => 'icon-wpb-contactform7',
        'category' => esc_html__( 'Content', 'js_composer' ),
        'description' => esc_html__( 'Place Contact Form7', 'js_composer' ),
        'params' => array(

            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Contact Form7 Layout', 'delphinus' ),
                'param_name' => 'layout',
                'value' => array(
                    esc_html__( 'Default', 'delphinus' ) => '',
                    esc_html__( 'Layout 1', 'delphinus' ) => '1',
                ),
                'description' => esc_html__( 'Choose contact form7 layout.', 'js_composer' ),
                "admin_label" => true,
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__( 'Select contact form', 'js_composer' ),
                'param_name' => 'id',
                'value' => $contact_forms,
                'save_always' => true,
                'description' => esc_html__( 'Choose previously created contact form from the drop down list.', 'js_composer' ),
                "admin_label" => true,
            ),
            delphinus_map_add_css_animation(),
            delphinus_map_add_css_animation_delay(),
            array(
                "type" => "textfield",
                "heading" => esc_html__( "Extra class name", "js_composer" ),
                "param_name" => "el_class",
                "description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "js_composer" ),
            ),
            array(
                'type' => 'css_editor',
                'heading' => esc_html__( 'CSS box', 'js_composer' ),
                'param_name' => 'css',
                // 'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
                'group' => esc_html__( 'Design Options', 'js_composer' )
            )
        ),
    ));

}