<?php

/**
 * Main function that handles 1-click form submissions
 *
 * @return void
 *
 * @phpcs:disable WordPress.Security.NonceVerification.Recommended
 */
function gpch_1_click_form() {
	if ( isset( $_GET['1ClickForm'], $_GET['formId'], $_GET['formData'] ) ) {
		$form_id             = intval( sanitize_text_field( $_GET['formId'] ) );
		$encrypted_form_data = sanitize_text_field( $_GET['formData'] );

		$result = gpch_decrypt_form_data( $encrypted_form_data );

		if ( $result !== false ) {
			$data = json_decode( $result, true );

			// By convention, we expect at least the keys 'l', 'f', and 'e' in the array (last name, first name, email)
			if ( is_array( $data ) && isset( $data['l'], $data['f'], $data['e'] ) ) {
				$form_data = [
					'first_name' => $data['f'],
					'last_name'  => $data['l'],
					'email'      => $data['e'],
				];

				// Include UTM parameters in data
				if ( isset( $_GET['utm_source'] ) ) {
					$form_data['utm_source'] = sanitize_text_field( $_GET['utm_source'] );
				}
				if ( isset( $_GET['utm_medium'] ) ) {
					$form_data['utm_medium'] = sanitize_text_field( $_GET['utm_medium'] );
				}
				if ( isset( $_GET['utm_campaign'] ) ) {
					$form_data['utm_campaign'] = sanitize_text_field( $_GET['utm_campaign'] );
				}
				if ( isset( $_GET['utm_term'] ) ) {
					$form_data['utm_term'] = sanitize_text_field( $_GET['utm_term'] );
				}
				if ( isset( $_GET['utm_content'] ) ) {
					$form_data['utm_content'] = sanitize_text_field( $_GET['utm_content'] );
				}

				gpch_add_1click_form_entry( $form_id, $form_data );
			}
		}
	}
}

add_action( 'template_redirect', 'gpch_1_click_form' );


/**
 * Decrypts encrypted form data using a private key.
 *
 * @param string $encrypted_form_data The encrypted form data string to be decrypted.
 *
 * @return string|false The decrypted form data as JSON if successful, or false if decryption fails.
 */
function gpch_decrypt_form_data( $encrypted_form_data ) {
	// Get the private key from environment
	if ( defined( 'NRO_FORM_DATA_PRIVATE_KEY' ) && ! empty( NRO_FORM_DATA_PRIVATE_KEY ) ) {
		$private_key = NRO_FORM_DATA_PRIVATE_KEY;
	} else {
		if ( function_exists( '\Sentry\captureMessage' ) ) {
			\Sentry\captureMessage( 'FORM_DATA_PRIVATE_KEY is not set in the environment.' );
		}

		return false;
	}

	// Decrypt the form data
	$data_decoded = gpch_base64url_decode( $encrypted_form_data );

	$form_data_json = null;
	openssl_private_decrypt( $data_decoded, $form_data_json, $private_key );

	if ( $form_data_json === null ) {
		if ( function_exists( '\Sentry\captureMessage' ) ) {
			\Sentry\captureMessage( 'Failed to decrypt formData.' );
		}

		return false;
	}

	return $form_data_json;
}


/**
 * URL safe base64 encode of binary data
 *
 * @param string $data The data to encode.
 *
 * @return string
 */
function gpch_base64url_encode( $data ) {
	/* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode */
	return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
}

/**
 * Decode URL safe base64 data
 *
 * @param string $data The data to decode.
 *
 * @return false|string
 */
function gpch_base64url_decode( $data ) {
	$remainder = strlen( $data ) % 4;
	if ( $remainder ) {
		$data .= str_repeat( '=', 4 - $remainder );
	}

	/* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode */
	return base64_decode( strtr( $data, '-_', '+/' ) );
}

/**
 * Adds a new entry to the specified Gravity Form with data provided.
 *
 * @param int   $form_id The ID of the Gravity Form to which the entry will be added.
 * @param array $data The data to be saved in the form entry. Keys should match the form's admin labels or field labels.
 *
 * @return bool|void Returns false if the form submission is invalid or fails. Otherwise, the function performs the operation without returning a value.
 */
