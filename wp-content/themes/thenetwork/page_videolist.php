<?php
/*
 *	Template Name: Video - List Style
 *
 *	======================
 *	Based on the Category list view for video
 *
 */
?>

<?php get_header( 'video' ); ?>

<?php
		the_post();

		// Assign the custom variables Subtitle and Video ID
		$subtitle = get_post_meta( $post->ID, 'subtitle_text', true );
		$ooyalaID = get_post_meta( $post->ID, 'videoid_text', true );
		$showname = get_post_meta( $post->ID, 'showname', true );
?>

<!-- Video List -->
 
        <div id="post-list">
            <div class="container">
                <div class="video-title" id="post-<?php the_ID(); ?>">
                    <h1><?php the_title(); ?></h1> <h2><?php echo $subtitle; ?></h2>
                </div>
                
                <div class="row">
                	
                    <!-- LEFT COLUMN: FILTER -->
                    <div class="col-md-2">
                    	
                        <?php get_template_part( 'content', 'filterlist' ); ?>
                        
                    </div>
                    <!--// END LEFT COLUMN //-->
                    
                    <!-- MIDDLE COLUMN: VIDEO LIST -->
                    <div class="col-md-6">
                    
                    	<?php //rewind_posts(); ?>
                        
						<?php 
                            // Pull all videos in the library, starting with the latest
							$videolist_args = array( 'post_type' => 'pt_videolibrary', 'order' => 'DESC' );
						
							$videolist_query = new WP_Query( $videolist_args );
							
							//global $wp_query;
							
                            $total_pages = $videolist_query->max_num_pages; 
                            
							if( $total_pages > 1 ) :
                        ?>
                        
                        <div id="nav-above" class="entry-navigation">
                            <span class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'hbd-theme' )) ?></span> 
                            <span class="nav-next"><?php previous_posts_link(__( ' | Newer posts <span class="meta-nav">&raquo;</span>', 'hbd-theme' )) ?></span>
                        </div><!-- #nav-above -->
                        
                        <?php endif; ?>
                        
					<?php
                        // Pull all videos in the library, starting with the latest
						//$videolist_args = array( 'post_type' => 'pt_videolibrary', 'order' => 'DESC' );
						
						//$videolist_query = new WP_Query( $videolist_args );
						
						if( $videolist_query->have_posts() ) :
						
							while ( $videolist_query->have_posts() ) : $videolist_query->the_post();
								$video_thumburl = get_post_meta( $post->ID, 'pt_video_thumbnail', true );
								$video_subtitle = get_post_meta( $post->ID, 'pt_video_subtitle', true );
							
                    ?>
                            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <div class="entry-title">
                                    <h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'hbd-theme'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                                </div>
                                
                                <div class="the-entry row">
                                    <div class="col-md-4 entry-image">
                                        <a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'hbd-theme'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><img src="<?php echo $video_thumburl; ?>" /></a>
                                    </div>
                                      
                                    <div class="col-md-12 entry-summary">
                                        <?php the_excerpt( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'hbd-theme' )  ); ?>
                                        <p class="cat-links">
                                            <a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'hbd-theme'), the_title_attribute('echo=0') ); ?>" rel="bookmark" class="watch-now"><span class="pictogram watch-now">> </span>Watch now</a>
                                            
                                            <?php the_tags( '<span class="tag-links"><span class="entry-utility-prep entry-utility-prep-tag-links"><span class="meta-sep"> | </span>' . __('tagged as ', 'hbd-theme' ) . '</span>', ", ", "</span>\n\t\t\t\t\t\t\n" ) ?>
                                        </p>
                                    </div><!-- .entry-summary -->
                                </div>
                            </div><!-- #post-<?php the_ID(); ?> -->
    
					<?php 
							endwhile; 

							else : 
								echo '<p>There are no videos available at this time</p>';

							endif;
					?>
                        
						<?php 
                            global $wp_query;
							
                            $total_pages = $wp_query->max_num_pages; 
                            
							if ( $total_pages > 1 ) :
                        ?>
                        
                        <div id="nav-above" class="entry-navigation">
                            <span class="nav-previous"><?php next_posts_link(__( '<span class="meta-nav">&laquo;</span> Older posts', 'hbd-theme' )) ?></span> 
                            <span class="nav-next"><?php previous_posts_link(__( ' | Newer posts <span class="meta-nav">&raquo;</span>', 'hbd-theme' )) ?></span>
                        </div><!-- #nav-above -->
                        
                        <?php endif; ?>
                                
                    </div>
                    <!--// END MIDDLE COLUMN //-->
                
                    <!-- RIGHT COLUMN: SIDEBAR -->
                    <div id="the-sidebar" class="col-md-4 visible-md visible-lg"> 
                        <?php get_sidebar( 'video' ); ?>
                    </div>
                    <!--// END RIGHT COLUMN //-->                    

                </div>
    
            </div>
        </div>
        <!-- // END SEARCH // -->
        
<?php get_footer( 'video' ); ?>