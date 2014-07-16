<?php 
/*
 *	Twitter Card and Open Graph Meta
 *
 */
?>

<?php 


// Define the Ooyala player
$ooyala_player = '729afcf094bf4f959df5cdc1e88c8675';

// Get the current post outside of the loop
$ID = $wp_query->post->ID;

// Get the current post type
$post_type = get_post_type();

switch( $post_type ) :
	case 'post' :

		
	break;
	
	case 'pt_videolibrary':
		
	break;
	
endswitch;

echo '<meta property="og:title" content="' . get_the_title() . '" />' . "\n";
echo '<meta property="og:description" content="' . get_the_content() . '" />' . "\n";
echo '<meta property="og:url" content="http://www.moneybizlife.com/videos/state-religious-freedom-u-s/" />' . "\n";
echo '<meta property="og:site_name" content="Money Business Life Network" />' . "\n";



if ( 'pt_videolibrary' == get_post_type() ) :
	echo '<!-- Twitter Card -->';
	$ooyala_video = get_post_meta( $ID, 'pt_video_ooyalaID', true );
	$ooyala_thumb = get_post_meta( $ID, 'pt_video_thumbnail', true );

	$ooyala_url = 'http://player.ooyala.com/twitter/meta/' . $ooyala_player . '/' . $ooyala_video;
	
	$twitter_metatags = get_meta_tags( $ooyala_url );
	
	echo '<meta name="twitter:card" content="' . $twitter_metatags['twitter:card'] . '" />'. "\n";
	echo '<meta name="twitter:title" content="' . $twitter_metatags['twitter:title'] . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . $twitter_metatags['twitter:description'] . '" />' . "\n";
	echo '<meta name="twitter:image" content="' . $twitter_metatags['twitter:image'] . '" />' . "\n";
	echo '<meta name="twitter:player" content="' . $twitter_metatags['twitter:player'] . '" />' . "\n";
	echo '<meta name="twitter:player:width" content="' . $twitter_metatags['twitter:player:width'] . '" />' . "\n";
	echo '<meta name="twitter:player:height" content="' . $twitter_metatags['twitter:player:height'] . '" />' . "\n";
	echo '<meta name="twitter:site" content="@moneybizlife">' . "\n";
	echo '<!-- Open Graph -->' . "\n";
	echo '<meta property="og:locale" content="en_US" />' . "\n";
	echo '<meta property="og:image" content="'. $ooyala_thumb .'" />' . "\n";
	echo '<meta property="og:image:type" content="image/jpeg">' . "\n";
	
	/*
	
	
	echo '<meta property="og:type" content="article" />' . "\n";
	echo '<meta property="og:title" content="The State of Religious Freedom In the U.S." />' . "\n";
	echo '<meta property="og:description" content="Pamela Geller is the founder and editor of AtlasShrugs.com" />' . "\n";
	echo '<meta property="og:url" content="http://www.moneybizlife.com/videos/state-religious-freedom-u-s/" />' . "\n";
	echo '<meta property="og:site_name" content="Money Business Life Network" />' . "\n";
	// Article, Video, or Podcast
	echo '<meta property="article:publisher" content="https://www.facebook.com/moneybizlife" />' . "\n";
	echo '<meta property="article:tag" content="Life" />' . "\n";
	echo '<meta property="article:tag" content="Lifestyle" />' . "\n";
	echo '<meta property="article:published_time" content="2014-05-02T12:40:41+00:00" />' . "\n";
	echo '<meta property="article:modified_time" content="2014-05-02T14:47:23+00:00" />' . "\n";
	echo '<meta property="og:updated_time" content="2014-05-02T14:47:23+00:00" />' . "\n";
	
	 */
	
endif;

?>
