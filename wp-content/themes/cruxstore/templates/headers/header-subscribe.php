<li>
    <?php
        $rand = rand();
    ?>
    <a href="#subscribe<?php echo $rand; ?>" class="header-subscribe">
        <i class="fa fa-envelope" aria-hidden="true"></i>
        <span class="text"><?php esc_html_e('Newletter', 'cruxstore') ?></span>
    </a>
    <?php
        printf('<div id="subscribe%s" class="header-subscribe-form mfp-hide mfp-with-anim">%s</div>', $rand, apply_filters('the_content', cruxstore_option( 'popup_form' )));
    ?>
</li>