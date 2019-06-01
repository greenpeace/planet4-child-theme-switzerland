<?php


if ( ! function_exists( 'p4_child_theme_gpch_custom_post_job' ) ) {
	/**
	 * Register Custom Post Type for Job
	 */
	function p4_child_theme_gpch_custom_post_job() {
		$labels       = array(
			'name'                  => _x( 'Jobs', 'Post Type General Name', 'planet4-child-theme-switzerland' ),
			'singular_name'         => _x( 'Job', 'Post Type Singular Name', 'planet4-child-theme-switzerland' ),
			'menu_name'             => __( 'Jobs', 'planet4-child-theme-switzerland' ),
			'name_admin_bar'        => __( 'Job', 'planet4-child-theme-switzerland' ),
			'archives'              => __( 'Jobs Archives', 'planet4-child-theme-switzerland' ),
			'attributes'            => __( 'Job Attributes', 'planet4-child-theme-switzerland' ),
			'parent_item_colon'     => __( 'Parent Job:', 'planet4-child-theme-switzerland' ),
			'all_items'             => __( 'All Jobs', 'planet4-child-theme-switzerland' ),
			'add_new_item'          => __( 'Add New Job', 'planet4-child-theme-switzerland' ),
			'add_new'               => __( 'New Job', 'planet4-child-theme-switzerland' ),
			'new_item'              => __( 'New JOb', 'planet4-child-theme-switzerland' ),
			'edit_item'             => __( 'Edit Job', 'planet4-child-theme-switzerland' ),
			'update_item'           => __( 'Update Job', 'planet4-child-theme-switzerland' ),
			'view_item'             => __( 'View Job', 'planet4-child-theme-switzerland' ),
			'view_items'            => __( 'View Jobs', 'planet4-child-theme-switzerland' ),
			'search_items'          => __( 'Search Job', 'planet4-child-theme-switzerland' ),
			'not_found'             => __( 'Not found', 'planet4-child-theme-switzerland' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'planet4-child-theme-switzerland' ),
			'featured_image'        => __( 'Featured Image', 'planet4-child-theme-switzerland' ),
			'set_featured_image'    => __( 'Set featured image', 'planet4-child-theme-switzerland' ),
			'remove_featured_image' => __( 'Remove featured image', 'planet4-child-theme-switzerland' ),
			'use_featured_image'    => __( 'Use as featured image', 'planet4-child-theme-switzerland' ),
			'insert_into_item'      => __( 'Insert into job', 'planet4-child-theme-switzerland' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'planet4-child-theme-switzerland' ),
			'items_list'            => __( 'Jobs list', 'planet4-child-theme-switzerland' ),
			'items_list_navigation' => __( 'Jobs list navigation', 'planet4-child-theme-switzerland' ),
			'filter_items_list'     => __( 'Filter jobs list', 'planet4-child-theme-switzerland' ),
		);
		$rewrite      = array(
			'slug'       => 'job',
			'with_front' => true,
			'pages'      => false,
			'feeds'      => true,
		);
		$capabilities = array(
			'edit_post'          => 'edit_job',
			'read_post'          => 'read_job',
			'delete_post'        => 'delete_jobs',
			'edit_posts'         => 'edit_jobs',
			'edit_others_posts'  => 'edit_others_jobs',
			'publish_posts'      => 'publish_jobs',
			'read_private_posts' => 'read_private_jobs',
		);
		$args         = array(
			'label'               => __( 'Job', 'planet4-child-theme-switzerland' ),
			'description'         => __( 'Job postings', 'planet4-child-theme-switzerland' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'menu_icon'           => 'dashicons-clipboard',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capabilities'        => $capabilities,
		);

		register_post_type( 'gpch_job', $args );
	}

	add_action( 'init', 'p4_child_theme_gpch_custom_post_job', 0 );
}


if ( ! function_exists( 'p4_child_theme_gpch_custom_post_archived_post' ) ) {
	/**
	 * Register Custom Post Type for Archived Post
	 */
	function p4_child_theme_gpch_custom_post_archived_post() {
		$labels       = array(
			'name'                  => _x( 'Archived Posts', 'Post Type General Name', 'planet4-child-theme-switzerland' ),
			'singular_name'         => _x( 'Archived Post', 'Post Type Singular Name', 'planet4-child-theme-switzerland' ),
			'menu_name'             => __( 'Archived Posts', 'planet4-child-theme-switzerland' ),
			'name_admin_bar'        => __( 'Archived Post', 'planet4-child-theme-switzerland' ),
			'archives'              => __( 'Archived Posts Archives', 'planet4-child-theme-switzerland' ),
			'attributes'            => __( 'Archived Post Attributes', 'planet4-child-theme-switzerland' ),
			'parent_item_colon'     => __( 'Parent Archived Post:', 'planet4-child-theme-switzerland' ),
			'all_items'             => __( 'All Archived Posts', 'planet4-child-theme-switzerland' ),
			'add_new_item'          => __( 'Add New Archived Post', 'planet4-child-theme-switzerland' ),
			'add_new'               => __( 'New Archived Post', 'planet4-child-theme-switzerland' ),
			'new_item'              => __( 'New JOb', 'planet4-child-theme-switzerland' ),
			'edit_item'             => __( 'Edit Archived Post', 'planet4-child-theme-switzerland' ),
			'update_item'           => __( 'Update Archived Post', 'planet4-child-theme-switzerland' ),
			'view_item'             => __( 'View Archived Post', 'planet4-child-theme-switzerland' ),
			'view_items'            => __( 'View Archived Posts', 'planet4-child-theme-switzerland' ),
			'search_items'          => __( 'Search Archived Post', 'planet4-child-theme-switzerland' ),
			'not_found'             => __( 'Not found', 'planet4-child-theme-switzerland' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'planet4-child-theme-switzerland' ),
			'featured_image'        => __( 'Featured Image', 'planet4-child-theme-switzerland' ),
			'set_featured_image'    => __( 'Set featured image', 'planet4-child-theme-switzerland' ),
			'remove_featured_image' => __( 'Remove featured image', 'planet4-child-theme-switzerland' ),
			'use_featured_image'    => __( 'Use as featured image', 'planet4-child-theme-switzerland' ),
			'insert_into_item'      => __( 'Insert into archived_post', 'planet4-child-theme-switzerland' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'planet4-child-theme-switzerland' ),
			'items_list'            => __( 'Archived Posts list', 'planet4-child-theme-switzerland' ),
			'items_list_navigation' => __( 'Archived Posts list navigation', 'planet4-child-theme-switzerland' ),
			'filter_items_list'     => __( 'Filter archived_posts list', 'planet4-child-theme-switzerland' ),
		);
		$rewrite      = array(
			'slug'       => 'archived_post',
			'with_front' => true,
			'pages'      => false,
			'feeds'      => true,
		);
		$capabilities = array(
			'edit_post'          => 'edit_archived_post',
			'read_post'          => 'read_archived_post',
			'delete_post'        => 'delete_archived_posts',
			'edit_posts'         => 'edit_archived_posts',
			'edit_others_posts'  => 'edit_others_archived_posts',
			'publish_posts'      => 'publish_archived_posts',
			'read_private_posts' => 'read_private_archived_posts',
		);
		$args         = array(
			'label'               => __( 'Archived Post', 'planet4-child-theme-switzerland' ),
			'description'         => __( 'Archived Post postings', 'planet4-child-theme-switzerland' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'menu_icon'           => 'dashicons-archive',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capabilities'        => $capabilities,
		);

		register_post_type( 'gpch_archived_post', $args );
	}

	add_action( 'init', 'p4_child_theme_gpch_custom_post_archived_post', 0 );
}

if ( ! function_exists( 'p4_child_theme_gpch_custom_post_magredirect' ) ) {
	/**
	 * Register Custom Post Type for Archived Post
	 */
	function p4_child_theme_gpch_custom_post_magredirect() {
		$labels       = array(
			'name'                  => _x( 'Magazine Redirects', 'Post Type General Name', 'planet4-child-theme-switzerland' ),
			'singular_name'         => _x( 'Magazine Redirect', 'Post Type Singular Name', 'planet4-child-theme-switzerland' ),
			'menu_name'             => __( 'Magazine Redirects', 'planet4-child-theme-switzerland' ),
			'name_admin_bar'        => __( 'Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'archives'              => __( 'Magazine Redirects Archives', 'planet4-child-theme-switzerland' ),
			'attributes'            => __( 'Magazine Redirect Attributes', 'planet4-child-theme-switzerland' ),
			'parent_item_colon'     => __( 'Parent Magazine Redirect:', 'planet4-child-theme-switzerland' ),
			'all_items'             => __( 'All Magazine Redirects', 'planet4-child-theme-switzerland' ),
			'add_new_item'          => __( 'Add New Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'add_new'               => __( 'New Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'new_item'              => __( 'New Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'edit_item'             => __( 'Edit Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'update_item'           => __( 'Update Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'view_item'             => __( 'View Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'view_items'            => __( 'View Magazine Redirects', 'planet4-child-theme-switzerland' ),
			'search_items'          => __( 'Search Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'not_found'             => __( 'Not found', 'planet4-child-theme-switzerland' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'planet4-child-theme-switzerland' ),
			'featured_image'        => __( 'Featured Image', 'planet4-child-theme-switzerland' ),
			'set_featured_image'    => __( 'Set featured image', 'planet4-child-theme-switzerland' ),
			'remove_featured_image' => __( 'Remove featured image', 'planet4-child-theme-switzerland' ),
			'use_featured_image'    => __( 'Use as featured image', 'planet4-child-theme-switzerland' ),
			'insert_into_item'      => __( 'Insert into magredirect', 'planet4-child-theme-switzerland' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'planet4-child-theme-switzerland' ),
			'items_list'            => __( 'Magazine Redirects list', 'planet4-child-theme-switzerland' ),
			'items_list_navigation' => __( 'Magazine Redirects list navigation', 'planet4-child-theme-switzerland' ),
			'filter_items_list'     => __( 'Filter magredirects list', 'planet4-child-theme-switzerland' ),
		);
		$rewrite      = array(
			'slug'       => 'magredirect',
			'with_front' => true,
			'pages'      => false,
			'feeds'      => true,
		);
		$capabilities = array(
			'edit_post'          => 'edit_magredirect',
			'read_post'          => 'read_magredirect',
			'delete_post'        => 'delete_magredirects',
			'edit_posts'         => 'edit_magredirects',
			'edit_others_posts'  => 'edit_others_magredirects',
			'publish_posts'      => 'publish_magredirects',
			'read_private_posts' => 'read_private_magredirects',
		);
		$args         = array(
			'label'               => __( 'Magazine Redirect', 'planet4-child-theme-switzerland' ),
			'description'         => __( 'Magazine Redirect postings', 'planet4-child-theme-switzerland' ),
			'labels'              => $labels,
			'supports'            => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'revisions',
				'custom-fields',
			),
			'show_in_rest'        => true, // needed  for Gutenberg editor
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'menu_icon'           => 'dashicons-book',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capabilities'        => $capabilities,
		);

		register_post_type( 'gpch_magredirect', $args );
	}

	add_action( 'init', 'p4_child_theme_gpch_custom_post_magredirect' );


	/**
	 * Add a custom template for Magazine Redirect single view
	 */
	function include_gpmagredirect_template( $template_path ) {
		if ( get_post_type() == 'gpch_magredirect' ) {
			if ( is_single() ) {
				$template_path = get_stylesheet_directory() . '/includes/post-templates/gpch-magredirect-single.php';
			}
		}

		return $template_path;
	}

	add_filter( 'template_include', 'include_gpmagredirect_template', 1 );


	/**
	 * Create custom field for Magazine Redirects using ACF
	 */
	function create_gpmagredirect_custom_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ):
			acf_add_local_field_group( array(
				'key'                   => 'group_5cf2025d115b9',
				'title'                 => 'Magazin Redirect',
				'fields'                => array(
					array(
						'key'               => 'field_5cf202661b26b',
						'label'             => __( 'Magazin URL', 'planet4-child-theme-switzerland' ),
						'name'              => 'magazin_url',
						'type'              => 'url',
						'instructions'      => __( 'The URL of the magazine article where the user is redirected.', 'planet4-child-theme-switzerland' ),
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'gpch_magredirect',
						),
					),
				),
				'menu_order'            => - 5,
				'position'              => 'acf_after_title',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
			) );

		endif;
	}

	add_action( 'init', 'create_gpmagredirect_custom_fields' );
}
