<?php

/**
 * Checks if a post has an embed block of a defined provider
 *
 * @param string  $provider Provider name.
 * @param WP_Post $post The current post.
 *
 * @return bool
 */
function gpch_has_block_embed_by_provider( $provider, $post = null ) {
	if ( ! has_blocks( $post ) ) {
		return false;
	}

	if ( ! is_string( $post ) ) {
		$wp_post = get_post( $post );
		if ( $wp_post instanceof WP_Post ) {
			$post = $wp_post->post_content;
		}
	}

	if ( has_block( 'embed', $post ) ) {
		$blocks = parse_blocks( $post );

		$provider_blocks = gpch_search_array_key_value( $blocks, 'providerNameSlug', $provider );

		if ( count( $provider_blocks ) > 0 ) {
			return true;
		}
	}

	return false;
}

/**
 * Searches multidimensional arrays for key/value pairs
 *
 * @param array  $array The array to search.
 * @param string $key The key name to search for.
 * @param string $value The value to search for.
 *
 * @return array
 */
function gpch_search_array_key_value( $array, $key, $value ) {
	$results = array();

	if ( is_array( $array ) ) {
		if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
			$results[] = $array;
		}

		foreach ( $array as $subarray ) {
			$results = array_merge( $results, gpch_search_array_key_value( $subarray, $key, $value ) );
		}
	}

	return $results;
}
