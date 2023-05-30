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
function gpch_gf_type_setting( $fields, $form ) {
	if ( ! array_key_exists( 'gpch_options', $fields ) ) {
		$new_fields['gpch_options'] = array(
			'title' => 'GPCH Options'
		);

		// Add new field to beginning of the $fields array
		$fields = array_merge( $new_fields, $fields );
	}

	$fields['gpch_options']['fields'][] = array(
		'type'           => 'select',
		'name'           => 'gpch_gf_type',
		'label'          => __( 'GPCH Form Type', 'planet4-child-theme-switzerland' ),
		'required'       => true,
		'default_value ' => 'other',
		'choices'        => array(
			array(
				'label' => 'Other',
				'value' => 'other',
			),
			array(
				'label' => 'Petition',
				'value' => 'petition',
			),
			array(
				'label' => 'Mail 2 Target',
				'value' => 'mail2target',
			),
			array(
				'label' => 'Event',
				'value' => 'event',
			),
			array(
				'label' => 'Contest',
				'value' => 'contest',
			),
			array(
				'label' => 'Poll',
				'value' => 'poll',
			),
			array(
				'label' => 'Quiz',
				'value' => 'quiz',
			),
			array(
				'label' => 'Volunteers',
				'value' => 'volunteers',
			),
			array(
				'label' => 'Testament',
				'value' => 'testament',
			),
			array(
				'label' => 'Order',
				'value' => 'order',
			),
			array(
				'label' => 'Leadgen',
				'value' => 'leadgen',
			),
			array(
				'label' => 'Contact Form',
				'value' => 'contact',
			),
			array(
				'label' => 'Newsletter (only) Form',
				'value' => 'newsletter',
			),
		),
	);

	return $fields;
}

add_filter( 'gform_form_settings_fields', 'gpch_gf_type_setting', 5, 2 );


/**
 * Add a setting to Gravity Forms to set the Sextant project_uid
 *
 * @param $settings
 * @param $form
 *
 * @return mixed
 */
function gpch_gf_sextant_project_uid_setting( $fields, $form ) {
	if ( ! array_key_exists( 'gpch_options', $fields ) ) {
		$new_fields['gpch_options'] = array(
			'title' => 'GPCH Options'
		);

		// Add new field to beginning of the $fields array
		$fields = array_merge( $new_fields, $fields );
	}

	$fields['gpch_options']['fields'][] = array(
		'type'     => 'text',
		'name'     => 'gpch_sextant_project_uid',
		'label'    => __( 'GPCH Sextant Project UID', 'planet4-child-theme-switzerland' ),
		'required' => false,
	);

	return $fields;
}

add_filter( 'gform_form_settings_fields', 'gpch_gf_sextant_project_uid_setting', 10, 2 );


/**
 * Add a setting to Gravity Forms to set whether or not to send newsletter data to Inxmail
 *
 * @param $settings
 * @param $form
 *
 * @return mixed
 */
function gpch_gf_inxmail_setting( $fields, $form ) {
	if ( ! array_key_exists( 'gpch_options', $fields ) ) {
		$new_fields['gpch_options'] = array(
			'title' => 'GPCH Options'
		);

		// Add new field to beginning of the $fields array
		$fields = array_merge( $new_fields, $fields );
	}

	$fields['gpch_options']['fields'][] = array(
		'type'           => 'select',
		'name'           => 'gpch_gf_inxmail',
		'label'          => __( 'GPCH Connect Inxmail', 'planet4-child-theme-switzerland' ),
		'required'       => true,
		'default_value ' => 'yes',
		'choices'        => array(
			array(
				'label' => 'Yes',
				'value' => 'yes',
			),
			array(
				'label' => 'No',
				'value' => 'no',
			),
		),
	);

	return $fields;
}

add_filter( 'gform_form_settings_fields', 'gpch_gf_inxmail_setting', 10, 2 );


/**
 * Add a setting to Gravity Forms with a custom entry counter that can be updated manually and automatically
 *
 * @param $settings
 * @param $form
 *
 * @return mixed
 */
