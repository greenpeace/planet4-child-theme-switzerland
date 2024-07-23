<?php

/**
 * Set Content Security Policy HTTP headers to allow embedding of gravity forms
 *
 * See GravityForms extensions for separate allowlist functionality
 *
 * @param array $allowlist Current allowlist of CSP entries.
 *
 * @return array
 */
function gpch_cps_allowlist( $allowlist ) {
	global $wp;

	$options = get_option( 'gpch_child_options' );

	$allowed_ancestors = preg_split( '/\r\n|\r|\n/', $options['gpch_child_field_content_embed_allowlist'] );

	return array_merge( $allowlist, $allowed_ancestors );
}

add_filter( 'planet4_csp_allowed_frame_ancestors', 'gpch_cps_allowlist' );
