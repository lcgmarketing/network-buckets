<?php
/*
 * Plugin Name:   Campaign Monitor RSS Feed
 * Plugin URI:    http://www.moneybizlife.com
 * Description:   A plugin to customize the default RSS Feed for Campaign Monitor RSS Emails
 * Version:       1.0
 * Author:        Chris Olstad
 * Author URI:    http://www.twitter.com/capitaljigga
 */

 
 // ==== adding title and subtitle ====
add_filter( 'the_content_feed', 'campaignmonitor_title_and_subtitle' );
 
function campaignmonitor_title_and_subtitle( $content ) {
    global $post;
	
    $post_subtitle = get_post_meta( $post->ID, 'pt_video_subtitle', TRUE );
    
	// add hgroup only if the Custom Field is set
    if ( $post_subtitle ) {
        $hgroup = '<hgroup><h1>' . $post->post_title . '</h1>';
        $hgroup .= '<h2>' . $post_subtitle . '</h2></hgroup>';
        return $hgroup . $content;
    } 
	else
        return $content;
}

// ==== adding images ====
// setup the media xml tag
add_filter( 'rss2_ns', 'campaignmonitor_namespace' );
 
function campaignmonitor_namespace() {
    echo 'xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:georss="http://www.georss.org/georss" ';
}

// pull image url
add_filter( 'rss2_item', 'campaignmonitor_attached_images' );
 
function campaignmonitor_attached_images() {
    global $post;
    
	/* 
	// for featured images 
	$attachments = get_posts( array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'posts_per_page' => -1,
        'post_parent' => $post->ID,
        'exclude' => get_post_thumbnail_id()
    ) );
	*/
    
	// get the video thumbnail url
	$video_thumb = get_post_meta( $post->ID, 'pt_video_thumbnail', TRUE );
	
	if ( $video_thumb ) {
	?>
		<media:content url="<?php echo $video_thumb; ?>" type="image/jpeg" medium="image" width="426" height="239"></media:content>
	<?php
    }
}


?>