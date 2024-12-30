<?php

/**
 * Adds custom rewrite rules for business card URLs.
 *
 * @return void
 */
function gpch_add_business_card_rewrite_rules()
{
	add_rewrite_rule(
		'business-card/([a-zA-Z0-9]*)/?$',
		'index.php?pagename=business-card&business_card_id=$matches[1]',
		'top');
}
add_action('init', 'gpch_add_business_card_rewrite_rules');


/**
 * Adds custom query vars for business card processing.
 *
 * @param array $query_vars An array of existing query variables.
 * @return array The modified array of query variables.
 */
function gpch_business_card_query_vars($query_vars)
{
	$query_vars[] = 'business_card_id';
	return $query_vars;
}
add_filter('query_vars', 'gpch_business_card_query_vars');

/**
 * Handles the display of a custom virtual page based on the 'business_card_id' query variable.
 *
 * @return void
 */
function custom_virtual_page() {
	$business_card_id = get_query_var('business_card_id');

	// Check if the query variable is set
	if ($business_card_id) {
		// Load the default page template
		include get_template_directory() . '/page.php';

		// Stop further execution to prevent loading other templates
		exit;
	}
}
add_action('template_redirect', 'custom_virtual_page');

/**
 * Outputs the content for a business card page.
 *
 * @param string $content The original page content.
 * @return string The modified content with custom business card details or the original content for non-business card pages.
 */
function custom_business_card_content($content) {
	$business_card_id = get_query_var('business_card_id');

	if ($business_card_id) {
		$context = [
			'business_card_id' => $business_card_id,
			'user' => [],
		];

		$user = get_user_by_business_card_id($business_card_id);

		// Check if a valid user object is retrieved
		if ($user && !is_wp_error($user)) {
			// Get all ACF fields for the user
			$user_acf_fields = get_fields('user_' . $user->ID); // ACF function to get all fields

			// Add user ACF fields starting with "bc_" to the context
			if (!empty($user_acf_fields)) {
				foreach ($user_acf_fields as $field_key => $field_value) {
					if (strpos($field_key, 'bc_') === 0) {
						$context['user'][$field_key] = $field_value;
					}
				}
			}
		}

		// Render the Twig template
		return Timber::compile('templates/business-card.twig', $context);

		// Replace default content
		return $custom_content;
	}

	// Return normal content for other pages
	return $content;

}
add_filter('the_content', 'custom_business_card_content');


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

