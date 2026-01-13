<?php
if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'gpch_advanced_settings',
			'title'                 => 'GPCH Advanced Settings',
			'fields'                => array(
				array(
					'key'               => 'gpch_advanced_settings_noindex',
					'label'             => __( 'Hide page in search engines', 'planet4-child-theme-switzerland' ),
					'name'              => 'setting_noindex',
					'type'              => 'true_false',
					'instructions'      => __( 'Outputs a "noindex" meta tag on the page. Search enignes like google will exclude the page from its search results. Use for landing pages, thank you pages and similar that don\'t need to be found through search engines.', 'planet4-child-theme-switzerland' ),
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
				array(
					'key'               => 'gpch_advanced_settings_block_popups',
					'label'             => __( 'Block Popups on this page', 'planet4-child-theme-switzerland' ),
					'name'              => 'setting_block_popups',
					'type'              => 'true_false',
					'instructions'      => __( 'Blocks popups from appearing on this post/page.', 'planet4-child-theme-switzerland' ),
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
						'param'    => 'post',
						'operator' => '!=',
						'value'    => '-1',
					),
				),
				array(
					array(
						'param'    => 'page',
						'operator' => '!=',
						'value'    => '-1',
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
		)
	);
}


/**
 * Output a noindex tag in the page when the option is set
 *
 * @param array $robots Associative array of robots directives.
 * @return array $robots
 */
function gpch_noindex_tag_output( array $robots ) {
	$noindex_setting = get_field( 'setting_noindex' );

	if ( $noindex_setting ) {
		return wp_robots_no_robots( $robots );
	}

	return $robots;
}
add_action( 'wp_robots', 'gpch_noindex_tag_output' );
