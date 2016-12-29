<?php
    wp_nav_menu( array(
        'theme_location'            =>'vertical',
        'container'       => '',
        'link_before'     => '<span>',
        'link_after'      => '</span>',
        'menu_id'         => 'main-vertical',
        'menu_class' => 'hidden-xs hidden-sm',
        'walker' => new KTMegaWalker(),
    ) );

 ?>