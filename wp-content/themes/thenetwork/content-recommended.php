<?php
 /*
  * -------------------------------------------------------------------------
  * List recommended posts at the bottom of blog articles.
  *	First column represents articles from the site. Second column represents 
  *	articles from Outbrain (third-party).
  * -------------------------------------------------------------------------
  */
?>

	<div class="recommended-posts">
    	
        <div class="row">
	        <!-- Column 1: Internal Articles -->
        	<div class="col-sm-6">
            <h4>More Articles from Money Biz Life</h4>
			<?php
            
                $orig_post = $post;
            
                global $post;
            
                $tags = wp_get_post_tags( $post->ID );
                
                if( $tags ) :
					
					echo '<ul>';
                    
					$tag_ids = array();
                    
                    foreach( $tags as $individual_tag ) 
                        $tag_ids[] = $individual_tag->term_id;
            
                    $recommended_args = array(
                            'tag__in'		=>	$tag_ids,
                            'post__not_in'	=>	array( $post->ID ),
                            'posts_per_page' =>	4, // Number of related posts to display.
                            'caller_get_posts' => 1
                            );
                
                    $recommended_query = new wp_query( $recommended_args );
            
                    while( $recommended_query->have_posts() ) : $recommended_query->the_post();
            ?>
                
                <li><a rel="external" href="<? the_permalink()?>"><?php the_title(); ?></a></li>
                
            <?php 
                    endwhile;
                	
					echo '</ul>';
					
                endif;
                
                $post = $orig_post;
                
                wp_reset_query();
            ?>
  	
            </div>

	        <!-- Column 2: External Articles -->
            <div class="col-sm-6">
            	<div data-src="<?php echo $post_permalink; ?>" class="OUTBRAIN" ></div>
<script type="text/javascript">(function(){window.OB_platformType=8;window.OB_langJS="http://widgets.outbrain.com/lang_en.js";window.OBITm="1381359568055";window.OB_recMode="brn_box";var ob=document.createElement("script");ob.type="text/javascript";ob.async=true;ob.src="http"+("https:"===document.location.protocol?"s":"")+"://widgets.outbrain.com/outbrainLT.js";var h=document.getElementsByTagName("script")[0];h.parentNode.insertBefore(ob,h);})();</script>
            </div>
        
        </div>
        	
    </div>