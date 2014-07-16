<?php
/*
 * CUSTOM POST TYPE: Video Library
 *
 * If you intend to use this, please:
 *
 * This code is based on CPT-Events found here: 
 * http://www.noeltock.com/web-design/wordpress/custom-post-types-events-pt1/
 */


// 0. Base

//add_action('admin_init', 'tf_functions_css');

//function tf_functions_css() {
//	wp_enqueue_style('tf-functions-css', get_bloginfo('template_directory') . '/css/tf-functions.css');
//}


// 1. Custom Post Type Registration (Video Library)

add_action( 'init', 'create_postype_videolibrary' );

function create_postype_videolibrary() {

	$labels = array(
		'name' => _x('Video Library', 'post type general name'),
		'singular_name' => _x('Video Library', 'post type singular name'),
		'add_new' => _x('Add New', 'pt_videolibrary'), //check this
		'add_new_item' => __('Add New Video'),
		'edit_item' => __('Edit Video'),
		'new_item' => __('New Video'),
		'view_item' => __('View Video'),
		'search_items' => __('Search Videos'),
		'not_found' =>  __('No videos found'),
		'not_found_in_trash' => __('No videos found in Trash'),
		'parent_item_colon' => '',
	);

	$args = array(
		'label' => __('Video Library'),
		'labels' => $labels,
		'public' => true,
		'can_export' => true,
		'show_ui' => true,
		'_builtin' => false,
		'_edit_link' => 'post.php?post=%d', // ?
		'capability_type' => 'post',
		'menu_icon' => get_bloginfo('template_url').'/img/admin/film.png',
		'hierarchical' => false,
		'rewrite' => array( "slug" => "videos" ),
		'supports'=> array( 'title', 'thumbnail', 'excerpt', 'editor', 'custom-fields' ) ,
		'show_in_nav_menus' => true,
		'taxonomies' => array( 'pt_videocategory', 'post_tag')
	);

	register_post_type( 'pt_videolibrary', $args );
	
}

// 2. Custom Taxonomy Registration (Video Library)

function create_videocategory_taxonomy() {

    $labels = array(
        'name' => _x( 'Video Category', 'taxonomy general name' ),
        'singular_name' => _x( 'Video Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Video Categories' ),
        'popular_items' => __( 'Popular Video Categories' ),
        'all_items' => __( 'All Video Categories' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Video Category' ),
        'update_item' => __( 'Update Video Category' ),
        'add_new_item' => __( 'Add New Video Category' ),
        'new_item_name' => __( 'New Video Category Name' ),
        'separate_items_with_commas' => __( 'Separate categories with commas' ),
        'add_or_remove_items' => __( 'Add or remove categories' ),
        'choose_from_most_used' => __( 'Choose from the most used categories' ),
    );

    register_taxonomy('pt_videocategory','pt_videolibrary', array(
        'label' => __( 'Video Category' ),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'video-category' ),
    ));

}

add_action( 'init', 'create_videocategory_taxonomy', 0 );


