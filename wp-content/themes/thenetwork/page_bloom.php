<?php
/*
 *	Template Name: Video Bloomberg Style
 *
 *	Custom Fields Available: 
 *	- subtitle_text
 *	- videoid_text
 * 	- action_text
 * 
 *	- pt_video_ooyalaID
 *	- pt_video_subtitle
 * 	- pt_video_thumbnail
 *
 *	Defined Schedule (as of 9/23/2013; all times Pacific)
 * 	9:00 am - 10:00 am 	= The Ray Lucia Show (ray-lucia-show)
 * 	10:00 am - Noon 	= Boomers' Braintrust (boomers-braintrust)
 *
 */ 

?>

<?php get_header( 'video' ); ?>
<!-- PAGE: video-bloom -->

<?php

	// Set Show Defaults
	// The Ray Lucia Show
	$start_ray 	= '85600';	// 8:56 am
	$end_ray	= '100000'; // 10:00 am
	
	// Boomers' Braintrust
	$start_boomers	= '95600'; 	// 9:56 am
	$end_boomers	= '120000'; // 12:00 pm

	// Smart Life with Dr. Gina
	$start_gina	= '125600';	// 12:56 pm
	$end_gina	= '140000'; // 2:00 pm
	
	// Network Start and End
	$start_network = '84500';	// Live broadcast starts with The Ray Lucia Show (8:45 am)
	$end_network = $end_boomers; 	// Live broadcast ends with Boomers Braintrust. 
	
	// Re-run Flags
	$live_gina = TRUE;
	$live_boomers = TRUE;
	$live_ray = TRUE;
	

?>

<?php 
	global $post;
	
	// Get the show name based on page slug
	$show_name = get_post( $post )->post_name;
	
	//echo '<!-- show_name = '. $show_name . ' -->';
	
	$actionTitle = get_post_meta( $post->ID, 'action_text', true );
	$actionURL = get_post_meta( $post->ID, 'actionurl_text', true );
	
	// Get the current time
	$current_time = date( 'Gis', current_time( 'timestamp' ));
	//echo '<!-- current time = ' . $current_time. ' -->';

	switch( $show_name ){
		
		case 'ray-lucia-show' :
			if( $current_time >= $start_ray && $current_time <= $end_ray ){
				
				if( $live_ray ){ // Live
					echo '<!-- RAY IS LIVE -->';
					get_template_part( 'content', 'loopvideosingle' );	// use the standard loop
				}
				else{
					echo '<!-- RAY IS RE-RUN -->';
					get_template_part( 'content', 'loopvideorerun' );	// use the rerun loop
				}
				
			}
			else
				get_template_part( 'content', 'loopvideolibrary' );	// query the video library and pull the latest video based on the show
		break;
		
		case 'boomers-braintrust' :
			if( $current_time >= $start_boomers && $current_time <= $end_boomers ){
				echo '<!-- THE BRAINTRUST IS LIVE -->';	
				get_template_part( 'content', 'loopvideosingle' );	// use the standard loop
			}
			else
				get_template_part( 'content', 'loopvideolibrary' );	// query the video library and pull the latest video based on the show
		break;

		case 'smart-life-dr-gina' :
			if( $current_time >= $start_gina && $current_time <= $end_gina ){
				
				if( $live_gina ){ // Live
					echo '<!-- GINA IS LIVE -->';
					get_template_part( 'content', 'loopvideosingle' );	// use the standard loop
				}
				else{
					echo '<!-- GINA IS RE-RUN -->';
					get_template_part( 'content', 'loopvideorerun' );	// use the rerun loop
				}
			}
			else
				get_template_part( 'content', 'loopvideolibrary' );	// query the video library and pull the latest video based on the show
		break;
		
		case 'hacksaw-headlines' :
			// hacksaw has no live content
			echo '<!-- HACKSAW HEADLINES -->';
			get_template_part( 'content', 'loopvideolibrary' );	// query the video library and pull the latest video based on the show
		break;
		
		// page = watch-live
		default :
			// BLOCK 1: 9:00 - 10:00
			if( $current_time >= $start_ray && $current_time <= $end_ray ){
				if( $live_ray ){ // Live
					echo '<!-- WATCHING LIVE: The Ray Lucia Show -->';
					get_template_part( 'content', 'loopvideosingle' );	// use the standard loop
				}
				else{ // In a rerun
					echo '<!-- WATCHING A RERUN -->';
					get_template_part( 'content', 'loopvideorerun' );	// use the rerun loop
				}

				//echo '<!-- WATCHING LIVE: The Ray Lucia Show -->';
				//get_template_part( 'content', 'loopvideosingle' );	// use the standard loop
				
				//echo '<!-- WATCHING A RERUN -->';
				//get_template_part( 'content', 'loopvideorerun' );	// use the rerun loop
			}
			
			// BLOCK 1: 10:00 - Noon
			if( $current_time >= $start_boomersray && $current_time <= $end_boomers ){ 
				if( $live_boomers ){ // Live
					echo '<!-- WATCHING LIVE: Boomers Braintrust -->';
					get_template_part( 'content', 'loopvideosingle' );	// use the standard loop
				}
				else{ // In a rerun
					echo '<!-- WATCHING A RERUN -->';
					get_template_part( 'content', 'loopvideorerun' );	// use the rerun loop
				}			
			}
			
			// BLOCK 2: 1:00 - 2:00
			elseif( $current_time >= $start_gina && $current_time <= $end_gina ){
				if( $live_gina ){ // Live
					echo '<!-- WATCHING LIVE: Smart Life with Dr. Gina -->';
					get_template_part( 'content', 'loopvideosingle' );	// use the standard loop
				}
				else{ // In a rerun
					echo '<!-- WATCHING A RERUN -->';
					get_template_part( 'content', 'loopvideorerun' );	// use the rerun loop
				}
			}
			
			else
				get_template_part( 'content', 'loopvideolibrary' );	// query the video library and pull the latest video based on the show
		break;
	
	}
	
?>
                        <!-- Right Column: Description, Social, and Advertising -->
                        <div id="the-sidebar" class="col-md-4 visible-md visible-lg">
                        
                        	<?php get_template_part( 'content', 'videorelated' ); // Related Video Feed ?>
							<?php get_sidebar( 'video' ); ?>
                                                        
                        </div>
                        <!--// RIGHT COLUMN //-->
    
                    </div>
                    
                </div>
            </div>
        	<!--// END VIDEOPLAYER //-->
            
<?php get_footer( 'video' ); ?>