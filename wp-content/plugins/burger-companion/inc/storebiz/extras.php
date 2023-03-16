<?php
if ( ! function_exists( 'storebiz_header_offer' ) ) {
	function storebiz_header_offer() {
		$hide_show_offer 		=	get_theme_mod('hide_show_offer','1');
		$hdr_nav_offer_content  =	get_theme_mod('hdr_nav_offer_content',storebiz_get_nav_offer_default());
		if($hide_show_offer =='1'){
	?>
		<div class="bn-breaking-news" id="newsOffer1">
			<div class="bn-label" style="display: none;"></div>
			<div class="bn-news">
				<ul>
				   <?php
					if ( ! empty( $hdr_nav_offer_content ) ) {
					$hdr_nav_offer_content = json_decode( $hdr_nav_offer_content );
					foreach ( $hdr_nav_offer_content as $offer_item ) {
						$icon = ! empty( $offer_item->icon_value ) ? apply_filters( 'storebiz_translate_single_string', $offer_item->icon_value, 'Offer section' ) : '';
						$title = ! empty( $offer_item->title ) ? apply_filters( 'storebiz_translate_single_string', $offer_item->title, 'Offer section' ) : '';
						$link = ! empty( $offer_item->link ) ? apply_filters( 'storebiz_translate_single_string', $offer_item->link, 'Offer section' ) : '';
							
					?>
					<li><i class="fa <?php echo esc_attr($icon); ?>"></i> <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></li>
					<?php } } ?>
				</ul>
			</div>
		</div>
	<?php } 
	}
}
add_filter( 'storebiz_header_offer', 'storebiz_header_offer' );

/**
 * Product Categories
 */
function storebiz_product_cat( ) {
		if ( class_exists( 'woocommerce' ) ) {
		$args                   = array(
			'post_type' => 'product',
		);
		/* Exclude hidden products from the loop */
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'exclude-from-catalog',
				'operator' => 'NOT IN',

			),
		);
		?>
		<nav class="owl-filter-bar">
			<?php 	
				$product_categories = get_terms( 'product_cat', $args );
				$count = count($product_categories);
				if ( $count > 0 ){
					foreach ( $product_categories as $product_category ) {
						?>
						<?php if($product_category->name == 'All'){ ?>
							<a href="javascript:void(0);" class="item current" data-owl-filter="<?php echo $product_category->slug; ?>"><?php  echo $product_category->name; ?></a>
						<?php }else{ ?>	
							<a href="javascript:void(0);" class="item" data-owl-filter="<?php echo '.'.$product_category->slug; ?>"><?php  echo $product_category->name; ?></a>
						<?php
						}
					}
				}
			?>
		</nav>
	<?php  } 
}
/**
 * Call a shortcode function by tag name.
 *
 * @since  1.0
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function storebiz_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}


if ( ! function_exists( 'storebiz_recent_products' ) ) {
	/**
	 * Display Recent Products
	 *
	 * @since  1.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function storebiz_recent_products( $args ) {
		$latest_product_title		= get_theme_mod('latest_product_title','Latest Product');
		$args = apply_filters(
			'storebiz_recent_products_args',
			array(
				'limit'   => 4,
				'columns' => 4,
				'orderby' => 'date',
				'order'   => 'desc',
				'title'   => $latest_product_title,
			)
		);

		$shortcode_content = storebiz_do_shortcode(
			'products',
			apply_filters(
				'storebiz_recent_products_shortcode_args',
				array(
					'orderby'  => esc_attr( $args['orderby'] ),
					'order'    => esc_attr( $args['order'] ),
					'per_page' => intval( $args['limit'] ),
					'columns'  => intval( $args['columns'] ),
				)
			)
		);

		/**
		 * Only display the section if the shortcode returns products
		 */
		if ( false !== strpos( $shortcode_content, 'product' ) ) {
			echo '<section class="storebiz-product-section storebiz-recent-products recent-products-carousel st-py-default" aria-label="' . esc_attr__( 'Recent Products', 'storebiz' ) . '"><div class="container">';

			do_action( 'storebiz_homepage_before_recent_products' );

			echo '<div class="row"><div class="col-lg-12 col-12 mx-lg-auto mb-5 text-center">
					<div class="heading-default wow fadeInUp">
						<div class="title">
							<h4>' . wp_kses_post( $args['title'] ) . '</h4>
						</div>
						<div class="heading-right">';
							storebiz_product_cat();
							echo '<div class="recent-product-nav owl-nav">
								<button type="button" role="presentation" class="owl-prev">
									<span aria-label="Previous">‹</span>
								</button>
								<button type="button" role="presentation" class="owl-next">
									<span aria-label="Next">›</span>
								</button>
							</div>
						</div>
					</div>
				</div></div>';

			do_action( 'storebiz_homepage_after_recent_products_title' );

			echo '<div class="row"><div class="col-12">'.$shortcode_content.'</div></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			do_action( 'storebiz_homepage_after_recent_products' );

			echo '</div></section>';
		}
	}
}
add_action( 'latest-product', 'storebiz_recent_products', 30 );



