<?php
/*
 *	Display the related / "You Might Like" videos
 *	Queries the custom post type: pt_videolibrary
 * 
 *	Custom Fields Available: 
 *	- pt_video_ooyalaID
 *	- pt_video_subtitle
 * 	- pt_video_thumbnail
 *
 */

?>
    
<div id="video-feed"> <!-- VIDEO-FEED -->

<?php
	//$post_categories = array( 'featured', 'popular', 'show-archives' );

	$postcount = 0;

	$videofeedargs = array(
					'post_type' => 'pt_videolibrary',
					'order' 	=> 'DESC',
					'orderby' 	=> 'date',
					'post__not_in'	=> array( $post->ID ),
					'tax_query'	=>	array(
										array( 
											'taxonomy' => 'pt_videocategory',
											'field' => 'slug',
											'terms' => 'featured'
										))
				);

	$videofeedquery = new WP_Query( $videofeedargs );
	
	$theTerm = get_term_by( 'slug', 'featured', 'pt_videocategory' );
	
	// Get the section title based on the Category
	// if the category description is blank, use the category name
	if( $theTerm->description ) : $section_title = $theTerm->description;
	else : $section_title = $theTerm->name;
	endif;
	
	if( $videofeedquery->have_posts() ) :
?>
	<?php // Insert Section Title - Based on Category Description, if available. Fallback to Category Name ?>
	<div class="video-list <?php echo $theTerm->slug; ?>">
        <h3><?php echo $section_title; ?></h3>
    
        <div class="row">
            
    <?php
            // We only want 4 videos (will add more with scroll later)
            while( $videofeedquery->have_posts() && $postcount <= 3 ) : $videofeedquery->the_post();
                
                $videothumbURL = get_post_meta( $videofeedquery->post->ID, 'pt_video_thumbnail', true );
    ?>
            <div class="col-sm-3 video-post-<?php the_ID();?> count-<?php echo $postcount ?>">
                <div class="small-thumb">
                    <a href="<?php the_permalink(); ?>"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></a>
                    <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>        
                </div>
            </div>
    
    <?php
                $postcount++;
            endwhile; // Close While Loop
            wp_reset_postdata(); // Restore original Post Data
    ?>        
    
        </div>
        <!--// END ROW: FEATURED VIDEOS //-->
    </div>
    <?php endif; // Close IF 	
        // END FEATURED VIDEOS ?>

<?php

	/* Get the current show */
	
	// Get the terms for the current video currently running. 
	// The live stream and individual show pages do now use terms, in that case, use the page slug
	$current_show = wp_get_post_terms( $post->ID, 'pt_videoshow');
	
	if( !$current_show )
		$showname = get_post( $post )->post_name;
	else
		$showname = $current_show[0]->slug;

?>

<?php // START FULL EPISODES ?>            

	<?php
        $postcount = 0;

		echo '<!-- showname = ' . $showname . ' -->';
		
		// If the page is Watch Live, pull the latest full episodes on MBLN, regardless of Show Name
		if( $showname == 'watch-live' ){ 
			$feedargs = array(
						'post_type' => 'pt_videolibrary', 
						'order' 	=> 'DESC',
						'orderby' 	=> 'date',
						'post__not_in'	=> array( $post->ID ),
						'tax_query' =>	array( array( 
												'taxonomy' => 'pt_videocategory',
												'field' => 'slug',
												'terms' => 'full-episode' ))
					);			
			
			$section_title = 'the Shows on MBLN';
		}
		
		else{
			$feedargs = array(
						'post_type' => 'pt_videolibrary', 
						'order' 	=> 'DESC',
						'orderby' 	=> 'date',
						'post__not_in'	=> array( $post->ID ),
						'tax_query' =>	array( 'relation' => 'AND',
											array( 
												'taxonomy' => 'pt_videoshow',
												'field' => 'slug',
												'terms' => $showname
											),
											array( 
												'taxonomy' => 'pt_videocategory',
												'field' => 'slug',
												'terms' => 'full-episode' ))
					);		

			$theTerm = get_term_by( 'slug', $showname, 'pt_videoshow' );
			
			// Get the section title based on the Category
			// if the category description is blank, use the category name
			if( $theTerm->description ) : $section_title = $theTerm->description;
			else : $section_title = $theTerm->name;
			endif;

		}
		
        $categoryQuery = new WP_Query( $feedargs );
		
		if( $categoryQuery->have_posts() ) :
?>

	<div class="video-list <?php echo $theTerm->slug; ?>">
        <h3>Full Episodes from <?php echo $section_title; ?></h3>
    
        <div class="row">
        
    <?php
            while( $categoryQuery->have_posts() && $postcount < 4  ) : $categoryQuery->the_post();
                $videothumbURL = get_post_meta( $categoryQuery->post->ID, 'pt_video_thumbnail', true );
                
                $postcount++;
        ?>
        
            <div class="col-sm-3 video-post-<?php the_ID();?>">
                <div class="small-thumb">
                    <a href="<?php the_permalink(); ?>"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></a>
                    <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>        
                </div>
            </div>
    
        <?php
            endwhile;
            wp_reset_postdata(); // Restore original Post Data
        ?>
    
        </div>
        <!--// END ROW //-->
        
    </div>
    <?php endif; // Close IF ?>    
    
<?php // START SHOW CLIPS ?>            

	<?php
        $postcount = 0;
		
		echo '<!-- showname = ' . $showname . ' -->';
		
		if( $showname == 'watch-live' ){ 
			$feedargs = array(
						'post_type' => 'pt_videolibrary', 
						'order' 	=> 'DESC',
						'orderby' 	=> 'date',
						'post__not_in'	=> array( $post->ID ),
						'tax_query' =>	array( array( 
												'taxonomy' => 'pt_videocategory',
												'field' => 'slug',
												'terms' => 'video-clip' ))
					);
			
			$section_title = 'MBLN Shows';
		}
		
		else{
			$feedargs = array(
						'post_type' => 'pt_videolibrary', 
						'order' 	=> 'DESC',
						'orderby' 	=> 'date',
						'post__not_in'	=> array( $post->ID ),
						'tax_query' =>	array( 'relation' => 'AND',
											array( 
												'taxonomy' => 'pt_videoshow',
												'field' => 'slug',
												'terms' => $showname
											),
											array( 
												'taxonomy' => 'pt_videocategory',
												'field' => 'slug',
												'terms' => 'video-clip'
											))
					);

			$theTerm = get_term_by( 'slug', $showname, 'pt_videoshow' );
			
			// Get the section title based on the Category
			// if the category description is blank, use the category name
			$section_title = $theTerm->name;

		}

        $categoryQuery = new WP_Query( $feedargs );
		
		if( $categoryQuery->have_posts() ) :
?>
	<div class="video-list <?php echo $theTerm->slug; ?>">
        <h3>Video Clips from <?php echo $section_title; ?></h3>
    
        <div class="row">
        
    <?php
            while( $categoryQuery->have_posts() && $postcount < 4  ) : $categoryQuery->the_post();
                $videothumbURL = get_post_meta( $categoryQuery->post->ID, 'pt_video_thumbnail', true );
                
                $postcount++;
        ?>
        
            <div class="col-sm-3 video-post-<?php the_ID();?>">
                <div class="small-thumb">
                    <a href="<?php the_permalink(); ?>"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></a>
                    <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>        
                </div>
            </div>
    
        <?php
            endwhile;
            wp_reset_postdata(); // Restore original Post Data
        ?>
    
        </div>
        <!--// END ROW //-->
        
    </div>
    <?php endif; // Close IF ?>
	
</div>
<!--// END VIDEO-FEED //-->