<?php

spl_autoload_register( 'weglot_autoload' );

/**
 * Weglot autoload class PHP with class-name.php
 * @since 2.0
 * @param string $class_name
 * @return void
 */
function weglot_autoload( $class_name ) {
	$dir_class  = __DIR__ . '/src/';
	$prefix     = 'class-';
	$file_parts = explode( '\\', $class_name );

	$total_parts = count( $file_parts ) - 1;
	$dir_file    = $dir_class;
	for ( $i = 1; $i <= $total_parts; $i++ ) {
		if ( $total_parts !== $i ) {
			$dir_file .= strtolower( $file_parts[ $i ] ) . '/';
		} else {
			$string    = str_replace( '_', '-', strtolower( $file_parts[ $i ] ) );
			$file_load = $dir_file . $prefix . $string . '.php';

			if ( file_exists( $file_load ) ) {
				include_once $file_load;
			}
		}
	}
}
