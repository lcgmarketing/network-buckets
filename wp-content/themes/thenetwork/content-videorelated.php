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

<?php
    
	$orig_post = $post;

	global $post;

	$postcount = 0;
            
	$tags = wp_get_post_tags( $post->ID );
	
	$tag_ids = array();
	
	if( !$tags )
		$tag_ids = array( 20,21,22 );		
		
	foreach( $tags as $individual_tag ) 
		$tag_ids[] = $individual_tag->term_id;

	$recommended_args = array(
							'post_type' => 'pt_videolibrary',
							'tag__in'	=>	$tag_ids,
							'post__not_in'	=>	array( $post->ID ),
							'order' 	=> 'DESC',
							'orderby'	=> 'date',
							'posts_per_page' =>	4, // Number of related posts to display.
							'caller_get_posts' => 1
						);
	
	$recommended_query = new WP_Query( $recommended_args );

	if( $recommended_query->have_posts() ) :
	
?>

<!-- RELATED VIDEO -->
<div id="related-video" class="widget">
    <h3>You Might Also Like</h3>
    
    <!-- <div class="row"> -->
    <?php
		
        // Only show 4 related videos
		while( $recommended_query->have_posts() ) : $recommended_query->the_post();
            
			echo "<!-- ".$postcount." -->"; // output count as comment
            $postcount++; // increase count
            
            // Get the thumbnail URL
			$videothumbURL = get_post_meta( $recommended_query->post->ID, 'pt_video_thumbnail', true );
			
			switch( $postcount ){
				case 1: // open row
					echo '<div class="row">';
					echo '<div class="col-sm-6 video-post-<?php the_ID(); ?>">';
					echo '<div class="small-thumb">';
					echo '<a href="' . get_permalink() .'"><img src="'. $videothumbURL . '" alt="'. get_the_title() . '" /></a>';
					echo '<p><a href="' . get_permalink() .'">' . get_the_title() .'</a></p>';
					echo '</div>';
					echo '</div>';
				break;
				case 2: // close row
					echo '<div class="col-sm-6 video-post-<?php the_ID(); ?>">';
					echo '<div class="small-thumb">';
					echo '<a href="' . get_permalink() .'"><img src="'. $videothumbURL . '" alt="'. get_the_title() . '" /></a>';
					echo '<p><a href="' . get_permalink() .'">' . get_the_title() .'</a></p>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				break;
				case 3: // open row
					echo '<div class="row">';
					echo '<div class="col-sm-6 video-post-<?php the_ID(); ?>">';
					echo '<div class="small-thumb">';
					echo '<a href="' . get_permalink() .'"><img src="'. $videothumbURL . '" alt="'. get_the_title() . '" /></a>';
					echo '<p><a href="' . get_permalink() .'">' . get_the_title() .'</a></p>';
					echo '</div>';
					echo '</div>';
				break;
				case 4: // close row
					echo '<div class="col-sm-6 video-post-<?php the_ID(); ?>">';
					echo '<div class="small-thumb">';
					echo '<a href="' . get_permalink() .'"><img src="'. $videothumbURL . '" alt="'. get_the_title() . '" /></a>';
					echo '<p><a href="' . get_permalink() .'">' . get_the_title() .'</a></p>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				break; 
			}
	?>
            <!-- <div class="col-md-2 video-post-<?php the_ID(); ?>">
                <div class="small-thumb">
                    <a href="<?php the_permalink(); ?>"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></a>
                    <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                </div>
            </div>-->
            
    <?php
            
        endwhile;
        
		$post = $orig_post;
                
        wp_reset_postdata(); // Restore original Post Data 
    ?>
    <!-- </div> -->
</div>
<?php endif; ?>

<!--// END RELATED-VIDEO //-->