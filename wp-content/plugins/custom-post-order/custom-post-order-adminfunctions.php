<?php
// this document displays and manages the administrative options for the CustomPostOrder plugin.

global $userdata;
get_currentuserinfo();


if ( !current_user_can ( "edit_pages" ) ) die ( __( 'Sorry You can\'t see this' ) ); // only admins can edit this page

$CustomPostOrder_Options = array(
    'page_id'   => '',
);

/**
 * Returns an array of plugin settings.
 */
function CustomPostOrder_settings() {
    $settings_arr = array(
        'orderElement' => 'post_date',
        'orderType'    => 'DESC',
        'applyTo'      => 'all',
        'applyArray'   => ''
    );
    return $settings_arr;
}

/**
 * Initializes settings and adds options to database if options are not set.
 * Updates database options if any post variables were submitted.
 * Returns the current CustomPostOrder options from the database.
 */
function CustomPostOrder_updateOptions(){
    $settings      = CustomPostOrder_settings();
    $settings_keys = array_keys ( $settings );
    if ( !get_option ( 'CustomPostOrder-settings' ) ) {
        //add the settings to options
        add_option ('CustomPostOrder-settings', $settings );
    }
    else
        $settings = get_option ( 'CustomPostOrder-settings' );
     
    $input = $settings;
    
   
    if ( $_POST['orderElement'] || $_POST['orderType'] || $_POST['applyTo'] || $_POST['applyArray']) {
    		check_admin_referer('custom_post_order'); # check for the header we don't go any further if this is not satisfied 
    		
            $input['orderElement'] = $_POST['orderElement'];
            $input['orderType']    = $_POST['orderType'];
            $input['applyTo']      = $_POST['applyTo'];
            $input['applyArray']   = $_POST['applyArray'];
            update_option ( 'CustomPostOrder-settings', $input );
        }
    return $input;
}

/**
 * Displays the Custom Post Order plugin options
 */

function CustomPostOrder_Form_init()
{
    global $wpdb, $user_ID, $blog_id;

    $option = CustomPostOrder_updateOptions();
    /*
     * If the options already exist in the database then use them,
     * else initialize the database by storing the defaults.
     */
   	?>
   	
   	<div class="wrap">
   	<?php
    if( $_POST ) /* let the user know the options have been updated */
    { ?>
    	<div id="message" class="updated fade"><p>Custom Post Order Options Updated</p></div>
    <?php
    }
    // display the options in a form
    ?>
    
    <h2><?php _e("Custom Post Order Options"); ?></h2>
  
    Please select the desired sorting type to be used, when displaying a series of posts:
    <form action="" method="post">
    <?php if ( function_exists('wp_nonce_field') )
				wp_nonce_field('custom_post_order'); #check the header 
	?>
    <table class="form-table">
            	<tr>
                    <th scope="row">Order By</th>
                    <td>
                        <fieldset><legend class="hidden">Order By</legend>
                        <label><input type="radio" name="orderElement" value="post_date"
    <?php
        if ( $option['orderElement'] == "post_date" ) {
            echo 'checked="checked"';
        }
    ?>  /> Post Date</label> <br />
    
   					 <label><input type="radio" name="orderElement" value="post_title"
    <?php
        if ( $option['orderElement'] == "post_title" ) {
            echo 'checked="checked"';
        }
    ?>  /> Post Title</label> <br />
    
    				<label><input type="radio" name="orderElement" value="post_author"
    <?php
        if ( $option['orderElement'] == "post_author" ) {
            echo 'checked="checked"';
        }
    ?>  /> Post Author</label> <br />
   					 <label><input type="radio" name="orderElement" value="post_modified"
    <?php
        if ( $option['orderElement'] == "post_modified" ) {
            echo 'checked="checked"';
        }
    ?>  /> Last modified</label> <br />
    				<label><input type="radio" name="orderElement" value="post_name"
    <?php
        if ( $option['orderElement'] == "post_name" ) {
            echo 'checked="checked"';
        }
    ?>  /> Post Name (the post slug)</label> <br />
                        </fieldset>
                    </td>
                </tr>
    
    
    			<tr>
                    <th scope="row">Order Direction</th>
                    <td>
                        <fieldset><legend class="hidden">Order Direction</legend>
                        <label><input type="radio" name="orderType" value="DESC"
    <?php
        if ( $option['orderType'] == "DESC" ) {
            echo 'checked="checked"';
        }
    ?>  /> Descending ( 4, 3, 2, 1 )</label> <br />
    					<label><input type="radio" name="orderType" value="ASC"
    <?php
        if ( $option['orderType'] == "ASC" ) {
            echo 'checked="checked"';
        }
    ?>  /> Ascending ( 1, 2, 3, 4 )</label> <br />
                        </fieldset>
                    </td>
                </tr>
  
    			
    			<tr>
                    <th scope="row">Apply To</th>
                    <td>
                        <fieldset><legend class="hidden">Apply To</legend>
  						<label><input type="radio" name="applyTo" value="all" onclick="jQuery('#select_categories').hide();"
       	<?php
            if ( $option['applyTo'] == 'all' ) {
                echo 'checked="checked"';
            }
        ?>  /> Apply to all categories </label> <br /> 
        				<label><input type="radio" name="applyTo" value="selected" onclick="jQuery('#select_categories').show();"
        <?php
            if ( $option['applyTo'] == 'selected' ) {
                echo 'checked="checked"';
            }
        ?>  /> Apply to selected categories </label>
        <br />

                         </fieldset>
                    </td>
                </tr>
                
                
                	<tr id="select_categories" <?php if( $option['applyTo'] == 'all') {?> style="display:none;" <?php } ?> >
                    <th scope="row" >Selected Categories</th>
                    <td>
                        <fieldset><legend class="hidden">Selected Categories</legend>
  						
            <?php 
            
            $cats = get_categories();
     
            foreach ( $cats as $cat ) : ?>
						<label><input type="checkbox" name="applyArray[]" value="<?php echo $cat->term_id; ?>" <?php if(is_array($option['applyArray']) && in_array($cat->term_id, $option['applyArray'])) echo ' checked="checked"'; ?> >
				<?php echo $cat->name; ?></label><br />
			<?php endforeach; ?>

                         </fieldset>
                    </td>
                </tr>
 
  </table>
    
    <input type="submit" target=_self class="button" value="Save Options" name="CustomPostOrderOptionButton"/>
    </form>
    </div>
    <?php
}

// let's show the form since we are already here
CustomPostOrder_Form_init();

?>