<?php
/*
 *	
 *
 *	
 */
?>

<?php

	$postcount = 1;
	
	$mbln_tags = array( 'money', 'business', 'life' );
	
	$money_tags = array( 'all', 'personal-savings', 'investing', 'credit' );
	$business_tags = array( 'all', 'personal-finance', 'small-business', 'real-estate' );
	$life_tags = array( 'all', 'retirement', 'health', 'travel' );
	
	
	// Money Query Arguments
	$args_money = array(
						'post_type'	=>	array( 'post', 'pt_videolibrary'),
						'order' 	=>	'DESC',
						'orderby'	=>	'date',
						'showposts'	=>	1,
						'tag'		=>	'money'
					);

	// Business Query Arguments
	$args_business = array(
						'post_type'	=>	array( 'post', 'pt_videolibrary'),
						'order' 	=>	'DESC',
						'orderby'	=>	'date',
						'showposts'	=>	1,
						'tag'		=>	'business'
					);
		
	// Life Query Arguments
	$args_life = array(
						'post_type'	=>	array( 'post', 'pt_videolibrary'),
						'order' 	=>	'DESC',
						'orderby'	=>	'date',
						'showposts'	=>	1,
						'tag'		=>	'life'
					);
						
	$query_mblnposts = new WP_Query( $args_business );
	
?>

<?php

	foreach( $mbln_tags as $mbln_tag ) :
		$postcount = 0;

		// Set the query arguments based on current tag (money, business, or life)
		$args_mbln = array(
							'post_type'	=>	array( 'post', 'pt_videolibrary'),
							'order' 	=>	'DESC',
							'orderby'	=>	'date',
							'showposts'	=>	1,
							'tag'		=>	$mbln_tag
						);
		
		// Need to determine if the tag is money, business, or life
		// Get child tags
		if( $mbln_tag == 'money' )
			$tags_array = $money_tags; 		// money
			
		else if( $mbln_tag == 'business' )
			$tags_array = $business_tags; 	// business

		else
			$tags_array = $life_tags; 		// life

?>

<!-- STANDARD STORY FEED -->
<div class="standard-stories">
    
    <!-- ARTICLE HEADING / MENU -->
    <div class="standard-heading">
        <div class="row">
            
            <div class="col-sm-3">
                <h2>Business</h2>
            </div>
            
            <nav class="col-sm-9 sub-menu nav nav-tabs">
                <ul>
                <?php
					foreach( $tags_array as $tag_array ) :
				?>
                    <li<?php if( 'all' == $tag_array ) echo ' class="active"'; ?>"><a href="#<?php echo $mbln_tag; ?>-<?php echo $tag_array; ?>" data-toggle="tab"><?php echo $tag_array; ?></a></li> 
				<?php 
						$postcount++;
					endforeach; 
				?>
                </ul>
            </nav>
            
        </div>                        
    </div>
    
    <!-- The STORY-FEED -->
    <div class="story-feed">
        <div class="row">
            <div class="col-sm-7">

			<?php
            
                if( $query_mblnposts->have_posts() ) :
            
                    // We only want 3 stories (vidoes or posts)
                    while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
                        $videothumbURL = get_post_meta( $query_mblnposts->post->ID, 'pt_video_thumbnail', true );
                        
                        $exclude = $post->ID;
            ?>

                <div class="row top-story">
                    <div class="col-sm-4 thumbs"><img src="<?php echo $videothumbURL; ?>" alt="<?php the_title(); ?>" /></div>

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
            
            <div class="col-sm-5">
                <div class="other-stories tab-content">
                
                <?php 
					// reset postcount
					$postcount = 0;
					
					foreach( $tags_array as $tag_array ) :
						
				?>
                    
                    <ul class="tab-pane active" id="business-all">
					<?php
                    
                        $args_business = array(
                                            'post_type'	=>	array( 'post', 'pt_videolibrary'),
                                            'order' 	=>	'DESC',
                                            'orderby'	=>	'date',
                                            'tag'		=>	$mbln_tag,
                                            'showposts'	=>	3, 
                                            'post__not_in' => array( $exclude )
                                        );
                    
                        $query_mblnposts = new WP_Query( $args_business );
                    
                        if( $query_mblnposts->have_posts() ) :
                            while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
                    ?>

                        <li><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4></li>

					<?php
                            endwhile; // close WHILE
                        endif; // close IF
                    
                        wp_reset_postdata();
                    ?>

                    </ul>
                    
                    <ul class="tab-pane" id="<?php echo $mbln_tag . '-' . $tag_array ?>">
					<?php
                    
                        $args_business = array(
                                            'post_type'	=>	array( 'post', 'pt_videolibrary'),
                                            'order' 	=>	'DESC',
                                            'orderby'	=>	'date',
                                            'tag'		=>	'personal-finance',
                                            'showposts'	=>	3, 
                                            'post__not_in' => array( $exclude )
                                        );
                    
                        $query_mblnposts = new WP_Query( $args_business );
                    
                        if( $query_mblnposts->have_posts() ) :
                            while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
                    ?>

                        <li><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4></li>

					<?php
                            endwhile; // close WHILE
                        endif; // close IF
                    
                        wp_reset_postdata();
                    ?>

                    </ul>
                    
                    <ul class="tab-pane" id="business-realestate">
					<?php
                    
                        $args_business = array(
                                            'post_type'	=>	array( 'post', 'pt_videolibrary'),
                                            'order' 	=>	'DESC',
                                            'orderby'	=>	'date',
                                            'tag'		=>	'real-estate',
                                            'showposts'	=>	3, 
                                            'post__not_in' => array( $exclude )
                                        );
                    
                        $query_mblnposts = new WP_Query( $args_business );
                    
                        if( $query_mblnposts->have_posts() ) :
                            while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
                    ?>

                        <li><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4></li>

					<?php
                            endwhile; // close WHILE
                        endif; // close IF
                    
                        wp_reset_postdata();
                    ?>

                    </ul>
                    
                    <ul class="tab-pane" id="business-smallbusiness">
					<?php
                    
                        $args_business = array(
                                            'post_type'	=>	array( 'post', 'pt_videolibrary'),
                                            'order' 	=>	'DESC',
                                            'orderby'	=>	'date',
                                            'tag'		=>	'small-business',
                                            'showposts'	=>	3, 
                                            'post__not_in' => array( $exclude )
                                        );
                    
                        $query_mblnposts = new WP_Query( $args_business );
                    
                        if( $query_mblnposts->have_posts() ) :
                            while( $query_mblnposts->have_posts() ) : $query_mblnposts->the_post();
                    ?>

                        <li><h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4></li>

					<?php
                            endwhile; // close WHILE
                        endif; // close IF
                    
                        wp_reset_postdata();
                    ?>

                    </ul>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
</div>
<!--// END STANDARD STORY FEED: BUSINESS //-->

<?php endforeach; ?>
