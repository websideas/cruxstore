<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$layouts = explode('-', cruxstore_option('footer_widgets_layout', '4-4-4'));
$footer_fullwidth = cruxstore_option('footer_fullwidth', true);
?>
<footer id="footer-area">
    <?php
    if($footer_fullwidth){
        echo '<div class="container">';
    }
    ?>
    <div class="row">
        <?php foreach($layouts as $i => $layout){ ?>
            <?php $footer_class = ($layout == 12) ? 'footer-area-one col-md-offset-2 col-md-8 col-sm-12 col-xs-12' : 'col-md-'.$layout . ' col-sm-'.$layout . ' col-xs-12'; ?>
            <div class="<?php echo esc_attr($footer_class); ?>">
                <?php dynamic_sidebar('footer-column-'.($i+1)) ?>
            </div>
        <?php } ?>
    </div>
    <?php
    if($footer_fullwidth){
        echo '</div>';
    }
    ?>
</footer><!-- #footer-area -->