<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

echo '<div class="navigation_socials">';

get_template_part( 'templates/footers/footer', 'navigation' );

get_template_part( 'templates/footers/footer', 'socials' );


echo '</div>';