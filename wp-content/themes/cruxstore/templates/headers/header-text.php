<?php
if($text = cruxstore_option('top_bar_text')){
    printf(
        '<li class="%s">%s</li>',
        'header-text',
        $text
    );
}
?>