<?php

include "business-cards/field-definitions.php";
include "business-cards/backend-page.php";
include "business-cards/frontend.php";
include "business-cards/vcard.php";

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
function get_user_by_business_card_id($business_card_id) {
	// Search for a user based on the ACF field "profile_url"
	$args = array(
		'meta_key' => 'business_card_id',
		'meta_value' => $business_card_id,
		'number' => 1,
		'fields' => 'all',
	);

	$users = get_users($args);

	// Return the first user if found, otherwise return null
	return !empty($users) ? $users[0] : null;
}


/**
 * Hooks into saving a profile page in the WordPress admin.
 * If the ACF field 'business_card_id' is empty, generates a 12-character random string and saves it to the field.
 *
 * @param int $user_id The ID of the user being saved.
 * @return void
 */
function gpch_generate_business_card_id_on_save($user_id)
{
	// Only run in WP admin
	if (!is_admin()) {
		return;
	}

	// Check if the 'business_card_id' field exists for the user and is not empty
	$business_card_id = get_field('business_card_id', 'user_' . $user_id); // ACF function to get the field

	if (empty($business_card_id)) {
		// Generate a random 12-character string
		$random_id = wp_generate_password(12, false, false);

		update_field('business_card_id', $random_id, 'user_' . $user_id); // ACF function to update the field
	}
}

add_action('profile_update', 'gpch_generate_business_card_id_on_save');
add_action('user_register', 'gpch_generate_business_card_id_on_save');


/**
 * Hides the color scheme, visual editor setting, and website field on the user profile page in the WordPress admin.
 *
 * @param WP_User $user The currently edited user info.
 * @return void
 */
/*
function gpch_hide_user_profile_fields($user)
{
	$screen = get_current_screen();

	// Only run the rest of the function on the profile page URL
	if (is_admin() && $screen->id == 'profile') {
		?>
		<style>
			.business-cards-table {
				display: inline-block;
				background-color: #fff;
				padding: 1em;
				border: solid 1px #6c0;
				border-radius: 2px;
			}
			.business-cards-table .acf-hl.acf-tab-group {
				width: 100%;
			}

			#acf-field_6772b21f60d0c {

			}
		</style>
		<script>
			// Add classes
			document.addEventListener("DOMContentLoaded", function() {
				acfFormElement = document.getElementById('acf-form-data');

				acfFormElement.nextElementSibling.classList.add('business-cards-title');
				acfFormElement.nextElementSibling.nextElementSibling.classList.add('business-cards-table');

				// Disable some form fields
				document.getElementById('acf-field_6772b21f60d0c').disabled = true;
				document.getElementById('acf-field_6778011d05453').disabled = true;
			});
		</script>
		<?php
	}
}

add_action('admin_head', 'gpch_hide_user_profile_fields');

*/

