<?php
/*
 *	Display posts from the custom post type Video Library
 *	Custom Post Type Name: pt_videolibrary
 * 
 *	Custom Fields Available: 
 *	- pt_video_ooyalaID
 *	- pt_video_subtitle
 * 	- pt_video_thumbnail
 *
 */

?>

<?php get_header( 'video' ); ?>
<!-- PAGE: single video-page -->
 
	<?php         
        // START THE LOOP
        if ( have_posts() ) : the_post();

                // Assign the custom variables Subtitle and Video ID
                $subtitle = get_post_meta( $post->ID, 'pt_video_subtitle', true );
                $ooyalaID = get_post_meta( $post->ID, 'pt_video_ooyalaID', true );
                $videothumbURL = get_post_meta( $post->ID, 'pt_video_thumbnail', true );
				
				$the_terms = wp_get_post_terms( $post->ID, 'pt_videoshow' );
				echo '<!-- show name = '. $the_terms[0]->slug .' -->';

				// Increase Post View count
				set_post_views( get_the_ID() );
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
							<?php get_template_part( 'content', 'videofeature' ); ?>
                            <?php get_template_part( 'content', 'videofeed' ); ?>
                        </div>
                        <!-- END COLUMN 1 -->
                        
		<?php
				/* Restore original Post Data */
				wp_reset_postdata();
				
            endif; // Close the IF/ELSE
        ?>

                        <!-- Right Column: Description, Social, and Advertising -->
                        <div id="the-sidebar" class="col-md-4 visible-md visible-lg">
                        	<?php get_template_part( 'content', 'videorelated' ); // related videos ?>
							<?php get_sidebar( 'video' ); // video sidebar. includes social and call to action ?>
                        </div>
    
                    </div>
                    
                </div>
            </div>
        	<!--// END VIDEOPLAYER //-->    
            
            


<?php get_footer( 'video' ); ?>