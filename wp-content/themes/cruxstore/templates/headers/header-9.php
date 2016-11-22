<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div class="<?php echo esc_attr(apply_filters('cruxstore_navbar_container', 'navbar-container')); ?>">
    <div class="apply-sticky">
        <div class="header-sticky-background"></div>
        <div class="container">
            <div class="navbar-container-inner clearfix">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
                <nav class="main-nav" id="nav">
                    <ul id="main-nav-tool">
                        <?php
                        get_template_part( 'templates/headers/header', 'search');
                        get_template_part( 'templates/headers/header', 'myaccount');
                        get_template_part( 'templates/headers/header', 'wishlist');
                        get_template_part( 'templates/headers/header', 'cart');
                        ?>
                    </ul><!-- #main-nav-tool -->
                </nav><!-- #nav -->
                <div class="top-menu">



                    <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
