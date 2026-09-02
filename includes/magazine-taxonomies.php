<?php

/**
 * Registers the "Issue" taxonomy for Magazine Articles.
 *
 * Only one Issue should be assigned per article (editorial convention, not technically enforced).
 * The term name itself holds the issue's identifier (e.g. "2026/2"); additional data lives in
 * the ACF fields registered further down in this file.
 *
 * @return void
 */
function gpch_register_taxonomy_magazine_issue() {
	$labels = [
		'name'              => _x( 'Issues', 'taxonomy general name', 'planet4-child-theme-switzerland' ),
		'singular_name'     => _x( 'Issue', 'taxonomy singular name', 'planet4-child-theme-switzerland' ),
		'search_items'      => __( 'Search Issues', 'planet4-child-theme-switzerland' ),
		'all_items'         => __( 'All Issues', 'planet4-child-theme-switzerland' ),
		'parent_item'       => __( 'Parent Issue', 'planet4-child-theme-switzerland' ),
		'parent_item_colon' => __( 'Parent Issue:', 'planet4-child-theme-switzerland' ),
		'edit_item'         => __( 'Edit Issue', 'planet4-child-theme-switzerland' ),
		'update_item'       => __( 'Update Issue', 'planet4-child-theme-switzerland' ),
		'add_new_item'      => __( 'Add New Issue', 'planet4-child-theme-switzerland' ),
		'new_item_name'     => __( 'New Issue Name', 'planet4-child-theme-switzerland' ),
		'menu_name'         => __( 'Issues', 'planet4-child-theme-switzerland' ),
	];
	$args   = [
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'issue' ],
		'show_in_rest'      => true,
	];
	register_taxonomy( 'gpch_magazine_issue', [ 'gpch_magazinearticle' ], $args );
}

add_action( 'init', 'gpch_register_taxonomy_magazine_issue' );


/**
 * Registers the "Section" ("Rubrik") taxonomy for Magazine Articles.
 *
 * Only one Section should be assigned per article (editorial convention, not technically enforced).
 *
 * @return void
 */
function gpch_register_taxonomy_magazine_section() {
	$labels = [
		'name'              => _x( 'Sections', 'taxonomy general name', 'planet4-child-theme-switzerland' ),
		'singular_name'     => _x( 'Section', 'taxonomy singular name', 'planet4-child-theme-switzerland' ),
		'search_items'      => __( 'Search Sections', 'planet4-child-theme-switzerland' ),
		'all_items'         => __( 'All Sections', 'planet4-child-theme-switzerland' ),
		'parent_item'       => __( 'Parent Section', 'planet4-child-theme-switzerland' ),
		'parent_item_colon' => __( 'Parent Section:', 'planet4-child-theme-switzerland' ),
		'edit_item'         => __( 'Edit Section', 'planet4-child-theme-switzerland' ),
		'update_item'       => __( 'Update Section', 'planet4-child-theme-switzerland' ),
		'add_new_item'      => __( 'Add New Section', 'planet4-child-theme-switzerland' ),
		'new_item_name'     => __( 'New Section Name', 'planet4-child-theme-switzerland' ),
		'menu_name'         => __( 'Sections (Rubriken)', 'planet4-child-theme-switzerland' ),
	];
	$args   = [
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'rubrik' ],
		'show_in_rest'      => true,
	];
	register_taxonomy( 'gpch_magazine_section', [ 'gpch_magazinearticle' ], $args );
}

add_action( 'init', 'gpch_register_taxonomy_magazine_section' );


/**
 * Create custom fields for Issue terms using ACF (shown on the "Edit Term" screen).
 */
function gpch_magazine_issue_create_custom_fields() {
	if ( function_exists( 'acf_add_local_field_group' ) ) {
		acf_add_local_field_group(
			array(
				'key'      => 'group_gpch_magazine_issue',
				'title'    => 'Issue Details',
				'fields'   => array(
					array(
						'key'          => 'field_gpch_magazine_issue_title',
						'label'        => 'Title',
						'name'         => 'issue_title',
						'type'         => 'text',
						'instructions' => '',
						'required'     => 0,
					),
					array(
						'key'           => 'field_gpch_magazine_issue_title_image',
						'label'         => 'Title Image',
						'name'          => 'issue_title_image',
						'type'          => 'image',
						'instructions'  => '',
						'required'      => 0,
						'return_format' => 'array',
					),
					array(
						'key'            => 'field_gpch_magazine_issue_publication_date',
						'label'          => 'Publication Date',
						'name'           => 'issue_publication_date',
						'type'           => 'date_picker',
						'instructions'   => '',
						'required'       => 0,
						'display_format' => 'd. m. Y',
						'return_format'  => 'Y-m-d',
						'first_day'      => 1,
					),
					array(
						'key'          => 'field_gpch_magazine_issue_introduction',
						'label'        => 'Introduction',
						'name'         => 'issue_introduction',
						'type'         => 'textarea',
						'instructions' => 'A short introduction text for this issue.',
						'required'     => 0,
					),
				),
				'location' => array(
					array(
						array(
							'param'    => 'taxonomy',
							'operator' => '==',
							'value'    => 'gpch_magazine_issue',
						),
					),
				),
				'active'   => true,
			)
		);
	}
}

add_action( 'init', 'gpch_magazine_issue_create_custom_fields' );
