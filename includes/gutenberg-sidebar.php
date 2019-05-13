<?php

/**
 * add the custom taxonomy "Page Type" of Planet4 to the sidebar
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group( array(
		'key'                   => 'group_5cd97c24f05ab',
		'title'                 => __( 'Page Type', 'planet4-child-theme-switzerland' ),
		'fields'                => array(
			array(
				'key'               => 'field_5cd97c2e6667c',
				'label'             => '',
				'name'              => 'p4-page-type',
				'type'              => 'taxonomy',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '',
					'class' => '',
					'id'    => '',
				),
				'taxonomy'          => 'p4-page-type',
				'field_type'        => 'select',
				'allow_null'        => 0,
				'add_term'          => 0,
				'save_terms'        => 1,
				'load_terms'        => 1,
				'return_format'     => 'id',
				'multiple'          => 0,
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		),
		'menu_order'            => - 100,
		'position'              => 'side',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	) );
}