function gpch_add_1click_form_entry( $form_id, $data ) {
	// Add a new entry to the Gravity Form
	if ( class_exists( 'GFAPI' ) ) {
		$form = GFAPI::get_form( $form_id );

		if ( $form !== false ) {
			// Match the data to the field input names using admin labels and labels
			$field_names = [
				'first_name',
				'last_name',
				'email',
				'utm_source',
				'utm_medium',
				'utm_campaign',
				'utm_term',
				'utm_content',
			];

			// Hiddden fields we need to transfer the default values of into the entry
			$hidden_values = [
				'form_type',
				'salesforce_campaign_id',
			];

			$form_entry_values = [];

			foreach ( $form['fields'] as $field ) {
				// Check the field admin label and alternatively the label if it's in the list if values we need to save
				if ( in_array( $field['adminLabel'], $field_names, true ) && array_key_exists( $field['adminLabel'], $data ) ) {
					$form_entry_values[ 'input_' . $field['id'] ] = $data[ $field['adminLabel'] ];
				} elseif ( in_array( $field['label'], $field_names ) && array_key_exists( $field['label'], $data ) ) {
					$form_entry_values[ 'input_' . $field['id'] ] = $data[ $field['label'] ];
				}

				if ( $field['label'] === 'is_1_click' ) {
					// Mark this entry as 1 click submission if the hidden field exists
					$form_entry_values[ 'input_' . $field['id'] ] = 1;
				}

				// Apply default values of hidden fields in the form
				if ( $field['type'] === 'hidden' ) {
					if ( in_array( $field['label'], $hidden_values, true ) ) {
						$form_entry_values[ 'input_' . $field['id'] ] = $field['defaultValue'];
					}
				}
			}

			// Get an instance of the P4\MasterTheme\GravityFormsExtensions class
			$p4_loader = P4\MasterTheme\Loader::get_instance();
			$services  = $p4_loader->get_services();

			$p4_gravity_forms_extensions = $services['P4\MasterTheme\GravityFormsExtensions'];

			// Remove the filter it sets that adds a frontend redirect for GravityForms confirmations.
			$remove = remove_filter(
				'gform_confirmation',
				array(
					$P4_GravityFormsExtensions,
					'p4_gf_custom_confirmation_redirect',
				),
				11
			);

			// Add a filter that disables Turnstile captcha validation, otherwise the captcha will fail form validation
			add_filter(
				'gform_field_validation',
				function ( $result, $value, $form, $field ) {
					if ( $field->type === 'turnstile' ) {
						$result['is_valid'] = true;
						$result['message']  = '';
					}

					return $result;
				},
				10,
				4
			);

			$result = GFAPI::submit_form( $form_id, $form_entry_values );

			if ( is_wp_error( $result ) ) {
				$error_message = $result->get_error_message();

				if ( function_exists( '\Sentry\captureMessage' ) ) {
					\Sentry\captureMessage( $error_message );
				}

				return;
			}

			if ( ! rgar( $result, 'is_valid' ) ) {
				$error_message = 'Submission is invalid.';
				$field_errors  = rgar( $result, 'validation_messages', array() );

				if ( function_exists( '\Sentry\captureMessage' ) ) {
					/* phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r */
					\Sentry\captureMessage( print_r( $field_errors ) );
				}

				return;
			}

			// Redircect to the confirmation page of the form.
			// Code is only executed when the filter in P4 is disabled that replaces the GravityForms redirect with a
			// frontend redirect (see above).
			if ( rgar( $result, 'confirmation_type' ) === 'redirect' ) {
				$redirect_url = rgar( $result, 'confirmation_redirect' );

				if ( wp_safe_redirect( $redirect_url ) ) {
					exit;
				}
			} else {
				$confirmation_message = rgar( $result, 'confirmation_message' );
			}
		}
	}

	return false;
}
