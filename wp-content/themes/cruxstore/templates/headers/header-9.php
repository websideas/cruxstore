<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div class="<?php echo esc_attr(apply_filters('cruxstore_navbar_container', 'navbar-container')); ?>">


    <div class="container">
        <div class="navbar-container-inner clearfix">
            <div id="header-nav">
                <?php get_template_part( 'templates/headers/header',  'branding'); ?>
                <nav class="main-nav" id="nav">
                    <?php get_template_part( 'templates/headers/header', 'search9'); ?>
                    <ul id="main-nav-tool">
                        <?php

                        get_template_part( 'templates/headers/header', 'myaccount');
                        get_template_part( 'templates/headers/header', 'wishlist');
                        get_template_part( 'templates/headers/header', 'cart');
                        ?>
                    </ul><!-- #main-nav-tool -->
                </nav><!-- #nav -->
            </div><!--header nav-->
            <div class="top-menu">

                <div id="menu-vartical-right">
                    <div class="menu-category">
                        <a href="#"> CATEGORIAS</a>
                    </div>
                    <div class="menu-right">
                        <?php get_template_part( 'templates/headers/header',  'vertical'); ?>
                        <span class="icon_show_menu" data-show="6">
                    </div>
                </div>

                <?php get_template_part( 'templates/headers/header',  'menu'); ?>
            </div>
        </div>
    </div>

</div>
