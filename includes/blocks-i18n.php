<?php
/**
 * Register JS translation loading for the food quiz frontend view script.
 *
 * @return void
 */
function gpch_set_food_quiz_script_translations() {
	if ( is_admin() ) {
		return;
	}

	$handle = 'planet4-child-theme-switzerland-food-quiz-view-script';

	if ( ! wp_script_is( $handle, 'registered' ) ) {
		return;
	}

	wp_set_script_translations(
		$handle,
		'planet4-child-theme-switzerland',
		get_stylesheet_directory() . '/languages'
	);
}

add_action( 'init', 'gpch_set_food_quiz_script_translations', 20 );
