<?php


add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99 );

function enqueue_child_styles() {
	$css_creation = filectime( get_stylesheet_directory() . '/style.css' );

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

/*
 * Includes
 */

// Custom Post Types: Jobs & Archive
require_once( 'includes/custom-post-types.php' );

// Roles, Usergroups & Capabilities
require_once( 'includes/user-roles.php' );

// Filter available Gutenberg standard blocks
require_once( 'includes/gutenberg-blocks.php' );

// Color Palette for Gutenberg
require_once( 'includes/gutenberg-palettes.php' );


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


