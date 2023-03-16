<?php

/**
 * Setto Product Category
 */
 add_action('product_cat_add_form_fields', 'setto_product_taxonomy_add_new_meta_field', 10, 1);
add_action('product_cat_edit_form_fields', 'setto_product_taxonomy_edit_meta_field', 10, 1);
//Product Cat Create page
function setto_product_taxonomy_add_new_meta_field() {
    ?>   
    <div class="form-field">
        <label for="setto_product_cat_icon"><?php _e('Icon', 'setto'); ?></label>
        <input type="text" name="setto_product_cat_icon" id="setto_product_cat_icon">
        <p class="description"><?php _e('Enter Icon Name', 'setto'); ?></p>
    </div>
    <?php
}
//Product Cat Edit page
function setto_product_taxonomy_edit_meta_field($term) {
    //getting term ID
    $term_id = $term->term_id;
    // retrieve the existing value(s) for this meta field.
    $setto_product_cat_icon = get_term_meta($term_id, 'setto_product_cat_icon', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="setto_product_cat_icon"><?php _e('Icon', 'setto'); ?></label></th>
        <td>
            <input type="text" name="setto_product_cat_icon" id="setto_product_cat_icon" value="<?php echo esc_attr($setto_product_cat_icon) ? esc_attr($setto_product_cat_icon) : ''; ?>">
        </td>
    </tr>
    <?php
}


add_action('edited_product_cat', 'setto_save_taxonomy_product_meta', 10, 1);
add_action('create_product_cat', 'setto_save_taxonomy_product_meta', 10, 1);
// Save extra taxonomy fields callback function.
function setto_save_taxonomy_product_meta($term_id) {
    $setto_product_cat_icon = filter_input(INPUT_POST, 'setto_product_cat_icon');
    update_term_meta($term_id, 'setto_product_cat_icon', $setto_product_cat_icon);
}


//Displaying Additional Columns
add_filter( 'manage_edit-product_cat_columns', 'setto_productFieldsListTitle' ); //Register Function
add_action( 'manage_product_cat_custom_column', 'setto_productFieldsListDisplay' , 10, 3); //Populating the Columns
/**
 * Meta Title and Description column added to category admin screen.
 *
 * @param mixed $columns
 * @return array
 */
function setto_productFieldsListTitle( $columns ) {
    $columns['setto_product_cat_icon'] = __( 'Icon', 'woocommerce' );
    return $columns;
}
/**
 * Meta Title and Description column value added to product category admin screen.
 *
 * @param string $columns
 * @param string $column
 * @param int $id term ID
 *
 * @return string
 */
function setto_productFieldsListDisplay( $columns, $column, $id ) {
    if ( 'setto_product_cat_icon' == $column ) {
        $columns = esc_html( get_term_meta($id, 'setto_product_cat_icon', true) );
    }
    return $columns;
}



/*
 *
 * Social Icon
 */
function setto_get_social_icon_default() {
	return apply_filters(
		'setto_get_social_icon_default', json_encode(
				 array(
				array(
					'icon_value'	  =>  esc_html__( 'fa-facebook', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_header_social_001',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-twitter', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_header_social_003',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-instagram', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_header_social_004',
				),
				array(
					'icon_value'	  =>  esc_html__( 'fa-pinterest', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
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
 function setto_get_slider_default() {
	return apply_filters(
		'setto_get_slider_default', json_encode(
				 array(
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/setto/images/slider/slider-1.jpg',
					'title'           => esc_html__( 'Digital', 'setto' ),
					'subtitle'         => esc_html__( 'camera', 'setto' ),
					'designation'         => esc_html__( 'DSLR start from', 'setto' ),
					'text'            => esc_html__( '$999', 'setto' ),
					'text2'	  =>  esc_html__( 'Explore now', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_slider_001',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/setto/images/slider/slider-2.jpg',
					'title'           => esc_html__( 'Choose', 'setto' ),
					'subtitle'         => esc_html__( 'freedom', 'setto' ),
					'designation'         => esc_html__( 'Desktop start from', 'setto' ),
					'text'            => esc_html__( '$899', 'setto' ),
					'text2'	  =>  esc_html__( 'Explore now', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_slider_002',
				),
				array(
					'image_url'       => BURGER_COMPANION_PLUGIN_URL . 'inc/setto/images/slider/slider-3.jpg',
					'title'           => esc_html__( 'Feel the', 'setto' ),
					'subtitle'         => esc_html__( 'music', 'setto' ),
					'designation'         => esc_html__( 'Start from', 'setto' ),
					'text'            => esc_html__( '$149', 'setto' ),
					'text2'	  =>  esc_html__( 'Shop now', 'setto' ),
					'link'	  =>  esc_html__( '#', 'setto' ),
					'id'              => 'customizer_repeater_slider_003',
			
				),
			)
		)
	);
}


/*
 *
 * Footer Bottom Contact Default
 */
 function setto_get_footer_bottom_contact_default() {
	return apply_filters(
		'setto_get_footer_bottom_contact_default', json_encode(
				 array(
				array(
					'icon_value'       => 'fa-volume-control-phone',
					'title'           => esc_html__( '(+91)0123456789', 'setto' ),
					'link'            => 'tell:0123456789',
					'id'              => 'customizer_repeater_footer_bottom_contact_001'
				),
				array(
					'icon_value'       => 'fa-envelope-o',
					'title'           => esc_html__( 'email@example.com', 'setto' ),
					'link'            => 'mailto:email@example.com',
					'id'              => 'customizer_repeater_footer_bottom_contact_002'
				),
				array(
					'icon_value'       => 'fa-map-pin',
					'title'           => esc_html__( '201,Platinum plaza.', 'setto' ),
					'id'              => 'customizer_repeater_footer_bottom_contact_003'
				)
			)
		)
	);
}