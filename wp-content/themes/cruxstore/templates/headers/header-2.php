<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>

<div class="header-top-ct">
    <div class="container">
        <div class="header-top-inner">
            <?php
            get_template_part( 'templates/headers/header',  'branding');
            get_template_part( 'templates/headers/header',  'searchf');
            ?>
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
                <ul id="main-nav-tool">
                    <?php get_template_part( 'templates/headers/header', 'cart'); ?>
                </ul>
            </div>
        </div>
    </div>
</div>
