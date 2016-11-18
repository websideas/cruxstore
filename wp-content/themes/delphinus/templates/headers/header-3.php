<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>

<div class="topbar">
    <div class="row">
        <div class="topbar-left col-sm-5">
            <ul class="top-navigation">
                <?php
                get_template_part( 'templates/headers/header',  'language');
                get_template_part( 'templates/headers/header',  'currency');
                get_template_part( 'templates/headers/header',  'search');
                ?>
            </ul>
        </div>
        <div class="topbar-center col-sm-2">
            <div class="branding-outer">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
            </div>
        </div>
        <div class="topbar-right col-sm-5">
            <?php if(delphinus_is_wc()){ ?>
                <ul class="top-navigation">
                    <?php
                    get_template_part( 'templates/headers/header',  'myaccount');
                    get_template_part( 'templates/headers/header',  'wishlist');
                    get_template_part( 'templates/headers/header',  'cart');
                    ?>
                </ul>
            <?php } ?>
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
