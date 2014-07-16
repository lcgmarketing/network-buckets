<?php
/*
 *	Based on the Category list view for video. Lists the terms in the custom
 *	taxonomy pt_videocategory
 *
 */
?>

<?php get_header( 'video' ); ?>

<!-- Video Category -->
 
        <div id="post-list">
            <div class="container">
                <div class="video-title" id="post-<?php the_ID(); ?>">
                    <?php 

						$video_category = wp_get_post_terms( $post->ID, 'pt_videocategory' );
						
						if( $video_category )
							echo '<!-- video type' . $video_category[0]->slug . '-->';
					?>
                    <h1><?php echo $video_category[0]->name; ?></h1> <h2>of MBLN Shows</h2>
                </div>
                
                <div class="row">
                	
                    <!-- LEFT COLUMN: FILTER -->
                    <div class="col-sm-2">
                        
                        <?php get_template_part( 'content', 'filterlist' ); ?>
                        
                    </div>
                    <!--// END LEFT COLUMN //-->
                    
                    <!-- MIDDLE COLUMN: VIDEO LIST -->
                    <div class="col-sm-6">
						<?php rewind_posts(); ?>
                        <?php //get_template_part( 'part', 'postnavigation' ); // next / previous post navigation ?>
                        
					<?php
						
						if( have_posts() ) :
						
							while ( have_posts() ) : the_post();
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
                                      
                                    <div class="col-md-8 entry-summary">
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

							else : echo '<p>There are no videos available at this time</p>';

							endif;
					?>
                        
						<?php get_template_part( 'part', 'postnavigation' ); // next / previous post navigation ?>
                                
                    </div>
                    <!--// END MIDDLE COLUMN //-->
                
                    <!-- RIGHT COLUMN: SIDEBAR -->
                    <div id="the-sidebar" class="col-sm-4"> 
                        <?php get_sidebar( 'video' ); ?>
                    </div>
                    <!--// END RIGHT COLUMN //-->                    

                </div>
    
            </div>
        </div>
        <!-- // END SEARCH // -->
        
<?php get_footer( 'video' ); ?>