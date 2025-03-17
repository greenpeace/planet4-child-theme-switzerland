<?php

add_action(
	'acf/include_fields',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}

		acf_add_local_field_group(
			array(
				'key'                   => 'user_business_card',
				'title'                 => 'Business Card',
				'fields'                => array(
					array(
						'key'                       => 'field_6772a98936fdd',
						'label'                     => __( 'Enable Business Card', 'planet4-child-theme-switzerland' ),
						'name'                      => 'enable_business_card',
						'aria-label'                => '',
						'type'                      => 'checkbox',
						'instructions'              => '',
						'required'                  => 0,
						'conditional_logic'         => 0,
						'wrapper'                   => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'choices'                   => array(
							'enabled' => __( 'Enable Business Card', 'planet4-child-theme-switzerland' ),
						),
						'default_value'             => array(),
						'return_format'             => 'value',
						'allow_custom'              => 0,
						'allow_in_bindings'         => 0,
						'layout'                    => 'vertical',
						'toggle'                    => 0,
						'save_custom'               => 0,
						'custom_choice_button_text' => 'Add new choice',
					),
					array(
						'key'               => 'field_6772b21f60d0c',
						'label'             => __( 'Business Card ID', 'planet4-child-theme-switzerland' ),
						'name'              => 'business_card_id',
						'aria-label'        => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => '',
						'allow_in_bindings' => 1,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_6772ab67904b9',
						'label'             => __( 'Personal Data', 'planet4-child-theme-switzerland' ),
						'name'              => '',
						'aria-label'        => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 1,
						'selected'          => 0,
					),
					array(
						'key'               => 'field_6772aa5b99990',
						'label'             => __( 'Profile picture', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_profile_picture',
						'aria-label'        => '',
						'type'              => 'image',
						'instructions'      => 'Upload your profile picture (JPG picture, minimum 1000x1000, maximum 3000x3000 pixels)',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'return_format'     => 'url',
						'library'           => 'uploadedTo',
						'min_width'         => 1000,
						'min_height'        => 1000,
						'min_size'          => '',
						'max_width'         => 3000,
						'max_height'        => 3000,
						'max_size'          => 3,
						'mime_types'        => 'jpg',
						'allow_in_bindings' => 0,
						'preview_size'      => 'medium',
					),
					array(
						'key'               => 'field_6772a8160c36f',
						'label'             => __( 'Name', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_name',
						'aria-label'        => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 1,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => 100,
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_677d1d3c372e2',
						'label'             => __( 'Job Title', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_job_title',
						'aria-label'        => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => 100,
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_677d1d5359536',
						'label'             => __( 'Organisation', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_organisation',
						'aria-label'        => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => 'Greenpeace Schweiz',
						'maxlength'         => 100,
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_6772ac794109f',
						'label'             => __( 'Email', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_email',
						'aria-label'        => '',
						'type'              => 'email',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_6772aca0410a0',
						'label'             => __( 'Phone', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_phone',
						'aria-label'        => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_6772acd6410a1',
						'label'             => __( 'Mobile', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_mobile',
						'aria-label'        => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
					array(
						'key'               => 'field_6772abb4904ba',
						'label'             => __( 'Social Media', 'planet4-child-theme-switzerland' ),
						'name'              => '',
						'aria-label'        => '',
						'type'              => 'tab',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'placement'         => 'top',
						'endpoint'          => 0,
						'selected'          => 0,
					),
					array(
						'key'               => 'field_6772ad17410a2',
						'label'             => __( 'LinkedIn Profile URL', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_linkedin',
						'aria-label'        => '',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_6772af1f410a5',
						'label'             => __( 'Bluesky Profile URL', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_bluesky',
						'aria-label'        => '',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_6772aef8410a4',
						'label'             => __( 'Facebook Profile URL', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_facebook',
						'aria-label'        => '',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'allow_in_bindings' => 0,
						'placeholder'       => '',
					),
					array(
						'key'               => 'field_6772ae8e410a3',
						'label'             => __( 'Instagram Handle', 'planet4-child-theme-switzerland' ),
						'name'              => 'bc_instagram',
						'aria-label'        => '',
						'type'              => 'url',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => array(
							array(
								array(
									'field'    => 'field_6772a98936fdd',
									'operator' => '!=empty',
								),
							),
						),
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'maxlength'         => 60,
						'allow_in_bindings' => 0,
						'placeholder'       => '',
						'prepend'           => '',
						'append'            => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'current_user_role',
							'operator' => '==',
							'value'    => 'administrator',
						),
						array(
							'param'    => 'user_form',
							'operator' => '==',
							'value'    => 'all',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'field',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 0,
			)
		);
	}
);


/**
 * Prepares the ID field by setting it to read-only for non-admin users.
 *
 * @param mixed $field The field to be processed.
 * @return mixed|false The processed field, or false if the user does not have permission.
 */
function gpch_bc_prepare_id_field( $field ) {
	// Change the field to read-only for everyone except admins
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	return $field;
}

add_filter( 'acf/prepare_field/name=business_card_id', 'gpch_bc_prepare_id_field' );
