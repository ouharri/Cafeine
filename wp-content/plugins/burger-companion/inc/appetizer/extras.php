<?php
/*
 *
 * Social Icon
 */
function appetizer_get_social_icon_default() {
	return apply_filters(
		'appetizer_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-pinterest', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_header_social_005',
				)
			)
		)
	);
}


/*
 *
 * Slider Default
 */
 function appetizer_get_slider_default() {
	return apply_filters(
		'appetizer_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/slider/img01.jpg',
					'title'           => esc_html__( 'Welcome to', 'appetizer' ),
					'subtitle'         => esc_html__( 'Appetizer Cafe & Restaurant', 'appetizer' ),
					'text'            => esc_html__( 'Our aim is to give you a rich experience.', 'appetizer' ),
					'text2'	  =>  esc_html__( 'Book a Table', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'designation'	  =>  esc_html__( '$18', 'appetizer' ),
					"slide_align" => "left", 
					'id'              => 'customizer_repeater_slider_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/slider/img02.jpg',
					'title'           => esc_html__( 'Welcome to', 'appetizer' ),
					'subtitle'         => esc_html__( 'Appetizer Cafe & Restaurant', 'appetizer' ),
					'text'            => esc_html__( 'Our aim is to give you a rich experience.', 'appetizer' ),
					'text2'	  =>  esc_html__( 'Book a Table', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'designation'	  =>  esc_html__( '$18', 'appetizer' ),
					"slide_align" => "center", 
					'id'              => 'customizer_repeater_slider_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/slider/img03.jpg',
					'title'           => esc_html__( 'Welcome to', 'appetizer' ),
					'subtitle'         => esc_html__( 'Appetizer Cafe & Restaurant', 'appetizer' ),
					'text'            => esc_html__( 'Our aim is to give you a rich experience.', 'appetizer' ),
					'text2'	  =>  esc_html__( 'Book a Table', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'designation'	  =>  esc_html__( '$18', 'appetizer' ),
					"slide_align" => "right", 
					'id'              => 'customizer_repeater_slider_003',
				)
			)
		)
	);
}


/*
 *
 * Service Default
 */
 function appetizer_get_service_default() {
	return apply_filters(
		'appetizer_get_service_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/services/service01.jpg',
					'title'           => esc_html__( 'Breakfast', 'appetizer' ),
					'subtitle'            => esc_html__( 'Sale', 'appetizer' ),
					'text2'	  =>  esc_html__( 'Read More', 'appetizer' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/services/service02.jpg',
					'title'           => esc_html__( 'Lunch', 'appetizer' ),
					'subtitle'            => esc_html__( 'Sale', 'appetizer' ),
					'text2'	  =>  esc_html__( 'Read More', 'appetizer' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_002',			
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/services/service03.jpg',
					'title'           => esc_html__( 'Brunch', 'appetizer' ),
					'subtitle'            => esc_html__( 'Sale', 'appetizer' ),
					'text2'	  =>  esc_html__( 'Read More', 'appetizer' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_003',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/services/service04.jpg',
					'title'           => esc_html__( 'Dinner', 'appetizer' ),
					'subtitle'            => esc_html__( 'Sale', 'appetizer' ),
					'text2'	  =>  esc_html__( 'Read More', 'appetizer' ),
					'link'	  =>  '#',
					'id'              => 'customizer_repeater_service_004',
				)
			)
		)
	);
}

