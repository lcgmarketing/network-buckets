<?php


/*
 *
 *	FUNCTION: posts_feed
 *	PURPOSE: 
 *	INPUT: 
 *
 */

function posts_feed( $post_type, $filter, $style ) {

	// feedcount keeps track of where we are in the Wordpress post loop
	// feedcount == 0: Featured item 
	// feedcount > 1: list
	
	// Query posts based on category or tag?
	if( 'category' == $post_type )
		$query_args = array(
						'post_type' =>	'post',
						'order'		=>	'desc',
						'order_by'	=>	'date',
						'category_name' =>	$filter
					);
	
	else
		$query_args = array(
						'post_type' =>	'post',
						'order'		=>	'desc',
						'order_by'	=>	'date',
						'tag'	=>	$filter
						);


	// Get the Category / Tag name and description
	$term_object = get_term_by( 'slug', $filter, $post_type );
	
	// Take the taxonomy description over the actual name
	if( empty( $term_object->description ))
		$section_title = $term_object->name;
	else
		$section_title = $term_object->description;
	
	$dropping_queries = new WP_Query( $query_args );

	$feedcount = 0;

?>

    <div class="title">
        <h4><?php /* the category title/description */ echo $section_title; ?></h4>
    </div>

    <div class="feature-feed">

        <div class="row">
            <div class="col-md-12">

<?php

	while ( $dropping_queries->have_posts() && $feedcount < 6 ) : $dropping_queries->the_post();
		$feedooyalaID = get_post_meta( $dropping_queries->post->ID, 'videoid_text', true ); 
		$videothumbURL = get_post_meta( $dropping_queries->post->ID, 'videothumb', true );
	
?>
    
                <p><span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span></p> 

<?php
		$feedcount++;

	endwhile;
	wp_reset_postdata();

?>
            </div>
        </div>

    </div>

<?php

} // END post_feed


/*
 *
 *	FUNCTION: posts_feed
 *	PURPOSE: 
 *	INPUT: 
 *
 */
 
function postthumb_feed( $post_type, $filter, $style ) {

	// feedcount keeps track of where we are in the Wordpress post loop
	// feedcount == 0: Featured item 
	// feedcount > 1: list
	
	// Query posts based on category or tag?
	if( 'category' == $post_type )
		$query_args = array(
						'post_type' =>	'post',
						'order'		=>	'desc',
						'order_by'	=>	'date',
						'category_name' =>	$filter
					);
	
	else
		$query_args = array(
						'post_type' =>	'post',
						'order'		=>	'desc',
						'order_by'	=>	'date',
						'tag'	=>	$filter
						);


	// Get the Category / Tag name and description
	$term_object = get_term_by( 'slug', $filter, $post_type );
	
	// Take the taxonomy description over the actual name
	if( empty( $term_object->description ))
		$section_title = $term_object->name;
	else
		$section_title = $term_object->description;
	
	$dropping_queries = new WP_Query( $query_args );

	$feedcount = 0;

?>

    <div class="title">
        <h4><?php echo $section_title; ?></h4>
    </div>

    <div class="feature-feed">


<?php

	while ( $dropping_queries->have_posts() && $feedcount < 6 ) : $dropping_queries->the_post();
		$feedooyalaID = get_post_meta( $dropping_queries->post->ID, 'videoid_text', true ); 
		$videothumbURL = get_post_meta( $dropping_queries->post->ID, 'videothumb', true );
	
?>
        <div class="row the-article">
            <div class="col-md-3 article-thumb">
            	<?php
					$thumb_id = get_post_thumbnail_id();
					$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );
				?>
                <a href="<?php the_permalink(); ?>"><img src="<?php echo $thumb_url[0]; ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /></a>
            </div>
            
			<div class="col-md-9 article-content">
                <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5> 
                <?php the_excerpt(); ?>
            </div>
        </div>

<?php
		$feedcount++;

	endwhile;
	wp_reset_postdata();

?>

    </div>
    <!--// END FEATURE FEED //-->

<?php

} // END post_feed



/*
 *
 *	FUNCTION: network_feed
 *	PURPOSE: 
 *	INPUT: 
 *
 */

