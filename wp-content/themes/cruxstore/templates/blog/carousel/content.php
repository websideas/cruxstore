<div class="article-post-item">
    <?php cruxstore_post_thumbnail_image( 'cruxstore_small', 'img-responsive' ); ?>
    <div class="content">
        <a class="title-link" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
        <div class="post-meta">
            <?php
            cruxstore_post_meta_author();
            cruxstore_post_meta_date();
            ?>
        </div>
        <?php cruxstore_entry_excerpt(); ?>
    </div>
</div>