<?php
/**
 * Addtional Shortcodes 
 *
 * @package WordPress
 */
 
//function register_shortcodes(){
//	add_shortcode( 'sayhello', 'init_sayhello' );
//}



function init_sayhello() {
	
	$return_string = 'hi';
	
	return $return_string;
}


/* 
 * Shortcode: Category Post
 * Creates a basic post feed based on provided category 
 * Takes the following arguments: 
 * Content: Category Slug
 *
 */
function init_categorypost( $atts, $content ) {
    extract(shortcode_atts(array(
        'category' 	=>	'press-release',
		'title'		=>	'',
		'date'		=>	'yes'
        ), $atts));
		
	$postargs = array(
					'order' => 'DESC',
					'orderby' => 'date',
					'category_name' => $category
					);
	
	$postquery = new WP_Query( $postargs );
		
	if( $postquery->have_posts() ):
		if( !empty( $title ) )
			 $output = '<h3>'.$title.'</h3>';
		else
			$output = '';

		while( $postquery->have_posts() ) :
			$postquery->the_post();

			$output .= '<div class="row">';
			
			$output .= '<div class="col-md-12">';
			
			if( $date == 'yes' )
				$output .= '<span class="biline">' . get_the_date( 'F j, Y' ) . '</span>';
			
			$output .= '<h4><a href="' . get_permalink() .'" title="' . get_the_title() .'">' . get_the_title() . '</a></h4>'; 
			
			$output .= '</div><!-- END SPAN12 -->';
			
			$output .= '</div><!-- END ROW -->';

		endwhile;
	
	endif;

	
	wp_reset_postdata(); // Restore original Post Data 

    return $output;
}

add_shortcode( 'categoryfeed', 'init_categorypost' );



function init_articlefeed( $atts, $content ) {

	//$post_type
	//$filter
	//$style
	
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

	$output = '<div class="title">';
	$output .= '<h4>' . $section_title . '</h4>';
    $output .= '</div>';
	
	$output .= '<div class="feature-feed">';

	while ( $dropping_queries->have_posts() && $feedcount < 6 ) : $dropping_queries->the_post();
		$feedooyalaID = get_post_meta( $dropping_queries->post->ID, 'videoid_text', true ); 
		$videothumbURL = get_post_meta( $dropping_queries->post->ID, 'videothumb', true );
	

	$output .= '<div class="row the-article">';
	$output .= '<div class="col-md-3 article-thumb">';

	$thumb_id = get_post_thumbnail_id();
	$thumb_url = wp_get_attachment_image_src( $thumb_id, 'full', true );

	$output .= '<a href="' .get_the_permalink() . '"><img src="' . $thumb_url[0] .'" alt="' . get_the_title() .'" title="' . get_the_title() . '" /></a>';
	$output .= '</div>';

	$output .= '<div class="col-md-9 article-content">';
	$output .= '<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>';
    $output .= the_excerpt(); 
    $output .= '</div>';
    $output .= '</div>';

	$feedcount++;

	endwhile;
	wp_reset_postdata();

	$output .= '</div> <!--// END FEATURE FEED //-->';


} // END post_feed
add_shortcode( 'articlefeed', 'init_articlefeed' );


/*
 *	Shortcode: Newsletter
 *	Creates a newsletter form powered by Campaign Monitor
 *	Inputs: None
 */
function init_newsletter( $atts, $content ) {
	
	$output = '<div class="four columns">
		<div id="subscribe">
			<h2>Join the Mailing List</h2>
			<p>Not only does Ray host a three-hour talk show, he also tours the country helping people just like you with their financial and retirement issues. Join the mailing list today and be the first to know when Ray comes to your town!</p>
			
			<!-- Form -->
			<form action="http://rjlmarketing.createsend.com/t/t/s/nhjjt/" method="post" id="subForm">
				<div>
					<span class="label"><label for="name">Name:</label></span>
					<span><input type="text" name="cm-name" id="name" size="25" /></span>
				</div>
				<div>
					<span class="label"><label for="nhjjt-nhjjt">Email Address:</label></span>
					<span><input type="text" name="cm-nhjjt-nhjjt" id="nhjjt-nhjjt" size="25" /></span>
				</div>
				
				<div>
					<span class="button"><input type="submit" value="Subscribe" /></span>
				</div>
			</form>
		
		</div>
	</div>';

	return $output;
	
}
add_shortcode( 'newsletter', 'init_newsletter' );

/* 
 * Shortcode: Button!
 * Creates an button for links. Takes the following arguments: 
 * url: Link for the Button
 * color: Color for the button
 * Content: Text to in the button. 
 *
 */
 
