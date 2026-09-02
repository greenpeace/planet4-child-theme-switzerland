<?php

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
	if ( get_post_type() === 'gpch_event' ) {
		if ( is_single() ) {
			$template_path = get_stylesheet_directory() . '/includes/post-templates/gpch-event-single.php';
		}
	}

	return $template_path;
}

add_filter( 'template_include', 'gpch_include_gpchevents_template', 1 );


/**
 * Register Custom Post Type for Magazine Article
 */
function gpch_custom_post_gpch_magazinearticle() {
	$labels = array(
		'name'                  => _x( 'Magazine Articles', 'Post Type General Name', 'planet4-child-theme-switzerland' ),
		'singular_name'         => _x( 'Magazine Article', 'Post Type Singular Name', 'planet4-child-theme-switzerland' ),
		'menu_name'             => __( 'Magazine Articles', 'planet4-child-theme-switzerland' ),
		'name_admin_bar'        => __( 'Magazine Article', 'planet4-child-theme-switzerland' ),
		'archives'              => __( 'Magazine Article Archives', 'planet4-child-theme-switzerland' ),
		'attributes'            => __( 'Magazine Article Attributes', 'planet4-child-theme-switzerland' ),
		'all_items'             => __( 'All Magazine Articles', 'planet4-child-theme-switzerland' ),
		'add_new_item'          => __( 'Add New Magazine Article', 'planet4-child-theme-switzerland' ),
		'add_new'               => __( 'New Magazine Article', 'planet4-child-theme-switzerland' ),
		'new_item'              => __( 'New Magazine Article', 'planet4-child-theme-switzerland' ),
		'edit_item'             => __( 'Edit Magazine Article', 'planet4-child-theme-switzerland' ),
		'update_item'           => __( 'Update Magazine Article', 'planet4-child-theme-switzerland' ),
		'view_item'             => __( 'View Magazine Article', 'planet4-child-theme-switzerland' ),
		'view_items'            => __( 'View Magazine Articles', 'planet4-child-theme-switzerland' ),
		'search_items'          => __( 'Search Magazine Article', 'planet4-child-theme-switzerland' ),
		'not_found'             => __( 'Not found', 'planet4-child-theme-switzerland' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'planet4-child-theme-switzerland' ),
		'featured_image'        => __( 'Title Image', 'planet4-child-theme-switzerland' ),
		'set_featured_image'    => __( 'Set title image', 'planet4-child-theme-switzerland' ),
		'remove_featured_image' => __( 'Remove title image', 'planet4-child-theme-switzerland' ),
		'use_featured_image'    => __( 'Use as title image', 'planet4-child-theme-switzerland' ),
		'insert_into_item'      => __( 'Insert into Magazine Article', 'planet4-child-theme-switzerland' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'planet4-child-theme-switzerland' ),
		'items_list'            => __( 'Magazine Articles list', 'planet4-child-theme-switzerland' ),
		'items_list_navigation' => __( 'Magazine Articles list navigation', 'planet4-child-theme-switzerland' ),
		'filter_items_list'     => __( 'Filter Magazine Articles list', 'planet4-child-theme-switzerland' ),
	);

	$args = array(
		'label'               => __( 'Magazine Article', 'planet4-child-theme-switzerland' ),
		'description'         => __( 'Magazine Articles', 'planet4-child-theme-switzerland' ),
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
		'taxonomies'          => array( 'gpch_magazine_issue', 'gpch_magazine_section', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 13,
		'menu_icon'           => 'dashicons-media-document',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => array(
			'slug'       => 'magazine-article',
			'with_front' => true,
			'pages'      => false,
			'feeds'      => true,
		),
		'show_in_rest'        => true, // needed for Gutenberg editor
	);

	register_post_type( 'gpch_magazinearticle', $args );
}

add_action( 'init', 'gpch_custom_post_gpch_magazinearticle', 0 );


/**
 * Add a custom template for GPCH Magazine Articles
 *
 * @param string $template_path The path to the template of a post of type gpch_magazinearticle.
 *
 * @return mixed|string
 */
function gpch_include_gpchmagazinearticle_template( $template_path ) {
	if ( get_post_type() === 'gpch_magazinearticle' ) {
		if ( is_single() ) {
			$template_path = get_stylesheet_directory() . '/includes/post-templates/gpch-magazinearticle-single.php';
		}
	}

	return $template_path;
}

add_filter( 'template_include', 'gpch_include_gpchmagazinearticle_template', 1 );


/**
 * Prevent publishing a Magazine Article without a title image (classic editor / quick edit / bulk edit).
 *
 * @param array $data    An array of slashed post data.
 * @param array $postarr An array of sanitized, but otherwise unmodified post data.
 *
 * @return array
 */
function gpch_magazinearticle_require_title_image( $data, $postarr ) {
	if ( $data['post_type'] !== 'gpch_magazinearticle' || $data['post_status'] !== 'publish' ) {
		return $data;
	}

	$post_id = $postarr['ID'] ?? 0;

	if ( ! $post_id || ! has_post_thumbnail( $post_id ) ) {
		$data['post_status'] = 'draft';
		add_filter( 'redirect_post_location', 'gpch_magazinearticle_missing_title_image_redirect' );
	}

	return $data;
}

add_filter( 'wp_insert_post_data', 'gpch_magazinearticle_require_title_image', 10, 2 );


/**
 * Add a query arg to the post edit redirect so an admin notice can be shown.
 *
 * @param string $location The destination URL.
 *
 * @return string
 */
function gpch_magazinearticle_missing_title_image_redirect( $location ) {
	remove_filter( 'redirect_post_location', 'gpch_magazinearticle_missing_title_image_redirect' );

	return add_query_arg( 'gpch_missing_title_image', '1', $location );
}


/**
 * Show an admin notice when a Magazine Article was kept as draft due to a missing title image.
 *
 * @return void
 */
function gpch_magazinearticle_missing_title_image_notice() {
	if ( isset( $_GET['gpch_missing_title_image'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="notice notice-error is-dismissible"><p>' .
			esc_html__( 'This Magazine Article was saved as a draft because it has no title image. Please add one before publishing.', 'planet4-child-theme-switzerland' ) .
			'</p></div>';
	}
}

add_action( 'admin_notices', 'gpch_magazinearticle_missing_title_image_notice' );


/**
 * Prevent publishing a Magazine Article without a title image via the block editor (REST API).
 *
 * @param stdClass        $prepared_post An object representing a single post prepared for inserting/updating the database.
 * @param WP_REST_Request $request       The request object.
 *
 * @return stdClass|WP_Error
 */
function gpch_magazinearticle_require_title_image_rest( $prepared_post, $request ) {
	if ( ( $prepared_post->post_status ?? '' ) !== 'publish' ) {
		return $prepared_post;
	}

	$featured_media = $request->get_param( 'featured_media' );

	if ( $featured_media === null && ! empty( $prepared_post->ID ) ) {
		$featured_media = get_post_thumbnail_id( $prepared_post->ID );
	}

	if ( empty( $featured_media ) ) {
		return new WP_Error(
			'gpch_magazinearticle_missing_title_image',
			__( 'A Magazine Article needs a title image before it can be published.', 'planet4-child-theme-switzerland' ),
			array( 'status' => 400 )
		);
	}

	return $prepared_post;
}

add_filter( 'rest_pre_insert_gpch_magazinearticle', 'gpch_magazinearticle_require_title_image_rest', 10, 2 );


/**
 * Redirect to Event URL if available
 *
 * @return void
 */
function gpch_events_redirect() {
	if ( get_post_type() === 'gpch_event' ) {
		if ( is_single() ) {
			$url = get_field( 'redirect_url' );

			if ( ! empty( $url ) && filter_var( $url, FILTER_VALIDATE_URL ) !== false ) {
				/* phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect */
				wp_redirect( $url, 301, 'GPCHEvent' );
				exit;
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
						'key'               => 'field_p4_gpch_events_end_date',
						'label'             => 'Event End Date',
						'name'              => 'event_end_date',
						'type'              => 'date_picker',
						'instructions'      => 'Optional. Leave empty for one-day events.',
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
							'class' => 'hidden visually-hidden', // This is an old field that we keep for backwards compatibility.
							'id'    => '',
						),
						'display_format'    => 'H:i',
						'return_format'     => 'H:i',
					),
					array(
						'key'               => 'field_p4_gpch_events_start_time_freeform',
						'label'             => 'Event Start Time',
						'name'              => 'start_time_freeform',
						'type'              => 'text',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
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




/**
 * Create meta box for custom post types
 *
 * Uses the post meta boxes of Planet4 in custom post types
 */
function gpch_create_custom_post_type_metabox() {
	if ( function_exists( 'new_cmb2_box' ) ) {
		$prefix = 'p4_';

		$p4_post = new_cmb2_box(
			[
				'id'           => $prefix . 'gpch_custom_post_type_metabox',
				'title'        => __( 'Post Articles Element Fields', 'planet4-child-theme-switzerland' ),
				'object_types' => [ 'gpch_event' ], // at the moment only for Events
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
