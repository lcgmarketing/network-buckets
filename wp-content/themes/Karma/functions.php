<?php

//check WP_DEBUG, if set to true, we suppress warning, notice, deprecated, and user notice
//If wpcu3er is activated, this wpcu3er plugin suppress all errors even if WP_DEBUG is set to true, 
//need to set display_errors to 1 in wpcu3er plugin to allow errors for troubleshooting
if(defined('WP_DEBUG') == 1 || WP_DEBUG == true){
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED ^ E_USER_NOTICE);
ini_set('display_errors', '1');
}


// Required TrueThemes Framework - do not edit
require_once(TEMPLATEPATH . '/truethemes_framework/truethemes_framework_init.php');

// Load translation text domain
load_theme_textdomain ('truethemes_localize');



// **** Add your custom codes below this line ****
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
		  'bsan_menu' => 'Private - Bucket Strategy Advisor Network',
		  'bsan_top_menu' => 'Private - Top Toolbar Menu'
		)
	);
}

?>