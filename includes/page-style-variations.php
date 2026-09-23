<?php
/**
 * Native "Ocean" page style, selectable via the core Page Attributes Template dropdown.
 */

/**
 * Register a virtual "Ocean" template so it appears in the native Template dropdown.
 *
 * No matching file exists on purpose: WordPress falls back to the normal page
 * template at render time while still persisting the choice in `_wp_page_template`.
 *
 * @param array $templates Existing page templates, keyed by file path.
 *
 * @return array
 */
function gpch_register_ocean_page_template( $templates ) {
	$templates['ocean-style.php'] = __( 'Ocean', 'planet4-child-theme-switzerland' );

	return $templates;
}

add_filter( 'theme_page_templates', 'gpch_register_ocean_page_template' );

/**
 * Add a body class when the "Ocean" template is selected for the current page.
 *
 * @param array $classes The classes added to the body tag.
 *
 * @return array $classes
 */
function gpch_add_ocean_page_body_class( $classes ) {
	if ( is_page() && get_page_template_slug( get_the_ID() ) === 'ocean-style.php' ) {
		$classes[] = 'page-style-ocean';
	}

	return $classes;
}

add_filter( 'body_class', 'gpch_add_ocean_page_body_class' );
