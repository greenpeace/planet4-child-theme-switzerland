<?php


add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99 );

function enqueue_child_styles() {
	$css_creation = filectime( get_stylesheet_directory() . '/style.css' );

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

/*
 * Includes
 */

// Custom Post Types: gpch_job & gpch_archived_post & gpch_magredirect
require_once( 'includes/custom-post-types.php' );

// Roles, Usergroups & Capabilities
require_once( 'includes/user-roles.php' );

// Filter available Gutenberg standard blocks
require_once( 'includes/gutenberg-blocks.php' );

// Color Palette for Gutenberg
require_once( 'includes/gutenberg-palettes.php' );

// Customize the Gutenberg sidebar
require_once( 'includes/gutenberg-sidebar.php' );

// Customize/extend Gravity Forms
require_once( 'includes/gravity-forms-extensions.php' );

// Custom taxonomy for background articles
require_once( 'includes/background-taxonomy.php' );


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
		array( 'wp-blocks', 'wp-dom', 'p4gbks_admin_script' ),
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

	// Enqueue editor styles.
	add_editor_style( 'admin/css/editor-style.css' );
}

add_action( 'after_setup_theme', 'p4_child_theme_gpch_setup' );


/*
 * Enqueue Scripts (Frontend)
 */
function p4_child_theme_gpch_scripts() {
	$js = '/js/gpch-child.js';

	wp_enqueue_script( 'gpch-child-theme-js',
		get_stylesheet_directory_uri() . $js,
		array( 'jquery' ),
		filemtime( get_stylesheet_directory() . $js ),
		true );
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
function p4_child_theme_gpch_tag_page_redirect ($redirect_page) {
	$permalink = get_permalink($redirect_page);

	if ($permalink !== false) {
		wp_safe_redirect($permalink, 301);
		exit;
	}
}

add_action('p4_action_tag_page_redirect', 'p4_child_theme_gpch_tag_page_redirect');


/**
 * Change default sort order of pages in Wordpress admin
 */
function gpch_set_post_order_in_admin( $wp_query ) {
	global $pagenow;

	if ( is_admin() && 'edit.php' == $pagenow && $_GET['post_type'] == 'page' && ! isset( $_GET['orderby'] ) ) {
		$wp_query->set( 'orderby', 'post_modified' );
		$wp_query->set( 'order', 'DESC' );
	}
}
add_filter( 'pre_get_posts', 'gpch_set_post_order_in_admin', 5 );


/**
 * Manipulate the GravityForms menu to display the forms sorted by ID (descending)
 */
function change_media_label(){
	global $menu, $submenu;

	// Change the forms list submenu to include sorting by ID (descending)
	$submenu['gf_edit_forms'][0][2] = 'admin.php?page=gf_edit_forms&orderby=id&order=desc';
}
add_action( 'admin_menu', 'change_media_label',  9999999);