function gpch_gf_entry_counter( $fields, $form ) {
	if ( ! array_key_exists( 'gpch_options', $fields ) ) {
		$new_fields['gpch_options'] = array(
			'title' => 'GPCH Options'
		);

		// Add new field to beginning of the $fields array
		$fields = array_merge( $new_fields, $fields );
	}

	$fields['gpch_options']['fields'][] = array(
		'type'                => 'text',
		'name'                => 'gpch_entry_counter',
		'label'               => __( 'GPCH Form Entry Counter', 'planet4-child-theme-switzerland' ),
		'required'            => true,
		'default_value'       => 0,
		'validation_callback' => function ( $field, $value ) {
			if ( ! is_numeric( $value ) || $value < 0 ) {
				$field->set_error( __( 'Please only use numbers >=0 in this field.', 'planet4-child-theme-switzerland' ) );
			}
		}
	);

	return $fields;
}

function gpch_gf_entry_counter_validation_callback( $field, $value ) {
	return array();
}

add_filter( 'gform_form_settings_fields', 'gpch_gf_entry_counter', 10, 2 );


/**
 * Increments the form entry counter after each submission
 *
 * @param $entry
 * @param $form
 */
function gpch_gf_increment_form_entry_counter( $entry, $form ) {
	if ( array_key_exists( 'gpch_entry_counter', $form ) && is_numeric( $form['gpch_entry_counter'] ) ) {
		$form['gpch_entry_counter'] = $form['gpch_entry_counter'] + 1;

		$result = GFAPI::update_form( $form, $form['id'] );
	} elseif ( ! array_key_exists( 'gpch_entry_counter', $form ) ) {
		$form['gpch_entry_counter'] = 1;

		$result = GFAPI::update_form( $form, $form['id'] );
	}
}

