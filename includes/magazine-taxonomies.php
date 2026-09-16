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
		'name'              => _x( 'Magazine Issue', 'taxonomy general name', 'planet4-child-theme-switzerland' ),
		'singular_name'     => _x( 'Magazine Issue', 'taxonomy singular name', 'planet4-child-theme-switzerland' ),
		'search_items'      => __( 'Search Magazine Issues', 'planet4-child-theme-switzerland' ),
		'all_items'         => __( 'All Magazine Issues', 'planet4-child-theme-switzerland' ),
		'parent_item'       => __( 'Parent Magazine Issue', 'planet4-child-theme-switzerland' ),
		'parent_item_colon' => __( 'Parent Magazine Issue:', 'planet4-child-theme-switzerland' ),
		'edit_item'         => __( 'Edit Magazine Issue', 'planet4-child-theme-switzerland' ),
		'update_item'       => __( 'Update Magazine Issue', 'planet4-child-theme-switzerland' ),
		'add_new_item'      => __( 'Add New Magazine Issue', 'planet4-child-theme-switzerland' ),
		'new_item_name'     => __( 'New Magazine Issue Name', 'planet4-child-theme-switzerland' ),
		'menu_name'         => __( 'Magazine Issues', 'planet4-child-theme-switzerland' ),
	];
	$args   = [
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'magazine-issue' ],
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
		'name'              => _x( 'Magazine Sections', 'taxonomy general name', 'planet4-child-theme-switzerland' ),
		'singular_name'     => _x( 'Magazine Section', 'taxonomy singular name', 'planet4-child-theme-switzerland' ),
		'search_items'      => __( 'Search Magazine Sections', 'planet4-child-theme-switzerland' ),
		'all_items'         => __( 'All Magazine Sections', 'planet4-child-theme-switzerland' ),
		'parent_item'       => __( 'Parent Magazine Section', 'planet4-child-theme-switzerland' ),
		'parent_item_colon' => __( 'Parent Magazine Section:', 'planet4-child-theme-switzerland' ),
		'edit_item'         => __( 'Edit Magazine Section', 'planet4-child-theme-switzerland' ),
		'update_item'       => __( 'Update Magazine Section', 'planet4-child-theme-switzerland' ),
		'add_new_item'      => __( 'Add New Magazine Section', 'planet4-child-theme-switzerland' ),
		'new_item_name'     => __( 'New Magazine Section Name', 'planet4-child-theme-switzerland' ),
		'menu_name'         => __( 'Magazine Sections (Rubriken)', 'planet4-child-theme-switzerland' ),
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


/**
 * Order the Issue archive by each Magazine Article's priority (ascending), falling
 * back to publish date for articles with the same priority (or no priority set at all).
 *
 * @param WP_Query $query The main query.
 *
 * @return void
 */
function gpch_magazine_issue_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! is_tax( 'gpch_magazine_issue' ) ) {
		return;
	}

	$query->set( 'meta_key', 'magazine_article_position' );
	$query->set(
		'orderby',
		array(
			'meta_value_num' => 'ASC',
			'date'           => 'DESC',
		)
	);
}

add_action( 'pre_get_posts', 'gpch_magazine_issue_archive_query' );


/**
 * Add a custom template for the Issue taxonomy archive (masonry overview page).
 *
 * @param string $template_path The path to the archive template.
 *
 * @return mixed|string
 */
function gpch_include_gpchmagazineissue_archive_template( $template_path ) {
	if ( is_tax( 'gpch_magazine_issue' ) ) {
		$template_path = get_stylesheet_directory() . '/includes/post-templates/gpch-magazine-issue-archive.php';
	}

	return $template_path;
}

add_filter( 'template_include', 'gpch_include_gpchmagazineissue_archive_template', 1 );
