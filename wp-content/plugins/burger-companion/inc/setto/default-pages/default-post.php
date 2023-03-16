<?php
$MediaId = get_option('setto_media_id');
$content='<p>Lorem Ipsum is simply.the printing.</p>';
  
if ( class_exists( 'woocommerce' ) ) { 

	wp_insert_term(
		'Trending',
		'product_cat',
		array(
		  'slug'    => 'trending'
		)
	); 


	wp_insert_term(
		'Clothes',
		'product_cat',
		array(
		  'slug'    => 'clothes'
		)
	); 

}
$postData = array(
				array(
					'post_title' => 'Where Can I Get Some?',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'post',
					'post_category' => array(1,16),
					'tax_input'    => array(
						'post_tag' => array('Lifestyle')
					),
				),
				array(
					'post_title' => 'Who Avoids A Pain That Produces?',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'post',
					'post_category' => array(1,16,17),
					'tax_input'    => array(
						'post_tag' => array('Fashion')
					),
				),
				array(
					'post_title' => 'Why Do We Use It?',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'post',
					'post_category' => array(1,16,18),
					'tax_input'    => array(
						'post_tag' => array('Designer')
					),
				),
				array(
					'post_title' => 'T-SHIRT FOR WOMEN',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				),
				array(
					'post_title' => 'LEATHER LOAFER',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				),
				array(
					'post_title' => 'FASHION SHOES',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				),
				array(
					'post_title' => 'EBOMB JACKET',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				),
				array(
					'post_title' => 'BEGS',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				),
				array(
					'post_title' => 'STRETCHABLE SHIRTS',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				),
				array(
					'post_title' => 'WOMEN PURSE',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				),
				array(
					'post_title' => 'DIGITAL WATCH',
					'post_status' => 'publish',
					'post_content' => $content,
					'post_author' => 1,
					'post_type'         =>   'product'
				)
			);

kses_remove_filters();
foreach ( $postData as $i => $postData1) : 
	$id = wp_insert_post($postData1);
	set_post_thumbnail( $id, $MediaId[$i + 1] );
	
	if ( class_exists( 'woocommerce' ) ) {
		if($i>2 && $i<=10){
			wp_set_object_terms( $id, 'simple', 'product_type' ); // set product is simple/variable/grouped
			update_post_meta( $id, '_visibility', 'visible' );
			update_post_meta( $id, '_stock_status', 'instock');
			update_post_meta( $id, 'total_sales', '0' );
			update_post_meta( $id, '_downloadable', 'no' );
			update_post_meta( $id, '_virtual', 'yes' );
			update_post_meta( $id, '_regular_price', '' );
			update_post_meta( $id, '_sale_price', '' );
			update_post_meta( $id, '_purchase_note', '' );
			update_post_meta( $id, '_featured', 'no' );
			update_post_meta( $id, '_weight', '50' );
			update_post_meta( $id, '_length', '50' );
			update_post_meta( $id, '_width', '50' );
			update_post_meta( $id, '_height', '50' );
			update_post_meta( $id, '_sku', 'SKU11' );
			update_post_meta( $id, '_product_attributes', array() );
			update_post_meta( $id, '_sale_price_dates_from', '' );
			update_post_meta( $id, '_sale_price_dates_to', '' );
			update_post_meta( $id, '_price', '50' );
			update_post_meta( $id, '_sold_individually', '' );
			update_post_meta( $id, '_manage_stock', 'yes' ); // activate stock management
			wc_update_product_stock($id, 100, 'set'); // set 1000 in stock
			update_post_meta( $id, '_backorders', 'no' );
		}
	}
endforeach;

if ( class_exists( 'woocommerce' ) ) {
	wp_set_object_terms( 21, [ 15, 17, 18 ], 'product_cat' );
	wp_set_object_terms( 22, [ 15, 22, 18 ], 'product_cat' );
	wp_set_object_terms( 23, [ 15, 21, 18 ], 'product_cat' );
}
kses_init_filters();