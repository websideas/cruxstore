<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

$layouts = explode('-', cruxstore_option('global_header_layout', '4-4-4'));

$global_header = false;

for ($i = 1; $i <= count($layouts); $i++) {
    if ( is_active_sidebar( 'global-banner-'.$i )  ) {
        $global_header = true;
        break;
    }
}

if (!$global_header) return;

?>
<div class="global-header">
    <div class="container">
        <div class="row">
            <?php foreach($layouts as $i => $layout){ ?>
                <?php $banner_class = 'col-md-'.$layout . ' col-sm-'.$layout . ' col-xs-12 global-header-'.($i+1); ?>
                <div class="<?php echo esc_attr($banner_class); ?>">
                    <?php dynamic_sidebar('global-banner-'.($i+1)) ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>