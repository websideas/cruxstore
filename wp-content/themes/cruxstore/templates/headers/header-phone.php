<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

if($phone = cruxstore_option('header_phone')){
    printf(
        '<li class="%s">%s</li>',
        'header-phone',
        '<i class="fa fa-user" aria-hidden="true"></i> '.$phone
    );
}