<?php
// This file is obsolete and will be removed.
// phpcs:ignoreFile

/**
 * Finds the first email address in a form entry and returns the gp_user_id
 *
 * @param $form
 * @param $entry
 *
 * @return null
 */
function gpch_generate_user_id_from_form_submission( $form, $entry ) {
	foreach ( $form['fields'] as $i => $field ) {
		if ( get_class( $field ) == 'GF_Field_Email' ) {
			$email = $entry[ $field['id'] ];

			return gpch_generate_user_id( $email );
		}
	}

	return null;
}

/**
 * Generates gp_user_id as a salted hash from the email address.
 *
 * @param $email
 *
 * @return string|null
 */
function gpch_generate_user_id( $email ) {
	// Hash and base64 encode the email using SHA-256
	$hashed_email = base64_encode( hash( 'sha256', $email, true ) );

	// Remove '/' characters if present in Base64 encoding
	$hashed_email = str_replace( '/', '', $hashed_email );

	return hash( 'sha256', $hashed_email );
}