/*
 *
 * Footer Info Default
 */
 function appetizer_get_footer_info_default() {
	return apply_filters(
		'appetizer_get_footer_info_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/icon_gif/footer-above-info/avatar-calm-approved.gif',
					'title'           => esc_html__( '24/7 Friendly Support', 'appetizer' ),
					'text'            => esc_html__( 'Our Support Team Always for You To 7 Days a Week', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_above_info_001',					
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/icon_gif/footer-above-info/truck-delivery.gif',
					'title'           => esc_html__( 'Free Shipping', 'appetizer' ),
					'text'            => esc_html__( 'Mon to Sat: 10 Am - 6 Pm', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_above_info_002',			
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/icon_gif/footer-above-info/gift.gif',
					'title'           => esc_html__( '7 Days Easy Return', 'appetizer' ),
					'text'            => esc_html__( 'Product Any Fault Within 7  Days For An Exchange', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_above_info_003',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/appetizer/images/icon_gif/footer-above-info/shield-security.gif',
					'title'           => esc_html__( 'Quality Guaranteed', 'appetizer' ),
					'text'            => esc_html__( 'If Your Product Arent Perfect  Return For a Full Refund', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_above_info_004',
				),
			)
		)
	);
}





/*
 *
 * Payment Icon
 */
function appetizer_get_payment_icon_default() {
	return apply_filters(
		'appetizer_get_payment_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-visa', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_payment_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-paypal', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_payment_002',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-mastercard', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_payment_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-cc-discover', 'appetizer' ),
					'link'	  =>  esc_html__( '#', 'appetizer' ),
					'id'              => 'customizer_repeater_footer_payment_004',
				),
			)
		)
	);
}



if ( ! function_exists( 'appetizer_footer_payment_icon' ) ) :
	function appetizer_footer_payment_icon() {
	$hs_footer_payment	    = get_theme_mod('hs_footer_payment','1');
	$footer_payment_icons   = get_theme_mod('footer_payment_icons',appetizer_get_payment_icon_default());
	if($hs_footer_payment=='1'){ ?>
	<ul class="payment_methods">
		<?php
			$footer_payment_icons = json_decode($footer_payment_icons);
			if( $footer_payment_icons!='' )
			{
			foreach($footer_payment_icons as $payment_item){	
			$social_icon = ! empty( $payment_item->icon_value ) ? apply_filters( 'appetizer_translate_single_string', $payment_item->icon_value, 'Footer section' ) : '';	
			$social_link = ! empty( $payment_item->link ) ? apply_filters( 'appetizer_translate_single_string', $payment_item->link, 'Footer section' ) : '';
		?>
			<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
		<?php }} ?>
	</ul>
<?php }}
add_action( 'appetizer_footer_payment_icon', 'appetizer_footer_payment_icon');
endif;




if ( ! function_exists( 'appetizer_header_opening_hour' ) ) {
	function appetizer_header_opening_hour() {
		$abv_hdr_opening_icon		=	get_theme_mod('abv_hdr_opening_icon','fa-clock-o');
		$abv_hdr_opening_ttl		=	get_theme_mod('abv_hdr_opening_ttl','Opening Hour');
		$abv_hdr_opening_content	=	get_theme_mod('abv_hdr_opening_content','Mon to Sat: 10 Am - 6 Pm');
		?>
		<aside class="widget widget-contact first">
			<div class="contact-area">
				<div class="contact-icon">
					<div class="contact-corn"><i class="fa <?php echo esc_attr( $abv_hdr_opening_icon ); ?>"></i></div>
				</div>
				<div class="contact-info">
					<h6 class="title"><?php echo wp_kses_post( $abv_hdr_opening_ttl ); ?></h6>
					<p class="text"><a href="javascript:void(0);"><?php echo wp_kses_post( $abv_hdr_opening_content ); ?></a></p>
				</div>
			</div>
		</aside>
		<?php
	}
}


if ( ! function_exists( 'appetizer_header_support' ) ) {
	function appetizer_header_support() {
		$hdr_support_icon =	get_theme_mod('hdr_support_icon','fa-phone');
		$hdr_support_ttl  =	get_theme_mod('hdr_support_ttl','Customer Support');
		$hdr_support_text =	get_theme_mod('hdr_support_text','<a href="tel:70 975 975 70">70 975 975 70</a>');
		?>
		<aside class="widget widget-contact second">
			<div class="contact-area">
				<div class="contact-icon">
					<div class="contact-corn"><i class="fa <?php echo esc_attr( $hdr_support_icon ); ?>"></i></div>
				</div>
				<div class="contact-info">
					<h6 class="title"><?php echo wp_kses_post( $hdr_support_ttl ); ?></h6>
					<p class="text"><?php echo wp_kses_post( $hdr_support_text ); ?></p>
				</div>
			</div>
		</aside>
		<?php
	}
}