function network_feed( $post_type, $filter ) {
	
	//echo '<p>post_type = '.$post_type.'</p>';
	//echo '<p>filter = '.$filter.'</p>';
	
	$query_args = array(
					'post_type' =>	'pt_videolibrary',
					'order'		=>	'desc',
					'order_by'	=>	'date',
					'tax_query' =>	array(
										array( 
											'taxonomy' => 'pt_videocategory',
											'field' => 'slug',
											'terms' => $filter
										))
				);
	
	$network_query = new WP_Query( $query_args );

	// feedcount keeps track of where we are in the Wordpress post loop
	$feedcount = 0;

?>


    <div class="title">
        <h4><?php /* the category title/description */ echo 'On The Network'; ?></h4>
    </div>
    
    <div class="network-feed">
    	<div class="row"> 


<?php

	while( $network_query->have_posts() && $feedcount < 8 ) : $network_query->the_post();
		$feedooyalaID = get_post_meta( $network_query->post->ID, 'pt_video_ooyalaID', true ); 
		$videothumbURL = get_post_meta( $network_query->post->ID, 'pt_video_thumbnail', true );
		$videoDuration = get_post_meta( $network_query->post->ID, 'pt_video_duration', true );
		
		if( $feedcount == 4 ){
			echo '</div>';
			echo '<div class="row">';
		}
		
?>		
		
        <div class="col-md-3">
            <a href="<?php the_permalink(); ?>"><img src="<?php echo $videothumbURL; ?>" /></a>
            <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
        </div>

<?php 
		$feedcount++;

	endwhile;
	
	wp_reset_postdata();

?>
		</div>
    </div>
    <!--// END NETWORK-FEED //-->

<?php
} // END post_feed


/*
 *
 *	FUNCTION: contributor_feed
 *	PURPOSE: 
 *	INPUT: 
 *
 */

function contributor_feed( ) {

	echo '<div class="title"><h4>';
	$contributor_object = get_term_by( 'slug', 'contributor', 'category' );	
	echo $contributor_object->description;
	echo '</h4></div>';
	
	// feedcount keeps track of where we are in the Wordpress post loop
	$feedcount = 0;
	
	// NOTE: NEED TO GET CONTRIBUTOR CATEGORY ID FOR 'child_of' ARGUEMENT 
	$contributor_args = array(
				'type'	=> 'post',
				'child_of'	=> 0, 
				'orderby'	=> 'name',
				'order'	=> 'ASC',
				'number'	=> '',
				'taxonomy'	=> 'category',
				'pad_counts'	=> false 
			);
	
	$contributors = get_categories( $contributor_args );
	
	// List the childern of the Contributor Category
	
	foreach( $contributors as $contributor ){
		echo 'do this';
	}


} // END contributor_feed


/*
 *
 *	FUNCTION: video_feed
 *	PURPOSE: Outputs a horizontal feed of video based on a provided category.
 *	If no category is provided, it outputs a list of videos based on the most recent date
 *	INPUT: the_category
 *
 */

function video_feed( $the_category = NULL ) {

	$postcount = 0;

	if( $the_category != NULL ){
		$args_videofeed = array(
					'post_type' => 'pt_videolibrary',
					'order' => 'DESC',
					'orderby' => 'date',
					'tax_query' =>	array( array( 'taxonomy' => 'pt_videocategory', 'field' => 'slug', 'terms' => $the_category ) )
				);

		$category_slug = get_category_by_slug( $the_category );

		// Get the section title based on the Category
		// if the category description is blank, use the category name
		if( $category_slug->description ) : $section_title = $category_slug->description;
		else : $section_title = $category_slug->name;
		endif;

	}
	
	else{
		$args_videofeed = array(
						'post_type' => 'pt_videolibrary',
						'order' => 'DESC',
						'orderby' => 'date',
					);
		
		$section_title = "More Videos";
	}

	$query_videofeed = new WP_Query( $args_videofeed );
	
	if( $query_videofeed->have_posts() ) :

?>

<!-- VIDEO-FEED -->
<div id="video-feed">
	<h3><?php echo $section_title; ?></h3>
    <div class="row">
    	

<?php
		// We only want 4 videos (will add more with scroll later)
		while( $query_videofeed->have_posts() && $postcount <= 3 ) : $query_videofeed->the_post();
			
			$videothumbURL = get_post_meta( $query_videofeed->post->ID, 'pt_video_thumbnail', true );
?>
    	<div class="col-md-3 video-post-<?php the_ID();?>">
			<div class="small-thumb">
                <a href="<?php the_permalink(); ?>"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></a>
                <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>        
            </div>
        </div>

<?php
			$postcount++;
		endwhile; // Close While Loop
?>        


    </div>
</div>
<!--// END FEATURED VIDEOS //-->

<?php
		wp_reset_postdata(); // Restore original Post Data
	endif; // Close IF

} // END video_feed



