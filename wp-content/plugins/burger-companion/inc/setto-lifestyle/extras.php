<?php
/*
 *
 * Slider 5 Default
 */
 function setto_get_slider5_default() {
	return apply_filters(
		'setto_get_slider5_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/setto-lifestyle/images/slider/slider-01.png',
					'title'           => esc_html__( 'Man collection', 'setto' ),
					'subtitle'         => esc_html__( 'Relaxed', 'setto' ),
					'subtitle2'         => esc_html__( 'fashion', 'setto' ),
					'text2'	  =>  esc_html__( 'Shop Now', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_slider5_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/setto-lifestyle/images/slider/slider-03.jpg',
					'title'           => esc_html__( 'Man collection', 'setto' ),
					'subtitle'         => esc_html__( 'Relaxed', 'setto' ),
					'subtitle2'         => esc_html__( 'fashion', 'setto' ),
					'text2'	  =>  esc_html__( 'Shop Now', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_slider5_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/setto-lifestyle/images/slider/slider-02.jpg',
					'title'           => esc_html__( 'Man collection', 'setto' ),
					'subtitle'         => esc_html__( 'Relaxed', 'setto' ),
					'subtitle2'         => esc_html__( 'fashion', 'setto' ),
					'text2'	  =>  esc_html__( 'Shop Now', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_slider5_001',
				),
			)
		)
	);
}