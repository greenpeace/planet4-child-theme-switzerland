<?php

/**
 * Save the email address in a Gravity Form to the session after submission.
 *
 * @param $entry
 * @param $form
 */
function gpch_save_gf_user_email( $entry, $form ) {
	foreach ( $form['fields'] as $field ) {
		if ( $field->type == 'email' ) {
			$email = rgar( $entry, (string) $field->id );

			if ( session_status() == PHP_SESSION_NONE ) {
				session_start();
			}

			$_SESSION['gf_connect_forms_email']        = $email;
			$_SESSION['gf_connect_forms_email_expire'] = time() + 1800;

			// In case the form contains multiple email fields, only use the first one
			break;
		}
	}
}

add_action( 'gform_after_submission', 'gpch_save_gf_user_email', 10, 2 );


/**
 * Prefill a Gravity Form field with the users email address if it's available in the session.
 * Used to connect to separate forms together.
 * The form needs:
 * - A field called "form_connect_email"
 * - The field "form_connect_email" needs to have the setting "Allow field to be populated dynamically" activated
 *
 * @return string
 */
function gpch_prefill_gf_user_email() {
	if ( session_status() == PHP_SESSION_NONE ) {
		session_start();
	}

	if ( isset( $_SESSION['gf_connect_forms_email_expire'] ) && $_SESSION['gf_connect_forms_email_expire'] >= time() ) {
		return $_SESSION['gf_connect_forms_email'];
	} else {
		return '';
	}
}

add_filter( 'gform_field_value_form_connect_email', 'gpch_prefill_gf_user_email' );
