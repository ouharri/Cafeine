<?php

function classic_coffee_shop_custom_styles($custom) {
	//Fonts
	$classic_coffee_shop_headings_font = esc_html(get_theme_mod('classic_coffee_shop_headings_fonts'));
	$classic_coffee_shop_body_font = esc_html(get_theme_mod('classic_coffee_shop_body_fonts'));

	if ( $classic_coffee_shop_headings_font ) {
		$font_pieces = explode(":", $classic_coffee_shop_headings_font);
		$custom .= "h1, h2, h3, h4, h5, h6 { font-family: {$font_pieces[0]}; }"."\n";
	}

	if ( $classic_coffee_shop_body_font ) {
		$font_pieces = explode(":", $classic_coffee_shop_body_font);
		$custom .= "body, button, input, select, textarea { font-family: {$font_pieces[0]}; }"."\n";
	}
	//Output all the styles
	wp_add_inline_style( 'classic-coffee-shop-basic-style', $custom );
}
add_action( 'wp_enqueue_scripts', 'classic_coffee_shop_custom_styles' );

//Sanitizes Fonts
function classic_coffee_shop_sanitize_fonts( $input ) {
	$valid = array(
		'Arvo:400,700,400italic,700italic' => 'Arvo',
		'Abril Fatface' => 'Abril Fatface',
		'Acme' => 'Acme',
		'Anton' => 'Anton',
		'Arimo:400,700,400italic,700italic' => 'Arimo',
		'Architects Daughter' => 'Architects Daughter',
		'Arsenal' => 'Arsenal',
		'Alegreya' => 'Alegreya',
		'Alfa Slab One' => 'Alfa Slab One',
		'Averia Serif Libre' => 'Averia Serif Libre',
		'Bitter:400,700,400italic' => 'Bitter',
		'Bangers' => 'Bangers',
		'Boogaloo' => 'Boogaloo',
		'Bad Script' => 'Bad Script',
		'Bree Serif' => 'Bree Serif',
		'BenchNine' => 'BenchNine',
		'Cabin:400,700,400italic' => 'Cabin',
		'Cardo' => 'Cardo',
		'Courgette' => 'Courgette',
		'Cherry Swash' => 'Cherry Swash',
		'Cormorant Garamond' => 'Cormorant Garamond',
		'Crimson Text' => 'Crimson Text',
		'Cuprum' => 'Cuprum',
		'Cookie' => 'Cookie',
		'Chewy' => 'Chewy',
		'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
		'Droid Sans:400,700' => 'Droid Sans',
		'Days One' => 'Days One',
		'Dosis' => 'Dosis',
		'Emilys Candy:' => 'Emilys Candy',
		'Economica' => 'Economica',
		'Fjalla One:400' => 'Fjalla One',
		'Francois One:400' => 'Francois One',
		'Fredoka One' => 'Fredoka One',
		'Frank Ruhl Libre' => 'Frank Ruhl Libre',
		'Gloria Hallelujah' => 'Gloria Hallelujah',
		'Great Vibes' => 'Great Vibes',
		'Josefin Sans:400,300,600,700' => 'Josefin Sans',
		'Libre Baskerville:400,400italic,700' => 'Libre Baskerville',
		'Lora:400,700,400italic,700italic' => 'Lora',
		'Lato:400,700,400italic,700italic' => 'Lato',
		'Merriweather:400,300italic,300,400italic,700,700italic' => 'Merriweather',
		'Montserrat:400,700' => 'Montserrat',
		'Oxygen:400,300,700' => 'Oxygen',
		'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
		'Open Sans:400italic,700italic,400,700' => 'Open Sans',
		'Oswald:400,700' => 'Oswald',
		'PT Serif:400,700' => 'PT Serif',
		'PT Sans:400,700,400italic,700italic' => 'PT Sans',
		'PT Sans Narrow:400,700' => 'PT Sans Narrow',
		'Playfair Display:400,700,400italic' => 'Playfair Display',
		'Poppins:0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900' => 'Poppins',
		'Roboto:400,400italic,700,700italic' => 'Roboto',
		'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
		'Roboto Slab:400,700' => 'Roboto Slab',
		'Rokkitt:400' => 'Rokkitt',
		'Raleway:400,700' => 'Raleway',
		'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',
		'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
		'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}
