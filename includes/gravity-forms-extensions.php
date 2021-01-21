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

		// Default value: no newsletter subscription
		$newsletter_subscription = 0;

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
					"newsletterSubscription": "' . $newsletter_subscription . '",
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
            		<option value="poll" ' . ( $value == 'poll' ? 'selected' : '' ) . '>' . __( 'Poll', 'planet4-child-theme-switzerland' ) . '</option>
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
 * Add a setting to Gravity Forms to set wheter or not to send newsletter data to Inxmail
 *
 * @param $settings
 * @param $form
 *
 * @return mixed
 */
function gpch_gf_inxmail_setting( $settings, $form ) {
	$value = rgar( $form, 'gpch_gf_inxmail' );
	if ( empty( $value ) ) {
		$value = 'other';
	}

	$select = '<select name="gpch_gf_inxmail">
            		<option value="no" ' . ( $value == 'no' ? 'selected="selected"' : '' ) . '>' . __( 'No', 'planet4-child-theme-switzerland' ) . '</option>
            		<option value="yes" ' . ( $value == 'yes' ? 'selected' : '' ) . '>' . __( 'Yes', 'planet4-child-theme-switzerland' ) . '</option>
				</select>';

	$settings[ __( 'Form Basics', 'gravityforms' ) ]['gpch_inxmail'] = '
        <tr>
            <th>
            	<label for="gpch_gf_inxmail">' . __( 'GPCH Connect Inxmail', 'planet4-child-theme-switzerland' ) . '</label>
            </th>
            <td>' . $select . '</td>
		</tr>';

	return $settings;
}

add_filter( 'gform_form_settings', 'gpch_gf_inxmail_setting', 10, 2 );


/**
 * Save our custom form settings
 */
function gpch_save_gf_settings( $form ) {
	$form['gpch_gf_type']    = rgpost( 'gpch_gf_type' );
	$form['gpch_gf_inxmail'] = rgpost( 'gpch_gf_inxmail' );

	return $form;
}

add_filter( 'gform_pre_form_settings_save', 'gpch_save_gf_settings' );


/**
 * Finds newsletter subscriptions in forms and sends them to the Inxmail API
 *
 * @param $entry
 * @param $form
 */
function gpch_gform_subscribe_newsletter( $entry, $form ) {
	// Only proceed if the setting in form options is set
	if ( ! ( array_key_exists( 'gpch_gf_inxmail', $form ) && $form['gpch_gf_inxmail'] == 'yes' ) ) {
		gform_update_meta( $entry['id'], 'inxmail_status', 0 );
		gform_update_meta( $entry['id'], 'inxmail_info', 'Inxmail connection was disabled in form settings when this entry was made.' );

		return;
	}

	// Find the field IDs of the form fields we need.
	$field_ids           = array();
	$fields_to_extract   = array( 'email', 'salutation', 'first_name', 'last_name', 'newsletter' );
	$inxmail_field_names = array(
		'email'      => 'email',
		'salutation' => 'Salutation',
		'first_name' => 'FirstName',
		'last_name'  => 'Name',
		'newsletter' => 'newsletter'
	);

	foreach ( $form['fields'] as $field ) {
		if ( in_array( $field->adminLabel, $fields_to_extract ) ) {
			$field_ids[ $field->adminLabel ] = $field->id;
		}
	}

	// See if there's a newsletter subscription to process, otherwise return.
	if ( array_key_exists( 'newsletter', $field_ids ) ) {
		// "newsletter" is a checkbox group, attaching ".1" to the ID gets us the value of the first checkbox.
		$subscribe_to_lists = rgar( $entry, $field_ids['newsletter'] . '.1' );

		if ( empty( $subscribe_to_lists ) ) {
			// Checkbox for newsletter subscription was not selected.
			return;
		}
	} else {
		// Looks like the form doesn't have a field for newsletter subscription.
		return;
	}

	try {
		// The email field is required. If it doesn't exist, we can't proceed.
		if ( ! array_key_exists( 'email', $field_ids ) ) {
			throw new Exception( 'Form doesn\'t contain the required email field' );
		} else {
			$email = rgar( $entry, $field_ids['email'] );

			// We expect the form to validate the email address, but let's double check.
			if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				throw new Exception( 'Email form field contains an invalid email address' );
			}
		}
	} catch ( Exception $exception ) {
		Sentry\captureException( $exception );

		gform_update_meta( $entry['id'], 'inxmail_status', 0 );
		gform_update_meta( $entry['id'], 'inxmail_error', $exception->getMessage() );

		return;
	}

	// Prepare data for Inxmail API
	$personal_data_fields = array( 'salutation', 'first_name', 'last_name' );
	$data                 = array();

	foreach ( $field_ids as $field_name => $field_id ) {
		if ( in_array( $field_name, $personal_data_fields ) ) {
			$data[ $inxmail_field_names [ $field_name ] ] = rgar( $entry, $field_id );
		}
	}

	$lists = explode( ',', $subscribe_to_lists );

	// Send data to Inxmail API
	$GPCH_Inxmail_API = new GPCH_Inxmail_API();
	$response         = $GPCH_Inxmail_API->subscribe( $email, $lists, $data );

	// Save status to entry meta data
	if ($response === true) {
		gform_update_meta( $entry['id'], 'inxmail_status', 1 );
	}
	else {
		gform_update_meta( $entry['id'], 'inxmail_status', 0 );
		gform_update_meta( $entry['id'], 'inxmail_error', $response['error'] );
	}

	gform_update_meta( $entry['id'], 'inxmail_date_last_try', date( 'c' ) );

	GFCommon::log_debug( 'gpch_gform_subscribe_newsletter: response => ' . print_r( $response, true ) );
}

