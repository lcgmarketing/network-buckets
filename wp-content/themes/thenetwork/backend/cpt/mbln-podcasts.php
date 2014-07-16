<?php
/*
 * CUSTOM POST TYPE: Podcasts
 *
 *
 * This code is based on CPT-Events found here: 
 * http://www.noeltock.com/web-design/wordpress/custom-post-types-events-pt1/
 */


// 0. Base

//add_action('admin_init', 'tf_functions_css');

//function tf_functions_css() {
//	wp_enqueue_style('tf-functions-css', get_bloginfo('template_directory') . '/css/tf-functions.css');
//}


// 1. Custom Post Type Registration (MBLN Podcasts)

add_action( 'init', 'create_postype_mblnpodcasts' );

function create_postype_mblnpodcasts() {

	$labels = array(
		'name' => _x('Podcasts', 'post type general name'),
		'singular_name' => _x('Podcasts', 'post type singular name'),
		'add_new' => _x('Add New', 'pt_mblnpodcasts'), //check this
		'add_new_item' => __('Add New Podcast'),
		'edit_item' => __('Edit Podcast'),
		'new_item' => __('New Podcast'),
		'view_item' => __('View Podcast'),
		'search_items' => __('Search Podcasts'),
		'not_found' =>  __('No podcasts found'),
		'not_found_in_trash' => __('No podcasts found in Trash'),
		'parent_item_colon' => '',
	);

	$args = array(
		'label' => __('Podcasts'),
		'labels' => $labels,
		'public' => true,
		'can_export' => true,
		'show_ui' => true,
		'_builtin' => false,
		'_edit_link' => 'post.php?post=%d', // ?
		'capability_type' => 'post',
		'menu_icon' => get_bloginfo('template_url').'/img/admin/media-player.png',
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'podcasts' ),
		'supports'=> array( 'title', 'thumbnail', 'excerpt', 'editor' ) ,
		'show_in_nav_menus' => true,
		'taxonomies' => array( 'pt_mblnpodcasts', 'post_tag')
	);

	register_post_type('pt_mblnpodcasts', $args);

}

// 2. Custom Taxonomy Registration (Podcast Name)
function create_podcastname_taxonomy() {

    $labels = array(
        'name' => _x( 'Podcast Name', 'taxonomy general name' ),
        'singular_name' => _x( 'Podcast Name', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Podcast Entries' ),
        'popular_items' => __( 'Popular Podcast Entries' ),
        'all_items' => __( 'All Podcast Entries' ),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __( 'Edit Podcast Name' ),
        'update_item' => __( 'Update Podcast Name' ),
        'add_new_item' => __( 'Add New Podcast Name' ),
        'new_item_name' => __( 'New Podcast Name' ),
        'separate_items_with_commas' => __( 'Separate types with commas' ),
        'add_or_remove_items' => __( 'Add or remove types' ),
        'choose_from_most_used' => __( 'Choose from the most used types' ),
    );

    register_taxonomy('tax_podcasting','pt_mblnpodcasts', array(
        'label' => __( 'Podcast Name' ),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 
			'slug' => 'podcasting',
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/" 
		)
    ));
}

add_action( 'init', 'create_podcastname_taxonomy', 0 );


// 3. Show Columns

// Define the admin column headings
add_filter( "manage_edit-pt_podcast_columns", "pt_podcast_edit_columns" );

function pt_podcast_edit_columns( $columns ) {

    //$columns = array(
    //    "cb" => "<input type=\"checkbox\" />",
    //    "title" => "Event",
    //    "tf_col_ev_date" => "Dates",
    //    "tf_col_ev_times" => "Times",
    //    "tf_col_ev_desc" => "Description",
    //    "tf_col_ev_cat" => "Category",
    //    "tf_col_ev_thumb" => "Thumbnail"
    //    );
	
	$columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Title",
        "pt_col_pod_date" 	=> "Created Date",
        "pt_col_pod_show" 	=> "Episode Name",
        "pt_col_pod_cat"	=> "Podcast Name"
        );

    return $columns;
}

// Populate the columns with content from the custom post type
add_action ("manage_posts_custom_column", "pt_podcast_custom_columns");

