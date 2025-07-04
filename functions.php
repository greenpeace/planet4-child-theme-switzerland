<?php

/**
 * Enqueue the stylesheet of this child theme
 *
 * @return void
 */
function gpch_enqueue_child_styles() {
	$css_creation = filectime( get_stylesheet_directory() . '/style.css' );

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

add_action( 'wp_enqueue_scripts', 'gpch_enqueue_child_styles', 99 );

/*
 * Includes
 */

// Helpers
require_once 'includes/helpers.php';

// GP user ID
require_once 'includes/gp-user-id.php';

// Author pages
require_once 'includes/author-pages.php';

// Author pages
require_once 'includes/business-cards.php';

// Child theme options
require_once 'includes/child-theme-options.php';

// Custom Post Types: gpch_job & gpch_archived_post & gpch_magredirect
require_once 'includes/custom-post-types.php';

// Roles, Usergroups & Capabilities
require_once 'includes/user-roles.php';

// Filter available Gutenberg standard blocks
require_once 'includes/gutenberg-blocks.php';

// Customize/extend Gravity Forms
require_once 'includes/gravity-forms-extensions.php';

// Gravity PDF
require_once 'includes/gravity-pdf.php';

// Custom taxonomy for background articles
require_once 'includes/background-taxonomy.php';

// GPCH advanced post settings
require_once 'includes/advanced-post-settings.php';

// GPCH CSP headers
require_once 'includes/csp.php';

// Hubspot
require_once 'includes/hubspot.php';

// WordPress
require_once 'includes/wordpress.php';


/**
 * Load Javascript for further Gutenberg customizations
 */
function gpch_gutenberg_scripts() {
	wp_enqueue_script(
		'gpch-be-editor-customizations',
		get_stylesheet_directory_uri() . '/admin/js/editor.js',
		// p4gbks_admin_script is the JS that is loaded in planet4-plugin-gutenberg-block:
		// https://github.com/greenpeace/planet4-plugin-gutenberg-blocks/blob/4ae684660c83361f6d5f9d96744362ea7422cc4f/classes/class-loader.php#L296-L302
		// By putting it in the dependency list, we ensure our code gets loaded later so we can overwrite some of it.
		array( 'wp-blocks', 'wp-dom' ),
		filemtime( get_stylesheet_directory() . '/admin/js/editor.js' ),
		true
	);

	$user  = wp_get_current_user();
	$roles = (array) $user->roles;

	$script_params = array(
		'roles'     => $roles,
		'post_type' => get_post_type(),
	);

	wp_localize_script( 'gpch-be-editor-customizations', 'gpchUserData', $script_params );
}

add_action( 'enqueue_block_editor_assets', 'gpch_gutenberg_scripts' );


/**
 * Add taxonomy terms as class name to body tag
 *
 * @param array $classes The classes added to the body tag.
 *
 * @return array $classes
 */
function gpch_add_taxonomy_classes( $classes ) {
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

add_filter( 'body_class', 'gpch_add_taxonomy_classes' );


/**
 * Add custom stylesheet to Gutenberg editor and configure theme support.
 *
 * @return void
 */
function gpch_editor_setup() {
	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Remove custom text sizes for blocks
	add_theme_support( 'disable-custom-font-sizes' );

	// Remove options from the text size dropdown
	add_theme_support( 'editor-font-sizes', [] );

	// Enqueue editor styles.
	add_editor_style( 'admin/css/editor-style.css' );
}

add_action( 'after_setup_theme', 'gpch_editor_setup', -9999 );


/**
 * Enqueue frontend scripts
 *
 * @return void
 */
function gpch_enqueue_scripts() {
	$js = '/js/gpch-child.js';

	wp_enqueue_script(
		'gpch-child-theme-js',
		get_stylesheet_directory_uri() . $js,
		array(),
		filemtime( get_stylesheet_directory() . $js ),
		true
	);

	$script_params = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
	);

	$block_popups_setting = get_field( 'setting_block_popups' );

	if ( $block_popups_setting === true ) {
		$script_params['block_popups'] = 1;
	} else {
		$script_params['block_popups'] = 0;
	}

	wp_localize_script( 'gpch-child-theme-js', 'gpchData', $script_params );
}

add_action( 'wp_enqueue_scripts', 'gpch_enqueue_scripts' );


/**
 * Modify the behavior of tag pages when a redirect is set. The master theme will just load the content of the page,
 * we'll redirect instead.
 *
 * @param string $redirect_page The page URL to redirect to.
 */
function gpch_tag_page_redirect( $redirect_page ) {
	$permalink = get_permalink( $redirect_page );

	if ( $permalink !== false ) {
		wp_safe_redirect( $permalink, 301 );
		exit;
	}
}

add_action( 'p4_action_tag_page_redirect', 'gpch_tag_page_redirect' );


/**
 * Change default sort order of pages in WordPress admin
 *
 * @param WP_Query $wp_query The current WordPress Qqery object.
 *
 * @return void
 */
function gpch_set_post_order_in_admin( $wp_query ) {
	global $pagenow;

	if ( is_admin() && 'edit.php' == $pagenow && array_key_exists( 'post_type', $_GET ) && $_GET['post_type'] == 'page' && ! isset( $_GET['orderby'] ) ) {
		$wp_query->set( 'orderby', 'post_modified' );
		$wp_query->set( 'order', 'DESC' );
	}
}

add_filter( 'pre_get_posts', 'gpch_set_post_order_in_admin', 5 );



// Remove Social Warfare meta box settings from pages and posts
// Duplicate functionality and causes a saving bug, see https://tickets.greenpeace.ch/view.php?id=406
add_filter(
	'swpmb_meta_boxes',
	function () {
		return array();
	}
);


/**
 * Manually enqueue the YouTube API script in pages that have the embed block
 *
 * @return void
 */
function gpch_enqueue_youtube_api() {
	$id         = get_the_ID();
	$has_yt_block = gpch_has_block_embed_by_provider( 'youtube', $id );

	if ( $has_yt_block ) {
		wp_enqueue_script( 'custom-js', 'https://www.youtube.com/iframe_api', [], '1', true );
	}
}

add_action( 'wp_enqueue_scripts', 'gpch_enqueue_youtube_api' );


/**
 * Change parameters in embedded Youtube videos. Enables the API used for Analytics.
 *
 * @param string $parameters_string String of parameters added to the URL of embedded YouTube videos.
 *
 * @return string
 */
function gpch_change_youtube_embed_parameters( $parameters_string ) {
	parse_str( $parameters_string, $parameters );

	$parameters = array_merge(
		$parameters,
		[
			'enablejsapi'    => '1',
			'origin'         => 'https://' . $_SERVER['SERVER_NAME'],
			'cc_load_policy' => 1,
		]
	);

	return http_build_query( $parameters );
}

add_filter( 'planet4_youtube_embed_parameters', 'gpch_change_youtube_embed_parameters' );


/**
 * Add pages with other status than 'published' to the parents page dropdown when editing a page.
 * Only works for the standard API request. With Elastic search enabled, check additional function gpch_show_drafts_as_parent_pages_elastic.
 *
 * @param array           $args Query arguments.
 * @param WP_REST_Request $request The current request.
 *
 * @return array $args
 */
function gpch_show_drafts_as_parent_pages( array $args, WP_REST_Request $request ) {
	if ( $request->get_param( 'context' ) == 'edit' && is_array($args['post_status']) ) {
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
 * @param array $status An array of Status tp include in the query.
 *
 * @return array
 */
function gpch_show_drafts_as_parent_pages_elastic( array $status ) {
	return array_unique(
		array_merge(
			$status,
			[ 'publish', 'draft', 'private', 'future', 'pending' ]
		)
	);
}

add_filter( 'ep_indexable_post_status', 'gpch_show_drafts_as_parent_pages_elastic' );


/**
 * Set the email sending address used for all emails sent through the Planet4 Sendgrid integration
 *
 * @param string $sender_email The email address used as a sender.
 *
 * @return string
 */
function gpch_change_email_sender( $sender_email ) {
	return 'noreply@greenpeace.ch';
}

add_filter( 'planet4_sendgrid_sender', 'gpch_change_email_sender' );


// Disable auto translations in WPML. Might be cause for a bug that changes translated pages back to the original language.
// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedConstantFound
define( 'WPML_TRANSLATION_AUTO_UPDATE_ENABLED', false );
