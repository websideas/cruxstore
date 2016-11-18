<?php

/**
 * Register custom field metabox
 *
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

// Make sure "input" field is loaded
require_once RWMB_FIELDS_DIR . 'input.php';


add_filter('rwmb_image_advanced_select_string', 'delphinus_rwmb_image_advanced_select_string', 10, 2);
function delphinus_rwmb_image_advanced_select_string($string, $field){
	if($field['max_file_uploads'] == 1){
		$string = esc_html__('Select your image', 'delphinus');
	}
	return $string;
}


if ( ! class_exists( 'RWMB_Image_Radio_Field' ) )
{
    class RWMB_Image_Radio_Field extends RWMB_Input_Field
    {
        /**
         * Get field HTML
         *
         * @param mixed $meta
         * @param array $field
         *
         * @return string
         */
        static function html( $meta, $field )
        {
            $html       = array();
            $tpl        = '<label><input %3$s %4$s><img src="%1$s" alt="%2$s"/></label>';
            $attributes = self::get_attributes($field);

            foreach ( $field['options'] as $value => $label )
            {
                $attributes['value'] = $value;
                $html[]              = sprintf(
                    $tpl,
                    $label['img'],
                    $label['alt'],
                    self::render_attributes( $attributes ),
                    checked( $value, $meta, false ),
                    $label['alt']
                );
            }


            return implode( ' ', $html );
        }


        static function get_attributes( $field, $value = null )
        {
            $attributes          = RWMB_Input_Field::get_attributes( $field, $value );
            $attributes['id']    = false;
            $attributes['type']  = 'radio';
            $attributes['value'] = $value;

            return $attributes;
        }

    }
}




if ( ! class_exists( 'RWMB_Background_Field' )){
    class RWMB_Background_Field extends RWMB_Field{

        /**
         * Get field HTML
         *
         * @param mixed $meta
         * @param array $field
         *
         * @return string
         */
        static function html( $meta, $field )
        {
            $ouput = '<div class="wrapper wrapper_delphinus_image_upload">';

            if(!is_array($meta)){
                $meta = array(
                    'color' => '',
                    'repeat' => '',
                    'size' => '',
                    'attachment' => '',
                    'position' => '',
                    'media' => '',
                    'url' => ''
                );
            }

            $ouput .= sprintf(
                '<input class="rwmb-color" type="text" name="%s" value="%s" />
				<div class="rwmb-color-picker"></div>',
                $field['field_name'].'[color]',
                $meta['color']
            );



            $bg_repeat = array(
                'field_name' => $field['field_name'].'[repeat]',
                'id' => '',
                'size' => 1,
                'options' => array(
                    'no-repeat' => 'No Repeat',
                    'repeat'    => 'Repeat All',
                    'repeat-x'  => 'Repeat Horizontally',
                    'repeat-y'  => 'Repeat Vertically',
                    'inherit'   => 'Inherit',
                ),
                'placeholder' => esc_html__('Background Repeat', 'delphinus')
            );

            $ouput .= self::select_html($meta['repeat'], $bg_repeat);

            $bg_size = array(
                'field_name' => $field['field_name'].'[size]',
                'id' => '',
                'size' => 1,
                'options' => array(
                    'inherit' => 'Inherit',
                    'cover'   => 'Cover',
                    'contain' => 'Contain',
                ),
                'placeholder' => esc_html__('Background Size', 'delphinus')
            );

            $ouput .= self::select_html($meta['size'], $bg_size);

            $bg_attachment = array(
                'field_name' => $field['field_name'].'[attachment]',
                'id' => '',
                'size' => 1,
                'options' => array(
                    'fixed'   => 'Fixed',
                    'scroll'  => 'Scroll',
                    'inherit' => 'Inherit',
                ),
                'placeholder' => esc_html__('Background Attachment', 'delphinus')
            );

            $ouput .= self::select_html($meta['attachment'], $bg_attachment);


            $bg_position = array(
                'field_name' => $field['field_name'].'[position]',
                'id' => '',
                'size' => 1,
                'options' => array(
                    'left top'      => 'Left Top',
                    'left center'   => 'Left center',
                    'left bottom'   => 'Left Bottom',
                    'center top'    => 'Center Top',
                    'center center' => 'Center Center',
                    'center bottom' => 'Center Bottom',
                    'right top'     => 'Right Top',
                    'right center'  => 'Right center',
                    'right bottom'  => 'Right Bottom',
                ),
                'placeholder' => esc_html__('Background Position', 'delphinus')
            );

            $ouput .= self::select_html($meta['position'], $bg_position);



            $ouput .= sprintf(
                '<div class="rwmb-field"><input type="text" readonly="" class="delphinus_image_url" name="%s" value="%s" placeholder="%s"/></div>',
                $field['field_name'].'[url]',
                $meta['url'],
                esc_html__( 'No media selected', 'delphinus' )
            );

            $remove_style = ($meta['media'] != '') ? 'inline-block' : 'none';

            $ouput .= sprintf(
                '<div class="upload_button_div"><span class="button delphinus_image_upload">%s</span> <span class="button delphinus_image_remove" style="display : %s">%s</span></div>',
                esc_html__('Upload', 'delphinus'),
                $remove_style,
                esc_html__('Remove', 'delphinus')
            );


            $ouput .= sprintf(
                '<input type="hidden" class="delphinus_image_attachment" name="%s" value="%s"/>',
                $field['field_name'].'[media]',
                $remove_style,
                $meta['media']
            );

            $ouput .= '</div><div class="clearfix"></div>';
            return $ouput;
        }

        /**
         * Get field HTML
         *
         * @param mixed $meta
         * @param array $field
         *
         * @return string
         */
        static function select_html( $meta, $field )
        {
            $html = '<div class="rwmb-field">';
            $html .= sprintf(
                '<select class="rwmb-select" name="%s" id="%s">',
                $field['field_name'],
                $field['id']
            );

            $html .= self::options_html( $field, $meta );

            $html .= '</select>';
            $html .= '</div>';

            //$html .= self::get_select_all_html( $field['multiple'] );

            return $html;
        }

        /**
         * Creates html for options
         *
         * @param array $field
         * @param mixed $meta
         *
         * @return array
         */
        static function options_html( $field, $meta )
        {
            $html = '';
            if ( $field['placeholder'] )
            {
                $html = "<option value=''>{$field['placeholder']}</option>";
            }

            $option = '<option value="%s"%s>%s</option>';

            foreach ( $field['options'] as $value => $label )
            {
                $html .= sprintf(
                    $option,
                    $value,
                    selected( in_array( $value, (array) $meta ), true, false ),
                    $label
                );
            }

            return $html;
        }

        /**
         * Enqueue scripts and styles
         *
         * @return void
         */
        static function admin_enqueue_scripts()
        {

            parent::admin_enqueue_scripts();
            wp_enqueue_style( 'rwmb-color', RWMB_CSS_URL . 'color.css', array( 'wp-color-picker' ), RWMB_VER );
            wp_enqueue_script( 'rwmb-color', RWMB_JS_URL . 'color.js', array( 'wp-color-picker' ), RWMB_VER, true );

            // Make sure scripts for new media uploader in WordPress 3.5 is enqueued
            wp_enqueue_media();

            wp_enqueue_script( 'delphinus_image', DELPHINUS_FW_JS . 'delphinus_image.js', array( 'jquery', 'underscore' ), RWMB_VER, true );
            wp_localize_script( 'rwmb-file-background', 'delphinus_image_lange', array(
                'frameTitle' => esc_html__( 'Select Image', 'delphinus' ),
            ) );
        }

    }
} // end RWMB_Background_Field