/*
 *
 *	FUNCTION: liveshow
 *	PURPOSE: LiveShow controls the internal banner ads 
 *	If the time is between 9:00 and Noon PT it will return a live show banner and link to the live show
 *	Otherwise it will return a show specific banner
 *	INPUT: At this time, the function takes no input
 *
 */
 
function liveShow(){

	// Set the timezone to GMT-0
	date_default_timezone_set('Etc/GMT0');
	
	$show_start = "155500"; // 9:00 AM Pacific
	$show_end = "190000"; 	// Noon Pacific	
	
	$current_time = date("Gis"); //Get the current time
	
	// Check if the current time is between the show_start and show_end            
	if( $current_time >= $show_start && $current_time <= $show_end ){
		$bannerImage = "banner-live.jpg"; 							// Show the Live banner
		$videoURL = "http://www.therayluciashow.com/live/";			// Link to Live stream
	}
	else{
		$bannerImage = "banner-replay.jpg";								// Show the replay banner
		$videoURL = "http://www.raylucia.com/contact-us/ask-ray-new";  	// Just refresh the page
		$target = "target=\"_blank\"";				 					// Open the link in a new window
	}

}

/*
 *
 *	FUNCTION: twitter_feed
 *	PURPOSE: 
 *	INPUT: 
 *
 */

function twitter_feed(){
?>
    <div id="twitter-feed">
        <h4>Twitter Feed</h4>

		<a class="twitter-timeline" height="200" href="https://twitter.com/moneybizlife" data-widget-id="365497424321605632" data-chrome="noheader nofooter noborders transparent" data-tweet-limit="3">Tweets by @moneybizlife</a>
		
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>


<?php
}

/*
 *
 *	FUNCTION: social_connect
 *	PURPOSE: 
 *	INPUT: 
 *
 */

function social_connect( $showname, $facebook = null, $twitter = null, $youtube = null, $subscribe = null, $podcast = null ){
	
	if( empty( $showname ))
		$showname = 'MBLN';
	
	if( empty( $subscribe ))
		$subscribe = 'https://confirmsubscription.com/h/t/3660BAA22565B5BE';
?>

	<div id="social-connect">
        <h3>Get Social : Connect with <?php echo $showname; ?></h3>
        <ul>
            <li class="facebook"><a href="http://www.facebook.com/<?php echo $facebook; ?>"><span>G</span>Like Us on Facebook</a></li>
            <li class="twitter"><a href="http://www.twitter.com/<?php echo $twitter; ?>"><span>t</span>Follow Us on Twitter</a></li>
            <li class="youtube"><a href="http://www.youtube.com/<?php echo $youtube; ?>"><span>P</span>Subscribe on YouTube</a></li>
            <li class="rss"><a href="<?php echo $subscribe; ?>"><span>m</span>Get the Newsletter</a></li>
        </ul>
    </div>
<?php
}


/*
 *
 *	FUNCTION: feedCTA_ad
 *	PURPOSE: 
 *	INPUT: 
 *
 */

function feedCTA_ad(){
	return '<div class="four columns">' . do_shortcode('[ad]'). '</div>';
}


/*
 *
 *	FUNCTION: feedCTA_form
 *	PURPOSE: 
 *	INPUT: 
 *
 */

function feedCTA_form(){
	return '<div class="four columns">' . do_shortcode('[newsletter]') . '</div>';
}



?>
