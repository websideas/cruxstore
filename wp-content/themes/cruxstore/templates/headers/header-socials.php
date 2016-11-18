<?php

$social = cruxstore_option('header_socials', 'facebook,twitter,instagram,linkedin');

$socials_arr = array(
    'facebook' => array('title' => esc_html__('Facebook', 'cruxstore'), 'icon' => 'fa fa-facebook', 'link' => '%s'),
    'twitter' => array('title' => esc_html__('Twitter', 'cruxstore'), 'icon' => 'fa fa-twitter', 'link' => 'http://www.twitter.com/%s'),
    'dribbble' => array('title' => esc_html__('Dribbble', 'cruxstore'), 'icon' => 'fa fa-dribbble', 'link' => 'http://www.dribbble.com/%s'),
    'vimeo' => array('title' => esc_html__('Vimeo', 'cruxstore'), 'icon' => 'fa fa-vimeo-square', 'link' => 'http://www.vimeo.com/%s'),
    'tumblr' => array('title' => esc_html__('Tumblr', 'cruxstore'), 'icon' => 'fa fa-tumblr', 'link' => 'http://%s.tumblr.com/'),
    'skype' => array('title' => esc_html__('Skype', 'cruxstore'), 'icon' => 'fa fa-skype', 'link' => 'skype:%s'),
    'linkedin' => array('title' => esc_html__('LinkedIn', 'cruxstore'), 'icon' => 'fa fa-linkedin', 'link' => '%s'),
    'googleplus' => array('title' => esc_html__('Google Plus', 'cruxstore'), 'icon' => 'fa fa-google-plus', 'link' => '%s'),
    'youtube' => array('title' => esc_html__('Youtube', 'cruxstore'), 'icon' => 'fa fa-youtube', 'link' => 'http://www.youtube.com/user/%s'),
    'pinterest' => array('title' => esc_html__('Pinterest', 'cruxstore'), 'icon' => 'fa fa-pinterest', 'link' => 'http://www.pinterest.com/%s'),
    'instagram' => array('title' => esc_html__('Instagram', 'cruxstore'), 'icon' => 'fa fa-instagram', 'link' => 'http://instagram.com/%s'),
);

foreach($socials_arr as $k => &$v){
    $val = cruxstore_option($k);
    $v['val'] = ($val) ? $val : '';
}
$social_icons = '';
if($social){
    $social_type = explode(',', $social);
    foreach ($social_type as $id) {
        $val = $socials_arr[$id];
        $social_text = '<i class="'.esc_attr($val['icon']).'"></i>';
        if($val['val']){
            $social_icons .= '<li><a class="'.esc_attr($id).'" title="'.esc_attr($val['title']).'" href="'.esc_url(str_replace('%s', $val['val'], $val['link'])).'" target="_blank">'.$social_text.'</a></li>';
        }
    }
}else{
    foreach($socials_arr as $key => $val){
        $social_text = '<i class="'.esc_attr($val['icon']).'"></i>';
        if($val['val']) {
            $social_icons .= '<li><a class="' . esc_attr($key) . '" title="' . esc_attr($val['title']) . '" href="' . esc_url(str_replace('%s', $val['val'], $val['link'])) . '" target="_blank">' . $social_text . '</a></li>';
        }
    }
}

printf('<li class="header-socials"><ul id="main-nav-socials">%s</ul><!-- #main-nav-socials --></li>', $social_icons);