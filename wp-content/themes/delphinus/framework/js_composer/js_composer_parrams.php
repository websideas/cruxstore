<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Heading field.
 *
 */
function delphinus_heading_settings_field( $settings, $value ) {
    $dependency = '';
	$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
	$type = isset($settings['type']) ? $settings['type'] : '';
	$class = isset($settings['class']) ? $settings['class'] : '';
    
    return '<input type="hidden" class="wpb_vc_param_value ' . $settings['param_name'] . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.esc_attr($value).'" '.$dependency.'/>';
}
WpbakeryShortcodeParams::addField( 'delphinus_heading', 'delphinus_heading_settings_field' );


/**
 * Number field.
 *
 */
function delphinus_number_settings_field($settings, $value){
	$dependency = '';
	$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
	$type = isset($settings['type']) ? $settings['type'] : '';
	$min = isset($settings['min']) ? $settings['min'] : '';
	$max = isset($settings['max']) ? $settings['max'] : '';
	$suffix = isset($settings['suffix']) ? $settings['suffix'] : '';
	$class = isset($settings['class']) ? $settings['class'] : '';
    $style = '';
    if($suffix){
        $style = 'style="max-width:100px; margin-right: 10px;"';
    }
	$output = '<input type="number" min="'.esc_attr($min).'" max="'.esc_attr($max).'" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.esc_attr($value).'" '.$dependency.' '.$style.' />'.$suffix;
	return $output;
}
WpbakeryShortcodeParams::addField('delphinus_number' , 'delphinus_number_settings_field');


/**
 * Radio select field.
 *
 */
function delphinus_radio_settings_field($settings, $value) {
	$dependency = '';
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
	$type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $class_input = isset($settings['class_input']) ? $settings['class_input'] : '';

    $output = "";
    $uniqid = uniqid();
    $radios = array();
    
    if(!count($settings['value'])) return;
    foreach( $settings['value'] as $k => $v ) {
        $checked = ($value == $v) ? ' checked="checked"' : '';
        $radios[] = "<label><input type='radio' name='{$param_name}_radio_{$uniqid}' class='delphinus_radio_select_input' value='{$v}' {$checked} /> {$k}</label>\n";
    }
    $output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.esc_attr($value).'" '.$dependency.' />';
    
    return $output."<div class='".$class_input."'>".implode(' ', $radios)."</div>";
}
WpbakeryShortcodeParams::addField('delphinus_radio', 'delphinus_radio_settings_field', DELPHINUS_FW_JS.'delphinus_radio.js');


/**
 * Switch field.
 *
 */
function delphinus_switch_settings_field($settings, $value) {
	$dependency = '';
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
	$type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $uniqeID    = uniqid();
    if(!$value){
        if(isset($settings['value'])){
            $value = $settings['value'];
        }
    }
    $output = "";
    $checked = ($value == 'true') ? 'checked="checked"': '';
    $output .= '<input type="checkbox" name="' . $param_name . '" class="wpb_vc_param_value cmn-toggle cmn-toggle-round-flat ' . $param_name . ' ' . $type . ' ' . $class . ' " '.$checked.' id="cmn-toggle-'.$uniqeID.'" value="'.esc_attr($value).'" '.$dependency.'>';
    $output .= '<label for="cmn-toggle-'.$uniqeID.'"></label>';
    
    return $output;
}
WpbakeryShortcodeParams::addField('delphinus_switch', 'delphinus_switch_settings_field', DELPHINUS_FW_JS.'delphinus_switch.js');



/**
 * Dropdown(select with options) shortcode attribute type generator.
 *
 * @param $settings
 * @param $value
 *
 * @since 4.4
 * @return string - html string.
 */
function delphinus_taxonomy_settings_field( $settings, $value ) {
    $output = '';

    $value_arr = $value;
    if ( !is_array($value_arr) ) {
        $value_arr = array_map( 'trim', explode(',', $value_arr) );
    }

    $size = (!empty($settings['size'])) ? 'size="'.esc_attr($settings['size']).'"' : '';
    $multiple = (!empty($settings['multiple'])) ? 'multiple="multiple"' : '';
    $placeholder = (!empty($settings['placeholder'])) ? 'data-placeholder="'.$settings['placeholder'].'"' : '';
    $select = (!empty($settings['select'])) ? 'id' : 'slug';

    $output .= '<select '.$multiple.' '.$placeholder.' '.$size.'
        class="wpb_vc_param_value delphinus-select-field wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type']. '">';
    if ( !empty($settings['taxonomy']) ) {
        if(empty($settings['multiple'])){
            if (empty($settings['placeholder'])) $settings['placeholder'] = '';
            $output .= "<option class='' value=''>".htmlspecialchars($settings['placeholder'])."</option>";
        }
        $terms = get_terms( $settings['taxonomy'] , array('hide_empty' => false));
        foreach( $terms as $term ) {
            $term_val = ($select == 'slug') ? $term->slug : $term->term_id;
            $selected = (in_array( $term_val, $value_arr )) ? ' selected="selected"' : '';
            $output .= "<option class='" . $term_val . "' value='".$term_val."' ".$selected.">".htmlspecialchars($term->name)."</option>";
        }
    }

    $output .= '</select>';
    $output .= '<input type="hidden"class="wpb_vc_param_value '.$settings['param_name'].'" name="' . $settings['param_name'] . '" value="'.esc_attr($value).'" />';

    return $output;
}

