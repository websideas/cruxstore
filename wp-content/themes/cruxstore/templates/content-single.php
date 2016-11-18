<article id="post-<?php the_ID(); ?>" <?php post_class('single-post-wrap'); ?> itemscope="" itemtype="http://schema.org/BlogPosting">
    <header class="entry-header">
        <?php
        the_title( sprintf( '<h1 class="entry-title" itemprop="name headline">', esc_url( get_permalink() ) ), '</h1>' );
        cruxstore_post_meta();
        ?>
    </header><!-- .entry-header -->
    <?php cruxstore_post_thumbnail_image('full', 'img-responsive', false, false); ?>
    <div class="single-post-content">
        <div class="entry-content clearfix" itemprop="articleBody">
            <?php the_content(); ?>
            <?php
            if( ! post_password_required( ) ):
                wp_link_pages( array(
                    'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'cruxstore' ) . '</span>',
                    'after'       => '</div>',
                    'link_before' => '<span>',
                    'link_after'  => '</span>',
                    'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'cruxstore' ) . ' </span>%',
                    'separator'   => '<span class="screen-reader-text">, </span>',
                ) );
            endif;
            ?>
        </div><!-- .entry-content -->
    </div>
    <footer class="entry-footer">
        <?php
        cruxstore_post_meta_tags( );

        if(cruxstore_post_option(null, '_cruxstore_social_sharing', 'single_share_box', 1)) {
            cruxstore_share_box(null, 'square', 'share-it', true);
        }
        ?>
    </footer><!-- .entry-footer -->

</article><!-- #post-## -->

<?php


    if(cruxstore_post_option(null, '_cruxstore_author_info', 'single_author', 1)){
        // Author bio.
        get_template_part( 'templates/author-bio' );
    }

    if(cruxstore_post_option(null, '_cruxstore_prev_next', 'single_next_prev', 0)){
        cruxstore_post_nav();
    }


    if(cruxstore_post_option(null, '_cruxstore_related_acticles', 'single_related', 0)){
        cruxstore_related_article(null, cruxstore_option('single_related_type', 'categories'));
    }

?>