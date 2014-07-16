<?php
/*
 *	
 *
 *	
 */
?>

<?php

	$postcount = 0;
	
	$post_year = date( 'Y');
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

	else
		$post_month = date( 'n' );
		
	// Must See Video Arguments
	$args = array(
				'post_type'	=>	'pt_videolibrary',
				'year'		=>	$post_year,
				'monthnum'	=>	$post_month,
				'meta_key'	=>	'post_view_count',
				'orderby'	=>	'meta_value_num',
				'order' 	=>	'DESC',
				'showposts'	=>	4
			);

?>

<!-- MUST SEE TV! -->
<div class="must-see-video">

    <div class="standard-heading">
        <h2>Must See Videos on MBLN</h2>
    </div>
    
    <!-- STORY-FEED -->
    <div class="story-feed">
        <div class="row">
        <?php
			$query_mblnposts = new WP_Query( $args );
			
			if( $query_mblnposts->have_posts() ) :
		
				// We only want 1 story(vidoes or posts)
				while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
					$videothumbURL = get_post_meta( $query_mblnposts->post->ID, 'pt_video_thumbnail', true );
		?>
            
            <!-- Top Video #<?php echo $postcount; ?> -->
            <div class="col-sm-3">
                <div class="thumb-frame">
                    <a href="<?php the_permalink(); ?>" title="the_title();"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></a>
                </div>
                
                <div class="video-post">
                    <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                    <p class="the-date"><?php echo get_the_date( 'F j, Y' ); ?></p>
                </div>
            </div>
	
    	<?php
					$postcount++;
				endwhile; // close WHILE
			endif; // close IF
		
			wp_reset_postdata();
		?>
            
        </div>
    </div>
    <!--// END STORY-FEED -->
    
</div>
<!--// END MUST SEE TV //-->