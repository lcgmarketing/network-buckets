<?php
/*
 * The template for displaying the video feeds on pages and single posts.
 *
 */

/*	// count the categories associated with the current post
 *	$mycats = get_categories ('include='.$post_category->cat_ID);
 *	$totalCategoryPosts = $mycats[0]->category_count;
*/

?>
<!-- Loop Single -->

		<?php 
			// START THE LOOP
			if ( have_posts() ) : the_post();
				//while ( have_posts() ) : 


					// Assign the custom variables Subtitle and Video ID
					$subtitle = get_post_meta( $post->ID, 'subtitle_text', true );
					$ooyalaID = get_post_meta( $post->ID, 'videoid_text', true );
					$actionTitle = get_post_meta( $post->ID, 'action_text', true );					
					$actionURL = get_post_meta( $post->ID, 'actionurl_text', true );
					$video_postID = get_the_ID();
		?>

        <script>
			// Set the default videoID
			var videoID = '<?php echo $ooyalaID; ?>';
			
			// Get the current page URL
			var url = document.URL;
			
			// Determine if a new video ID is passed in the URL
			// If yes, set videoID to the ID passed in the URL and play it
			// If not, continue to use the default ID
			if(url.lastIndexOf('ooyalaid=') > 0)
				var videoID = url.substring(url.lastIndexOf('ooyalaid=')+9);
		</script>
        
        	<div id="videoPlayer">
                <div class="container">
                    <!-- Video Title -->
                    <div class="video-title" id="post-<?php the_ID(); ?>">
                        <h1><?php the_title(); ?></h1>
                        <h2><?php echo $subtitle; ?></h2>
                    </div>
                    
                    <!-- The Video and Related -->
                    <div class="row">
                    
                    	<!-- Left Column: Video, Social Sharing, and Video Feed -->
                    	<div class="col-md-8">
							<?php get_template_part( 'content', 'videofeature' ); // Ooyala Video Code ?>
                            <?php video_feed( 'featured' ); ?>
                            <?php video_feed( 'show-archives' ); ?>
                        </div>
                        
			<?php
                // ENDWHILE and ENDIF need to be moved	
                    //endwhile;
                endif;
            ?>

