<?php
/*
 *  The Graveyard
 *	Where PHP Code goes to die
 *
 */
 
 
/*
 * 	FUNCTION: feed_large
 *	PURPOSE: Output a video feed with a large leading thumbnail
 *	Will also output an ad, newsletter signup form, or additional thumbnails
 *	The large feed will always include 1 column of small thumbnails.
 *	INPUT: theQuery - the Wordpress query loop. ctaType - 'ad', 'form', or 'normal'
 *
 *	Note: This function is bugged. 
 *	When outputting a normal feed, the While loop will run twice without output. 
 */

function feed_large($theQuery, $ctaType) {

	$feedcount = 0;		// Keeps track of where we are in the main loop
	$columncount = 0;	// Keeps track of number of columns in the feed
	$smallcolumns = 0;	// Keeps track of small thumbnails
	$end = 9;			// Mainly used for small thumbnails. Don't want to output more than 6
	
	
	// Make sure a CTA is provided. If not, set it to 'normal'
	if ( $ctaType == '' ) 
		$ctaType = 'normal';

	while ( $theQuery->have_posts() && $feedcount < $end ) : $theQuery->the_post();
		$feedooyalaID = get_post_meta( $theQuery->post->ID, 'videoid_text', true ); 
		$videothumbURL = get_post_meta( $theQuery->post->ID, 'videothumb', true );
		
		// IF columncount == 0, you're in the first pass and need to output a large column.
		if ($columncount == 0) :

			// First large column is done, get ready to move to the next column.
			$columncount++;
			$feedcount++;
?>
            <!-- SIX COLUMNS - THE LARGE THUMB --> 
            <div class="six columns">
                <div class="four columns alpha omega">
                    <div class="large-thumb play">
                        <a href="<?php the_permalink(); ?>" class="mosaic-overlay">&nbsp;</a>
                        <div class="mosaic-backdrop">
                            <img src="<?php echo $videothumbURL; ?>" />
                        </div>
                    </div>
                    <!--// END LARGE THUMB //-->
                </div>
                
                <div class="two columns alpha omega">
                    <div class="large-deets">
                        <p class="heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                        <p><?php the_content(); ?></p>
                    </div>
                </div>
            </div>
            <!--// SIX COLUMNS - THE LARGE THUMB //--> 
<?php
		
		// Else, you're not in the first pass and need to output small columns or a call-to-action
		else :
		
			switch( $smallcolumns ){
				// IF smallcolumns = 0, you're in the second pass of the large feed. 
				// Output the top of a small column.
				case 0:
?>
                    <div class="two columns">
                        <div class="two columns row alpha omega">
                            <div class="small-thumb play">
                                <a href="<?php the_permalink(); ?>" class="mosaic-overlay">&nbsp;</a>
                                <div class="mosaic-backdrop"><img src="<?php echo $videothumbURL; ?>" /></div>
                                <div class="short-deets"><?php the_title(); ?></div>
                            </div>
                        </div>
			<?php 
					$smallcolumns++;
					$feedcount++; 

				break;
				
				// third pass of the large feed
				// Output the bottom of a small column
				case 1:
			?>
					<div class="two columns row alpha omega">
                        <div class="small-thumb">
                            <div class="small-thumb play">
                                <a href="<?php the_permalink(); ?>" class="mosaic-overlay">&nbsp;</a>
                                <div class="mosaic-backdrop"><img src="<?php echo $videothumbURL; ?>" /></div>
                                <div class="short-deets"><?php the_title(); ?></div>
                            </div>
                        </div>
                    </div>
                    <!--// END ROW //-->
                </div>
			<?php // end the two column for thumbs
				
					$smallcolumns++;
					$feedcount++; 
					
				break;
				
				// Insert the CTA or additional small columns.
				default:
					switch( $ctaType ){
						case 'form' : // Output a newletter form
							echo '<div class="four columns">';
							echo do_shortcode('[newsletter]');
							echo '</div>';
							$feedcount = $end; // You're done. End the main loop
						break;
							
						case 'ad' : // Output an ad
							echo '<div class="four columns">';
							echo do_shortcode('[newsletter]');
							echo '</div>';
							$feedcount = $end; // You're done. End the main loop
						break;
							
						case 'normal' :
							// Output small columns
							$smallcolumns = 0;
							$feedcount++;
							//largeFeed_smallthumbs( $top );
						break;
						
					} // END SWITCH #2
					
				break;	
			} // END SWITCH #1

		endif;
	
	endwhile; // END while loop

} // END feed_large


/*
 *
 * FUNCTION: largeFeed_smallthumbs
 * PURPOSE: 
 * INPUT: 
 *
 */
 
function largeFeed_smallthumbs( $top ){

		// Top or bottom of the small thumb column?
		if( $top ) : // Top
?>
		<div class="two columns">
			<div class="two columns row alpha omega">
				<div class="small-thumb play">
                    <a href="<?php the_permalink(); ?>" class="mosaic-overlay">&nbsp;</a>
                    <div class="mosaic-backdrop"><img src="<?php echo $videothumbURL; ?>" /></div>
                    <div class="short-deets"><?php the_title(); ?></div>
                </div>
			</div>
			
<?php
		
		else : // Bottom
?>
			<div class="two columns row alpha omega">
				<div class="small-thumb play">
                    <a href="<?php the_permalink(); ?>" class="mosaic-overlay">&nbsp;</a>
                    <div class="mosaic-backdrop"><img src="<?php echo $videothumbURL; ?>" /></div>
                    <div class="short-deets"><?php the_title(); ?></div>
                </div>
			</div>
			<!--// END ROW //-->
		</div>
	
	
<?php 
		endif;
}


