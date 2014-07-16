<?php
/*
 *	Display the featured video
 *	Pulls from Pages
 * 
 *	Custom Fields Available: 
 *	- pt_video_ooyalaID
 *	- pt_video_subtitle
 * 	- pt_video_thumbnail
 *
 */

?>

    <!-- THEVIDEO -->
    <div id="theVideo">
        <div id="playerwrapper"></div>
        <script>
            OO.ready(function() { OO.Player.create('playerwrapper', videoID, {"autoplay":true, height: '', width: '' }); }); // autoplay
            //OO.ready(function() { OO.Player.create('playerwrapper', videoID); }); // no autoplay
        </script>
        <noscript><div>Please enable Javascript to watch this video</div></noscript>
    </div>
    <!--// END THEVIDEO //-->
    
    <!-- SOCIAL: VIDEO -->  
    <div id="social">
        <div class="addthis_toolbox">
            <div class="custom-icons">
                <a class="video-description no-hover" id="description"><span>=</span>Share This on: </a>
                <a class="addthis_button_facebook" id="facebook"><span>F</span><span class="network">Facebook</span></a> 
                <a class="addthis_button_twitter" id="twitter"><span>t</span><span class="network">Twitter</span></a> 
                <a class="addthis_button_email" id="email" ><span>m</span><span class="network">Email</span></a>
            </div>

        </div>
    
        <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=raylucia"></script>
        
        <div class="description">
            <?php the_content(); ?>
        </div>
        
    </div>
    <!--// END SOCIAL: VIDEO //-->