function init_button( $atts, $content ) {
    extract(shortcode_atts(array(
        'url' => '#',
        'color' => 'blue',
		'size' => ''
        ), $atts));
		
	// Color Switch
	switch( $color ){
		case 'blue':
		$color_style = 'btn-primary ';
		break;
		
		case 'red':
		$color_style = 'btn-danger ';
		break;
		
		case 'teal':
		$color_style = 'btn-info ';
		break;

		case 'green':
		$color_style = 'btn-success ';
		break;

		case 'yellow':
		$color_style = 'btn-warning ';
		break;

		case 'black':
		$color_style = 'btn-inverse ';
		break;
		
		default:
		$color_style = 'btn-primary ';
		break;
	}

	// Color Switch
	switch( $size ){
		case 'large':
		$size_style = 'btn-large';
		break;
		
		case 'small':
		$size_style = 'btn-small';
		break;

		case 'mini':
		$size_style = 'btn-mini';
		break;

		default:
		$size_style = '';
		break;
	}
	
	$output = '<a class="btn ' . $color_style . $size_style . '" href="'. $url .'">' . $content. '</a>';

    return $output;

}
add_shortcode( 'button', 'init_button' );


/* 
 * Shortcode: Podcast Feed
 * Creates a podcast feed based on provided show
 * Takes the following arguments: 
 * Show: 
 *
 */
function init_podcastfeed( $atts, $content ) {
    extract(shortcode_atts(array(
        'show' 		=>	'',
		'rss'		=>	'',
		'itunes'	=>	''
        ), $atts));
	
	if( $show ) // Show has been provdied, pull only that show.
		$podcastfeedargs = array(
						'post_type' => 'pt_mblnpodcasts',
						'posts_per_page'	=>	'10',
						'order' 	=> 'DESC',
						'orderby' 	=> 'date',
						'tax_query'	=>	array(
											array( 
												'taxonomy' => 'tax_podcasting',
												'field' => 'slug',
												'terms' => $show
											))
					);
					
	else // Show is empty, get all Shows
		$podcastfeedargs = array(
						'post_type' => 'pt_mblnpodcasts',
						'post_per_page'	=>	10,
						'order' 	=> 'DESC',
						'orderby' 	=> 'date',
					);
	
	$postcount = 0;

	$podcastfeedquery = new WP_Query( $podcastfeedargs );
	
	//$theTerm = get_term_by( 'slug', 'featured', 'pt_videocategory' );

	if( $podcastfeedquery->have_posts() ):

		$output .= '<div class="row">';
		$output .= '<div class="col-md-12">';
		
		$rss_url = '<a href="'. $rss .'" target="_blank">RSS</a>';
		$itunes_url = '<a href="'. $itunes .'" target="_blank">iTunes</a>';
		
		if( $rss != '' || $itunes != '' )
			$output .= '<p>Subscribe to the podcast: ';
		
		if( $rss != '' )
			$output .= $rss_url;
		
		if( $itunes != '' ) :	// if iTunes url is not empty, let's output the link...
			if( $rss == '' )	// but first check if there is an rss feed. If there is, we need a space 
				$output .= $itunes_url;
			else
				$output .= ' | ' . $itunes_url;
		endif;
			
		
		$output .= '</p>';

		while( $podcastfeedquery->have_posts() ) : $podcastfeedquery->the_post();
			
			if( function_exists( 'powerpress_get_enclosure_data' )) : 
				$podcast_data = powerpress_get_enclosure_data( $podcastfeedquery->post->ID );
			
				if( $podcast_data )
					$podcastURL = $podcast_data[ 'url' ];
			
			endif;						

			// We want to group podcast episodes when the dates are the same.
			// Get the date and then compare it to the date of the next post
			$current_date = get_the_date( 'F j, Y' );
			
			$output .= '<div id="post-' . get_the_ID() . '">'; 
			
			// If there is an old_date and current_date does not equal old_date, output the date. Otherwise, skip it.
			if( !$old_date || $current_date != $old_date )
				$output .= '<p class="entry-title podcast-title">' . get_the_date( 'F j, Y' ) . '</p>';
 			
			
			$output .= '<ul class="podcast-list">';
 			$output .= '<li>' . get_the_title() . '<br />';
			$output .= '<a href="'. get_permalink() .'" title="' . get_the_title() . '">Listen</a> | <a href="' . $podcastURL .'" target="_blank" title="' . get_the_title() .'">Download</a></li>';
			$output .= '</ul>';
                            
			//<div class="podcast-list">';
			
			//$output .= '<a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() .'</a>';

			//$output .= '</div><!-- end podcast-list -->';
			$output .= '</div><!-- end post -->';
			
			//$output .= '<h4><a href="' . get_permalink() .'" title="' . get_the_title() .'">' . get_the_title() . '</a></h4>'; 
			
			$old_date = get_the_date( 'F j, Y' );
			

		endwhile;
	
		$output .= '</div><!-- END SPAN12 -->';
		$output .= '</div><!-- END ROW -->';

	endif;

	
	wp_reset_postdata(); // Restore original Post Data 

    return $output;

}

add_shortcode( 'podcastfeed', 'init_podcastfeed' );



?>