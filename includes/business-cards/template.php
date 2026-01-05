<?php
/**
 * The template for displaying business cards.
 */

use P4\MasterTheme\Context;
use Timber\Timber;

/* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound */
$timber_context = Timber::context();

$gpch_business_card_id = get_query_var( 'business_card_id' );

if ( $gpch_business_card_id ) {
	$gpch_bc_user = gpch_get_user_by_business_card_id( $gpch_business_card_id );
	if ( $gpch_bc_user && ! is_wp_error( $gpch_bc_user ) ) {
		// Get all ACF fields for the user
		$gpch_user_acf_fields = get_fields( 'user_' . $gpch_bc_user->ID ); // ACF function to get all fields

		// vCard file link
		$timber_context['vcard_link'] = get_site_url() . '/business-card/vcard/' . $gpch_user_acf_fields['business_card_id']; /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound */

		// Render the Twig template
		return Timber::render( 'templates/business-card.twig', $timber_context );
	}
}

// If the above doesn't work, show a 404 error
global $wp_query;
$wp_query->set_404();
status_header( 404 );
get_template_part( 404 );
exit();
