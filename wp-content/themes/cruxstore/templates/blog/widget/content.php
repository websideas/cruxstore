<div class="article-widget-item">
    <?php cruxstore_post_thumbnail_image( 'cruxstore_widgets', 'img-responsive' ); ?>
    <div class="content">
        <a class="title-link" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
        <div class="post-meta">
            <?php
            cruxstore_post_meta_author();
            cruxstore_post_meta_comments();
            ?>
        </div>
    </div>
</div>