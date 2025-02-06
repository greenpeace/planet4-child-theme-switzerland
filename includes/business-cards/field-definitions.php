<?php

add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key' => 'user_business_card',
		'title' => 'Business Card',
		'fields' => array(
			array(
				'key' => 'field_6772a98936fdd',
				'label' => 'Enable Business Card',
				'name' => 'enable_business_card',
				'aria-label' => '',
				'type' => 'checkbox',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'enabled' => 'Enable Business Card',
				),
				'default_value' => array(
				),
				'return_format' => 'value',
				'allow_custom' => 0,
				'allow_in_bindings' => 0,
				'layout' => 'vertical',
				'toggle' => 0,
				'save_custom' => 0,
				'custom_choice_button_text' => 'Add new choice',
			),
			array(
				'key' => 'field_6772ab67904b9',
				'label' => 'Personal Data',
				'name' => '',
				'aria-label' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 1,
				'selected' => 0,
			),
			array(
				'key' => 'field_6772aa5b99990',
				'label' => 'Profile picture',
				'name' => 'bc_profile_picture',
				'aria-label' => '',
				'type' => 'image',
				'instructions' => 'Upload your profile picture (JPG picture, minimum 1000x1000, maximum 3000x3000 pixels)',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'url',
				'library' => 'uploadedTo',
				'min_width' => 1000,
				'min_height' => 1000,
				'min_size' => '',
				'max_width' => 3000,
				'max_height' => 3000,
				'max_size' => 3,
				'mime_types' => 'jpg',
				'allow_in_bindings' => 0,
				'preview_size' => 'medium',
			),
			array(
				'key' => 'field_6772a8160c36f',
				'label' => 'Name',
				'name' => 'bc_name',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => 100,
				'allow_in_bindings' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_677d1d3c372e2',
				'label' => 'Job Title',
				'name' => 'bc_job_title',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => 100,
				'allow_in_bindings' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_677d1d5359536',
				'label' => 'Organisation',
				'name' => 'bc_organisation',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'Greenpeace Schweiz',
				'maxlength' => 100,
				'allow_in_bindings' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_6772ac794109f',
				'label' => 'Email',
				'name' => 'bc_email',
				'aria-label' => '',
				'type' => 'email',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'allow_in_bindings' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_6772aca0410a0',
				'label' => 'Phone',
				'name' => 'bc_phone',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'allow_in_bindings' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_6772acd6410a1',
				'label' => 'Mobile',
				'name' => 'bc_mobile',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'allow_in_bindings' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_6772abb4904ba',
				'label' => 'Social Media',
				'name' => '',
				'aria-label' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
				'selected' => 0,
			),
			array(
				'key' => 'field_6772ad17410a2',
				'label' => 'LinkedIn Profile URL',
				'name' => 'bc_linkedin',
				'aria-label' => '',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'allow_in_bindings' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_6772af1f410a5',
				'label' => 'Bluesky Profile URL',
				'name' => 'bc_bluesky',
				'aria-label' => '',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'allow_in_bindings' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_6772aef8410a4',
				'label' => 'Facebook Profile URL',
				'name' => 'bc_facebook',
				'aria-label' => '',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'allow_in_bindings' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_6772ae8e410a3',
				'label' => 'Instagram Handle',
				'name' => 'bc_instagram',
				'aria-label' => '',
				'type' => 'url',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => 60,
				'allow_in_bindings' => 0,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_6772b146bf677',
				'label' => 'Share your business card',
				'name' => '',
				'aria-label' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
				'selected' => 0,
			),
			array(
				'key' => 'field_6772b21f60d0c',
				'label' => 'Business Card ID',
				'name' => 'business_card_id',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'allow_in_bindings' => 1,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
			array(
				'key' => 'field_6778011d05453',
				'label' => 'Business Card URL',
				'name' => 'bc_url',
				'aria-label' => '',
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6772a98936fdd',
							'operator' => '!=empty',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'maxlength' => '',
				'allow_in_bindings' => 1,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'user_form',
					'operator' => '==',
					'value' => 'edit',
				),
			),
		),
		'menu_order' => -20,
		'position' => 'acf_after_title',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'field',
		'hide_on_screen' => '',
		'active' => false,
		'description' => '',
		'show_in_rest' => 0,
	) );
} );

