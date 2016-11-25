<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

?>

<div class="row">

    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
        <div class="footer-area-left">
            <?php dynamic_sidebar('footer-column-1'); ?>
        </div>
    </div>

    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
        <div class="footer-area-right">
            <div class="row">
                <div class="col-md-4 col-sm-3 col-xs-12">
                    <?php dynamic_sidebar('footer-column-2'); ?>
                </div>
                <div class="col-md-6 col-sm-3 col-xs-12">
                    <?php dynamic_sidebar('footer-column-3'); ?>
                </div>
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <?php dynamic_sidebar('footer-column-4'); ?>
                </div>
            </div>
        </div>
    </div>

</div>
