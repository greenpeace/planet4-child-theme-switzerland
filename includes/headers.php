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


/**
 * Allow Stripe and Google Pay in Planet4 permissions policy header.
 *
 * @param string $policy_header Current permissions policy header value.
 *
 * @return string
 */
function gpch_permissions_policy_header( $policy_header ) {
	$payment_policy = 'payment=(self "https://tamaro.raisenow.com" "https://pay.google.com" "https://*.stripe.com")';

	if ( false !== strpos( $policy_header, 'payment=' ) ) {
		return preg_replace( '/payment=\([^)]*\)/', $payment_policy, $policy_header );
	}

	return rtrim( $policy_header, ',' ) . ',' . $payment_policy;
}

add_filter( 'planet4_permissions_policy_header', 'gpch_permissions_policy_header' );
