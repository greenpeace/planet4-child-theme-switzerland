<?php

add_action( 'template_redirect', 'gpch_disable_author_pages' );

/**
 * Show 404 instead of author pages
 */
function gpch_disable_author_pages() {
	global $wp_query;

	if ( is_author() ) {
		$wp_query->set_404();
		status_header( 404 );
	}
}
