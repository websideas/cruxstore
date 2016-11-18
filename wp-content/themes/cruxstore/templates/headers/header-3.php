<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>



<div class="navbar-container sticky-header sticky-header-down">
    <div class="apply-sticky">
        <div class="header-sticky-background"></div>
        <div class="container">
            <div class="navbar-container-inner clearfix">
                <div class="row">
                    <div class="col-sm-5 header-top-left">
                        <nav class="main-nav" id="nav">
                            <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                        </nav><!-- #nav -->
                    </div>
                    <div class="col-sm-2 header-top-center">
                        <?php get_template_part( 'templates/headers/header',  'branding'); ?>
                    </div>
                    <div class="col-sm-5 header-top-right">
                        <?php if(cruxstore_is_wc()){ ?>
                            <ul id="main-nav-wc">
                                <?php
                                get_template_part( 'templates/headers/header',  'myaccount');
                                get_template_part( 'templates/headers/header',  'wishlist');
                                get_template_part( 'templates/headers/header',  'cart');
                                get_template_part( 'templates/headers/header',  'search');
                                ?>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
