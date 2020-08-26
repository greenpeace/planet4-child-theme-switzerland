<?php

/**
 * Add Swiss phone number validation
 *
 * @param $phone_formats
 *
 * @return mixed
 */
function gpch_add_swiss_phone_format( $phone_formats ) {
	$phone_formats['ch'] = array(
		'label'       => '+41 ## ### ## ## (Telefon Schweiz)',
		'mask'        => '+41 99 999 99 99',
		'regex'       => '/^(\+41)\s(\d{2})\s(\d{3})\s(\d{2})\s(\d{2})$/',
		'instruction' => '+41 xx xxx xx xx',
	);

	$phone_formats['ch-mobile'] = array(
		'label'       => '+41 7# ### ## ## (Mobile Schweiz)',
		'mask'        => '+41 79 999 99 99',
		'regex'       => '/^(\+41\s7)(\d{1})\s(\d{3})\s(\d{2})\s(\d{2})$/',
		'instruction' => '+41 7x xxx xx xx',
	);

	return $phone_formats;
}

add_filter( 'gform_phone_formats', 'gpch_add_swiss_phone_format', 10, 2 );


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
 * Prefill a Gravity Form field with the users email address if it's available in the session (AJAX request)
 * Used to connect to separate forms together.
 * The form needs:
 * - A hidden field with the value "form_connect_email"
 */
function gpch_ajax_form_prefill() {
	$field = $_GET['field'];

	if ( $field == 'session_email' ) {
		if ( session_status() == PHP_SESSION_NONE ) {
			session_start();
		}

		if ( isset( $_SESSION['gf_connect_forms_email_expire'] ) && $_SESSION['gf_connect_forms_email_expire'] >= time() ) {
			$data = array(
				'email' => $_SESSION['gf_connect_forms_email'],
			);

			wp_send_json_success( $data );
		} else {
			wp_send_json_error( 'email not found' );
		}
	}

	wp_send_json_error( 'field not available' );
}

add_filter( 'wp_ajax_nopriv_gpch_gf_prefill_field', 'gpch_ajax_form_prefill' );
add_filter( 'wp_ajax_gpch_gf_prefill_field', 'gpch_ajax_form_prefill' );


/**
 * Put zip code before city in address fields
 */
add_filter( 'gform_address_display_format', 'gpch_gf_address_format', 10, 2 );
function gpch_gf_address_format( $format, $field ) {
	return 'zip_before_city';
}

/**
 * Suppress the redirect in forms to use our own redirect handling (see below)
 */
add_filter( 'gform_suppress_confirmation_redirect', '__return_true' );

/**
 * Redirect using Javascript after form submission instead of sending a header. Makes it possible to send tag manager
 * events before redirecting.
 */
add_filter( 'gform_confirmation', function ( $confirmation, $form, $entry, $ajax ) {
	GFCommon::log_debug( __METHOD__ . '(): running.' );
	if ( isset( $confirmation['redirect'] ) ) {
		$url = esc_url_raw( $confirmation['redirect'] );
		GFCommon::log_debug( __METHOD__ . '(): Redirect to URL: ' . $url );

		$html = sprintf(
			'<p><b>%s</b></p><p>%s <a href="' . $url . '">%s</a> %s</p>',
			__( 'Thank you!', 'planet4-child-theme-switzerland' ),
			__( 'Please', 'planet4-child-theme-switzerland' ),
			__( 'click here', 'planet4-child-theme-switzerland' ),
			__( 'if you aren\'t redirected within a few seconds.', 'planet4-child-theme-switzerland' )
		);

		// Find the newsletter field
		// Convention: use an admin label in the form field settings that contains "newsletter"
		foreach ( $form['fields'] as $field ) {
			if ( $field['type'] == 'checkbox' && strpos( $field['adminLabel'], 'newsletter' ) !== false ) {
				$newsletter_type     = $field['adminLabel'];
				$newsletter_field_id = $field['inputs'][0]['id'];

				// Retrieve newsletter checkbox value
				$newsletter_field       = RGFormsModel::get_field( $form, $newsletter_field_id );
				$newsletter_field_value = is_object( $newsletter_field ) ? $field->get_value_export( $entry ) : '';

				if ( ! empty( $newsletter_field_value ) ) {
					$newsletter_subscription = 1;
				} else {
					$newsletter_subscription = 0;
					$newsletter_type         = '';
				}

			}
		}

		// Get the tag manager data layer ID from master theme settings
		$options = get_option( 'planet4_options' );
		$gtm_id  = $options['google_tag_manager_identifier'];

		$script = '<script type="text/javascript">
			if (window["google_tag_manager"]) {
				window.dataLayer = window.dataLayer || [];
				dataLayer.push({
					"event": "gravityFormSubmission", 
					"formType": "' . $form['gpch_gf_type'] . '",
					"newsletterSubscription": ' . $newsletter_subscription . ',
					"newsletterType": "' . $newsletter_type . '",
					"eventCallback" : function(id) {
						// There might be multiple gtm containers, make sure we only redirect for our main container
						if( id == "' . $gtm_id . '") { 
	                        window.top.location.href = "' . $url . '";
	                    }
	                },
	                "eventTimeout" : 2000
				});
			}
			else {
				/* Redirect latest after two seconds. This is a failsafe in case the request to tag manager is blocked */
				setTimeout(function() {
					window.top.location.href = "' . $url . '";
				}, 2000);
			}
			</script>';

		$confirmation = $html . $script;
	}

	return $confirmation;
}, 10, 4 );


/**
 * Add a setting to gravity Forms to set the type of form
 *
 * @param $settings
 * @param $form
 *
 * @return mixed
 */
function gpch_gf_type_setting( $settings, $form ) {
	$value = rgar( $form, 'gpch_gf_type' );
	if ( empty( $value ) ) {
		$value = 'other';
	}

	$select = '<select name="gpch_gf_type">
            		<option value="other" ' . ( $value == 'other' ? 'selected="selected"' : '' ) . '>' . __( 'Other', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="petition" ' . ( $value == 'petition' ? 'selected' : '' ) . '>' . __( 'Petition', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="event" ' . ( $value == 'event' ? 'selected' : '' ) . '>' . __( 'Event', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="contest" ' . ( $value == 'contest' ? 'selected' : '' ) . '>' . __( 'Contest', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="quiz" ' . ( $value == 'quiz' ? 'selected' : '' ) . '>' . __( 'Quiz', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="volunteers" ' . ( $value == 'volunteers' ? 'selected' : '' ) . '>' . __( 'Volunteers', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="testament" ' . ( $value == 'testament' ? 'selected' : '' ) . '>' . __( 'Testament', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="order" ' . ( $value == 'order' ? 'selected' : '' ) . '>' . __( 'Order', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="leadgen" ' . ( $value == 'leadgen' ? 'selected' : '' ) . '>' . __( 'Leadgen', 'planet4-child-theme-switzerland' ) . '</option>
				</select>';

	$settings[ __( 'Form Basics', 'gravityforms' ) ]['gpch_form_type'] = '
        <tr>
            <th><label for="gpch_gf_type">' . __( 'GPCH Form Type', 'planet4-child-theme-switzerland' ) . '</label></th>
            <td>' . $select . '</td>
		</tr>';

	return $settings;
}

add_filter( 'gform_form_settings', 'gpch_gf_type_setting', 10, 2 );


/**
 * Save our custom form setting
 */
function gpch_save_gf_type_setting( $form ) {
	$form['gpch_gf_type'] = rgpost( 'gpch_gf_type' );

	return $form;
}

add_filter( 'gform_pre_form_settings_save', 'gpch_save_gf_type_setting' );
