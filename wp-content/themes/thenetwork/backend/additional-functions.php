<?php
/*
 *  ADDITIONAL FUNCTIONS
 *	FILES REMOVED FROM FUNCTIONS.PHP
 *
 */
 
// ** FUNCTION: CUSTOM COMMENTS ** //
// Custom callback to list comments in the hbd-theme style
function custom_comments($comment, $args, $depth) {
  $GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;
  ?>
	<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
		<div class="comment-author vcard"><?php commenter_link() ?></div>
		<div class="comment-meta"><?php printf(__('Posted %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this comment">Permalink</a>', 'hbd-theme'),
					get_comment_date(),
					get_comment_time(),
					'#comment-' . get_comment_ID() );
					edit_comment_link(__('Edit', 'hbd-theme'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
  <?php if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'hbd-theme') ?>
		  <div class="comment-content">
			<?php comment_text() ?>
		</div>
		<?php // echo the comment reply link
			if($args['type'] == 'all' || get_comment_type() == 'comment') :
				comment_reply_link(array_merge($args, array(
					'reply_text' => __('Reply','hbd-theme'),
					'login_text' => __('Log in to reply.','hbd-theme'),
					'depth' => $depth,
					'before' => '<div class="comment-reply-link">',
					'after' => '</div>'
				)));
			endif;
		?>
<?php } // end custom_comments


// ** FUNCTION: LIST PINGS ** //
// Custom callback to list pings
function custom_pings($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment;
		?>
			<li id="comment-<?php comment_ID() ?>" <?php comment_class() ?>>
				<div class="comment-author"><?php printf(__('By %1$s on %2$s at %3$s', 'hbd-theme'),
						get_comment_author_link(),
						get_comment_date(),
						get_comment_time() );
						edit_comment_link(__('Edit', 'hbd-theme'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
	<?php if ($comment->comment_approved == '0') _e('\t\t\t\t\t<span class="unapproved">Your trackback is awaiting moderation.</span>\n', 'hbd-theme') ?>
			<div class="comment-content">
				<?php comment_text() ?>
			</div>
<?php } // end custom_pings

	
// ** FUNCTION: AVATAR IMAGES ** //
// Produces an avatar image with the hCard-compliant photo class
function commenter_link() {
	$commenter = get_comment_author_link();
	if ( ereg( '<a[^>]* class=[^>]+>', $commenter ) ) {
		$commenter = ereg_replace( '(<a[^>]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = ereg_replace( '(<a )/', '\\1class="url "' , $commenter );
	}
	$avatar_email = get_comment_author_email();
	$avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( $avatar_email, 80 ) );
	echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
} 
// end commenter_link



?>
