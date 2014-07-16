<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

    <div id="homeSlider">
	    <?php echo get_new_royalslider(1); ?>
    </div>
    
	<?php
		// START THE LOOP
		if ( have_posts() ) : 
			while ( have_posts() ) : the_post();

				// Assign the custom variables Subtitle and Video ID
				$subtitle = get_post_meta( $post->ID, 'subtitle_text', true );
				$ooyalaID = get_post_meta( $post->ID, 'videoid_text', true );
				$videothumbURL = get_post_meta( $post->ID, 'videothumb', true );

			endwhile;

		else: echo "<p>Sorry, no posts here</p>";
		
		endif;
	?>
    
    
