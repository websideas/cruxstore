<?php
/**
 * Single Product tabs
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$tabs = apply_filters( 'woocommerce_product_tabs', array() );


if ( ! empty( $tabs ) ) : ?>
<div class="panel-group" id="wc-accordion" role="tablist" aria-multiselectable="true">
    <?php $i = 1; ?>
    <?php foreach ( $tabs as $key => $tab ) : ?>

        <div class="panel panel-wc-default">
            <div class="panel-heading" role="tab" id="heading<?php echo esc_attr( $key ); ?>">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#wc-accordion" href="#collapse<?php echo esc_attr( $key ); ?>" aria-expanded="true" aria-controls="collapse<?php echo esc_attr( $key ); ?>">
                        <?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?>
                    </a>
                </h4>
            </div>
            <div id="collapse<?php echo esc_attr( $key ); ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo esc_attr( $key ); ?>">
                <div class="panel-body">
                    <?php call_user_func( $tab['callback'], $key, $tab ); ?>
                </div>
            </div>
        </div>
        <?php $i++ ?>
    <?php endforeach; ?>


</div>

<?php endif;