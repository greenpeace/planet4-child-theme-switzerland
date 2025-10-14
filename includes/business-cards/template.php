<?php

/**
 * The template for displaying business cards.
 */

use P4\MasterTheme\Context;
use Timber\Timber;

$context = Timber::context();

$business_card_id = get_query_var( 'business_card_id' );

if ( $business_card_id ) {
	$user = gpch_get_user_by_business_card_id( $business_card_id );
	if ( $user && ! is_wp_error( $user ) ) {
		// Get all ACF fields for the user
		$user_acf_fields = get_fields( 'user_' . $user->ID ); // ACF function to get all fields

		// vCard file link
		$context['vcard_link'] = get_site_url() . '/business-card/vcard/' . $user_acf_fields['business_card_id'];

		// Render the Twig template
		return Timber::render( 'templates/business-card.twig', $context );
	}
}

// If the above doesn't work, show a 404 error
global $wp_query;
$wp_query->set_404();
status_header( 404 );
get_template_part( 404 );
exit();