WpbakeryShortcodeParams::addField('delphinus_taxonomy', 'delphinus_taxonomy_settings_field', DELPHINUS_FW_JS.'delphinus_select.js');

/**
 * Posts field.
 *
 */
function delphinus_posts_settings_field($settings, $value) {

    $output = '';

    $value_arr = $value;
    if ( !is_array($value_arr) ) {
        $value_arr = array_map( 'trim', explode(',', $value_arr) );
    }

    $size = (!empty($settings['size'])) ? 'size="'.esc_attr($settings['size']).'"' : '';
    $multiple = (!empty($settings['multiple'])) ? 'multiple="multiple"' : '';
    $placeholder = (!empty($settings['placeholder'])) ? 'data-placeholder="'.$settings['placeholder'].'"' : '';

    $output .= '<select '.$multiple.' '.$placeholder.' '.$size.'
        class="wpb_vc_param_value delphinus-select-field wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type']. '">';
    if ( !empty($settings['args']) ) {
        $query = new WP_Query( $settings['args']);
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) { $query->the_post();
                $selected = (in_array( get_the_ID(), $value_arr )) ? ' selected="selected"' : '';
                $output .= "<option value='".get_the_ID()."' {$selected}>".get_the_title()."</option>";
            }
        }
        wp_reset_postdata();
    }
    $output .= '</select>';
    $output .= '<input type="hidden"class="wpb_vc_param_value '.$settings['param_name'].'" name="' . $settings['param_name'] . '" value="'.esc_attr($value).'" />';

    return $output;

}
WpbakeryShortcodeParams::addField('delphinus_posts', 'delphinus_posts_settings_field', DELPHINUS_FW_JS.'delphinus_select.js');




/**
 * Authors field.
 *
 */
function delphinus_authors_settings_field($settings, $value) {

    $output = '';

    $value_arr = $value;
    if ( !is_array($value_arr) ) {
        $value_arr = array_map( 'trim', explode(',', $value_arr) );
    }

    $size = (!empty($settings['size'])) ? 'size="'.esc_attr($settings['size']).'"' : '';
    $multiple = (!empty($settings['multiple'])) ? 'multiple="multiple"' : '';
    $placeholder = (!empty($settings['placeholder'])) ? 'data-placeholder="'.$settings['placeholder'].'"' : '';

    $output .= '<select '.$multiple.' '.$placeholder.' '.$size.'
        class="wpb_vc_param_value delphinus-select-field wpb-input wpb-select '
        . $settings['param_name']
        . ' ' . $settings['type']. '">';

    $authors = get_users( array() );
    foreach( $authors as $author ) {
        $selected = (in_array( $author->ID, $value_arr )) ? ' selected="selected"' : '';
        $output .= "<option value='{$author->ID}' {$selected}>{$author->display_name}</option>";
    }


    $output .= '</select>';
    $output .= '<input type="hidden"class="wpb_vc_param_value '.$settings['param_name'].'" name="' . $settings['param_name'] . '" value="'.esc_attr($value).'" />';

    return $output;

}
WpbakeryShortcodeParams::addField('delphinus_authors', 'delphinus_authors_settings_field', DELPHINUS_FW_JS.'delphinus_select.js');


/**
 * Authors field.
 *
 */
function delphinus_socials_settings_field($settings, $value) {
    $dependency = '';
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';

    $socials = array(
        'facebook' => 'fa fa-facebook',
        'twitter' => 'fa fa-twitter',
        'pinterest' => 'fa fa-pinterest-p',
        'dribbble' => 'fa fa-dribbble',
        'vimeo' => 'fa fa-vimeo-square',
        'tumblr' => 'fa fa-tumblr',
        'skype' => 'fa fa-skype',
        'linkedin' => 'fa fa-linkedin',
        'googleplus' => 'fa fa-google-plus',
        'youtube' => 'fa fa-youtube-play',
        'instagram' => 'fa fa-instagram'
    );
    $arr_val = ($value) ? explode(',', $value) : array();

    $output = '';
    $output .= '<div class="delphinus-socials-options">';
        $output .= '<ul class="delphinus-socials-lists clearfix">';
        foreach($socials as $key => $social){
            $class = (in_array($key, $arr_val)) ? 'selected' : '';
            $output .= sprintf('<li data-type="%s" class="%s"><i class="%s"></i><span></span></li>', $key, $class, $social);
        }
        $output .= "</ul><!-- .delphinus-socials-lists -->";
        $output .= '<ul class="delphinus-socials-profiles clearfix">';

        if(count($arr_val)){
            foreach($arr_val as $item){
                $output .= sprintf('<li data-type="%s"><i class="%s"></i><span></span></li>', $item,  $socials[$item]);
            }
        }
        $output .= "</ul><!-- .delphinus-socials-profiles -->";
        $output .= '<input type="hidden" class="wpb_vc_param_value delphinus-socials-value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.esc_attr($value).'" '.$dependency.' />';

    $output .= '</div><!-- .delphinus-socials-options -->';

    return $output;

}
WpbakeryShortcodeParams::addField('delphinus_socials', 'delphinus_socials_settings_field', DELPHINUS_FW_JS.'delphinus_socials.js');




