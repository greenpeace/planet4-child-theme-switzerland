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
require_once('includes/custom-post-types.php');

// Roles, Usergroups & Capabilities
require_once('includes/user-roles.php');

// Filter available Gutenberg standard blocks
require_once ('includes/gutenberg-blocks.php');

// Color Palette for Gutenberg
require_once ('includes/gutenberg-palettes.php');


