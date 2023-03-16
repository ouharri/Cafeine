<?php  
if ( ! function_exists( 'burger_storebiz_latest_product' ) ) :
function burger_storebiz_latest_product() {
	$hs_latest_product		= get_theme_mod('hs_latest_product','1');
	if($hs_latest_product =='1'):	
		do_action( 'latest-product' );
	endif;	
}
endif;
if ( function_exists( 'burger_storebiz_latest_product' ) ) {
$section_priority = apply_filters( 'stortebiz_section_priority', 12, 'burger_storebiz_latest_product' );
add_action( 'storebiz_sections', 'burger_storebiz_latest_product', absint( $section_priority ) );
}
