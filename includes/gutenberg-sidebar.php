<?php

/**
 * Remove comments ("Discussion") and trackbacks from sidebar.
 */
function p4_child_theme_gpch_post_editing() {
	//remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'trackbacks' );

	remove_post_type_support( 'page', 'comments' );
	remove_post_type_support( 'page', 'trackbacks' );
}

add_action( 'init', 'p4_child_theme_gpch_post_editing' );


/**
 * Adds the Planet4 restricted tag box to our own custom post types
 */
function gpch_add_restricted_tags_box() {
	// Get the class that contains the function to print the custom menu
	$context = Timber::get_context();
	$site    = $context['site'];

	add_meta_box(
		'restricted_tags_box',
		__( 'Tags', 'planet4-master-theme-backend' ),
		[ $site, 'print_restricted_tags_box' ],
		[ 'gpch_event', 'gpch_magredirect', 'gpch_archived_post' ],
		'side'
	);
}

add_action( 'admin_menu', 'gpch_add_restricted_tags_box' );
