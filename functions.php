<?php

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99 );

function enqueue_child_styles() {
	$css_creation = filectime( get_stylesheet_directory() . '/style.css' );

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

/*
 * Includes
 */

// Classes
require_once( 'classes/GPCH_i_REST_API.php' );
require_once( 'classes/GPCH_Inxmail_API.php' );

// Helpers
require_once ( 'includes/helpers.php' );

// Author pages
require_once ( 'includes/author-pages.php' );

// Child theme options
require_once( 'includes/child-theme-options.php' );

// Custom Post Types: gpch_job & gpch_archived_post & gpch_magredirect
require_once( 'includes/custom-post-types.php' );

// Roles, Usergroups & Capabilities
require_once( 'includes/user-roles.php' );

// Filter available Gutenberg standard blocks
require_once( 'includes/gutenberg-blocks.php' );

// Customize the Gutenberg sidebar
require_once( 'includes/gutenberg-sidebar.php' );

// Customize/extend Gravity Forms
require_once( 'includes/gravity-forms-extensions.php' );

// Custom taxonomy for background articles
require_once( 'includes/background-taxonomy.php' );

// GPCH advanced post settings
require_once( 'includes/advanced-post-settings.php' );

// GPCH CSP headers
require_once( 'includes/csp.php' );

// Hubspot
require_once( 'includes/hubspot.php' );

/**
 * Load Javascript for further Gutenberg customizations
 */
function p4_child_theme_gpch_gutenberg_scripts() {
	wp_enqueue_script(
		'gpch-be-editor-customizations',
		get_stylesheet_directory_uri() . '/admin/js/editor.js',
		// p4gbks_admin_script is the JS that is loaded in planet4-plugin-gutenberg-block:
		// https://github.com/greenpeace/planet4-plugin-gutenberg-blocks/blob/4ae684660c83361f6d5f9d96744362ea7422cc4f/classes/class-loader.php#L296-L302
		// By putting it in the dependency list, we ensure our code gets loaded later so we can overwrite some of it.
		array( 'wp-blocks', 'wp-dom', 'p4gbks_admin_script', 'planet4-blocks-editor-script' ),
		filemtime( get_stylesheet_directory() . '/admin/js/editor.js' ),
		true
	);

	$user  = wp_get_current_user();
	$roles = ( array ) $user->roles;

	$script_params = array(
		'roles'     => $roles,
		'post_type' => get_post_type(),
	);

	wp_localize_script( 'gpch-be-editor-customizations', 'gpchUserData', $script_params );
}

add_action( 'enqueue_block_editor_assets', 'p4_child_theme_gpch_gutenberg_scripts' );


/*
 * Add taxonomy terms as class name to body tag
 */
add_filter( 'body_class', 'p4_child_theme_gpch_add_taxonomy_classes' );

function p4_child_theme_gpch_add_taxonomy_classes( $classes ) {
	if ( is_singular() ) {
		global $post;

		$taxonomy_terms = get_the_terms( $post->ID, 'post_tag' );

		if ( $taxonomy_terms ) {
			foreach ( $taxonomy_terms as $taxonomy_term ) {
				$classes[] = 'tag-' . $taxonomy_term->slug;
			}
		}
	}

	return $classes;
}


/*
 * Add custom styles to Gutenberg editor
 */
function p4_child_theme_gpch_setup() {
	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Disable the color selector
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'disable-custom-gradients' );
	add_theme_support( 'editor-color-palette', [] );

	// Remove custom text sizes for blocks
	add_theme_support( 'disable-custom-font-sizes' );

	// Remove options from the text size dropdown
	add_theme_support( 'editor-font-sizes', [] );

	// Enqueue editor styles.
	add_editor_style( 'admin/css/editor-style.css' );
}

add_action( 'after_setup_theme', 'p4_child_theme_gpch_setup', -9999 );

function p4_child_theme_gpch_enqueue_editor_assets() {
	wp_enqueue_style(
		'gpch-gutenberg-editor-fixes',
		get_stylesheet_directory_uri() . '/admin/css/editor-fixes.css',
		[ 'wp-edit-blocks' ],
		filemtime( plugin_dir_path( __FILE__ ) . 'admin/css/editor-fixes.css' )
	);
}

// Hook into editor only hook
add_action( 'enqueue_block_editor_assets', 'p4_child_theme_gpch_enqueue_editor_assets' );


/*
 * Enqueue Scripts (Frontend)
 */
function p4_child_theme_gpch_scripts() {
	$js = '/js/gpch-child.js';

	wp_enqueue_script( 'gpch-child-theme-js',
		get_stylesheet_directory_uri() . $js,
		array(),
		filemtime( get_stylesheet_directory() . $js ),
		true );

	$child_options = get_option( 'gpch_child_options' );
	if ( key_exists( 'gpch_child_field_ssa_properties', $child_options ) ) {
		$ssa_properties = $child_options['gpch_child_field_ssa_properties'];
	} else {
		$ssa_properties = '';
	}

	$script_params = array(
		'ajaxurl'        => admin_url( 'admin-ajax.php' ),
		'ssa_properties' => $ssa_properties,
	);

	$block_popups_setting = get_field( 'setting_block_popups' );

	if ( $block_popups_setting === true ) {
		$script_params['block_popups'] = 1;
	} else {
		$script_params['block_popups'] = 0;
	}

	wp_localize_script( 'gpch-child-theme-js', 'gpchData', $script_params );
}

