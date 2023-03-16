<?php  
if ( ! function_exists( 'burger_storebiz_feature_product' ) ) :
function burger_storebiz_feature_product() {
	$hs_featured_product	= get_theme_mod('hs_featured_product','1'); 
	if($hs_featured_product =='1'):
		do_action( 'featured-product' );
	endif;	
}
endif;
if ( function_exists( 'burger_storebiz_feature_product' ) ) {
$section_priority = apply_filters( 'stortebiz_section_priority', 14, 'burger_storebiz_feature_product' );
add_action( 'storebiz_sections', 'burger_storebiz_feature_product', absint( $section_priority ) );
}