if ( ! function_exists( 'storebiz_featured_products' ) ) {
	/**
	 * Display Featured Products
	 *
	 * @since  1.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function storebiz_featured_products( $args ) {
		$featured_product_title		= get_theme_mod('featured_product_title','Featured Product');
		$args = apply_filters(
			'storebiz_featured_products_args',
			array(
				'limit'      => 4,
				'columns'    => 4,
				'orderby'    => 'date',
				'order'      => 'desc',
				'visibility' => 'featured',
				'title'      =>$featured_product_title,
			)
		);

		$shortcode_content = storebiz_do_shortcode(
			'products',
			apply_filters(
				'storebiz_featured_products_shortcode_args',
				array(
					'per_page'   => intval( $args['limit'] ),
					'columns'    => intval( $args['columns'] ),
					'orderby'    => esc_attr( $args['orderby'] ),
					'order'      => esc_attr( $args['order'] ),
					'visibility' => esc_attr( $args['visibility'] ),
				)
			)
		);

		/**
		 * Only display the section if the shortcode returns products
		 */
		if ( false !== strpos( $shortcode_content, 'product' ) ) {
			echo '<section class="storebiz-product-section storebiz-featured-products featured-products-carousel st-py-default" aria-label="' . esc_attr__( 'Featured Products', 'storebiz' ) . '"><div class="container"><div class="row">';

			do_action( 'storebiz_homepage_before_featured_products' );

			echo '<div class="col-lg-12 col-12 mx-lg-auto mb-5 text-center">
					<div class="heading-default wow fadeInUp">
						<div class="title">
							<h4>' . wp_kses_post( $args['title'] ) . '</h4>
						</div>
						<div class="heading-right">';
							storebiz_product_cat();
							echo '<div class="featured-product-nav owl-nav">
								<button type="button" role="presentation" class="owl-prev">
									<span aria-label="Previous">‹</span>
								</button>
								<button type="button" role="presentation" class="owl-next">
									<span aria-label="Next">›</span>
								</button>
							</div>
						</div>
					</div>
				</div>';

			do_action( 'storebiz_homepage_after_featured_products_title' );

			echo $shortcode_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			do_action( 'storebiz_homepage_after_featured_products' );

			echo '</div></div></section>';
		}
	}
}
add_action( 'featured-product', 'storebiz_featured_products', 40 );

/*
 *
 * Offer Default
 */
 function storebiz_get_nav_offer_default() {
	return apply_filters(
		'storebiz_get_nav_offer_default', json_encode(
				 array(
				array(
					'icon_value'           => 'fa-gift',
					'title'           => esc_html__( 'Big Offer Zone', 'storebiz' ),
					'id'              => 'customizer_repeater_nav_offer_001',
				),
			)
		)
	);
}


/*
 *
 * Slider Default
 */
 function storebiz_get_slider_default() {
	return apply_filters(
		'storebiz_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/slider/img01.jpg',
					'title'           => esc_html__( 'SALE UP TO 30% OFF', 'storebiz' ),
					'subtitle'         => esc_html__( 'Music', 'storebiz' ),
					'text'         => esc_html__( 'Addicted', 'storebiz' ),
					'text2'	  =>  esc_html__( 'Shop Now', 'storebiz' ),
					"slide_align" => "left", 
					'id'              => 'customizer_repeater_slider_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/slider/img02.jpg',
					'title'           => esc_html__( 'SALE UP TO 30% OFF', 'storebiz' ),
					'subtitle'         => esc_html__( 'Music', 'storebiz' ),
					'text'         => esc_html__( 'Addicted', 'storebiz' ),
					'text2'	  =>  esc_html__( 'Shop Now', 'storebiz' ),
					"slide_align" => "center", 
					'id'              => 'customizer_repeater_slider_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/slider/img03.jpg',
					'title'           => esc_html__( 'SALE UP TO 30% OFF', 'storebiz' ),
					'subtitle'         => esc_html__( 'Music', 'storebiz' ),
					'text'         => esc_html__( 'Addicted', 'storebiz' ),
					'text2'	  =>  esc_html__( 'Shop Now', 'storebiz' ),
					"slide_align" => "right", 
					'id'              => 'customizer_repeater_slider_003',
				),
			)
		)
	);
}


