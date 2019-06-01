<?php
/*Template Name: Magazine Redirects single
*/

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		// Get the magazine URL from post meta
		$meta = get_post_meta( get_the_ID(), 'magazin_url' );

		if ( filter_var( $meta[0], FILTER_VALIDATE_URL ) === false ) {
			// Output 404
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
		} else {
			// Redirect to magazine URL
			header( 'Location: ' . $meta[0] );
		}

	}
} else {
	{
		// Output 404
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
	}
}
