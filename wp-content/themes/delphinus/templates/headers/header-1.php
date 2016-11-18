<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>

<div class="topbar">
    <div class="row">
        <div class="topbar-left col-sm-6">
            <ul class="top-navigation">
                <?php
                get_template_part( 'templates/headers/header',  'language');
                get_template_part( 'templates/headers/header',  'currency');
                ?>
            </ul>
        </div>
        <div class="topbar-right col-sm-6">
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

<div class="<?php echo esc_attr(apply_filters('delphinus_navbar_container', 'navbar-container')); ?>">
    <div class="apply-sticky">
        <div class="header-sticky-background"></div>
        <div class="navbar-container-inner clearfix">
            <div class="navbar-container-content">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
                <nav class="main-nav" id="nav">
                    <div class="container">
                        <div class="main-nav-outer">
                            <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                        </div>
                    </div>
                    <ul id="main-nav-tool">
                        <?php get_template_part( 'templates/headers/header',  'search'); ?>
                    </ul><!-- #main-nav-tool -->
                </nav><!-- #nav -->
            </div>
        </div>
    </div>
</div>