add_action( 'gform_after_submission', 'gpch_gform_subscribe_newsletter', 10, 2 );


/**
 * Add a meta box to the Gravity Forms entry detail page containing info on Inxmail API
 *
 * @param array $meta_boxes The properties for the meta boxes.
 * @param array $entry The entry currently being viewed/edited.
 * @param array $form The form object used to process the current entry.
 *
 * @return array
 */
function gpch_register_gravityforms_inxmail_metabox( $meta_boxes, $entry, $form ) {
	// Find out if the form has a field to register for a newsletter
	$form_has_newsletter_field = false;

	$fields = GFAPI::get_fields_by_type( $form, array( 'checkbox' ) );

	if ( ! empty( $fields ) ) {
		foreach ( $fields as $field ) {
			if ( $field->adminLabel == 'newsletter' ) {
				$form_has_newsletter_field = true;
			}
		}
	}

	if ( $form_has_newsletter_field ) {
		$meta_boxes['gpch_inxmail'] = array(
			'title'    => esc_html__( 'Inxmail API', 'planet4-child-theme-switzerland' ),
			'callback' => 'gpch_gravityforms_inxmail_metabox_callback',
			'context'  => 'side',
		);
	}

	return $meta_boxes;
}

add_filter( 'gform_entry_detail_meta_boxes', 'gpch_register_gravityforms_inxmail_metabox', 10, 3 );


/**
 * The callback used to echo the content of the Inxmail API metabox for Gravity Forms
 *
 * @param array $args An array containing the form and entry objects.
 */
function gpch_gravityforms_inxmail_metabox_callback( $args ) {
	$form  = $args['form'];
	$entry = $args['entry'];

	$html = '';

	// Status
	$inxmail_status = gform_get_meta( $entry['id'], 'inxmail_status' );

	if ( $inxmail_status === '0' ) {
		$html .= '<p><b>Status:</b> <span style="color: red;">Not sent</span></p>';
	} elseif ( $inxmail_status === "1" ) {
		$html .= '<p><b>Status:</b> <span style="color: green;">OK</span></p>';
	}

	// Messages
	$inxmail_error = gform_get_meta( $entry['id'], 'inxmail_error' );
	$inxmail_info = gform_get_meta( $entry['id'], 'inxmail_info' );

	if ($inxmail_error !== false) {
		$html .= '<p><b>Error Message:</b> ' . $inxmail_error . '</p>';
	}

	if ($inxmail_info !== false) {
		$html .= '<p><b>Info:</b> ' . $inxmail_info . '</p>';
	}

	// Date of last tried connection
	$inxmail_date_last_try = gform_get_meta( $entry['id'], 'inxmail_date_last_try' );

	if ( $inxmail_date_last_try ) {
		$html .= '<p><b>Last try:</b> ' . $inxmail_date_last_try . '</p>';
	}

	if (empty($html)) {
		$html = '<p><i>No data available</i></p>';
	}

	echo $html;
}


/**
 * Set HTTP headers to allow embedding of gravity forms
 */
function gpch_gravityforms_embed_whitelist( $whitelist ) {
	global $wp;

	// Only modify the whitelist if the requested page is an Gravity Form to embed
	if ( $wp->request == 'gfembed' ) {
		$options = get_option( 'gpch_child_options' );

		$allowed_ancestors = preg_split( '/\r\n|\r|\n/', $options['gpch_child_field_gf_embed_whitelist'] );

		return array_merge( $whitelist, $allowed_ancestors );
	} else {
		return $whitelist;
	}
}

add_filter( 'planet4_csp_allowed_frame_ancestors', 'gpch_gravityforms_embed_whitelist' );
