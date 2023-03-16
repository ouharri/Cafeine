<?php
/**
 * Sanitization Functions
 * 
 * @package Blossom_Recipe
*/

function blossom_recipe_sanitize_checkbox( $checked ){
    // Boolean check.
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

function blossom_recipe_sanitize_select( $value ){    
    if ( is_array( $value ) ) {
		foreach ( $value as $key => $subvalue ) {
			$value[ $key ] = sanitize_text_field( $subvalue );
		}
		return $value;
	}
	return sanitize_text_field( $value );    
}

function blossom_recipe_sanitize_number_absint( $number, $setting ) {
    // Ensure $number is an absolute integer (whole number, zero or greater).
    $number = absint( $number );
    
    // If the input is an absolute integer, return it; otherwise, return the default
    return ( $number ? $number : $setting->default );
}

function blossom_recipe_sanitize_number_floatval( $number, $setting ) {
    // Ensure $number is an floatval.
    $number = floatval( $number );                                                          
    // If the input is an absolute integer, return it; otherwise, return the default
    return ( $number ? $number : $setting->default );
}

function blossom_recipe_sanitize_radio( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function blossom_recipe_sanitize_image( $image, $setting ) {
    /*
     * Array of valid image file types.
     *
     * The array includes image mime types that are included in wp_get_mime_types()
     */
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
    // Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
    // If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : '' );
}

function blossom_recipe_sanitize_sortable( $value = array() ) {
	if ( is_string( $value ) || is_numeric( $value ) ) {
		return array(
			sanitize_text_field( $value ),
		);
	}
	$sanitized_value = array();
	foreach ( $value as $sub_value ) {
		$sanitized_value[] = sanitize_text_field( $sub_value );
	}
	return $sanitized_value;
}

function blossom_recipe_sanitize_code( $value ){
    return htmlspecialchars_decode( stripslashes( $value ) );
}