<?php

require 'business-cards/field-definitions.php';
require 'business-cards/backend-page.php';
require 'business-cards/frontend.php';
require 'business-cards/vcard.php';

/**
 * Retrieves a user by their business card ID.
 *
 * Searches for a user based on the ACF field "business_card_id" that matches
 * the provided business card ID. Returns the first matching user if found,
 * otherwise returns null.
 *
 * @param string $business_card_id The unique identifier of the business card.
 * @return WP_User|null The user object if a match is found, otherwise null.
 */
function gpch_get_user_by_business_card_id( $business_card_id ) {
	// Search for a user based on the ACF field "profile_url"
	$args = array(
		'meta_key'   => 'business_card_id',
		'meta_value' => $business_card_id,
		'number'     => 1,
		'fields'     => 'all',
	);

	$users = get_users( $args );

	// Return the first user if found, otherwise return null
	return ! empty( $users ) ? $users[0] : null;
}


/**
 * Checks if the business card feature is enabled for a user by their business card ID.
 *
 * @param string $business_card_id The unique identifier of the business card.
 * @return bool True if the business card is enabled, false otherwise.
 */
function gpch_get_is_business_card_enabled_by_id( $business_card_id ) {
	$user = gpch_get_user_by_business_card_id( $business_card_id );

	if ( $user ) {
		$bc_is_enabled_field = get_field( 'enable_business_card', 'user_' . $user->ID );

		if ( $bc_is_enabled_field && $bc_is_enabled_field[0] === 'enabled' ) {
			return true;
		}
	}

	return false;
}


/**
 * Hooks into saving a profile page in the WordPress admin.
 * If the ACF field 'business_card_id' is empty, generates a 12-character random string and saves it to the field.
 *
 * @param int $user_id The ID of the user being saved.
 * @return void
 */
function gpch_generate_business_card_id_on_save( $user_id ) {
	// Only run in WP admin
	if ( ! is_admin() ) {
		return;
	}

	// Check if the 'business_card_id' field exists for the user and is not empty
	$business_card_id = get_field( 'business_card_id', 'user_' . $user_id ); // ACF function to get the field

	if ( empty( $business_card_id ) ) {
		// Generate a random 12-character string
		$random_id = wp_generate_password( 12, false, false );

		update_field( 'business_card_id', $random_id, 'user_' . $user_id ); // ACF function to update the field
	}
}

add_action( 'profile_update', 'gpch_generate_business_card_id_on_save' );
add_action( 'user_register', 'gpch_generate_business_card_id_on_save' );
