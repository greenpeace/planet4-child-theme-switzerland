<?php
add_filter( 'gfpdf_template_location', function ( $directory, $working_folder, $upload_path ) {
	/* Make sure you include the forward slash! */
	return get_stylesheet_directory() . '/plugins/' . $working_folder . '/';
}, 10, 3 );

/**
 * You must use the `gfpdf_template_location` filter in conjunction with the `gfpdf_template_location_uri` filter
 * Both filters should point to the same directory (one is the path and one is the URL)
 */
add_filter( 'gfpdf_template_location_uri', function ( $url, $working_folder, $upload_url ) {
	/* Make sure you include the forward slash! */
	return get_stylesheet_directory_uri() . '/plugins/' . $working_folder . '/';
}, 10, 3 );

