<?php
$theme = wp_get_theme(); // gets the current theme
if( 'Crowl' == $theme->name){
	$file 		= BURGER_COMPANION_PLUGIN_URL .'inc/crowl/images/logo_1.png';
	$ImagePath  = BURGER_COMPANION_PLUGIN_URL .'inc/crowl/images';	
}else{
	$file 		= BURGER_COMPANION_PLUGIN_URL .'inc/owlpress/images/logo_1.png';
	$ImagePath  = BURGER_COMPANION_PLUGIN_URL .'inc/owlpress/images';	
}
$images = array(
$ImagePath. '/logo_1.png',
);
$parent_post_id = null;
foreach($images as $name) {
$filename = basename($name);
$upload_file = wp_upload_bits($filename, null, file_get_contents($name));
if (!$upload_file['error']) {
	$wp_filetype = wp_check_filetype($filename, null );
	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_parent' => $parent_post_id,
		'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
		'post_excerpt' => 'owlpress caption',
		'post_status' => 'inherit'
	);
	$ImageId[] = $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent_post_id );
	
	if (!is_wp_error($attachment_id)) {
		require_once(ABSPATH . "wp-admin" . '/includes/image.php');
		$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
		wp_update_attachment_metadata( $attachment_id,  $attachment_data );
	}
}

}

 update_option( 'owlpress_media_id', $ImageId );
