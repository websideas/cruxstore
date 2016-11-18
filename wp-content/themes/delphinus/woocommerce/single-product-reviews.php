<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.2
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="row">
	<div id="comments" class="col-md-6 col-sm-7">
		<h3 class="comments-title"><?php
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) )
				printf( _n( '%s review', '%s reviews', $count, 'delphinus' ), $count );
			else
				_e( 'Reviews', 'woocommerce' );
		?></h3>

		<?php if ( have_comments() ) : ?>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->id ) ) : ?>

		<div id="review_form_wrapper" class="col-md-5 col-md-offset-1 col-sm-5">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();

					$comment_form = array(
						'title_reply'          => have_comments() ? esc_html__( 'Here you can review this item.', 'delphinus' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
						'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
						'comment_notes_before' => '',
						'comment_notes_after'  => '',
                        'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title comments-title">',
						'fields'               => array(
							'author' => '<p class="comment-form-author">' .
							            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="'.esc_html__( 'Name', 'woocommerce' ).' *'.'" aria-required="true" /></p>',
							'email'  => '<p class="comment-form-email">' .
							            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"  placeholder="'.esc_html__( 'Email', 'woocommerce' ).' *'.'"  size="30" aria-required="true" /></p>',
						),
						'label_submit'  => esc_html__( 'Submit', 'woocommerce' ),
						'logged_in_as'  => '',
						'comment_field' => '<p class="comment-form-comment"><textarea placeholder="'.esc_html__( 'Your Review', 'woocommerce' ).'" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
                        'submit_button' => '',
                        'class_submit'  => 'btn btn-block btn-dark-b',
					);

					if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
						$comment_form['must_log_in'] = '<p class="must-log-in">' .  sprintf( esc_html__( 'You must be <a href="%s">logged in</a> to post a review.', 'woocommerce' ), esc_url( $account_page_url ) ) . '</p>';
					}

					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
                        $comment_form['submit_button'] = '<span class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'woocommerce' ) .'</label><select name="rating" id="rating">
							<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . esc_html__( 'Very Poor', 'woocommerce' ) . '</option>
						</select></span>';
					}

                    $comment_form['submit_button'] .= '<span class="comment-form-button"><button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button></span>';

					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required col-md-offset-1 col-md-5"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
