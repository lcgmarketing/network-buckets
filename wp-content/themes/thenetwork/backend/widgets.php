<?php 
/*
 *
 *	Widget: Twitter Feed
 *	Description: Displays a twitter feed for a given handle
 *	Author: Chris Olstad
 *
 */

add_action( 'widgets_init', 'mbln_register_twitterfeed_widget' );

function mbln_register_twitterfeed_widget() {
	register_widget( 'MBLN_Twitter_Feed_Widget' );
}

class MBLN_Twitter_Feed_Widget extends WP_Widget {

	function MBLN_Twitter_Feed_Widget() {
		$widget_ops = array( 'classname' => 'mbln-twitter', 'description' => __('Display your Twitter feed', 'mbln-twitter') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mbln-twitter-feed-widget' );
		
		$this->WP_Widget( 'mbln-twitter-feed-widget', __('Twitter Feed', 'mbln-twitter'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$twitter_user = $instance['username'];
		$twitter_id = $instance['twitterid'];

		echo $before_widget;
		
		echo '<div id="twitter-feed">';

		// Display the widget title 
		if ( $title )
			echo $before_title . $title . $after_title;
		else
			echo '<h4>Twitter Feed</h4>';
			
		$widget_output = '<a class="twitter-timeline" href="https://twitter.com/BraintrustShow/money-biz-life-network" data-widget-id="387699526456188928" data-chrome="noheader nofooter noborders transparent" data-tweet-limit="3">Tweets from The Money Biz Life Network</a>';
		
		//$widget_output = '<a class="twitter-timeline" height="200" href="https://twitter.com/' . $twitter_user . '" data-widget-id="'. $twitter_id .'" data-chrome="noheader nofooter noborders transparent" data-tweet-limit="3">Tweets by @'. $twitter_user .'</a>';
		
		$widget_output .= '<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
		
		$widget_output .= '</div>';
		
		echo $widget_output;

		echo $after_widget;

	}

	//Update the widget  
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['twitterid'] = $new_instance['twitterid'];

		return $instance;
	}

	// Setup the Admin Form
	function form( $instance ) {
		
		$title = esc_attr( $instance['title'] );
		$username = esc_attr( $instance['username'] );
		$twitterid = esc_attr( $instance['twitterid'] );

		?>
	
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
		<p>
            <label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
		</p>
		
		<p>
            <label for="<?php echo $this->get_field_id('twitterid'); ?>"><?php _e('Enter your Twitter API ID:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitterid'); ?>" name="<?php echo $this->get_field_name('twitterid'); ?>" type="text" value="<?php echo $twitterid; ?>" />
		</p>
	
		<?php
	}
}


/* 
 *
 *	Name: Trending Tabs
 *	Description: Pulls a feed of Popular, Featured and Social posts and displays them in a series of tabs
 *	Author: Chris Olstad
 *
 */
 
add_action( 'widgets_init', 'mbln_register_trendingtabs_widget' );

function mbln_register_trendingtabs_widget() {
	register_widget( 'MBLN_TrendingTabs_Widget' );
}

class MBLN_TrendingTabs_Widget extends WP_Widget {

	function MBLN_TrendingTabs_Widget() {
		$widget_ops = array( 'classname' => 'mbln-trendingtabs', 'description' => __('Display your Popular, Featured, and Social videos', 'mbln-trendingtabs') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mbln-trendingtabs-widget' );
		
		$this->WP_Widget( 'mbln-trendingtabs-widget', __('Trending Tabs', 'mbln-trendingtabs'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args ); 

		//Our variables from the widget settings.
		//$title = apply_filters('widget_title', $instance['title'] );
		//$twitter_user = $instance['username'];
		//$twitter_id = $instance['twitterid'];

		echo $before_widget;

		$widget_output  = '<div id="trending">';
		$widget_output .= '<ul class="nav nav-tabs">';
		$widget_output .= '<li class="active"><a href="#latest-trending" data-toggle="tab">Latest</a></li>';
		$widget_output .= '<li><a href="#popular" data-toggle="tab">Popular</a></li>';
		// $widget_output .= '<li><a href="#social-trending" data-toggle="tab">Social</a></li>'; // removed Social tab
		$widget_output .= '</ul>';
		$widget_output .= '<div class="tab-content row">';

		// Check the current day. If it's less than 10, rollback a month
		$post_week = date( 'W' ) - 1;
		
		if( date( 'j' ) < 10 ) {
			// If the month is January, roll back to the previous year
			if( date( 'n' ) == 1 ){
				$post_month = 12;
				$post_year = date( 'Y' ) - 1;
			}
			
			else
				$post_month = date( 'n' ) - 1;
		}
	
		else {
			$post_month = date( 'n' );
			$post_year = date( 'Y');
		}
		
		$latest_args = array(
						'post_type' =>	array( 'post', 'pt_videolibrary' ),
						'order'		=>	'desc',
						'order_by'	=>	'date',
						'posts_per_page'	=> 5,
					);

		$popular_args = array(
							'post_type'	=>	array( 'post', 'pt_videolibrary' ),
							/*'year'		=>	$post_year,
							'monthnum'	=>	$post_month,*/
							'meta_key'	=>	'post_view_count',
							'orderby'	=>	'meta_value_num',
							'order' 	=>	'DESC',
							'date_query' => array( array(
													'year' => date('Y'),
													'week' => date('W'),
												)),
							'posts_per_page'	=> '5'
					);
		
		
		/*
		$featured_args = array(
						'post_type' =>	'pt_videolibrary',
						'order'		=>	'desc',
						'order_by'	=>	'date',
						'posts_per_page'	=> 5,
						'tax_query' =>	array(
											array( 
												'taxonomy' => 'pt_videocategory',
												'field' => 'slug',
												'terms' => 'featured'
											))
					);
		
		$social_args = array(
						'post_type' =>	'pt_videolibrary',
						'order'		=>	'desc',
						'order_by'	=>	'date',
						'posts_per_page'	=> 5,
						'tax_query' =>	array(
											array( 
												'taxonomy' => 'pt_videocategory',
												'field' => 'slug',
												'terms' => 'social'
											))
					);    
		*/
		
		// Start the Latest posts query
		$latest_query = new WP_Query( $latest_args );
		
		$widget_output .= '<div id="latest-trending" class="tab-pane active col-md-12">';
		$widget_output .= '<ul class="trending-meta">';
		
		while ( $latest_query->have_posts()) : $latest_query->the_post();
			$post_type = get_post_type(); // post or pt_videolibrary. important for featured image
			$post_id = get_the_ID();
			$post_title = get_the_title( );
			$permalink = get_permalink( );
			$post_created_date = get_the_date( 'F j, Y' );
			$postimageURL = get_post_meta( $post_id, 'pt_video_thumbnail', true );
			
			if( $post_type == 'pt_videolibrary' )
				$videoruntime = get_post_meta( $post_id, 'pt_video_duration', true );
			
			else
				$videoruntime = '';

			// We only need the video runtime for videos, not regular posts. If the runtime is empty, don't display it. 
			if( $videoruntime != '' )
				$videoruntime = '(' . gmdate( 'H:i:s', intval( $videoruntime )) . ')';

			// Get the featured image of a Wordpress Post or Page			
			if( has_post_thumbnail( $post->ID ))
				$featuredimage_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'trending-thumb' );
					
			// Check if the postimageURL is blank. If so, set it to the post featured image.
			if( !$postimageURL )
				$postimageURL = $featuredimage_url[0];
	
			$widget_output .= '<li>';
			$widget_output .= '<a href="' . $permalink  . '" class="trending-thumb"><img src="'. $postimageURL .'" /></a>';
			$widget_output .= '<div class="trending-content">';
			$widget_output .= '<h4><a href="'. $permalink . '">' . $post_title . ' </a><span>'. $videoruntime .'</span></h4>';
			$widget_output .= '<p>'. $post_created_date . '</p>';
			$widget_output .= '</div>';
			$widget_output .= '</li>';
		
		endwhile;
		
		// Close the Featured posts query
		$widget_output .= '</ul>';
		$widget_output .= '</div>';
	
		wp_reset_postdata();
		

		// Start the Popular post query
		$popular_query = new WP_Query( $popular_args );
		
		$widget_output .= '<div id="popular" class="tab-pane col-md-12">'; 
		$widget_output .= '<ul class="trending-meta">';
	
		while ( $popular_query->have_posts()) : $popular_query->the_post();
			$post_type = get_post_type(); // post or pt_videolibrary. important for featured image
			$post_id = get_the_ID();
			$post_title = get_the_title( );
			$permalink = get_permalink( );
			$post_created_date = get_the_date( 'F j, Y' );
			
			if( $post_type == 'pt_videolibrary' ) :
				$postimageURL = get_post_meta( $post_id, 'pt_video_thumbnail', true );
				$videoruntime = get_post_meta( $post_id, 'pt_video_duration', true );
			
			else : // regular post
				if( has_post_thumbnail( $post_id )){ // make sure a featured image exists
					$featuredimage_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'trending-thumb' );
					$postimageURL = $featuredimage_url[0]; // wp_get_attachment_image_src returns an array. we want the first element of the array
					$videoruntime = '';
				}
			endif;
				
			// We only need the video runtime for videos, not regular posts. If the runtime is empty, don't display it. 
			if( $videoruntime != '' )
				$videoruntime = '(' . gmdate( 'H:i:s', intval( $videoruntime )) . ')';

			$widget_output .= '<li>';
			$widget_output .= '<a href="' . $permalink  . '" class="trending-thumb"><img src="'. $postimageURL .'" /></a>';
			$widget_output .= '<div class="trending-content">';
			$widget_output .= '<h4><a href="'. $permalink . '">' . $post_title . ' </a><span>'. $videoruntime .'</span></h4>';
			$widget_output .= '<p>'. $post_created_date . '</p>';
			$widget_output .= '</div>';
			$widget_output .= '</li>';
	
		endwhile;
		
		// Close the Popular posts query
		$widget_output .= '</ul>';
		$widget_output .= '</div>';
	
		wp_reset_postdata();
	
	
		/*
		// Start the Featured posts query
		$featured_query = new WP_Query( $featured_args );
		
		$widget_output .= '<div id="featured" class="tab-pane col-md-12">';
		$widget_output .= '<ul class="trending-meta">';
		
		while ( $featured_query->have_posts()) : $featured_query->the_post();
			$post_id = get_the_ID();
			$post_title = get_the_title( );
			$permalink = get_permalink( );
			$post_created_date = get_the_date( 'F j, Y' );
			$postimageURL = get_post_meta( $post_id, 'pt_video_thumbnail', true );
			$videoruntime = gmdate( 'H:i:s', intval( get_post_meta( $post_id, 'pt_video_duration', true )));
						
			// We only need the video runtime for videos, not regular posts. If the runtime is empty, don't display it. 
			if( $videoruntime )
				$videoruntime = '(' . gmdate( 'H:i:s', intval( get_post_meta( $post_id, 'pt_video_duration', true ))) . ')';

			// Get the featured image of a Wordpress Post or Page			
			if( has_post_thumbnail( $post->ID ))
				$featuredimage_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
					
			// Check if the postimageURL is blank. If so, set it to the post featured image.
			if( !$postimageURL )
				$postimageURL = $featuredimage_url[0];
	
			$widget_output .= '<li>';
			$widget_output .= '<a href="' . $permalink  . '" class="trending-thumb"><img src="'. $postimageURL .'" /></a>';
			$widget_output .= '<div class="trending-content">';
			$widget_output .= '<h4><a href="'. $permalink . '">' . $post_title . ' </a><span>'. $videoruntime .'</span></h4>';
			$widget_output .= '<p>'. $post_created_date . '</p>';
			$widget_output .= '</div>';
			$widget_output .= '</li>';
		
		endwhile;
		
		// Close the Featured posts query
		$widget_output .= '</ul>';
		$widget_output .= '</div>';
	
		wp_reset_postdata();

		// Start the Social posts query
		$social_query = new WP_Query( $social_args );
		
		$widget_output .= '<div id="social-trending" class="tab-pane col-md-12">';
		$widget_output .= '<ul class="trending-meta">';
		
		while ( $social_query->have_posts()) : $social_query->the_post();
			$post_id = get_the_ID();
			$post_title = get_the_title( );
			$permalink = get_permalink( );
			$post_created_date = get_the_date( 'F j, Y' );
			$postimageURL = get_post_meta( $post_id, 'pt_video_thumbnail', true );
			$videoruntime = gmdate( 'H:i:s', intval( get_post_meta( $post_id, 'pt_video_duration', true )));
					
			// We only need the video runtime for videos, not regular posts. If the runtime is empty, don't display it. 
			if( $videoruntime )
				$videoruntime = '(' . gmdate( 'H:i:s', intval( get_post_meta( $post_id, 'pt_video_duration', true ))) . ')';

			// Get the featured image of a Wordpress Post or Page			
			if( has_post_thumbnail( $post->ID ))
				$featuredimage_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
					
			// Check if the postimageURL is blank. If so, set it to the post featured image.
			if( !$postimageURL )
				$postimageURL = $featuredimage_url[0];
	
			$widget_output .= '<li>';
			$widget_output .= '<a href="' . $permalink  . '" class="trending-thumb"><img src="'. $postimageURL .'" /></a>';
			$widget_output .= '<div class="trending-content">';
			$widget_output .= '<h4><a href="'. $permalink . '">' . $post_title . ' </a><span>' . $videoruntime . '</span></h4>';
			$widget_output .= '<p>'. $post_created_date . '</p>';
			$widget_output .= '</div>';
			$widget_output .= '</li>'; 
	
		endwhile;
		
	
		// Close the Social posts query
		$widget_output .= '</ul>';
		$widget_output .= '</div>';
	
		wp_reset_postdata();
		*/
			
		$widget_output .= '</div>'; // Close tabbed-content
		$widget_output .= '</div>'; // Close trending

		echo $widget_output;

		echo $after_widget;
	}

	//Update the widget  
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['tab_one_title'] = strip_tags( $new_instance['tab_one_title'] );
		$instance['tab_two_title'] = strip_tags( $new_instance['tab_two_title'] );
		$instance['tab_three_title'] = strip_tags( $new_instance['tab_three_title'] );

		return $instance;
	}

	// Setup the Admin Form
	function form( $instance ) {
		
		$tab_one = esc_attr( $instance['tab_one_title'] );
		$tab_two = esc_attr( $instance['tab_two_title'] );
		$tab_three = esc_attr( $instance['tab_three_title'] );

		?>
	
		<p>
            <label for="<?php echo $this->get_field_id('tab_one_title'); ?>"><?php _e('Title of the First Tab'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('tab_one_title'); ?>" name="<?php echo $this->get_field_name('tab_one_title'); ?>" type="text" value="<?php echo $tab_one; ?>" />
		</p>
		
		<p>
            <label for="<?php echo $this->get_field_id('tab_two_title'); ?>"><?php _e('Title of the Second Tab'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('tab_two_title'); ?>" name="<?php echo $this->get_field_name('tab_two_title'); ?>" type="text" value="<?php echo $tab_two; ?>" />
		</p>
		
		<p>
            <label for="<?php echo $this->get_field_id('tab_three_title'); ?>"><?php _e('Title of the Third Tab'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('tab_three_title'); ?>" name="<?php echo $this->get_field_name('tab_three_title'); ?>" type="text" value="<?php echo $tab_three; ?>" />
		</p>
	
		<?php
	}
}


/* 
 *
 *	Name: Social Sharing
 *	Description: Display a list of social network icons. Currently supports Facebook, Twitter, Google+, YouTube, and email subscriptions
 *	Author: Chris Olstad
 *
 */
 
add_action( 'widgets_init', 'mbln_register_socialshare_widget' );

function mbln_register_socialshare_widget() {
	register_widget( 'MBLN_SocialShare_Widget' );
}

class MBLN_SocialShare_Widget extends WP_Widget {

	function MBLN_SocialShare_Widget() {
		$widget_ops = array( 'classname' => 'mbln-socialshare', 'description' => __('Links to popular social networking sites', 'mbln-socialshare') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mbln-socialshare-widget' );
		
		$this->WP_Widget( 'mbln-socialshare-widget', __('Social Sharing', 'mbln-socialshare'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args ); 

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		
		$facebook 	= 	$instance['facebook'];
		$twitter 	= 	$instance['twitter'];
		$googleplus = 	$instance['googleplus'];
		$youtube 	= 	$instance['youtube'];
		$email 		= 	$instance['email-marketing'];
		$podcast 	= 	$instance['podcast'];

		$showname 	= 	$instance['showname'];
		
		if( !$showname )
			$showname = 'Connect with MBLN';

		echo $before_widget;
		
		$widget_output = '<div id="social-connect">';
		
		// Display the widget title 
		if ( $title )
			$widget_output .= $before_title . $title . $after_title;
		else
			$widget_output .= $before_title . 'Get Social' . $after_title;
		
		$widget_output .= '<ul class="row">';
		
		if( $facebook )
			$widget_output .= '<li class="facebook"><a href="http://www.facebook.com/' . $facebook . '" target="_blank"><span>G</span></a><a href="http://www.facebook.com/' . $facebook . '" class="social-title" target="_blank">Like Us on Facebook</a></li>';
		
		if( $twitter )
			$widget_output .= '<li class="twitter"><a href="http://www.twitter.com/' . $twitter . '" target="_blank"><span>t</span></a><a href="http://www.twitter.com/' . $twitter . '" class="social-title" target="_blank">Follow Us on Twitter</a></li>';
			
		if( $googleplus )
			$widget_output .= '<li class="googleplus"><a href="' . $googleplus . '" target="_blank"><span>P</span></a><a href="' . $googleplus . '" class="social-title" target="_blank">+1 on Google+</a></li>';
			
		if( $youtube )
			$widget_output .= '<li class="youtube"><a href="http://www.youtube.com/' . $youtube . '" target="_blank"><span>P</span></a><a href="http://www.youtube.com/' . $youtube . '" class="social-title" target="_blank">Subscribe on YouTube</a></li>';
						
		if( $podcast )
			$widget_output .= '<li class="podcast"><a href="' . $podcast . '" target="_blank"><span>&#177;</span></a><a href="' . $podcast . '" class="social-title" target="_blank">Get the Podcast</a></li>';	

		if( $email )
			$widget_output .= '<li class="email-marketing"><a href="' . $email . '" target="_blank"><span>m</span></a><a href="' . $email . '" class="social-title" target="_blank">Get the Newsletter</a></li>';

		$widget_output .= '</ul></div>'; // Close List and SOCIAL-CONNECT
		
		echo $widget_output;

		echo $after_widget;
	}

	//Update the widget  
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['showname'] = strip_tags( $new_instance['showname'] );
		$instance['facebook'] = strip_tags( $new_instance['facebook'] );
		$instance['twitter'] = strip_tags( $new_instance['twitter'] );
		$instance['googleplus'] = strip_tags( $new_instance['googleplus'] );
		$instance['youtube'] = strip_tags( $new_instance['youtube'] );
		$instance['email-marketing'] = strip_tags( $new_instance['email-marketing'] );
		$instance['podcast'] = strip_tags( $new_instance['podcast'] );

		return $instance;
	} // Close Function: Update

	// Setup the Admin Form
	function form( $instance ) {

		$title = esc_attr( $instance['title'] );
		$facebook = esc_attr( $instance['facebook'] );
		$twitter = esc_attr( $instance['twitter'] );
		$googleplus = esc_attr( $instance['googleplus'] );
		$youtube = esc_attr( $instance['youtube'] );
		$email = esc_attr( $instance['email-marketing'] );
		$podcast = esc_attr( $instance['podcast'] );
		$showname = esc_attr( $instance['showname'] );

		?>
	
		<!-- Widget Title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
        <!-- MBLN Show Name -->
		<p>
            <label for="<?php echo $this->get_field_id('showname'); ?>"><?php _e('MBLN Show Name'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('showname'); ?>" name="<?php echo $this->get_field_name('showname'); ?>" type="text" value="<?php echo $showname; ?>" />
		</p>
		
        <!-- Facebook URL -->
		<p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo $facebook; ?>" />
		</p>
        
        <!-- Twitter URL -->
		<p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter Handle'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" />
		</p>

        <!-- Google Plus URL -->
		<p>
            <label for="<?php echo $this->get_field_id('googleplus'); ?>"><?php _e('Google +'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('googleplus'); ?>" name="<?php echo $this->get_field_name('googleplus'); ?>" type="text" value="<?php echo $googleplus; ?>" />
		</p>
        
        <!-- YouTube URL -->
		<p>
            <label for="<?php echo $this->get_field_id('youtube'); ?>"><?php _e('YouTube Channel'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo $youtube; ?>" />
		</p>        

        <!-- Email Signup URL -->
		<p>
            <label for="<?php echo $this->get_field_id('email-marketing'); ?>"><?php _e('Campaign Monitor'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('email-marketing'); ?>" name="<?php echo $this->get_field_name('email-marketing'); ?>" type="text" value="<?php echo $email; ?>" />
		</p>
        
        <!-- Podcast URL -->
		<p>
            <label for="<?php echo $this->get_field_id('podcast'); ?>"><?php _e('Podcast RSS'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('podcast'); ?>" name="<?php echo $this->get_field_name('podcast'); ?>" type="text" value="<?php echo $podcast; ?>" />
		</p>
        
		<?php
	} // Close Function: Form
}


/* 
 *
 *	Name: TV Show Call to Action
 *	Description: Unique link to a contact form. Specific to each show.
 *	Author: Chris Olstad
 *
 */
 
add_action( 'widgets_init', 'mbln_register_contactnow_widget' );

function mbln_register_contactnow_widget() {
	register_widget( 'MBLN_ContactNow_Widget' );
}

class MBLN_ContactNow_Widget extends WP_Widget {

	function MBLN_ContactNow_Widget() {
		$widget_ops = array( 'classname' => 'mbln-contactnow', 'description' => __('MBLN Show specific call to action button', 'mbln-contactnow') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mbln-contactnow-widget' );
		
		$this->WP_Widget( 'mbln-contactnow-widget', __('MBLN Contact Now!', 'mbln-contactnow'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args ); 

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		
		$action_title 	= 	$instance['action-title'];
		$action_url		= 	$instance['action-url'];

		echo $before_widget;

		$widget_output = '<div id="show-specific" class="widget">';
		
		// Display the widget title 
		if ( $title )
			$widget_output .= $before_title . $title . $after_title;
		else
			$widget_output .= $before_title . 'Send Us Your Questions' . $after_title;
		
		$widget_output .= '<a href="' . $action_url . '" class="btn btn-large btn-primary" target="_blank">'. $action_title . '</a>';
		
		$widget_output .= '</div>'; // close show-specific
		
		echo $widget_output;

		echo $after_widget;
	}

	//Update the widget  
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['action-title'] = strip_tags( $new_instance['action-title'] );
		$instance['action-url'] = strip_tags( $new_instance['action-url'] );

		return $instance;
	} // Close Function: Update

	// Setup the Admin Form
	function form( $instance ) {

		$title = esc_attr( $instance['title'] );
		$action_title = esc_attr( $instance['action-title'] );
		$action_url = esc_attr( $instance['action-url'] );

		?>
	
		<!-- Widget Title: Used for Button Title -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
        <!-- Action Title: Button Name -->
		<p>
            <label for="<?php echo $this->get_field_id('action-title'); ?>"><?php _e('Button Title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('action-title'); ?>" name="<?php echo $this->get_field_name('action-title'); ?>" type="text" value="<?php echo $action_title; ?>" />
		</p>
		
        <!-- Action URL: Link to Contact Page  -->
		<p>
            <label for="<?php echo $this->get_field_id('action-url'); ?>"><?php _e('Call To Action URL'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('action-url'); ?>" name="<?php echo $this->get_field_name('action-url'); ?>" type="text" value="<?php echo $action_url; ?>" />
		</p>
                
		<?php
	} // Close Function: Form
}

/* 
 *
 *	Name: Leaderboard (728x90)
 *	Description: Ad space for 728x90 banner ad
 *	Author: Chris Olstad
 *
 */

add_action( 'widgets_init', 'mbln_register_bannerads_widget' );

function mbln_register_bannerads_widget() {
	register_widget( 'MBLN_Banner_Ad_Widget' );
}

class MBLN_Banner_Ad_Widget extends WP_Widget {

	function MBLN_Banner_Ad_Widget() {
		$widget_ops = array( 'classname' => 'mbln-bannerad', 'description' => __('Displays a banner ad. Supported sizes: 728x90 (leaderboard), 300x250 (medium square), 250x250 (square), 125x125 (small square)', 'mbln-bannerad') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mbln-bannerad-widget' );
		
		$this->WP_Widget( 'mbln-bannerad-widget', __('Ad Widget', 'mbln-bannerad'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args ); 

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		
		$ad_tag 	= 	$instance['ad-tag'];	// Original Ad Tag for MBLN: div-gpt-ad-1383343084242-0 
		$ad_type	= 	$instance['ad-type'];
		
		echo $before_widget;

		$widget_output = '<!-- Ad Type: '. $ad_type .' -->';
		
		$widget_output .= '<div id="' . $ad_tag . '" class="'. $ad_type .'">';
		
		$widget_output .= '<script type="text/javascript">';

		$widget_output .= 'googletag.cmd.push(function() { googletag.display("' . $ad_tag . '"); });';
		
		$widget_output .= '</script>';
		
		$widget_output .= '<!-- <div class="ad-label">Advertisement</div>--></div>'; // close the script and the ad widget
		
		echo $widget_output;

		echo $after_widget;
	}
 
	//Update the widget  
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['ad-tag'] = strip_tags( $new_instance['ad-tag'] );
		$instance['ad-type'] = strip_tags( $new_instance['ad-type'] );

		return $instance;
	} // Close Function: Update

	// Setup the Admin Form
	function form( $instance ) {

		$title		= esc_attr( $instance['title'] );
		$ad_tag 	= esc_attr( $instance['ad-tag'] );
		$ad_type	= esc_attr( $instance['ad-type'] );
		
		echo '<!-- ad type = ' . $ad_type . ' -->';
		
		if( $ad_type == 'medium-square-ad' )
			echo '<!-- yes! -->';
		?>
	
		<!-- Widget Title: Used for Organization on the back-end. Not displayed -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		
        <!-- Google DFP Ad Tag -->
		<p>
            <label for="<?php echo $this->get_field_id('ad-tag'); ?>"><?php _e('DFP Ad Tag'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('ad-tag'); ?>" name="<?php echo $this->get_field_name('ad-tag'); ?>" type="text" value="<?php echo $ad_tag; ?>" />
		</p>

        <!-- Ad Type: Leaderboard, Square, Medium Square, Small Square -->
		<p>
            <label for="<?php echo $this->get_field_id('ad-type'); ?>"><?php _e('Ad Type'); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id('ad-type'); ?>" name="<?php echo $this->get_field_name('ad-type'); ?>">

            	<option value="leaderboard-ad"		id="leaderboard-ad"		<?php if( $ad_type == 'leaderboard-ad' ) echo 'selected="selected"'; ?>>Leaderboard (728x90)</option>
                <option value="medium-square-ad"	id="medium-square-ad"	<?php if( $ad_type == 'medium-square-ad' ) echo 'selected="selected"'; ?>>Medium Square (300x250)</option>
                <option value="square-ad" 			id="square-ad" 			<?php if( $ad_type == 'square-ad' ) echo 'selected="selected"'; ?>>Square (250x250)</option>
                <option value="small-square-ad" 	id="small-square-ad" 	<?php if( $ad_type == 'small-square-ad' ) echo 'selected="selected"'; ?>>Small Square (125x125)</option>

            </select>
		</p>

		
		<?php
	} // Close Function: Form
}


?>