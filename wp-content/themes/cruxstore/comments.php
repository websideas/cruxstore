<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title post-single-heading"><span>
            <?php
            printf( _nx( 'One comment', '%1$s comments', get_comments_number(), 'comments title', 'cruxstore' ),
                number_format_i18n( get_comments_number() ), get_the_title() );
            ?></span>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback' => 'cruxstore_comments'
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php cruxstore_comment_nav(); ?>

    <?php endif; // have_comments() ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'cruxstore' ); ?></p>
    <?php endif; ?>

    <?php


    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $html_req = ( $req ? " required='required'" : '' );

    $required = ' '.esc_html__('(required)', 'cruxstore');

    $new_fields = array(
        'author' => '<div class="comment-form-fields row clearfix"><p class="comment_field-column col-md-6 comment-form-author">' .
            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"  placeholder="'.esc_html__('Name', 'cruxstore').'"'. $aria_req . $html_req .'/></p>',
        'email'  => '<p class="comment_field-column col-md-6 comment-form-email">' .
            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="'.esc_html__('Email', 'cruxstore').'"'. $aria_req . $html_req.'/></p></div>',
        'url'    => '<p class="comment-form-url">' .
            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="'.esc_html__('Website', 'cruxstore').'" /></p>',
    );

    $comments_args = array(
        'label_submit'      => esc_html__( 'Post Comment','cruxstore' ),
        'fields' => apply_filters( 'comment_form_default_fields', $new_fields ),
        'comment_field' => '<p><textarea id="comment" name="comment" placeholder="'.esc_html__('Your Comment', 'cruxstore').'"  aria-required="true" rows="6"></textarea></p>',
        'class_submit'      => 'btn btn-dark-b btn-block btn-lg',
        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title post-single-heading"><span>',
        'title_reply_after' => '</span></h3>'
    );

    ?>
    <?php comment_form($comments_args); ?>
</div><!-- .comments-area -->
