<?php
/*
 *	-------------------------------------------------------------------------
 *	Page Content: Homepage Top Story Feed. 
 *	Displays a short list of top story posts. 
 *	
 *	-------------------------------------------------------------------------
 */
?>

<?php

	global $exclude; // array of posts already used. Defined in page_home.php
	
	// if the exclude array is empty, init as empty
	if( !isset( $exclude )) 
		$exclude = array();

	$postcount = 1;

    // Featured Ray Lucia videos
	$args_featured_raylucia = array(
								'post_type' =>	'pt_videolibrary',
								'order' 	=>	'DESC',
								'orderby' 	=>	'date',
								'showposts' => 	1,
								'tax_query' =>	array( 
													'relation' => 'AND',
														array( 
															'taxonomy'	=>	'pt_videocategory', 
															'field'		=>	'slug', 
															'terms'		=>	'featured'
														),
														array( 
															'taxonomy'	=>	'pt_videoshow', 
															'field'		=>	'slug', 
															'terms'		=>	'ray-lucia-show'
														)
												)
								);
	
	// Featured Boomers Braintrust videos					
    $args_featured_boomers = array(
								'post_type' =>	'pt_videolibrary',
								'order' 	=>	'DESC',
								'orderby' 	=>	'date',
								'showposts' => 	1,
								'tax_query' =>	array( 
													'relation' => 'AND',
														array( 
															'taxonomy'	=>	'pt_videocategory', 
															'field'		=>	'slug', 
															'terms'		=>	'featured'
														),
														array( 
															'taxonomy'	=>	'pt_videoshow', 
															'field'		=>	'slug', 
															'terms'		=>	'boomers-braintrust'
														)
												)
								);

	// Featured Boomers Braintrust videos
    $args_featured_smartlife = array(
								'post_type' =>	'pt_videolibrary',
								'order' 	=>	'DESC',
								'orderby' 	=>	'date',
								'showposts' => 	1,
								'tax_query' =>	array( 
													'relation' => 'AND',
														array( 
															'taxonomy'	=>	'pt_videocategory', 
															'field'		=>	'slug', 
															'terms'		=>	'featured'
														),
														array( 
															'taxonomy'	=>	'pt_videoshow', 
															'field'		=>	'slug', 
															'terms'		=>	'smart-life-dr-gina'
														)
												)
								);
?>

<!-- TOP STORIES -->                
<div class="top-stories">
    <nav>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#featuredhome" data-toggle="tab">Featured on the Network</a></li>
        </ul>
    </nav>

    <div class="row story-feed tab-content">
		<!-- Homepage: Featured on the Network -->
		<div class="tab-pane active" id="featuredhome">
		<?php
			while( $postcount <= 3 ) : 
				
				switch( $postcount ){
					case 1: // ray lucia post
						$args_featuredposts = $args_featured_raylucia;
					break;
					
					case 2: // boomers braintrust post
						$args_featuredposts = $args_featured_boomers;
					break;
					
					case 3: // smart life post
						$args_featuredposts = $args_featured_smartlife;
					break;
				}
					
				$query_mblnposts = new WP_Query( $args_featuredposts );
	
				if( $query_mblnposts->have_posts() ) :
			
					// We only want 3 stories (vidoes or posts)
					while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
						$videothumbURL = get_post_meta( $query_mblnposts->post->ID, 'pt_video_thumbnail', true );
			?>
					<!-- FEATURED STORIES COLUMN <?php echo $postcount; ?> -->
					<div class="col-sm-4 the-story">
						<div class="thumbs">
							<div class="thumb-frame">
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></a>
							</div>
						</div>
							
						<p class="the-date"><?php echo get_the_date( 'F j, Y' ); ?></p>
						<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
						<p><?php the_excerpt(); ?></p>
					</div>
		<?php
						// Story has been used, don't show it again (if it crosses categories)
						// Add it to the exclude array
                        array_push( $exclude, $post->ID );
						
					endwhile; // close WHILE
				endif; // close IF
			
				wp_reset_postdata();
				
				$postcount++;
			
			endwhile;
        ?>
        </div>

    </div>
</div>
<!--// END TOP-STORIES //-->
                    
