<?php
    if ( delphinus_is_wc()  && !delphinus_option('catalog_mode', 0)){
        $text = (is_user_logged_in ()) ? esc_html__('My Account', 'delphinus') : esc_html__('Login', 'delphinus');
        printf(
            '<li class="%s"><a href="%s">%s</a>',
            'header-wc-myaccount',
            get_permalink( get_option('woocommerce_myaccount_page_id') ),
            $text
        );
    }
?>