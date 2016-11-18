<?php
/**
 * The template used for displaying page content in page.php
 *
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header hide">
        <div class="entry-title"><?php the_title(); ?></div>
    </header><!-- .entry-header -->
    <div id="page-entry-content" class="entry-content">
        <?php
        the_content();
        wp_link_pages( array(
            'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'cruxstore' ) . '</span>',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
            'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'cruxstore' ) . ' </span>%',
            'separator'   => '<span class="screen-reader-text">, </span>',
        ) );
        ?>
    </div><!-- .entry-content -->

</div><!-- #post-## -->