add_action( 'gform_after_submission', 'gpch_gf_increment_form_entry_counter', 10, 2 );

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
		// Check admin labels
		if ( in_array( $field->adminLabel, $fields_to_extract ) ) {
			$field_ids[ $field->adminLabel ] = $field->id;
		}

		// Check labels
		if ( in_array( $field->label, $fields_to_extract ) ) {
			$field_ids[ $field->label ] = $field->id;
		}
	}

	// See if there's a newsletter subscription to process, otherwise return.
	if ( array_key_exists( 'newsletter', $field_ids ) ) {
		// If "newsletter" is a checkbox group, attaching ".1" to the ID gets us the value of the first checkbox.
		$subscribe_to_lists = rgar( $entry, $field_ids['newsletter'] . '.1' );

		if ( empty( $subscribe_to_lists ) ) {
			// Try for other fields than checkboxes
			$subscribe_to_lists = rgar( $entry, $field_ids['newsletter'] );
		}

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

	// Add the Sextant ID if there is one set in the form
	if ( array_key_exists( 'gpch_sextant_project_uid', $form ) && ! empty( $form['gpch_sextant_project_uid'] ) ) {
		$data['project_uid'] = (int) $form['gpch_sextant_project_uid'];
	}

	// Set the language
	$inxmail_language_codes = array(
		'de' => "1",
		'fr' => "2",
	);

	if ( array_key_exists( ICL_LANGUAGE_CODE, $inxmail_language_codes ) ) {
		$data['LanguageCode'] = $inxmail_language_codes[ ICL_LANGUAGE_CODE ];
	}

	$lists = explode( ',', $subscribe_to_lists );

	// Send data to Inxmail API
	$GPCH_Inxmail_API = new GPCH_Inxmail_API();
	$response         = $GPCH_Inxmail_API->subscribe( $email, $lists, $data );

	// Save status to entry meta data
	if ( $response === true ) {
		gform_update_meta( $entry['id'], 'inxmail_status', 1 );
	} else {
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

	$fields = GFAPI::get_fields_by_type( $form, array( 'checkbox', 'hidden' ) );

	if ( ! empty( $fields ) ) {
		foreach ( $fields as $field ) {
			// Check admin labels
			if ( $field->adminLabel == 'newsletter' ) {
				$form_has_newsletter_field = true;
			}

			// Check labels
			if ( $field->label == 'newsletter' ) {
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
	$inxmail_info  = gform_get_meta( $entry['id'], 'inxmail_info' );

	if ( $inxmail_error !== false ) {
		$html .= '<p><b>Error Message:</b> ' . $inxmail_error . '</p>';
	}

	if ( $inxmail_info !== false ) {
		$html .= '<p><b>Info:</b> ' . $inxmail_info . '</p>';
	}

	// Date of last tried connection
	$inxmail_date_last_try = gform_get_meta( $entry['id'], 'inxmail_date_last_try' );

	if ( $inxmail_date_last_try ) {
		$html .= '<p><b>Last try:</b> ' . $inxmail_date_last_try . '</p>';
	}

	if ( empty( $html ) ) {
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


function gpch_custom_address_validation( $result, $value, $form, $field ) {
	if ( 'address' === $field->type && $field->isRequired ) {
		$city = rgar( $value, $field->id . '.3' );
		$zip  = rgar( $value, $field->id . '.5' );


		// Validate zip
		$input_number = 5;
		if ( ! empty( $zip ) && $field->get_input_property( $input_number, 'isHidden' ) !== true ) {
			// 4-6 digits
			if ( ! ctype_digit( $zip ) || strlen( $zip ) < 4 || strlen( $zip ) >= 5 ) {
				$field->set_input_validation_state( $input_number, false );

				$result['is_valid'] = false;
				$result['message']  = empty( $field->errorMessage ) ? __( 'Please enter a valid zip code.', 'planet4-child-theme-switzerland' ) : $field->errorMessage;
			}
		}
	}

	return $result;
}

add_filter( 'gform_field_validation', 'gpch_custom_address_validation', 10, 4 );


/**
 * Forces all GravityForms to use AJAX submission, overwriting the block level setting.
 *
 * @param $form_args
 *
 * @return $form_args
 */
add_filter( 'gform_form_args', function ( $form_args ) {
	$form_args["ajax"] = true;

	return $form_args;
}, 10, 1 );


/**
 * Load our own version of chosen that is enabled on mobile
 */
function gpch_fix_gform_chosen_mobile() {
	wp_deregister_script( 'gform_chosen' );

	wp_register_script( 'gform_chosen', path_join( get_stylesheet_directory_uri(), 'js/chosen.jquery.fix.js' ), [ 'jquery' ], '1.8.8-fix' );
}

add_action( 'init', 'gpch_fix_gform_chosen_mobile', 11 );


/**
 * Custom validator for form fields looking for often used characters or strings in spam entries.
 *
 * @param $result
 * @param $value
 * @param $form
 * @param $field
 *
 * @return mixed
 */
function gpch_spam_entry_filter( $result, $value, $form, $field ) {
	if ( $field->type == 'text' || $field->type == 'textarea' ) {
		$contains_characters = gpch_contains_forbidden_characters( $value );

		if ( $contains_characters ) {
			$result['is_valid'] = false;
			$result['message']  = __( 'Please don\'t use cyrillic characters.', 'planet4-child-theme-switzerland' );
		}
	}

	if ( 'name' === $field->type ) {
		// Input values.
		$prefix = rgar( $value, $field->id . '.2' );
		$first  = rgar( $value, $field->id . '.3' );
		$middle = rgar( $value, $field->id . '.4' );
		$last   = rgar( $value, $field->id . '.6' );
		$suffix = rgar( $value, $field->id . '.8' );

		$name_field = array( '2' => $prefix, '3' => $first, '4' => $middle, '6' => $last, '8' => $suffix );

		foreach ( $name_field as $input_number => $input_value ) {
			if ( ! $field->get_input_property( $input_number, 'isHidden' ) ) {
				$contains_characters = gpch_contains_forbidden_characters( $input_value );

				if ( $contains_characters ) {
					$field->set_input_validation_state( $input_number, false );
					$result['is_valid'] = false;
					$result['message']  = __( 'Please don\'t use cyrillic characters.', 'planet4-child-theme-switzerland' );
				}
			}
		}
	}

	return $result;
}

add_filter( 'gform_field_validation', 'gpch_spam_entry_filter', 10, 4 );

/**
 * Checks strings for forbidden characters in form submissions.
 *
 * @param $string
 *
 * @return bool
 */
function gpch_contains_forbidden_characters( $string ): bool {
	$cyrillic_characters = preg_match( '/[А-Яа-яЁё]/u', $string );

	if ( $cyrillic_characters === 1 ) {
		return true;
	}

	return false;
}


