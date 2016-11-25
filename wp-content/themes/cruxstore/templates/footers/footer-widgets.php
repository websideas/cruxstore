<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$footer_fullwidth = cruxstore_option('footer_fullwidth', true);
$widgets_layout = cruxstore_option('footer_widgets_layout', '1');
?>
<footer id="footer-area" class="footer-area-<?php echo esc_attr($widgets_layout) ?>">
    <?php
    if($footer_fullwidth){
        echo '<div class="container">';
    }

    get_template_part( 'templates/footers/layouts/footer', $widgets_layout);

    if($footer_fullwidth){
        echo '</div>';
    }
    ?>
</footer><!-- #footer-area -->