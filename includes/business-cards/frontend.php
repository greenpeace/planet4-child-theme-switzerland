<?php

use Timber\Timber;

/**
 * Adds custom rewrite rules for business card URLs.
 *
 * @return void
 */
function gpch_add_business_card_rewrite_rules() {
	add_rewrite_rule(
		'business-card/([a-zA-Z0-9]*)/?$',
		'index.php?pagename=business-card&business_card_id=$matches[1]',
		'top'
	);
	add_rewrite_rule(
		'business-card/vcard/([a-zA-Z0-9]*)/?$',
		'index.php?pagename=business-card-vcard&business_card_id=$matches[1]',
		'top'
	);
}
add_action( 'init', 'gpch_add_business_card_rewrite_rules' );


/**
 * Adds custom query vars for business card processing.
 *
 * @param array $query_vars An array of existing query variables.
 * @return array The modified array of query variables.
 */
function gpch_business_card_query_vars( $query_vars ) {
	$query_vars[] = 'business_card_id';
	return $query_vars;
}
add_filter( 'query_vars', 'gpch_business_card_query_vars' );


/**
 * Handles virtual page rendering for business card URLs.
 *
 * @param string $template The path to the current template being used.
 * @return string The path to the appropriate template file for the virtual page or the original template.
 */
function gpch_business_card_virtual_page( $template ) {
	$business_card_id = get_query_var( 'business_card_id' );
	$pagename         = get_query_var( 'pagename' );

	// Check if the query variable is set
	if ( $business_card_id && $pagename === 'business-card' ) {
		$is_enabled = gpch_get_is_business_card_enabled_by_id( $business_card_id );

		if ( $is_enabled ) {
			status_header( 200 );
			add_filter( 'wp_robots', 'wp_robots_no_robots' );

			// Load the page template
			return get_stylesheet_directory() . '/includes/business-cards/template.php';
		}
	}

	return $template;
}
add_action( 'template_include', 'gpch_business_card_virtual_page' );


/**
 * Modifies the page title for a business card page.
 *
 * @param string $title The original page title.
 * @return string The modified page title if a valid business card ID and associated user are found; otherwise, the original title.
 */
function gpch_business_card_change_page_title( $title ) {
	$business_card_id = get_query_var( 'business_card_id' );

	if ( $business_card_id ) {
		$is_enabled = gpch_get_is_business_card_enabled_by_id( $business_card_id );

		if ( $is_enabled ) {
			$user = gpch_get_user_by_business_card_id( $business_card_id );

			// Check if a valid user object is retrieved
			if ( $user && ! is_wp_error( $user ) ) {
				$bc_name = get_field( 'bc_name', 'user_' . $user->ID );

				return $bc_name;
			}
		}
	}

	return $title;
}
add_filter( 'wp_title', 'gpch_business_card_change_page_title', 100 );
