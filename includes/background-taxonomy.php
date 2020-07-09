<?php
function gpch_register_taxonomy_article_type() {
	$labels = [
		'name'              => _x( 'Background Article Type', 'taxonomy general name', 'planet4-child-theme-switzerland' ),
		'singular_name'     => _x( 'Background Article Type', 'taxonomy singular name', 'planet4-child-theme-switzerland' ),
		'search_items'      => __( 'Search Background Article Types', 'planet4-child-theme-switzerland' ),
		'all_items'         => __( 'All Background Article Types', 'planet4-child-theme-switzerland' ),
		'parent_item'       => __( 'Parent Background Article Type', 'planet4-child-theme-switzerland' ),
		'parent_item_colon' => __( 'Parent Background Article Type:', 'planet4-child-theme-switzerland' ),
		'edit_item'         => __( 'Edit Background Article Type', 'planet4-child-theme-switzerland' ),
		'update_item'       => __( 'Update Background Article Type', 'planet4-child-theme-switzerland' ),
		'add_new_item'      => __( 'Add New Background Article Type', 'planet4-child-theme-switzerland' ),
		'new_item_name'     => __( 'New Background Article Type Name', 'planet4-child-theme-switzerland' ),
		'menu_name'         => __( 'Background Article Type', 'planet4-child-theme-switzerland' ),
	];
	$args   = [
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'article-type' ],
		'show_in_rest'      => true,
	];
	register_taxonomy( 'gpch-article-type', [ 'post' ], $args );
}

add_action( 'init', 'gpch_register_taxonomy_article_type' );
