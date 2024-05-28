<?php

/**
 * Finds the first email address in a form entry and returns the gp_user_id
 *
 * @param $form
 * @param $entry
 *
 * @return null
 */
function gpch_generate_user_id_from_form_submission( $form, $entry ) {
	foreach ( $form["fields"] as $i => $field ) {
		if ( get_class( $field ) == "GF_Field_Email" ) {
			$email = $entry[ $field["id"] ];

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

	$child_options = get_option( 'gpch_child_options' );

	if ( array_key_exists( 'gpch_child_field_gp_user_id_salt', $child_options ) && ! empty( $child_options['gpch_child_field_gp_user_id_salt'] ) ) {
		$salt = $child_options['gpch_child_field_gp_user_id_salt'];

		return hash( 'sha256', $email . $salt );
	} else {
		return null;
	}
}
