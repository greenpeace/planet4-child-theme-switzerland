<?php

/**
 * Manipulate the GravityForms menu to display the forms sorted by ID (descending)
 */
function gpch_change_media_label() {
	global $menu, $submenu;

	// Change the forms list submenu to include sorting by ID (descending)
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$submenu['gf_edit_forms'][0][2] = 'admin.php?page=gf_edit_forms&orderby=id&order=desc';
}

add_action( 'admin_menu', 'gpch_change_media_label', 9999999 );


/**
 * Add Swiss phone number validation
 *
 * @param array $phone_formats The formats that already defined.
 *
 * @return array
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
 *
 * @param string       $format The format to be filtered.
 * @param Field_Object $field The current field.
 *
 * @return string
 */
function gpch_gf_address_format( $format, $field ) {
	return 'zip_before_city';
}

add_filter( 'gform_address_display_format', 'gpch_gf_address_format', 10, 2 );


/**
 * Suppress the redirect in forms to use our own redirect handling (see below)
 */
add_filter( 'gform_suppress_confirmation_redirect', '__return_true' );


/**
 * Redirect using Javascript after form submission instead of sending a header. Makes it possible to send tag manager
 * events before redirecting.
 *
 * @param string | array $confirmation The confirmation message/array to be filtered.
 * @param Form_Object    $form The current form.
 * @param Entry_Object   $entry The current entry.
 * @param bool           $ajax Specifies if this form is configured to be submitted via AJAX.
 *
 * @return mixed|string|void
 */
function gpch_gpform_confirmation( $confirmation, $form, $entry, $ajax ) {
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

		// Find address and phone fields for event parameters
		$address_field_used = 0;
		$phone_field_used = 0;
		foreach ( $form['fields'] as $field ) {
			if ( $field['type'] == 'address' ) {
				$address_field_used = 1;
			} elseif ( $field['type'] == 'phone' ) {
				$phone_field_used = 1;
			}
		}

		// Get the tag manager data layer ID from master theme settings
		$options = get_option( 'planet4_options' );
		$gtm_id  = $options['google_tag_manager_identifier'];

		$gp_user_id = gpch_generate_user_id_from_form_submission( $form, $entry );

		$script = '<script type="text/javascript">
			if ( window["google_tag_manager"] ) {
				window.dataLayer = window.dataLayer || [];
				dataLayer.push({
					"event": "gravityFormSubmission",
					"formType": "' . $form['gpch_gf_type'] . '",
					"formID": "' . $form['id'] . '",
					"formPlugin": "Gravity Form",
					"formTitle": "' . $form['title'] . '",
					"newsletterSubscription": "' . $newsletter_subscription . '",
					"newsletterType": "' . $newsletter_type . '",
					"formContainsPhoneField": "' . $phone_field_used . '",
					"formContainsAddressField": "' . $address_field_used . '",
					"eventCallback" : function(id) {
						// There might be multiple gtm containers, make sure we only redirect for our main container
						if( id == "' . $gtm_id . '") {
	                        window.top.location.href = "' . $url . '";
	                    }
	                },
	                "gp_user_id": "' . $gp_user_id . '",
	                "eventTimeout" : 2000
				});
			}
			else {
				/* Redirect latest after two seconds. This is a failsafe in case the request to tag manager is blocked */
				setTimeout( function() {
					window.top.location.href = "' . $url . '";
				}, 2000);
			}
			</script>';

		$confirmation = $html . $script;
	}

	return $confirmation;
}

add_filter( 'gform_confirmation', 'gpch_gpform_confirmation', 10, 4 );


/**
 * Add a setting to gravity Forms to set the type of form
 *
 * @param array       $fields The fields of the current form.
 * @param Form_Object $form The current form.
 *
 * @return mixed
 */
