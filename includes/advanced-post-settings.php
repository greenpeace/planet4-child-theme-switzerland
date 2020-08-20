<?php
if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group( array(
		'key'                   => 'gpch_advanced_settings',
		'title'                 => 'GPCH Advanced Settings',
		'fields'                => array(
			array(
				'key'               => 'gpch_advanced_settings_color_scheme_override',
				'label'             => __('Color Scheme Override', 'planet4-child-theme-switzerland' ),
				'name'              => 'color_scheme_override',
				'type'              => 'taxonomy',
				'instructions'      => __('The color scheme is automatically set using a pages/posts tags. Use this option to override and select a different color scheme.', 'planet4-child-theme-switzerland' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'taxonomy'          => 'post_tag',
				'field_type'        => 'select',
				'allow_null'        => 1,
				'add_term'          => 0,
				'save_terms'        => 0,
				'load_terms'        => 0,
				'return_format'     => 'object',
				'multiple'          => 0,
			),
			array(
				'key'               => 'gpch_advanced_settings_noindex',
				'label'             => __('Hide page in search engines', 'planet4-child-theme-switzerland' ),
				'name'              => 'noindex',
				'type'              => 'true_false',
				'instructions'      => __('Outputs a "noindex" meta tag on the page. Search enignes like google will exclude the page from its search results. Use for landing pages, thank you pages and similar that don\'t need to be found through search engines.', 'planet4-child-theme-switzerland' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'message'           => '',
				'default_value'     => 0,
				'ui'                => 1,
				'ui_on_text'        => 'Yes',
				'ui_off_text'       => 'No',
			),
		),
		'location'              => array(
			array(
				array(
					'param' => 'post',
					'operator' => '!=',
					'value' => '-1',
				),
			),
			array(
				array(
					'param' => 'page',
					'operator' => '!=',
					'value' => '-1',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	) );
}
