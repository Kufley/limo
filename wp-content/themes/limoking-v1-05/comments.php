<?php
/**
 * The template for displaying Comments.
 */

if ( post_password_required() )
	return;
?>

<div id="comments" class="limoking-comments-area">
<?php if(have_comments()){ ?>
	<h3 class="comments-title">
		<?php 
			if( get_comments_number() <= 1 ){
				echo get_comments_number() . ' ' . esc_html__('Response', 'limoking'); 
			}else{
				echo get_comments_number() . ' ' . esc_html__('Responses', 'limoking'); 
			}
		?>
	</h3>

	<ol class="commentlist">
		<?php wp_list_comments(array('callback' => 'limoking_comment_list', 'style' => 'ol')); ?>
	</ol><!-- .commentlist -->

	<?php if (get_comment_pages_count() > 1 && get_option('page_comments')){ ?>
		<nav id="comment-nav-below" class="navigation" role="navigation">
			<h1 class="assistive-text section-heading"><?php echo esc_html__( 'Comment navigation', 'limoking' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'limoking' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'limoking' ) ); ?></div>
		</nav>
	<?php } ?>

<?php } ?>

<?php 
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ($req ? " aria-required='true'" : '');
	
	$args = array(
		'id_form'           => 'commentform',
		'id_submit'         => 'submit',
		'title_reply'       => esc_html__('Leave a Reply', 'limoking'),
		'title_reply_to'    => esc_html__('Leave a Reply to %s', 'limoking'),
		'cancel_reply_link' => esc_html__('Cancel Reply', 'limoking'),
		'label_submit'      => esc_html__('Post Comment', 'limoking'),
		'comment_notes_before' => '',
		'comment_notes_after' => '',

		'must_log_in' => '<p class="must-log-in">' .
			sprintf( wp_kses(__('You must be <a href="%s">logged in</a> to post a comment.', 'newsstand'), array('a'=>array('href'=>array(),'title'=>array()))),
			wp_login_url(apply_filters( 'the_permalink', get_permalink())) ) . '</p>',
		'logged_in_as' => '<p class="logged-in-as">' .
			sprintf( wp_kses(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'newsstand'), array('a'=>array('href'=>array(),'title'=>array()))),
			admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink( ))) ) . '</p>',

		'fields' => apply_filters('comment_form_default_fields', array(
			'author' =>
				'<div class="comment-form-head">' .
				'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
				'" data-default="' . esc_attr(esc_html__('Name*', 'limoking')) . '" size="30"' . $aria_req . ' />',
			'email' => 
				'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
				'" data-default="' . esc_attr(esc_html__('Email*', 'limoking')) . '" size="30"' . $aria_req . ' />',
			'url' =>
				'<input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) .
				'" data-default="' . esc_attr(esc_html__('Website', 'limoking')) . '" size="30" /><div class="clear"></div></div>'
		)),
		'comment_field' =>  '<div class="comment-form-comment">' .
			'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
			'</textarea></div>'
		
	);
	comment_form($args); 

?>
</div><!-- limoking-comment-area -->