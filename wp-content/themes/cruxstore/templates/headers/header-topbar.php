<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;


$top_bar = cruxstore_option('top_bar', false );
if(!$top_bar) return false;

?>

<div class="topbar">
    <div class="container">
        <div class="display-table">
            <?php
            $top_bar_left = cruxstore_option('top_bar_left', array('currency', 'language') );

            if(count($top_bar_left)){
                echo '<div class="topbar-left display-cell"><ul class="top-navigation">';
                foreach($top_bar_left as $item){
                    get_template_part( 'templates/headers/header',  $item);
                }
                echo '</ul></div>';
            }

            $top_bar_right = cruxstore_option('top_bar_right', array('currency', 'language') );
            //print_r($top_bar_right);
            if(count($top_bar_right)){
                echo '<div class="topbar-right display-cell"><ul class="top-navigation">';
                foreach($top_bar_right as $item){
                    get_template_part( 'templates/headers/header',  $item);
                }
                echo '</ul></div>';
            }
            ?>

        </div>
    </div>
</div>