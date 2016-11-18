<?php

    $layout = delphinus_option('footer_bottom_layout', 1);
    $footer_bottom = false;
    
    if($layout == 2){
        if ( has_nav_menu( 'footer_bottom' ) ) {
            $footer_bottom = true;
        }
    }else{
        if( is_active_sidebar( 'footer-bottom-1' ) || is_active_sidebar( 'footer-bottom-2' )|| is_active_sidebar( 'footer-bottom-3' )){
            $footer_bottom = true;
        }
    }
    
    if(!$footer_bottom) return;
    
    $footer_fullwidth = delphinus_option('footer_fullwidth', true);
    
?>
<footer id="footer-bottom" class="footer-bottom-<?php echo esc_attr($layout); ?>">
    <?php
    if($footer_fullwidth){
        echo '<div class="container">';
    }
    if($layout == 2){
        if ( has_nav_menu( 'footer_bottom' ) ) {
            wp_nav_menu( array( 'theme_location' => 'footer_bottom', 'container' => 'nav', 'container_id' => 'footer-bottom-nav' ) );
        }
    }else{ ?>
        <div class="row">
            <div class="col-md-3">
                <?php dynamic_sidebar('footer-bottom-1') ?>
            </div>
            <div class="col-md-3">
                <?php dynamic_sidebar('footer-bottom-2') ?>
            </div>
            <div class="col-md-6">
                <?php dynamic_sidebar('footer-bottom-3') ?>
            </div>
        </div>
    <?php }
    if($footer_fullwidth){
        echo '</div>';
    }
    ?>
</footer><!-- #footer-bottom -->