<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?> itemscope itemtype="http://schema.org/BlogPosting">
    <div class="blog-post-content">
        <?php cruxstore_post_thumbnail_image('cruxstore_grid'); ?>
        <div class="blog-post-inner">
            <?php the_title( sprintf( '<h2 class="entry-title" itemprop="name headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <?php cruxstore_entry_meta(array('author', 'comments')); ?>
        </div>
    </div>
</article><!-- #post-## -->