add_action( 'wp_enqueue_scripts', 'p4_child_theme_gpch_scripts' );


/*
 * Adds theme support for various things
 */
function p4_child_theme_gpch_theme_support() {
	add_theme_support( 'responsive-embeds' );
}

add_action( 'after_setup_theme', 'p4_child_theme_gpch_theme_support' );


/**
 * @param $tags
 * @param $context
 *
 * @return mixed
 */
function p4_child_theme_gpch_allowed_html_tags( $tags, $context ) {
	if ( 'post' === $context ) {
		$tags['select'] = array(
			'name'          => true,
			'id'            => true,
			'class'         => true,
			'aria-required' => true,
			'aria-invalid'  => true,
			'autocomplete'  => true,
			'autofocus'     => true,
			'disabled'      => true,
			'form'          => true,
			'multiple'      => true,
			'required'      => true,
			'size'          => true,
		);
		$tags['option'] = array(
			'value'    => true,
			'selected' => true,
			'disabled' => true,
			'label'    => true,
		);
	}

	return $tags;
}

add_filter( 'wp_kses_allowed_html', 'p4_child_theme_gpch_allowed_html_tags', 10, 2 );


/**
 * Modify the behavior of tag pages when a redirect is set. The master theme will just load the content of the page,
 * we'll redirect instead.
 *
 * @param $redirect_page
 */
function p4_child_theme_gpch_tag_page_redirect( $redirect_page ) {
	$permalink = get_permalink( $redirect_page );

	if ( $permalink !== false ) {
		wp_safe_redirect( $permalink, 301 );
		exit;
	}
}

add_action( 'p4_action_tag_page_redirect', 'p4_child_theme_gpch_tag_page_redirect' );


/**
 * Change default sort order of pages in Wordpress admin
 */
function gpch_set_post_order_in_admin( $wp_query ) {
	global $pagenow;

	if ( is_admin() && 'edit.php' == $pagenow && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'page' && ! isset( $_GET['orderby'] ) ) {
		$wp_query->set( 'orderby', 'post_modified' );
		$wp_query->set( 'order', 'DESC' );
	}
}

add_filter( 'pre_get_posts', 'gpch_set_post_order_in_admin', 5 );


/**
 * Manipulate the GravityForms menu to display the forms sorted by ID (descending)
 */
function change_media_label() {
	global $menu, $submenu;

	// Change the forms list submenu to include sorting by ID (descending)
	$submenu['gf_edit_forms'][0][2] = 'admin.php?page=gf_edit_forms&orderby=id&order=desc';
}

add_action( 'admin_menu', 'change_media_label', 9999999 );

// Remove Social Warfare meta box settings from pages and posts
// Duplicate functionality and causes a saving bug, see https://tickets.greenpeace.ch/view.php?id=406
add_filter( 'swpmb_meta_boxes', function() {
	return array();
} );


function gpch_enqueue_youtube_api() {
	$id = get_the_ID();
	$hasYTBlock = gpch_has_block_embed_by_provider( 'youtube', $id );

	if ($hasYTBlock) {
		wp_enqueue_script( 'custom-js', 'https://www.youtube.com/iframe_api', [], '', true );
	}
}
add_action( 'wp_enqueue_scripts', 'gpch_enqueue_youtube_api' );


/* Change parameters in embedded Youtube videos. Enables the API used for Analytics */
add_filter( 'planet4_youtube_embed_parameters', function($parametersString){
	parse_str($parametersString, $parameters);

	$parameters = array_merge($parameters, [
		'enablejsapi' => '1',
		'origin' => 'https://' . $_SERVER['SERVER_NAME'],
		'cc_load_policy' => 1,
	]);

	return http_build_query($parameters);
} );


/**
 * Add pages with other status than 'published' to the parents page dropdown when editing a page.
 * Only works for the standard API request. With Elastic search enabled, check additional function gpch_show_drafts_as_parent_pages_elastic.
 *
 * @param array $args
 * @param WP_REST_Request $request
 *
 * @return array
 */
function gpch_show_drafts_as_parent_pages( array $args, WP_REST_Request $request ) {
	if ( $request->get_param( 'context' ) == 'edit' ) {
		$show_statuses = [ 'publish', 'draft', 'private', 'future', 'pending' ];

		foreach ( $show_statuses as $status ) {
			if ( ! in_array( $status, $args['post_status'] ) ) {
				$args['post_status'][] = $status;
			}
		}

		$args['posts_per_page'] = 500; // Default was 100
		$args['cache_results']  = false;

		return $args;
	}

	return $args;
}

add_filter( 'rest_page_query', 'gpch_show_drafts_as_parent_pages', 100, 2 );

/**
 * By default, elasticsearch only indexes posts of status "published". This function adds more statuses to the index so they can be used in various internal functionality.
 * One such use case is adding drafts as parent pages in Gutenberg editor. The results in the search field are provided by ElsaticPress when enables.
 *
 *
 * @param array $args
 * @param WP_REST_Request $request
 *
 * @return array
 */
function gpch_show_drafts_as_parent_pages_elastic( array $status ) {
	return array_unique( array_merge(
		$status,
		[ 'publish', 'draft', 'private', 'future', 'pending' ]
	) );
}

add_filter( 'ep_indexable_post_status', 'gpch_show_drafts_as_parent_pages_elastic' );
