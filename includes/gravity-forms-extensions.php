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
		'mask'        => false,
		'regex'       => '/^[0-9 +()CH]*$/',
		'instruction' => __( 'Use only numbers, spaces, parentheses and "+"', 'planet4-child-theme-switzerland' ),
	);

	$phone_formats['ch-mobile'] = array(
		'label'       => '+41 7# ### ## ## (Mobile Schweiz)',
		'mask'        => false,
		'regex'       => '/^[0-9 +()CH]*$/',
		'instruction' => __( 'Use only numbers, spaces, parentheses and "+"', 'planet4-child-theme-switzerland' ),
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
 * Adds custom parameters to form submission datalayer events.
 *
 * @param array $parameters The event parameters.
 * @param mixed $form The form properties.
 * @param mixed $entry The current entry.
 *
 * @return array $parameters
 */
function gpch_form_submission_event_parameters( $parameters, $form, $entry ) {
	// Find the newsletter field
	// Convention: use an admin label in the form field settings that contains "newsletter"
	foreach ( $form['fields'] as $field ) {
		if ( $field['type'] === 'checkbox' && strpos( $field['adminLabel'], 'newsletter' ) !== false ) {
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
	$phone_field_used   = 0;
	foreach ( $form['fields'] as $field ) {
		if ( $field['type'] === 'address' ) {
			$address_field_used = 1;
		} elseif ( $field['type'] === 'phone' ) {
			$phone_field_used = 1;
		}
	}

	if ( array_key_exists( 'gpch_gf_type', $form ) ) {
		$parameters['formType'] = $form['gpch_gf_type'];
	}

	$parameters['newsletterSubscription']   = $newsletter_subscription;
	$parameters['newsletterType']           = $newsletter_type;
	$parameters['formContainsPhoneField']   = $phone_field_used;
	$parameters['formContainsAddressField'] = $address_field_used;

	return $parameters;
}

add_filter( 'planet4_datalayer_form_submission', 'gpch_form_submission_event_parameters', 10, 3 );

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
 * Format phone numbers in Gravity Forms phone fields before saving.
 *
 * @param string      $value The current value of the field.
 * @param array       $entry The entry object containing form submission values.
 * @param GF_Field    $field The current field being processed.
 * @param Form_Object $form The current form object.
 * @param string      $input_id The specific input ID of the field.
 *
 * @return string The formatted phone number if matched, otherwise the original value.
 */
function gpch_gf_format_phone_number( $value, $entry, $field, $form, $input_id ) {
	if ( $field->get_input_type() === 'phone' ) {
		// Remove whitespace
		$new_value = preg_replace( '/\s+/', '', $value );

		// Normalize
		$pattern = '/^(0041|041|\+41|\+\+41|41)?(0|\(0\))?([1-9]\d{1})(\d{3})(\d{2})(\d{2})$/';

		if ( preg_match( $pattern, $new_value, $matches ) ) {
			$new_value = '+41' . $matches[3] . $matches[4] . $matches[5] . $matches[6];

			return $new_value;
		}
	}

	return $value;
}
add_filter( 'gform_save_field_value', 'gpch_gf_format_phone_number', 10, 5 );


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
	if ( $wp->request === 'gfembed' ) {
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
 * Set allowed html tags in form submissions.
 *
 * @param string|bool $allowable_tags The allowed html tags.
 *
 * @return string
 *
 * @phpcs:ignore(Generic.CodeAnalysis.UnusedFunctionParameter)
 */
function gpch_set_allowed_form_submission_tags( $allowable_tags ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	// Return an empty string to disallow all html tags.
	return '';
}

add_filter( 'gform_allowable_tags', 'gpch_set_allowed_form_submission_tags', 10, 1 );


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
	if ( $field->type === 'text' || $field->type === 'textarea' ) {
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
			if ( $field->label === 'salesforce_campaign_id' ) {
				//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				if ( strlen( $field->defaultValue ) < 16 || $field->defaultValue === '701090000005gMWAAY' ) {
					echo "<div class=\"editor-warning\" style=\"border: 5px solid red; margin: 1em 0; padding: 1em;\"><span style=\"font-weight: bold;\">The Salesform Campaign ID in this form is missing or still using the default. Are you sure that's correct? </span><br>(This warning is only shown to editors.)</div>";
				}
			}
		}
	}

	return $form;
}

add_filter( 'gform_pre_render', 'gpch_salesforce_id_check' );

/**
 * Enable default meta parameters for Gravity forms. Overwrite parameters from master theme.
 *
 * @param array $meta Associative array containing all form properties.
 */
function gpch_gf_enable_default_meta_settings( array $meta ): array {
	$meta['personalData']['preventIP'] = false;

	return $meta;
}

// Apply only for specific forms
// ACT: CFC Finanzplatz-Initiative DE
add_filter( 'gform_form_post_get_meta_443', 'gpch_gf_enable_default_meta_settings', 10, 1 );
// ACT: CFC Finanzplatz-Initiative FR
add_filter( 'gform_form_post_get_meta_450', 'gpch_gf_enable_default_meta_settings', 10, 1 );