/*
 *
 * FUNCTION: 
 * PURPOSE: Prepare the Video Feed(s). 
 * INPUT: 
 *	theTitle: title of the section
 *	theQuery: the current post query
 *	feedRow: current row being displayed. The second row is always large
 *	rowType: in a Large row, is the last column normal (i.e. more video), an ad, or a form
 *
 */

function prep_feed( $theTitle, $theQuery, $feedRow, $rowType ){
?>
	<div class="row wide">
		<div class="twelve columns">
			<?php 
				// Output the feed section title
				// Print the Category Description if one exists. 
				// Otherwise, output the Category Name
				if ($theTitle->description)
					echo '<h2>'.$theTitle->description.'</h2>';
				else
					echo '<h2>'.$theTitle->name.'</h2>';
			?>
		</div>
	
	<?php 
		// Check how many rows of videos have already been displayed on screen
		if( $feedRow == 1 )
			feed_large($theQuery, $rowType);
		else
			feed_normal($theQuery);
	?>
	
	</div>

<?php 
}

/*
 *
 * FUNCTION: feed_normal
 * PURPOSE: 
 * INPUT: 
 *	theQuery: the current post query
 *
 */

function feed_normal( $theQuery ) {

	// feedcount keeps track of where we are in the Wordpress post loop
	// For now, display only six videos in each video feed list
	// initalize the count
	$feedcount = 0;

	while ( $theQuery->have_posts() && $feedcount < 6 ) : $theQuery->the_post();
		$feedooyalaID = get_post_meta( $theQuery->post->ID, 'videoid_text', true ); 
		$videothumbURL = get_post_meta( $theQuery->post->ID, 'videothumb', true );

?>
	<div class="two columns">
	
		<div class="small-thumb play">
			<a href="<?php the_permalink(); ?>" class="mosaic-overlay">&nbsp;</a>
            <div class="mosaic-backdrop">
                <img src="<?php echo $videothumbURL; ?>" />
            </div>
		</div>
        <!--// END SMALL THUMB //-->
		
		<div class="small-deets">
			<p class="heading"><?php the_title(); ?></p>
			<p><?php the_content(); ?></p>
		</div>
        <!--// END SMALL DEETS //-->
	
	</div>

<?php
		$feedcount++;

	endwhile;

} // END feed_normal


/*
<!-- Trending Tabs: Demo Code -->
<!-- 
<div class="widget">
    <div id="trending">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#popular" data-toggle="tab">Popular</a></li>
            <li><a href="#featured" data-toggle="tab">Featured</a></li>
            <li><a href="#social-tre" data-toggle="tab">Social</a></li>
        </ul>
        
        <div class="tab-content">
            <div id="popular" class="tab-pane active">
                <ul class="trending-meta">
                    <li>
                        <a href="#" class="trending-thumb"><img src="http://dev.moneybizlife.com/moneybizlife/images/feature-sample.jpg" /></a>
                        <div class="trending-content">
                            <h4>Social Security <span>(02:30)</span></h4>
                            <p>July 23, 2013</p>
                        </div>
                    </li>

                    <li>
                        <a href="#" class="trending-thumb"><img src="http://dev.moneybizlife.com/moneybizlife/images/feature-sample.jpg" /></a>
                        <div class="trending-content">
                            <p><span>Social Security</span> (02:30)</p>
                            <p><span>July 23, 2013</span></p>
                        </div>
                    </li>
                </ul>
                
            </div>
            
            <div id="featured" class="tab-pane">
                <ul class="trending-meta">
                    <li>
                        <a href="#" class="trending-thumb"><img src="http://dev.moneybizlife.com/moneybizlife/images/feature-sample.jpg" /></a>
                        <div class="trending-content">
                            <h4>Social Security <span>(02:30)</span></h4>
                            <p>July 23, 2013</p>
                        </div>
                    </li>

                    <li>
                        <a href="#" class="trending-thumb"><img src="http://dev.moneybizlife.com/moneybizlife/images/feature-sample.jpg" /></a>
                        <div class="trending-content">
                            <p><span>Social Security</span> (02:30)</p>
                            <p><span>July 23, 2013</span></p>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div id="social-trending" class="tab-pane">
                <ul class="trending-meta">
                    <li>
                        <a href="#" class="trending-thumb"><img src="http://dev.moneybizlife.com/moneybizlife/images/feature-sample.jpg" /></a>
                        <div class="trending-content">
                            <h4>Social Security <span>(02:30)</span></h4>
                            <p>July 23, 2013</p>
                        </div>
                    </li>

                    <li>
                        <a href="#" class="trending-thumb"><img src="http://dev.moneybizlife.com/moneybizlife/images/feature-sample.jpg" /></a>
                        <div class="trending-content">
                            <p><span>Social Security</span> (02:30)</p>
                            <p><span>July 23, 2013</span></p>
                        </div>
                    </li>
                </ul>

            </div>
        </div>

    </div>
</div>
-->
*/


?>

