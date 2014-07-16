<?php
	// Checks if the user is logged into the system and at least a level 1
	if( is_user_logged_in() ) : get_header( 'private' );
	else : header( "Location: http://www.bucketnetwork.com/request-additional-information/");
	//get_header();
	endif;
?>