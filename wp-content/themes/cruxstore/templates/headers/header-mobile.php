<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
?>
<div id="header-content-mobile" class="clearfix">
    <div class="header-content-mobile-inner">
        <div class="branding branding-mobile">
            <?php
            $logo = cruxstore_get_logo();
            $logo_class = ($logo['retina']) ? 'retina-logo-wrapper' : '';
            ?>
            <p class="site-logo <?php echo esc_attr($logo_class); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <img src="<?php echo esc_url($logo['default']['url']); ?>" width="<?php echo esc_attr($logo['default']['width']) ?>" height="<?php echo esc_attr($logo['default']['height']) ?>" class="default-logo" alt="<?php bloginfo( 'name' ); ?>" />
                    <?php  if($logo['retina']){ ?>
                        <img src="<?php echo esc_url($logo['retina']['url']); ?>" width="<?php echo esc_attr($logo['retina']['width']) ?>" height="<?php echo esc_attr($logo['retina']['height']) ?>" class="retina-logo retina-default-logo" alt="<?php bloginfo( 'name' ); ?>" />
                    <?php } ?>
                </a>
            </p><!-- .site-logo -->
        </div><!-- .site-branding -->
        <div class="header-mobile-tools">
            <a title="<?php esc_attr_e('Menu', 'cruxstore') ?>" href="#" id="hamburger-icon" class="">
                <span class="hamburger-icon-inner">
                    <span class="line line-1"></span>
                    <span class="line line-2"></span>
                    <span class="line line-3"></span>
                </span>
            </a>
            <?php if ( cruxstore_is_wc() && cruxstore_option('header_cart', 1) && !cruxstore_option('catalog_mode', 0) ) { ?>
                <?php cruxstore_cart_link('cart-mobile'); ?>
            <?php } ?>
        </div>
    </div>
</div>