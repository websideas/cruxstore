<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

?>

<div class="row">

    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 footer-area-right">
        <?php dynamic_sidebar('footer-column-1'); ?>
    </div>
    <div class="col-lg-offset-1 col-lg-7 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12">
                <?php dynamic_sidebar('footer-column-2'); ?>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <?php dynamic_sidebar('footer-column-3'); ?>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <?php dynamic_sidebar('footer-column-4'); ?>
            </div>
        </div>
    </div>
</div>
