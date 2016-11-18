<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>

<div class="topbar">
    <div class="container">
        <div class="row">
            <div class="topbar-left col-sm-6">
                <ul class="top-navigation">
                    <?php
                    get_template_part( 'templates/headers/header',  'language');
                    get_template_part( 'templates/headers/header',  'currency');
                    get_template_part( 'templates/headers/header',  'search');
                    ?>
                </ul>
            </div>
            <div class="topbar-right col-sm-6">
                <?php if(cruxstore_is_wc()){ ?>
                    <ul class="top-navigation">
                        <?php
                        get_template_part( 'templates/headers/header',  'myaccount');
                        get_template_part( 'templates/headers/header',  'cart');
                        ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<div class="<?php echo esc_attr(apply_filters('cruxstore_navbar_container', 'navbar-container')); ?>">
    <div class="apply-sticky">
        <div class="header-sticky-background"></div>
        <div class="container">
            <div class="navbar-container-inner clearfix">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
                <nav class="main-nav" id="nav">
                    <?php get_template_part( 'templates/headers/header',  'menu'); ?>
                </nav><!-- #nav -->
            </div>
        </div>
    </div>
</div>
