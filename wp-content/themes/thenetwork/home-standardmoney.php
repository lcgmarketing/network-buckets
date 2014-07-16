<?php
/*
 *	
 *
 *	
 */
?>

<?php

	global $exclude; // array of posts already used. Defined in page_home.php
	
	// if the exclude array is empty, init as empty
	if( !isset( $exclude )) 
		$exclude = array();

	$postcount = 0;
	
	$mbln_tag = 'money';
	
	$tags = array( 'all', 'personal-savings', 'credit', 'retirement' );
	
	$all_tags = 'personal-savings, credit, retirement';
	
	// Business Query Arguments
	$args = array(
				'post_type'	=>	array( 'post', 'pt_videolibrary'),
				'order' 	=>	'DESC',
				'orderby'	=>	'date',
				'showposts'	=>	1,
				'tag'		=>	$mbln_tag,
				'tax_query'	=>	array(
									array(
										'taxonomy' => 'pt_videocategory',
										'field' => 'slug',
										'operator'	=>	'NOT IN',
										'terms' => 'full-episode' 
									)
								)
			);
	
?>

<!-- STANDARD STORY FEED -->
<div class="standard-stories"> 
    
    <!-- ARTICLE HEADING / MENU -->
    <div class="standard-heading">
        <div class="row">
            
            <div class="col-sm-3">
                <h2>Money</h2>
            </div>
            
            <nav class="col-sm-9 sub-menu nav nav-tabs">
                <ul>
                    <li class="active"><a href="#<?php echo $mbln_tag ?>-all" data-toggle="tab">All</a></li>
                    <li><a href="#<?php echo $mbln_tag ?>-personal-savings" data-toggle="tab">Personal Savings</a></li>
                    <li><a href="#<?php echo $mbln_tag ?>-credit" data-toggle="tab">Credit</a></li>
                    <li><a href="#<?php echo $mbln_tag ?>-retirement" data-toggle="tab">Retirement</a></li>
                </ul>
            </nav>
            
        </div>                        
    </div>
    
    <!-- The STORY-FEED -->
    <div class="story-feed">
        <div class="row">
            
            <!-- COLUMN ONE: LEADING STORY -->
            <div class="col-sm-7">

			<?php
            	$query_mblnposts = new WP_Query( $args );
				
                if( $query_mblnposts->have_posts() ) :
            
                    // We only want 1 story(vidoes or posts)
                    while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
                        $postthumbURL = get_post_meta( $query_mblnposts->post->ID, 'pt_video_thumbnail', true );
                        
						// Check if the postthumbURL is empty. If so, use the post featured image instead.
						if( !$postthumbURL ) :
							if( has_post_thumbnail( $post->ID ))
								$featuredimage_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'feed-story' );
							$postthumbURL = $featuredimage_url[0];
						endif;
						
						// Story has been used, don't show it again (if it crosses categories)
						// Add it to the exclude array
                        array_push( $exclude, $post->ID );
            ?>

                <div class="row top-story">
                    <div class="col-sm-4 thumbs">
                    	<div class="thumb-frame">
                        	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $postthumbURL; ?>" alt="<?php the_title(); ?>" /></a>
                        </div>
					</div>

                    <div class="col-sm-8 the-story">
                        <p class="the-date"><?php echo get_the_date( 'F j, Y' ); ?></p>
                        <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php the_excerpt(); ?></p>
                    </div>
                </div>

			<?php
                        $postcount++;
                    endwhile; // close WHILE
                endif; // close IF
            
                wp_reset_postdata();
            ?>

            </div>
            <!--// END LEADING STORY -->
            
            <!-- COLUMN TWO: OTHER STORIES -->
            <div class="col-sm-5">
                <div class="other-stories tab-content">
                
                <?php 
					// reset postcount
					$postcount = 0;
					
					foreach( $tags as $tag ) :	
				?>
                    
                    <ul class="tab-pane<?php if( 'all' == $tag ) echo ' active' ?>" id="<?php echo $mbln_tag; ?>-<?php echo $tag; ?>">

					<?php
						
						if( 'all' == $tag )
							$tag = $all_tags;
							
						// Business Arguments - CURRENT TAGS  
						$args = array(
											'post_type'	=>	array( 'post', 'pt_videolibrary'),
											'order' 	=>	'DESC',
											'orderby'	=>	'date',
											'showposts'	=>	3, 
											'tag'		=>	$tag,
											'post__not_in' =>	$exclude
										);
                    
                        $query_mblnposts = new WP_Query( $args );
                    
                        if( $query_mblnposts->have_posts() ) :
                            while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
								
								// check if article is post or video (pt_videolibrary)
								if( get_post_type() == 'post' ) $pictogram = 'a'; 
								else $pictogram = 'P'; //$pictogram = '&#62;';
                    ?>
                        <li><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><span class="pictogram"><?php echo $pictogram; ?></span><?php the_title(); ?></a></h4></li>

					<?php
								// Story has been used, don't show it again (if it crosses categories)
								// Add it to the exclude array
								array_push( $exclude, $post->ID );
					
                            endwhile; // close WHILE
                        endif; // close IF
                    
                        wp_reset_postdata();
                    ?>

                    </ul>
                    
                    <?php endforeach; ?>
                    
                </div>
                <!--// OTHER STORIES //-->
                
            </div>
            <!--// END COLUMN TWO //-->

        </div>
    </div>
    <!-- END STORY-FEED -->
    
</div>
<!--// END STANDARD STORY FEED: <?php echo $mbln_tag; ?> //-->