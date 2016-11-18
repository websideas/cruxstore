<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?> itemscope itemtype="http://schema.org/BlogPosting">
    <div class="blog-post-content">
        <?php cruxstore_post_thumbnail_image('cruxstore_classic'); ?>
        <div class="blog-post-inner">
            <?php the_title( sprintf( '<h3 class="entry-title" itemprop="name headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
            <?php cruxstore_entry_meta(); ?>
            <div class="entry-content" itemprop="articleBody">
                <?php
                /* translators: %s: Name of current post */
                the_content( sprintf(
                    wp_kses( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'cruxstore' ), array( 'span' => array('class' => array()))),
                    get_the_title()
                ) );

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
        </div>
    </div>
</article><!-- #post-## -->
