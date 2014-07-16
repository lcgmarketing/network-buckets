<?php

/* ======  Custom Metaboxes ====== */
 
// Additional Details - Post Custom Fields designed to provide a post Subtitle and Video ID


// 1.  Initalize the custom metadata
add_action( 'add_meta_boxes', 'add_meta_addydeets' );

function add_meta_addydeets(){
	$pagetypes = array('post', 'page');
	foreach ($pagetypes as $pagetype){
		add_meta_box( 'addydeets', 'Additional Details', 'render_meta_addydeets', $pagetype, 'normal', 'high' );
	}
}


// 2.  Render the custom metadata
function render_meta_addydeets( $post ){

	$values = get_post_custom( $post->ID );
	
	$text = null;
	
	$subtitle 	 = isset( $values['subtitle_text'] )	? esc_attr( $values['subtitle_text'][0] ) : ''; 
	$videoid 	 = isset( $values['videoid_text'] )		? esc_attr( $values['videoid_text'][0] ) : ''; 
	$actiontitle = isset( $values['action_text'] )	? esc_attr( $values['action_text'][0] ) : ''; 
	$actionurl 	 = isset( $values['actionurl_text'] )	? esc_attr( $values['actionurl_text'][0] ) : ''; 
	$category_show_slug = isset( $values['cat_show_slug'] ) ? esc_attr( $values['cat_show_slug'][0] ) : '';
	
	// For Reruns
	$rerun 			= isset( $values['rerun_check'] ) 	? esc_attr( $values['rerun_check'][0] ) : '';
	$rerun_videoid 	= isset( $values['rerun_videoid'] )	? esc_attr( $values['rerun_videoid'][0] ) : ''; 
	
	// For checkboxes
	// $check = isset( $values['subtitle_widget'] ) ? esc_attr( $values['subtitle_widget'][0] ) : '';
	
	// We'll use this nonce field later on when saving.  
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	
?>
        <!-- == This code was originally used to display a checkbox... removed! == -->
        <!-- 
        <div> 
        	<label for="subtitle_widget" style="font-weight:bold;">Include Subtitle widget?</label>  
            <input type="checkbox" id="subtitle_widget" name="subtitle_widget" <?php //checked( $check, 'on' ); ?> />
        </div>
        -->

        <div class="addydeets">
        	<style>
				.addydeets p{ margin-bottom: 3px; font-weight: bold; }
				.addydeets span{ font-size: .9em; font-style: italic; }
			</style>
            
            <p>Subtitle</p>
            <input type="text" name="subtitle_text" id="subtitle_text" style="width:100%;" value="<?php echo $subtitle; ?>" />
            
            <p>Ooyala Video ID</p>
            <input type="text" name="videoid_text" id="videoid_text" style="width:100%;" value="<?php echo $videoid; ?>" />
            <span>Note: The Video ID must come from Backlot. If you do not know the Video ID, please contact your Ooyala administrator.</span>
            
            <p>Call to Action Button</p>
            <label width="15%" style="margin-right: 5px;">Button Title</label><input type="text" name="action_text" id="action_text" style="width:35%; margin-right: 10px;" value="<?php echo $actiontitle; ?>" /> 
            <label width="15%" style="margin-right: 5px;">Form Link</label><input type="text" name="actionurl_text" id="actionurl_text" style="width:35%;" value="<?php echo $actionurl; ?>" />
            <span style="display:block">Enter details for a show specific call to action form.</span>
            
            <p>Related Show</p>
            <input type="text" name="cat_show_slug" id="cat_show_slug" style="width:100%;" value="<?php echo $category_show_slug; ?>" />
            <span>Enter the <a href="/wp-admin/edit-tags.php?taxonomy=category" target="_blank">Category short name</a> for the related show.</span>
            
            <p style="font-weight:bold;">Rerun? <input type="checkbox" id="rerun_check" name="rerun_check" <?php checked( $rerun, 'on' ); ?> style="margin-left: 10px;" /></p>  
            <span>Important: check this box only if the episode will be a repeat.</span>
            
            <p>Rerun Ooyala Video ID</p>
            <input type="text" name="rerun_videoid" id="rerun_videoid" style="width:100%;" value="<?php echo $rerun_videoid; ?>" />
            <span>If the show is a repeat, enter the Ooyala Video ID you wish to repeat. You can obtain the Video ID from Backlot. If you do not know the Video ID, please contact your Ooyala administrator.</span>
        </div>

	<?php		
}


// 3. Save the data
add_action( 'save_post', 'save_meta_addydeets' );

