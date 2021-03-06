<?php
$classes = array('post-item post-layout-1 clearfix', $blog_atts['class']);
$image_size = 'kt_gird';
?>
<?php //print_r($blog_atts); ?>
<article <?php post_class($classes); ?>>
    <div class="list-media-wrapper">
        <?php if($blog_atts['show_meta']){ ?>
            <div class="entry-meta-data meta-date">
                <?php
                    if($blog_atts['show_date']){
                        kt_entry_meta_time($blog_atts['date_format']);
                    }
                ?>
            </div><!-- .entry-meta-data -->
        <?php } ?>
        <?php
            if($blog_atts['thumbnail_type'] == 'image'){
                kt_post_thumbnail_image($image_size, 'img-responsive');
            }else{
                kt_post_thumbnail($image_size, 'img-responsive');
            }
        ?>
    </div>
    <div class="entry-main-content">
        <div class="post-info">
            <div class="entry-ci">
                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <?php if($blog_atts['show_meta']){ ?>
                    <div class="entry-meta-data">
                        <?php
                        if($blog_atts['show_author']){
                            kt_entry_meta_author();
                        }
                        if($blog_atts['show_category']){
                            kt_entry_meta_categories();
                        }
                        if($blog_atts['show_comment']){
                            kt_entry_meta_comments();
                        }
                        if($blog_atts['show_view_number']){
                            kt_get_post_views( get_the_ID() );
                        }
                        if($blog_atts['show_like_post']){
                            kt_like_post();
                        }
                        ?>
                    </div><!-- .entry-meta-data -->
                <?php } ?>
                <?php if($blog_atts['show_excerpt']){ ?>
                    <div class="entry-excerpt">
                        <?php the_excerpt(); ?>
                    </div><!-- .entry-excerpt -->
                <?php } ?>
                <?php if($blog_atts['readmore']){ ?>
                    <?php $moreclass = ( $blog_atts['readmore'] == 'link' ) ? 'readmore-link' : 'btn '.$blog_atts['readmore']; ?>
                    <div class="entry-more">
                        <?php
                            printf( '<a href="%1$s" class="%2$s">%3$s</a>',
                                esc_url( get_permalink( get_the_ID() ) ),
                                $moreclass,
                                sprintf( esc_html__( 'Read more %s', 'wingman' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
                            );
                        ?>
                    </div><!-- .entry-more -->
                <?php } ?>
            </div>
        </div><!-- .post-info -->
    </div><!-- .entry-main-content -->
</article>