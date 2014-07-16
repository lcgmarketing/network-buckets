<?php 
function leftORright( $position ){

	switch( $position ) :
		case 'left' :

			outputLargeThumb();
			
			switch ( $cta ) :
				case 'form' :
					
				break;

				case 'ad' :
					
				break;

				case 'normal' :
					
				break;
				
			endswitch;
			
		break;

		case 'right' :

			switch ( $cta ) :
				case 'form' :
					
				break;

				case 'ad' :
					
				break;

				case 'normal' :
					
				break;
				
			endswitch;
			
			outputLargeThumb();
			
		break;
	
	endswitch;

}
?>