<?php

use P4\MasterTheme\CloudflarePurger;

function gpch_invalidate_cache_on_publish( $post ) {
	// Get the option value containing page IDs
	$options = get_option( 'gpch_child_options' );

	$page_ids_string = $options['gpch_child_cache_pages_ids'];

	// Convert comma-separated string to array and remove empty values
	$page_ids = array_filter( array_map( 'trim', explode( ',', $page_ids_string ) ) );

	// Get URLs for all pages
	$urls = array();

	foreach ( $page_ids as $page_id ) {
		$url = get_permalink( (int) $page_id );
		if ( $url ) {
			$urls[] = $url;
		}
	}

	//echo( print_r( $urls, true ) );

	// Purge object cache
	wp_cache_flush();

	// Purge Redis
	global $nginx_purger;
	//echo( print_r( $urls, true ) );
	//var_dump( $nginx_purger );

	if ( $nginx_purger !== null ) {
		//echo("purging nginx");
		
		foreach ( $page_ids as $page_id ) {
			$nginx_purger->purge_post( $page_id );
		}

		foreach ( $urls as $url ) {
			$nginx_purger->purge_url( $url );
		}
	}
	else {
		//echo "nginx purger is null :(";
	}

	// Purge Cloudflare
	$cf = new CloudflarePurger();

	foreach ( $cf->purge( $urls ) as $i => [$result, $chunk] ) {
		//echo( 'Chunk ' . $i . ': ' . ( $result ? 'ok' : 'failed' ) );

		if ( true === $result ) {
			continue;
		}

		$joined = implode( "\n", $chunk );
		//echo( "Chunk $i failed, one or more of these didn't work out: \n$joined" );
	}

}

/** See https://codex.wordpress.org/Post_Status_Transitions */
add_action( 'draft_to_publish', 'gpch_invalidate_cache_on_publish' );
add_action( 'future_to_publish', 'gpch_invalidate_cache_on_publish' );
add_action( 'private_to_publish', 'gpch_invalidate_cache_on_publish' );