/*
 *
 * Slider Info Default
 */
 function storebiz_get_slider_info_default() {
	return apply_filters(
		'storebiz_get_slider_info_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/slider-info/info1.jpg',
					'title'           => esc_html__( 'Fashion', 'storebiz' ),
					'subtitle'        => esc_html__( 'Style', 'storebiz' ),
					'text'            => esc_html__( 'Start from $99', 'storebiz' ),
					'text2'	  		  =>  esc_html__( 'Buy Now', 'storebiz' ),
					'id'              => 'customizer_repeater_slider_info_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/slider-info/info2.jpg',
					'title'           => esc_html__( 'Amazing', 'storebiz' ),
					'subtitle'        => esc_html__( 'Fashion', 'storebiz' ),
					'text'            => esc_html__( 'Start from $149', 'storebiz' ),
					'text2'	          =>  esc_html__( 'Buy Now', 'storebiz' ),
					'id'              => 'customizer_repeater_slider_info_002',
				),
			)
		)
	);
}


/*
 *
 * Testimonial Default
 */
 
 function storebiz_get_testimonial_default() {
	return apply_filters(
		'storebiz_get_testimonial_default', json_encode(
			array(
				array(
					'title'           => esc_html__( 'John Smith', 'storebiz' ),
					'subtitle'        => esc_html__( 'Designer', 'storebiz' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'storebiz' ),
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/testimonials/img01.png',
					'id'              => 'customizer_repeater_testimonial_001',
				),
				array(
					'title'           => esc_html__( 'Romies Ames', 'storebiz' ),
					'subtitle'        => esc_html__( 'Founder', 'storebiz' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'storebiz' ),
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/testimonials/img02.png',
					'id'              => 'customizer_repeater_testimonial_002',
				),
				array(
					'title'           => esc_html__( 'Jessica Sunio', 'storebiz' ),
					'subtitle'        => esc_html__( 'Manager', 'storebiz' ),
					'text'            => esc_html__( 'This is Photoshop version  of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin.', 'storebiz' ),
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/storebiz/images/testimonials/img03.png',
					'id'              => 'customizer_repeater_testimonial_003',
				),
		    )
		)
	);
}



/*
 *
 * Info Default
 */
 function storebiz_get_info_default() {
	return apply_filters(
		'storebiz_get_info_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/shopmax/images/info/info1.jpg',
					'title'           => esc_html__( 'Winter', 'storebiz-pro' ),
					'subtitle'        => esc_html__( 'Street', 'storebiz-pro' ),
					'text'            => esc_html__( 'Collection', 'storebiz-pro' ),
					'text2'	  		  =>  esc_html__( 'Shop Now', 'storebiz-pro' ),
					"slide_align" => "left", 
					'id'              => 'customizer_repeater_info_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/shopmax/images/info/info2.jpg',
					'title'           => esc_html__( 'Kidz', 'storebiz-pro' ),
					'subtitle'        => esc_html__( 'Street', 'storebiz-pro' ),
					'text'            => esc_html__( 'Collection', 'storebiz-pro' ),
					'text2'	          =>  esc_html__( 'Shop Now', 'storebiz-pro' ),
					"slide_align" => "center", 
					'id'              => 'customizer_repeater_info_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/shopmax/images/info/info3.jpg',
					'title'           => esc_html__( 'Winter', 'storebiz-pro' ),
					'subtitle'        => esc_html__( 'Street', 'storebiz-pro' ),
					'text'            => esc_html__( 'Collection', 'storebiz-pro' ),
					'text2'	  		  =>  esc_html__( 'Shop Now', 'storebiz-pro' ),
					"slide_align" => "right", 
					'id'              => 'customizer_repeater_info_003',
			
				),
			)
		)
	);
}