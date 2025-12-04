<?php

if ( ! function_exists( 'p4_child_theme_gpch_custom_post_gpch_event' ) ) {
	/**
	 * Register Custom Post Type for Event
	 */
	function gpch_custom_post_gpch_event() {
		$labels       = array(
			'name'                  => _x( 'Events', 'Post Type General Name', 'planet4-child-theme-switzerland' ),
			'singular_name'         => _x( 'Event', 'Post Type Singular Name', 'planet4-child-theme-switzerland' ),
			'menu_name'             => __( 'Events', 'planet4-child-theme-switzerland' ),
			'name_admin_bar'        => __( 'Event', 'planet4-child-theme-switzerland' ),
			'archives'              => __( 'Events Archives', 'planet4-child-theme-switzerland' ),
			'attributes'            => __( 'Event Attributes', 'planet4-child-theme-switzerland' ),
			'parent_item_colon'     => __( 'Parent Event:', 'planet4-child-theme-switzerland' ),
			'all_items'             => __( 'All Events', 'planet4-child-theme-switzerland' ),
			'add_new_item'          => __( 'Add New Event', 'planet4-child-theme-switzerland' ),
			'add_new'               => __( 'New Event', 'planet4-child-theme-switzerland' ),
			'new_item'              => __( 'New Event', 'planet4-child-theme-switzerland' ),
			'edit_item'             => __( 'Edit Event', 'planet4-child-theme-switzerland' ),
			'update_item'           => __( 'Update Event', 'planet4-child-theme-switzerland' ),
			'view_item'             => __( 'View Event', 'planet4-child-theme-switzerland' ),
			'view_items'            => __( 'View Events', 'planet4-child-theme-switzerland' ),
			'search_items'          => __( 'Search Event', 'planet4-child-theme-switzerland' ),
			'not_found'             => __( 'Not found', 'planet4-child-theme-switzerland' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'planet4-child-theme-switzerland' ),
			'featured_image'        => __( 'Featured Image', 'planet4-child-theme-switzerland' ),
			'set_featured_image'    => __( 'Set featured image', 'planet4-child-theme-switzerland' ),
			'remove_featured_image' => __( 'Remove featured image', 'planet4-child-theme-switzerland' ),
			'use_featured_image'    => __( 'Use as featured image', 'planet4-child-theme-switzerland' ),
			'insert_into_item'      => __( 'Insert into Events', 'planet4-child-theme-switzerland' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'planet4-child-theme-switzerland' ),
			'items_list'            => __( 'Events list', 'planet4-child-theme-switzerland' ),
			'items_list_navigation' => __( 'Events list navigation', 'planet4-child-theme-switzerland' ),
			'filter_items_list'     => __( 'Filter Events list', 'planet4-child-theme-switzerland' ),
		);
		$rewrite      = array(
			'slug'       => 'event',
			'with_front' => true,
			'pages'      => false,
			'feeds'      => true,
		);
		$capabilities = array(
			'edit_post'          => 'edit_gpch_event',
			'read_post'          => 'read_gpch_event',
			'delete_post'        => 'delete_gpch_events',
			'edit_posts'         => 'edit_gpch_events',
			'edit_others_posts'  => 'edit_others_gpch_events',
			'publish_posts'      => 'publish_gpch_events',
			'read_private_posts' => 'read_private_gpch_events',
		);
		$args         = array(
			'label'               => __( 'Event', 'planet4-child-theme-switzerland' ),
			'description'         => __( 'Events', 'planet4-child-theme-switzerland' ),
			'labels'              => $labels,
			'supports'            => array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
				'author',
				'excerpt',
				'custom-fields',
			),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 12,
			'menu_icon'           => 'dashicons-calendar',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => $rewrite,
			'capabilities'        => $capabilities,
			'show_in_rest'        => true, // needed  for Gutenberg editor
			'taxonomies'          => array( 'post_tag' ),
		);

		register_post_type( 'gpch_event', $args );
	}

	add_action( 'init', 'gpch_custom_post_gpch_event', 0 );


	/**
	 * Add a custom template for GPCH Events
	 *
	 * @param string $template_path The path to the template of a post of type gpch_event.
	 *
	 * @return mixed|string
	 */
	function gpch_include_gpchevents_template( $template_path ) {
		if ( get_post_type() == 'gpch_event' ) {
			if ( is_single() ) {
				$template_path = get_stylesheet_directory() . '/includes/post-templates/gpch-event-single.php';
			}
		}

		return $template_path;
	}

	add_filter( 'template_include', 'gpch_include_gpchevents_template', 1 );


	/**
	 * Redirect to Event URL if available
	 *
	 * @return void
	 */
	function gpch_events_redirect() {
		if ( get_post_type() == 'gpch_event' ) {
			if ( is_single() ) {
				$url = get_field( 'redirect_url' );

				if ( ! empty( $url ) && filter_var( $url, FILTER_VALIDATE_URL ) !== false ) {
					wp_redirect( $url, 301, 'GPCHEvent' );
				}
			}
		}
	}

	add_filter( 'template_redirect', 'gpch_events_redirect', 1 );


	/**
	 * Create custom field for GPCH Events using ACF
	 */
	function gpch_event_create_custom_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group(
				array(
					'key'                   => 'group_p4_gpch_events',
					'title'                 => 'Events',
					'fields'                => array(
						array(
							'key'               => 'field_p4_gpch_events_date',
							'label'             => 'Event Date',
							'name'              => 'event_date',
							'type'              => 'date_picker',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'display_format'    => 'd. m. Y',
							'return_format'     => 'Y-m-d',
							'first_day'         => 1,
						),
						array(
							'key'               => 'field_p4_gpch_events_start_time',
							'label'             => 'Event Start Time',
							'name'              => 'start_time',
							'type'              => 'time_picker',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'display_format'    => 'H:i',
							'return_format'     => 'H:i',
						),
						array(
							'key'               => 'field_p4_gpch_events_place',
							'label'             => 'Place',
							'name'              => 'place',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_p4_gpch_events_redirect',
							'label'             => 'Redirect URL',
							'name'              => 'redirect_url',
							'type'              => 'url',
							'instructions'      => 'Add a URL to redirect instead of showing the details page of the event.',
							'required'          => 0,
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
								'value'    => 'gpch_event',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'acf_after_title',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
		}
	}

	add_action( 'init', 'gpch_event_create_custom_fields' );
}

if ( ! function_exists( 'p4_child_theme_gpch_custom_post_magredirect' ) ) {
	/**
	 * Register Custom Post Type for Magazine Redirects
	 */
	function gpch_custom_post_magredirect() {
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
				'excerpt',
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
			'taxonomies'          => array( 'post_tag' ),
		);

		register_post_type( 'gpch_magredirect', $args );
	}

	add_action( 'init', 'gpch_custom_post_magredirect' );


	/**
	 * Add a custom template for Magazine Redirect single view
	 *
	 * @param string $template_path The path to the template of a post of type gpch_magredirect.
	 */
	function gpch_include_gpmagredirect_template( $template_path ) {
		if ( get_post_type() == 'gpch_magredirect' ) {
			if ( is_single() ) {
				$template_path = get_stylesheet_directory() . '/includes/post-templates/gpch-magredirect-single.php';
			}
		}

		return $template_path;
	}

	add_filter( 'template_include', 'gpch_include_gpmagredirect_template', 1 );


	/**
	 * Create custom field for Magazine Redirects using ACF
	 */
	function gpch_create_gpmagredirect_custom_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group(
				array(
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
				)
			);
		}
	}

	add_action( 'init', 'gpch_create_gpmagredirect_custom_fields' );
}

/**
 * Create meta box for custom post types
 *
 * Since we want to have the same meta box like P4, we steal a bit ...
 */
function gpch_create_custom_post_type_metabox() {
	if ( function_exists( 'new_cmb2_box' ) ) {
		$prefix = 'p4_';

		$p4_post = new_cmb2_box(
			[
				'id'           => $prefix . 'gpch_custom_post_type_metabox',
				'title'        => __( 'Post Articles Element Fields', 'planet4-child-theme-switzerland' ),
				'object_types' => [ 'gpch_magredirect' ], // at the moment only for Magazine Redirects
			]
		);

		$p4_post->add_field(
			[
				'name' => __( 'Author Override', 'planet4-child-theme-switzerland' ),
				'desc' => __( 'Enter author name if you want to override the author', 'planet4-child-theme-switzerland' ),
				'id'   => $prefix . 'author_override',
				'type' => 'text_medium',
			]
		);

	}
}

add_action( 'cmb2_admin_init', 'gpch_create_custom_post_type_metabox' );
