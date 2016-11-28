<?php


$footer_top = false;

if(is_page()){
    $footer_top = cruxstore_meta('_cruxstore_footer_top');
}

if($footer_top == 'on'){
    $footer_top = true;
}elseif($footer_top == 'off'){
    $footer_top = false;
}else{
    $footer_top = cruxstore_option('footer_top', true);
}

if(!$footer_top) return;

$layout = cruxstore_option('footer_top_layout', 1);

if(!is_active_sidebar('footer-top-1') && !is_active_sidebar('footer-top-2')){
    return;
}elseif($layout == 1 && !is_active_sidebar('footer-top-1')){
    return;
}

$footer_fullwidth = cruxstore_option('footer_fullwidth', true);

?>
<footer id="footer-top" class="footer-top-<?php echo esc_attr($layout); ?>">
    <?php
    if($footer_fullwidth){
        echo '<div class="container">';
    }
    if($layout == 1){
        dynamic_sidebar('footer-top-1');
    }else{ ?>
        <div class="footer-top-inner">
            <div class="footer-top-bg"></div>
            <div class="row">
                <div class="footer-top-left col-md-8">
                    <?php dynamic_sidebar('footer-top-1') ?>
                </div>
                <div class="footer-top-right col-md-4">
                    <?php dynamic_sidebar('footer-top-2') ?>
                </div>
            </div>
        </div>
    <?php }
    if($footer_fullwidth){
        echo '</div>';
    }
    ?>
</footer><!-- #footer-bottom -->