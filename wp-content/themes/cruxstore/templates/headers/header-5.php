<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>

<div class="header-top">
    <div class="container">
        <div class="header-top-ct">
            <div class="row">
                <div class="header-top-left col-sm-5">
                    <ul class="top-navigation">
                        <?php
                        get_template_part( 'templates/headers/header',  'phone');
                        get_template_part( 'templates/headers/header',  'currency');
                        ?>
                    </ul>
                </div>
                <div class="header-top-center col-sm-2">
                    <div class="branding-outer">
                        <?php get_template_part( 'templates/headers/header',  'branding'); ?>
                    </div>
                </div>
                <div class="header-top-right col-sm-5">
                    <ul id="main-nav-tool">
                        <?php
                        get_template_part( 'templates/headers/header', 'search');
                        get_template_part( 'templates/headers/header', 'myaccount');
                        get_template_part( 'templates/headers/header', 'wishlist');
                        get_template_part( 'templates/headers/header', 'cart');
                        ?>
                    </ul><!-- #main-nav-tool -->
                </div>
            </div>
        </div>
    </div>
</div>



<div class="navbar-container sticky-header sticky-header-down">
    <div class="apply-sticky">
        <div class="header-sticky-background"></div>
        <div class="container">
            <div class="navbar-container-inner clearfix">
                <nav class="main-nav" id="nav">
                    <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                </nav><!-- #nav -->
            </div>
        </div>
    </div>
</div>
