<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package freddo
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

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) : ?>
		<h2 class="comments-title"><i class="fa fa-comments-o spaceRight" aria-hidden="true"></i>
			<?php
			$comment_count = get_comments_number();
			if ( 1 === $comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html_e( 'One thought on &ldquo;%1$s&rdquo;', 'freddo' ),
					'<span>' . esc_html(get_the_title()) . '</span>'
				);
			} else {
				printf( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'freddo' ) ),
					esc_html( number_format_i18n( $comment_count ) ),
					'<span>' . esc_html(get_the_title()) . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => '70',
					'reply_text'        =>  '<span>' .esc_html__( 'Reply'  , 'freddo' ) . '<i class="fa fa-reply spaceLeft"></i></span>',
				) );
			?>
		</ol><!-- .comment-list -->

		<?php the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'freddo' ); ?></p>
		<?php
		endif;

	endif; // Check for have_comments().

	$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields =  array(
			'author' => '<p class="comment-form-author"><label for="author"><span class="screen-reader-text">' . esc_html__( 'Name *'  , 'freddo' ) . '</span><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' placeholder="' . esc_attr__( 'Name *'  , 'freddo' ) . '"/></label></p>',
			'email'  => '<p class="comment-form-email"><label for="email"><span class="screen-reader-text">' . esc_html__( 'Email *'  , 'freddo' ) . '</span><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' placeholder="' . esc_attr__( 'Email *'  , 'freddo' ) . '"/></label></p>',
			'url'    => '<p class="comment-form-url"><label for="url"><span class="screen-reader-text">' . esc_html__( 'Website'  , 'freddo' ) . '</span><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . esc_attr__( 'Website'  , 'freddo' ) . '"/></label></p>',
		);
		$required_text = esc_html__('Required fields are marked ', 'freddo').' <span class="required">*</span>';
		?>
		<?php comment_form( array(
			'fields' => apply_filters( 'comment_form_default_fields', $fields ),
			/* translators: %s: wordpress login url */
			'must_log_in' => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' , 'freddo' ), wp_login_url( apply_filters( 'the_permalink', esc_url( get_permalink( ) ) ) ) ) . '</p>',
			/* translators: 1: profile user link, 2: username, 3: logout link */
			'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'  , 'freddo' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', esc_url( get_permalink( ) ) ) ) ) . '</p>',
			'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Your email address will not be published.'  , 'freddo' ) . ( $req ? $required_text : '' ) . '</p>',
			'title_reply' => esc_html__( 'Leave a Reply'  , 'freddo' ),
			/* translators: %s: name of person to reply */
			'title_reply_to' => esc_html__( 'Leave a Reply to %s'  , 'freddo' ),
			'cancel_reply_link' => esc_html__( 'Cancel reply'  , 'freddo' ) . '<i class="fa fa-times spaceLeft"></i>',
			'label_submit' => esc_html__( 'Post Comment'  , 'freddo' ),
			'comment_field' => '<p class="comment-form-comment"><label for="comment"><span class="screen-reader-text">' . esc_html__( 'Comment *'  , 'freddo' ) . '</span><textarea id="comment" name="comment" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Comment *'  , 'freddo' ) . '"></textarea></label></p>',
		));
	?>

</div><!-- #comments -->