function pt_podcast_custom_columns( $column ) {

    global $post;
	
    $custom = get_post_custom();

    switch ( $column ){
            case "pt_col_pod_cat":
                // - show taxonomy terms -
                $podcastcats = get_the_terms($post->ID, 'tax_podcasting');
				
                $podcastcats_html = array();
                
				if ($podcastcats) {
                    foreach( $podcastcats as $podcastcat )
                    array_push( $podcastcats_html, $podcastcat->name );
                    echo implode( $podcastcats_html, ", " );
                } 
				else
					_e('None', 'themeforce');
            break;
			
            case "pt_col_vl_date":
                // - show dates -
                //$startd = $custom["tf_events_startdate"][0];
                //$endd = $custom["tf_events_enddate"][0];
                //$startdate = date("F j, Y", $startd);
                //$enddate = date("F j, Y", $endd);
                //echo $startdate . '<br /><em>' . $enddate . '</em>';
            break;
			
            case "pt_col_vl_thumb":
                // - show thumb -
                $post_image_id = get_post_thumbnail_id( get_the_ID() );
				
                if ($post_image_id) {
                    $thumbnail = wp_get_attachment_image_src( $post_image_id, 'post-thumbnail', false);
                    if ($thumbnail) (string)$thumbnail = $thumbnail[0];
                    echo '<img src="';
                    echo bloginfo('template_url');
                    echo '/timthumb/timthumb.php?src=' . $thumbnail . '&h=60&w=60&zc=1" alt="" />';
                }
            break;
			
            case "pt_col_vl_desc";
                the_excerpt();
            
			break;

        }
}

/*
 * ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
 * https://gist.github.com/906872
 */
add_filter("manage_pt_podcast_sortable_columns", 'sort_pt_podcast_columns');

function sort_pt_podcast_columns( $columns ) {
	$custom = array( "pt_col_vl_date" => "Date" );
	
	return wp_parse_args( $custom, $columns );

}


// 4. Show Meta-Box

add_action( 'admin_init', 'pt_podcast_create' );

function pt_podcast_create() {
    add_meta_box('pt_podcast_meta', 'Podcasts', 'pt_podcast_meta', 'pt_mblnpodcasts');
}

function pt_podcast_meta() {

    // - grab data -
    global $post;
    $custom = get_post_custom( $post->ID );
    
    // - security -
    echo '<input type="hidden" name="pt-podcast-nonce" id="pt-podcast-nonce" value="' . wp_create_nonce( 'pt-podcast-nonce' ) . '" />';

}


// Additional Metaboxes to capture additional details
// Event Details: Maintains Link to 3rd Party Registration Page
// OLD CODE: add_action( 'admin_init', 'tf_eventfields_detail' );
add_action( 'admin_init', 'pt_podcastfields_detail' );

function pt_podcastfields_detail() {
	
    add_meta_box( 
		'pt_podcast_additional_data',		// HTML ID attribute, used for naming the full metabox
        'Podcast Details',					// Title of the full metabox
        'display_podcast_details_meta_box',	// function to call to render the metabox 
        'pt_mblnpodcasts',					// Custom Post Type Name:  pt_mblnpodcasts <--- IMPORTANT
		'normal',							// Part of the edit page where the metabox will be displayed
		'high'								// Priority
    );

}


function display_podcast_details_meta_box( $pt_mblnpodcasts ) {
    // Pull the url for registration
    //$ooyalaID = 		esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_ooyalaID', true ) );
	//$videoThumb = 		esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_thumbnail', true ) );
	//$videoSubtitle = 	esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_subtitle', true ) );
	//$videoDuration = 	esc_html( get_post_meta( $pt_videolibrary->ID, 'pt_video_duration', true ) );


	/*
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
	*/

?>
<?php
}


// 5. Save Data
add_action ('save_post', 'save_pt_mblnpodcasts');

function save_pt_mblnpodcasts(){

    global $post;

    // - still require nonce
    if ( !wp_verify_nonce( $_POST['pt-podcast-nonce'], 'pt-podcast-nonce' ))
        return $post->ID;

    if ( !current_user_can( 'edit_post', $post->ID ))
        return $post->ID;

	/*
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
	*/

}

// 6. Customize Update Messages
add_filter('post_updated_messages', 'pt_podcasts_updated_messages');

function pt_podcasts_updated_messages( $messages ) {

  global $post, $post_ID;

  $messages['pt_mblnpodcasts'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Podcast updated. <a href="%s">View item</a>'), esc_url( get_permalink( $post_ID ))),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Podcast updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Podcast restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Podcast published. <a href="%s">View post</a>'), esc_url( get_permalink( $post_ID ))),
		7 => __('Podcast saved.'),
		8 => sprintf( __('Podcast submitted. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Podcast scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview post</a>'),
		  // translators: Publish box date format, see http://php.net/date
		  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Podcast draft updated. <a target="_blank" href="%s">Preview post</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}


// 8. Custom Template Page for MBLN Podcasts
//add_filter( 'template_include', 'include_podcast_template', 1 );

/*
function include_podcast_template( $template_path ) {
    if ( get_post_type() == 'pt_mblnpodcasts' ) {
        //if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            $theme_file = locate_template( array ( 'podcast-page.php' ));
			$template_path = $theme_file;
        //}
    }
    return $template_path;
}
*/

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