function gpch_gf_type_setting( $fields, $form ) {
	if ( ! array_key_exists( 'gpch_options', $fields ) ) {
		$new_fields['gpch_options'] = array(
			'title' => 'GPCH Options',
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
 * Add a setting to Gravity Forms with a custom entry counter that can be updated manually and automatically
 *
 * @param array       $fields The form setting fields to filter.
 * @param Form_Object $form the current form.
 *
 * @return array
 */
function gpch_gf_entry_counter( $fields, $form ) {
	if ( ! array_key_exists( 'gpch_options', $fields ) ) {
		$new_fields['gpch_options'] = array(
			'title' => 'GPCH Options',
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
		},
	);

	return $fields;
}

add_filter( 'gform_form_settings_fields', 'gpch_gf_entry_counter', 10, 2 );


/**
 * Increments the form entry counter after each submission
 *
 * @param Entry_Object $entry The current form entry.
 * @param Form_Object  $form the current form.
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
 * Set HTTP headers to allow embedding of gravity forms
 *
 * @param array $allowlist The allowlist for domains that can ambed our forms.
 *
 * @return array
 */
function gpch_gravityforms_embed_whitelist( $allowlist ) {
	global $wp;

	// Only modify the whitelist if the requested page is an Gravity Form to embed
	if ( $wp->request == 'gfembed' ) {
		$options = get_option( 'gpch_child_options' );

		$allowed_ancestors = preg_split( '/\r\n|\r|\n/', $options['gpch_child_field_gf_embed_whitelist'] );

		return array_merge( $allowlist, $allowed_ancestors );
	} else {
		return $allowlist;
	}
}

add_filter( 'planet4_csp_allowed_frame_ancestors', 'gpch_gravityforms_embed_whitelist' );


/**
 * Custom address validator
 *
 * @param array          $result The validation result to be filtered.
 * @param string | array $value The field value to be validated. Multi-input fields like Address will pass an array of values.
 * @param Form_Object    $form Current form object.
 * @param Field_Object   $field Current field object.
 *
 * @return array
 */
function gpch_custom_address_validation( $result, $value, $form, $field ) {
	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
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
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$result['message'] = empty( $field->errorMessage ) ? __( 'Please enter a valid zip code.', 'planet4-child-theme-switzerland' ) : $field->errorMessage;
			}
		}
	}

	return $result;
}

add_filter( 'gform_field_validation', 'gpch_custom_address_validation', 10, 4 );


/**
 * Forces all GravityForms to use AJAX submission, overwriting the block level setting.
 *
 * @param array $form_args The arguments for the current form.
 *
 * @return array $form_args
 */
function gpch_gform_arguments( $form_args ) {
	$form_args['ajax'] = true;

	return $form_args;
}

add_filter( 'gform_form_args', 'gpch_gform_arguments', 10, 1 );


/**
 * Load our own version of chosen that is enabled on mobile
 */
function gpch_fix_gform_chosen_mobile() {
	wp_deregister_script( 'gform_chosen' );

	wp_register_script( 'gform_chosen', path_join( get_stylesheet_directory_uri(), 'js/vendor/chosen.jquery.fix.js' ), [ 'jquery' ], '1.8.8-fix', false );
}

add_action( 'init', 'gpch_fix_gform_chosen_mobile', 11 );


/**
 * Custom validator for form fields looking for often used characters or strings in spam entries.
 *
 * @param array          $result The validation result to be filtered.
 * @param string | array $value The field value to be validated. Multi-input fields like Address will pass an array of values.
 * @param Form_Object    $form Current form object.
 * @param Field_Object   $field Current field object.
 *
 * @return array
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

		$name_field = array(
			'2' => $prefix,
			'3' => $first,
			'4' => $middle,
			'6' => $last,
			'8' => $suffix,
		);

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
 * @param string $text Input to string to check.
 *
 * @return bool
 */
function gpch_contains_forbidden_characters( $text ): bool {
	$cyrillic_characters = preg_match( '/[А-Яа-яЁё]/u', $text );

	if ( $cyrillic_characters === 1 ) {
		return true;
	}

	return false;
}


/**
 * Shows a warning to editors if the Salesforce ID is missing or still the default
 *
 * @param Form_Object $form The current form.
 *
 * @return Form_Object
 */
function gpch_salesforce_id_check( $form ) {
	if ( current_user_can( 'edit_posts' ) ) {
		foreach ( $form['fields'] as &$field ) {
			if ( $field->label == 'salesforce_campaign_id' ) {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				if ( strlen( $field->defaultValue ) < 16 || $field->defaultValue == '701090000005gMWAAY' ) {
					echo "<div class=\"editor-warning\" style=\"border: 5px solid red; margin: 1em 0; padding: 1em;\"><span style=\"font-weight: bold;\">The Salesform Campaign ID in this form is missing or still using the default. Are you sure that's correct? </span><br>(This warning is only shown to editors.)</div>";
				}
			}
		}
	}

	return $form;
}

add_filter( 'gform_pre_render', 'gpch_salesforce_id_check' );