function save_meta_addydeets( $post_id ){

	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) )
		return;
	
	// if our current user can't edit this post, bail
	if( 'page' == $_POST['post_type']){
		if( !current_user_can('edit_page') )
			return;
	}
	else {
		if( !current_user_can( 'edit_post' ) ) 
			return;
	}
	
	// now we can actually save the data
	$allowed = array( 
				'a' => array( 		// on allow a tags
				'href' => array() 	// and those anchors can only have href attribute
				));
	
	// Save the Subtitle
	if( isset( $_POST['subtitle_text'] ))
		update_post_meta( $post_id, 'subtitle_text', wp_kses( $_POST['subtitle_text'], $allowed ) );
		
	// Save the Video ID
	if( isset( $_POST['videoid_text'] ))
		update_post_meta( $post_id, 'videoid_text', $_POST['videoid_text'] );

	// Save the Call to Action Button Title
	if( isset( $_POST['action_text'] ))
		update_post_meta( $post_id, 'action_text', $_POST['action_text'] );

	// Save the Call to Action URL
	if( isset( $_POST['actionurl_text'] ))
		update_post_meta( $post_id, 'actionurl_text', $_POST['actionurl_text'] );

	// Save the Category Slug Name
	if( isset( $_POST['cat_show_slug'] ))
		update_post_meta( $post_id, 'cat_show_slug', $_POST['cat_show_slug'] );
		
	// Save the Rerun Video ID
	if( isset( $_POST['rerun_videoid'] ))
		update_post_meta( $post_id, 'rerun_videoid', $_POST['rerun_videoid'] );
		
	// Save the Rerun checkbox
    $rerun = isset( $_POST['rerun_check'] ) && $_POST['rerun_check'] ? 'on' : 'off';  
    update_post_meta( $post_id, 'rerun_check', $rerun );
	
}


// Syndicator Details - Post Custom Fields designed to provide syndicator information

// 1.  Initalize the custom metadata
add_action( 'add_meta_boxes', 'add_meta_syndicator' );

function add_meta_syndicator(){
	$pagetypes = array( 'post' );
	
	foreach ($pagetypes as $pagetype){
		add_meta_box( 'syndicatordetails', 'Syndicator Details', 'render_meta_syndicator', $pagetype, 'normal', 'high' );
	}
}


// 2.  Render the custom metadata
function render_meta_syndicator( $post ){

	$values = get_post_custom( $post->ID );
	
	$text = null;
	
	$syndicator	 = isset( $values['syndicator_text'] )	?	esc_attr( $values['syndicator_text'][0] ) : ''; 
	$author 	 = isset( $values['author_text'] )		?	esc_attr( $values['author_text'][0] ) : ''; 
	
	// For checkboxes
	// $check = isset( $values['subtitle_widget'] ) ? esc_attr( $values['subtitle_widget'][0] ) : '';
	
	// We'll use this nonce field later on when saving.  
    wp_nonce_field( 'syndicator_meta_nonce', 'meta_syndicator_nonce' );
	
?>
        <!-- == This code was originally used to display a checkbox... removed! == -->
        <!-- 
        <div> 
        	<label for="subtitle_widget" style="font-weight:bold;">Include Subtitle widget?</label>  
            <input type="checkbox" id="subtitle_widget" name="subtitle_widget" <?php //checked( $check, 'on' ); ?> />
        </div>
        -->

        <div class="syndicator">
        	<style>
				.syndicator p{ margin-bottom: 3px; font-weight: bold; }
				.syndicator span{ font-size: .9em; font-style: italic; }
			</style>
            
            <p>Syndicator</p>
            <input type="text" name="syndicator_text" id="syndicator_text" style="width:100%;" value="<?php echo $syndicator; ?>" />
            
            <p>Author</p>
            <input type="text" name="author_text" id="author_text" style="width:100%;" value="<?php echo $author; ?>" />
            <span></span>
        </div>

	<?php		
}


// 3. Save the data
add_action( 'save_post', 'save_meta_syndicator' );

function save_meta_syndicator( $post_id ){

	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_syndicator_nonce'] ) || !wp_verify_nonce( $_POST['meta_syndicator_nonce'], 'syndicator_meta_nonce' ) )
		return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) )
		return;
	
	// now we can actually save the data
	$allowed = array( 
				'a' => array( 		// on allow a tags
				'href' => array() 	// and those anchors can only have href attribute
				));
	
	// Save the Subtitle
	if( isset( $_POST['syndicator_text'] ))
		update_post_meta( $post_id, 'syndicator_text', wp_kses( $_POST['syndicator_text'], $allowed ) );
		
	// Save the Video ID
	if( isset( $_POST['videoid_text'] ))
		update_post_meta( $post_id, 'author_text', wp_kses( $_POST['author_text'], $allowed ) );

}


?>