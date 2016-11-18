<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: http://codex.wordpress.org/The_Loop
 *
 */


$layout = cruxstore_get_archive_layout();

do_action( 'cruxstore_loop_before' );

$readmore_class = (!$layout['readmore'] || $layout['readmore'] == 'none' ) ? ' no-readmore' : '';

echo '<div class="blog-posts blog-posts-'.esc_attr($layout['type']).' '.esc_attr($readmore_class).'">';

if($layout['type'] == 'grid'|| $layout['type'] == 'masonry'){
    echo '<div class="row multi-columns-row">';
    $article_columns = 12/$layout['columns'];
    $article_columns_tab = 12/$layout['columns_tab'];
}
if($layout['type'] == 'masonry') {
    printf('<div class="blog-post-sizer col-lg-%1$s col-md-%1$s col-sm-%2$s"></div>', $article_columns, $article_columns_tab);
}

$i = 1;

while ( have_posts() ) : the_post();

	/* Include the Post-Format-specific template for the content.
	 * If you want to override this in a child theme, then include a file
	 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
	 */

    $format = get_post_format();

    if($layout['type'] == 'grid' || $layout['type'] == 'masonry') {
        printf('<div class="blog-post-wrap col-lg-%1$s col-md-%1$s col-sm-%2$s" >', $article_columns, $article_columns_tab);
    }

    if($layout['type'] == 'zigzag'){
        $format = ($i % 2 )? '' : 'even';
    }
    get_template_part( 'templates/blog/'.$layout['type'].'/content', $format );

    if($layout['type'] == 'grid' || $layout['type'] == 'masonry') {
        echo '</div>';
    }
    $i++;
endwhile;
if($layout['type'] == 'grid' || $layout['type'] == 'masonry') {
    echo "</div><!-- .row -->";
}
echo "</div><!-- .blog-posts -->";

/**
 * @hooked cruxstore_paging_nav
 */

do_action( 'cruxstore_loop_after',  $layout);