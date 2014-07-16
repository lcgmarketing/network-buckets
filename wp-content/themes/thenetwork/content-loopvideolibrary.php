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

<?php 
	global $post;
	
	// Get the show name based on page slug
	$show_name = get_post( $post )->post_name;
	
	// if the viewer hit the watch-live page after live show hours, just display the latest video on the network (based on time)
	if( $show_name == 'watch-live' )
		$videolibrary_args = array(
								'post_type' => 'pt_videolibrary',
								'order'		=>	'DESC',
								'order_by'	=>	'date',
							);

	// else, pull the latest video based on the current show
	else
		$videolibrary_args = array(
								'post_type' => 'pt_videolibrary',
								'order'		=>	'DESC',
								'order_by'	=>	'date',
								'tax_query' =>	array( array( 
													'taxonomy' => 'pt_videoshow',
													'field' => 'slug',
													'terms' => $show_name
												)) // filter video based on show_name. $show_name is defined by page slug
							);
							
	$videolibrary_query = new WP_Query( $videolibrary_args );
?>

		<div id="videoPlayer">
			<div class="container">

<?php        

        // START THE LOOP
        if ( $videolibrary_query->have_posts() ) : 
            //while ( $videolibrary_query->have_posts() ) : 
				
				$videolibrary_query->the_post();

                // Assign the custom variables Subtitle and Video ID
                $subtitle = get_post_meta( $post->ID, 'pt_video_subtitle', true );
                $ooyalaID = get_post_meta( $post->ID, 'pt_video_ooyalaID', true );
                $videothumbURL = get_post_meta( $post->ID, 'pt_video_thumbnail', true );
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
		?>
        
        <?php else : // sorry, no posts ?>
                    <!-- Video Title -->
                    <div class="video-title" id="post-<?php the_ID(); ?>">
                        <h1>Sorry, there are no videos at this time</h1>
                    </div>
                    
                    <!-- The Video and Related -->
                    <div class="row">
                    
                    	<!-- Left Column: Video, Social Sharing, and Video Feed -->
                    	<div class="col-md-8">
							<p>Come back soon for more videos from the Money Biz Life Network</p>
                        </div>
                        <!-- END COLUMN 1 -->
		<?php endif; // Close the IF/ELSE ?>