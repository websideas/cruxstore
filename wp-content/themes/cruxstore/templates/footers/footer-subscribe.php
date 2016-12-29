<?php $rand = rand(); ?>
    <a href="#subscribe<?php echo $rand; ?>" class="header-subscribe">
        <i class="fa fa-envelope icon-space" aria-hidden="true"></i>
        <span class="text"><?php esc_html_e('Newletter', 'cruxstore') ?></span>
    </a>
<?php cruxstore_get_subscribe_form('subscribe'.$rand); ?>