function create_videoshow_taxonomy() {

    $labels = array(
        'name' => _x( 'MBLN Show', 'taxonomy general name' ),
        'singular_name' => _x( 'MBLN Show', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search MBLN Shows' ),
        'popular_items' => __( 'Popular MBLN Shows' ),
        'all_items' => __( 'All MBLN Shows' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit MBLN Show' ),
        'update_item' => __( 'Update MBLN Show' ),
        'add_new_item' => __( 'Add New MBLN Show' ),
        'new_item_name' => __( 'New MBLN Show Name' ),
        'separate_items_with_commas' => __( 'Separate show names with commas' ),
        'add_or_remove_items' => __( 'Add or remove shows' ),
        'choose_from_most_used' => __( 'Choose from the most used shows' ),
    );

	register_taxonomy('pt_videoshow','pt_videolibrary', array(
        'label' => __( 'Show' ),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'video-show' ),
    ));

}

add_action( 'init', 'create_videoshow_taxonomy', 0 );

// 3. Show Columns
add_filter( 'manage_edit-pt_videolibrary_columns', 'pt_videolibrary_edit_columns');
add_action( 'manage_posts_custom_column', 'pt_videolibrary_custom_columns');

// Define the admin column headings
function pt_videolibrary_edit_columns( $columns ) {
	
	$columns = array(
        'cb' => '<input type=\"checkbox\" />',
        'title' => 'Video Title',
        'videolibrary_col_show' => 'MBLN Show',
        'videolibrary_col_category' => 'Video Category',
        'videolibrary_col_thumb' => 'Thumbnail'
        );

    return $columns;
}

// Populate the columns with content from the custom post type
function pt_videolibrary_custom_columns( $column ) {

    global $post;
	
    $custom = get_post_custom();

    switch( $column ){
		case 'videolibrary_col_show':
			// - show taxonomy terms -
			$shownames = get_the_terms( $post->ID, 'pt_videoshow' );
			
			$shownames_html = array();
			
			if( $shownames ) {
				foreach( $shownames as $showname )
					array_push( $shownames_html, $showname->name );
					
				echo implode( $shownames_html, ", " );
			} 
			
			else
				_e('None', 'themeforce');
		
		break;

		case 'videolibrary_col_category' :
			// - show taxonomy terms -
			$videocats = get_the_terms( $post->ID, 'pt_videocategory' );
			
			$videocats_html = array();
			
			if( $videocats ) {
				foreach( $videocats as $videocat )
					array_push( $videocats_html, $videocat->name );
					
				echo implode( $videocats_html, ", " );
			} 
			
			else
				_e('None', 'themeforce');
		break;
				
		case 'videolibrary_col_thumb' :
			// - show thumb -
			//$post_image_id = get_post_thumbnail_id( get_the_ID() );
			$videothumbURL = get_post_meta( get_the_ID(), 'pt_video_thumbnail', true );
			
			if( $videothumbURL ){
				//$thumbnail = wp_get_attachment_image_src( $post_image_id, 'post-thumbnail', false);
				//if ($thumbnail) (string)$thumbnail = $thumbnail[0];
				//echo '<img src="' . bloginfo('template_url') . '/timthumb/timthumb.php?src=' . $thumbnail . '&h=60&w=60&zc=1" alt="" />';
				echo '<img src="' . $videothumbURL . '" alt="" width="100" />';
			}
		break;

        }
}

/*
 * ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
 * https://gist.github.com/906872
 */

/*
add_filter("manage_pt_videos_sortable_columns", 'sort_pt_videos_columns');

function sort_pt_videos_columns( $columns ) {
	$custom = array( "pt_col_vl_date" => "Date" );
	
	return wp_parse_args( $custom, $columns );

}
*/


// 4. Show Meta-Box

// Additional Metaboxes to capture additional details
// Video Details: Maintains Link to 3rd Party Registration Page
add_action( 'admin_init', 'pt_videofields_detail' );

function pt_videofields_detail() {
	
    add_meta_box( 
		'pt_video_additional_data',			// HTML ID attribute, used for naming the full metabox
        'Video Details',					// Title of the full metabox
        'display_video_details_meta_box',	// function to call to render the metabox 
        'pt_videolibrary',					// Custom Post Type Name:  pt_videolibrary <--- IMPORTANT
		'normal',							// Part of the edit page where the metabox will be displayed
		'high'								// Priority
    );

}


function display_video_details_meta_box( $pt_videolibrary ) {
    // Pull the url for registration
    $ooyalaID = 		esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_ooyalaID', true ) );
	$videoThumb = 		esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_thumbnail', true ) );
	$videoSubtitle = 	esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_subtitle', true ) );
	$videoDuration = 	esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_duration', true ) );

    // - security - Add the Nonce
    echo '<input type="hidden" name="pt-video-nonce" id="pt-video-nonce" value="' . wp_create_nonce( 'pt-video-nonce' ) . '" />';

?>
	<style>
		div.addydeets div{ margin-bottom: 25px; }
		div.addydeets label{ display:block; margin-bottom: 6px; font-size: 1.1em; font-weight: bold; }
		div.addydeets p{ margin-top: 3px; }
	</style>

    
    <div class="addydeets">
    	<div>
            <label for="pt_video_subtitle">Subtitle</label>
            <input type="text" size="100" name="pt_video_subtitle" value="<?php echo $videoSubtitle; ?>" />
        </div>

		<div>
            <label for="pt_video_ooyalaID">Ooyala Video ID</label>
            <input type="text" size="100" name="pt_video_ooyalaID" value="<?php echo $ooyalaID; ?>" />
            <p>Enter the <em>Content ID</em> from Backlot of the video you would like to display on the page.</p>
        </div>

		<div>
            <label for="pt_video_thumbnail">Video Thumbnail URL</label>
            <input type="text" size="100" name="pt_video_thumbnail" value="<?php echo $videoThumb; ?>" />
        </div>
        
        <div>
            <label for="pt_video_duration">Video Duration <em>(in seconds)</em></label>
            <input type="text" size="100" name="pt_video_duration" value="<?php echo $videoDuration; ?>" />
            
        </div>

    </div>


<?php
}

/*
add_action( 'add_meta_boxes', 'tf_eventfields_disclosure' );

function tf_eventfields_disclosure(){
	add_meta_box( 
		'tf_event_disclosure', 					// HTML ID attribute, used for naming the full metabox
		'Additional Disclosures', 				// Title of the full metabox
		'display_event_disclosure_meta_box', 	// function to call to render the metabox 
		'tf_events', 							// Custom Post Type tf_events
		'normal', 								// Part of the edit page where the metabox will be displayed
		'high'									// Priority
	);
}

function display_event_disclosure_meta_box( $tf_events ){

	$values = get_post_custom( $tf_events->ID );
	
	$text = null;
	
	$text = isset( $values['disclosures_text'] ) ? esc_attr( $values['disclosures_text'][0] ) : ''; 
	$check = isset( $values['disclosures_widget'] ) ? esc_attr( $values['disclosures_widget'][0] ) : '';
	
	// We'll use this nonce field later on when saving.  
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' ); 

	?>
        <div> 
        	<label for="disclosures_widget" style="font-weight:bold;">Include Disclosure widget?</label>  
            <input type="checkbox" id="disclosures_widget" name="disclosures_widget" <?php checked( $check, 'on' ); ?> />              
        </div>

        <div>
            <p>Use this space to add addtional disclosures to the page. The formal site-wide disclosure is a widget and 
            can be found under <a href="/wp-admin/widgets.php">Appearance > Widgets</a>.</p>
            <textarea name="disclosures_text" id="disclosures_text" style="width:100%;"><?php echo $text; ?></textarea>
        </div>

	<?php		
}
*/


// 5. Save Data
add_action ('save_post', 'save_pt_videolibrary');

function save_pt_videolibrary(){

    global $post;

    // - still require nonce
    if ( !wp_verify_nonce( $_POST['pt-video-nonce'], 'pt-video-nonce' ))
        return $post->ID;

    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;

    // - convert back to unix & update post
    /*
	if(!isset($_POST["tf_events_startdate"])):
        return $post;
	endif;
	*/
	
	/*
	$updatestartd = strtotime ( $_POST["tf_events_startdate"] . $_POST["tf_events_starttime"] );
	update_post_meta($post->ID, "tf_events_startdate", $updatestartd );

    if(!isset($_POST["tf_events_enddate"])):
        return $post;
	endif;
	
	$updateendd = strtotime ( $_POST["tf_events_enddate"] . $_POST["tf_events_endtime"]);
	update_post_meta( $post->ID, "tf_events_enddate", $updateendd );
	*/
	
	// Save the Video Subtitle
	if( !isset( $_POST["pt_video_subtitle"] ))
        return $post;

	update_post_meta( $post->ID, "pt_video_subtitle", $_POST["pt_video_subtitle"] );

	// Save the Ooyala Video ID
	if( !isset( $_POST["pt_video_ooyalaID"] ))
        return $post;

	update_post_meta( $post->ID, "pt_video_ooyalaID", $_POST["pt_video_ooyalaID"] );

	// Save the Video Thumbnail Image
	if( !isset( $_POST["pt_video_thumbnail"] ))
        return $post;

	update_post_meta( $post->ID, "pt_video_thumbnail", $_POST["pt_video_thumbnail"] );
	
	// Save the Video Duration
	if( !isset( $_POST["pt_video_duration"] ))
        return $post;

	update_post_meta( $post->ID, "pt_video_duration", $_POST["pt_video_duration"] );

	/*
	if(!isset($_POST["tf_events_url"]))
        return $post;
		
	update_post_meta( $post->ID, "tf_events_url", $_POST["tf_events_url"] );
	
	if(!isset($_POST["disclosures_widget"]))
        return $post;
		
	update_post_meta( $post->ID, "disclosures_widget", $_POST["disclosures_widget"] );		

	
	if(!isset($_POST["disclosures_text"]))
        return $post;

	update_post_meta( $post->ID, "disclosures_text", $_POST["disclosures_text"] );
	*/
}

// 6. Customize Update Messages
add_filter('post_updated_messages', 'videolibrary_updated_messages');

function videolibrary_updated_messages( $messages ) {

  global $post, $post_ID;

  $messages['pt_videolibrary'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Video updated. <a href="%s">View item</a>'), esc_url( get_permalink( $post_ID ))),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Video updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Video restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Video published. <a href="%s">View post</a>'), esc_url( get_permalink( $post_ID ))),
		7 => __('Video saved.'),
		8 => sprintf( __('Video submitted. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Video scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview post</a>'),
		  // translators: Publish box date format, see http://php.net/date
		  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Video draft updated. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}


// 8. Custom Template Page for Video Library
add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {
    if ( get_post_type() == 'pt_videolibrary' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            $theme_file = locate_template( array ( 'video-page.php' ));
			$template_path = $theme_file;
        }
    }
    return $template_path;
}


// 9. Setup Isotope Post_Class list
/*
add_filter( 'post_class', 'event_taxonomy_post_class', 10, 3 );
 
function event_taxonomy_post_class( $classes, $class, $ID ) {
	$taxonomy = 'tf_eventcategory';
	$terms = get_the_terms( (int) $ID, $taxonomy );
    
	if( !empty( $terms )){
		foreach( (array) $terms as $order => $term ){
            if( !in_array( $term->slug, $classes )){
                $classes[] = $term->slug;
            }
        }
    }
    return $classes;
}
*/

?>