function delphinus_responsive_settings_field($settings, $value){

    $dependency = '';
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $unit = isset($settings['unit']) ? $settings['unit'] : '';
    $queries = isset($settings['settings']) ? $settings['settings'] : array('desktop'=> '', 'tablet' => '', 'mobile' => '');

    $output = '';
    $arr_val = array();

    if($value){
        $arrs = ($value) ? explode(';', $value) : array();
        foreach($arrs as $arr){
            if($arr){
                $item_arr = explode(':', $arr);
                $arr_val[$item_arr[0]] = intval($item_arr[1]);
            }
        }
    }

    foreach($queries as $key => $val) {
        $val_new = isset($arr_val[$key]) ? $arr_val[$key] : '';

        if($val_new == ''){
            $val_new = $val;
        }

        if($key == 'desktop'){
            $output .= delphinus_responsive_field_render('<span class="dashicons dashicons-desktop" title="Desktop"></span>', 'desktop', $val_new);
        }elseif($key == 'tablet'){
            $output .= delphinus_responsive_field_render('<span class="dashicons dashicons-tablet" title="Tablet"></span>', 'tablet', $val_new);
        }elseif($key == 'mobile'){
            $output .= delphinus_responsive_field_render('<span class="dashicons dashicons-smartphone" title="Mobile"></span>', 'mobile', $val_new);
        }
    }

    $output .= '<span class="delphinus-responsive-unit">'.$unit.'</span>';
    $output .= '<input type="hidden" class="wpb_vc_param_value delphinus-responsive-value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.esc_attr($value).'" '.$dependency.' />';

    return $output;
}
WpbakeryShortcodeParams::addField('delphinus_responsive', 'delphinus_responsive_settings_field',  DELPHINUS_FW_JS.'delphinus_responsive.js');


function delphinus_responsive_field_render($addon_before, $name, $value ){

    $output = sprintf(
        '<div class="delphinus-input-group"><div class="input-group-addon">%s</div> <input type="number" class="form-control" name="%s" value="%s"></div>',
        $addon_before,
        $name,
        $value
    );
    return $output;

}


/**
 * Image sizes.
 *
 */
function delphinus_image_sizes_settings_field($settings, $value){
    $dependency = '';
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $custom = isset($settings['custom']) ? $settings['custom'] : false;

    $posts_fields = array();
    $sizes = delphinus_get_image_sizes(true, $custom);
    foreach($sizes as $key => $size){
        $selected = ($value == $key) ? ' selected="selected"' : '';
        $posts_fields[] = "<option value='{$key}' {$selected}>".$size."</option>";
    }
    $output = '<select class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" '.$dependency.'>'
        .implode( $posts_fields )
        .'</select>';
    return $output;
}
WpbakeryShortcodeParams::addField('delphinus_image_sizes', 'delphinus_image_sizes_settings_field');



function delphinus_icons_settings_field($settings, $value){

    $dependency = '';
    $param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
    $type = isset($settings['type']) ? $settings['type'] : '';
    $class = isset($settings['class']) ? $settings['class'] : '';
    $output = '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.esc_attr($value).'" '.$dependency.' />';


    $placeholder = isset($settings['placeholder']) ? $settings['placeholder'] : esc_html__('Search icon ...', 'delphinus');


    $output .= '<div class="param-icon-header clearfix">';

    $preview_class = ($value) ? 'active' : '';

    $output .= '<span class="param-icon-preview '.$preview_class.'"><i class="'.$value.'"></i><span class="icon-preview-remove"></span></span>';
    $output .= '<p><input type="text" placeholder="'.$placeholder.'" name="param-icon-search" class="param-icon-search" /></p>';

    $lists = apply_filters( 'delphinus_icons_source', array() );
    if(is_array($lists)){
        $icons = '';
        $output .= '<p><select name="param-icon-categories" class="param-icon-categories">';
        $output .= '<option value="">'.esc_html__('From all categories', 'delphinus').'</option>';
        foreach($lists as $k => $v){
            $text = ucwords(str_replace('_', ' ', $k));
            $output .= '<option value="'.$k.'">'.$text.'</option>';
            foreach($v as $icon){
                foreach($icon as $key => $label){
                    $current = ($value == $key) ? 'current' : '';
                    $icons .= '<li data-source="'.$k.'" class="'.$current.'" data-key="'.$key.'"><i class="'.$key.'" title="'.$label.'"></i></li>';
                }
            }
        }
        $output .= '</select></p>';
    }

    $output .= '</div>';
    $output .= '<ul class="clearfix">'.$icons.'</ul>';

    return $output;
}
WpbakeryShortcodeParams::addField('delphinus_icons', 'delphinus_icons_settings_field',  DELPHINUS_FW_JS.'delphinus_icons.js');

