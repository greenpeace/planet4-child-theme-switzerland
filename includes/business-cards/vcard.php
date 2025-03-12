<?php

/**
 * Handles the display of a custom virtual page based on the 'business_card_id' query variable.
 *
 * @return void
 */
function gpch_business_card_vcard_virtual_page() {
	$business_card_id = get_query_var( 'business_card_id' );
	$pagename         = get_query_var( 'pagename' );

	// Check if the query variable is set
	if ( $business_card_id && $pagename === 'business-card-vcard' ) {
		// Generate the vCard
		gpch_generate_vcard( $business_card_id );
	}
}
add_action( 'template_redirect', 'gpch_business_card_vcard_virtual_page' );


/**
 * Generates and outputs a vCard based on the provided business card ID.
 *
 * This function retrieves user details using the associated business card ID, formats
 * the information into the vCard standard, and serves it as a downloadable file.
 *
 * @param int $business_card_id The unique identifier for a business card to generate the vCard for.
 *
 * @return void This function does not return a value. Instead, it directly outputs the vCard content and terminates script execution.
 */
function gpch_generate_vcard( $business_card_id ) {
	$user = gpch_get_user_by_business_card_id( $business_card_id );

	if ( $user && ! is_wp_error( $user ) ) {

		$user_acf_fields = get_fields( 'user_' . $user->ID ); // ACF function to get all fields

		if ( $user_acf_fields['enable_business_card'][0] === 'enabled' ) {
			$filename = esc_html( trim( $user_acf_fields['bc_name'] . '.vcf' ) );

			// Set the content type for vCard
			header( 'Content-Type: text/vcard' );
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			header( "Content-Disposition: attachment; filename={$filename}" );
			http_response_code( 200 );

			// Begin vCard content
			$vcard  = "BEGIN:VCARD\r\n";
			$vcard .= "VERSION:3.0\r\n";
			$vcard .= 'FN:' . esc_html( $user_acf_fields['bc_name'] ) . "\r\n";

			if ( ! empty( $user_acf_fields['bc_job_title'] ) ) {
				$vcard .= 'TITLE:' . esc_html( $user_acf_fields['bc_job_title'] ) . "\r\n";
			}

			if ( ! empty( $user_acf_fields['bc_organisation'] ) ) {
				$vcard .= 'ORG:' . esc_html( $user_acf_fields['bc_organisation'] ) . "\r\n";
			}

			if ( ! empty( $user_acf_fields['bc_phone'] ) ) {
				$vcard .= 'TEL;TYPE=WORK:' . esc_html( $user_acf_fields['bc_phone'] ) . "\r\n";
			}

			if ( ! empty( $user_acf_fields['bc_mobile'] ) ) {
				$vcard .= 'TEL;TYPE=CELL:' . esc_html( $user_acf_fields['bc_mobile'] ) . "\r\n";
			}

			if ( ! empty( $user_acf_fields['bc_email'] ) ) {
				$vcard .= 'EMAIL;TYPE=WORK:' . esc_html( $user_acf_fields['bc_email'] ) . "\r\n";
			}

			$vcard .= "URL:https://www.greenpeace.ch\r\n";

			if ( ! empty( $user_acf_fields['bc_linkedin'] ) ) {
				$vcard .= 'URL:' . esc_html( $user_acf_fields['bc_linkedin'] ) . "\r\n";
			}
			if ( ! empty( $user_acf_fields['bc_bluesky'] ) ) {
				$vcard .= 'URL:' . esc_html( $user_acf_fields['bc_bluesky'] ) . "\r\n";
			}
			if ( ! empty( $user_acf_fields['bc_facebook'] ) ) {
				$vcard .= 'URL:' . esc_html( $user_acf_fields['bc_facebook'] ) . "\r\n";
			}
			if ( ! empty( $user_acf_fields['bc_instagram'] ) ) {
				$vcard .= 'URL:' . esc_html( $user_acf_fields['bc_instagram'] ) . "\r\n";
			}

			// Add a logo to the vCard
			$logo_url = 'https://files.greenpeace.ch/logo/Greenpeace_Logo_Green_RGB.png';
			if ( ! empty( $logo_url ) ) {
				$vcard .= 'LOGO;VALUE=URL:' . esc_url( $logo_url ) . "\r\n";
			}

			// Add a photo  to the vCard
			if ( ! empty( $user_acf_fields['bc_profile_picture'] ) ) {
				$vcard .= 'PHOTO;VALUE=URL:' . esc_url( $user_acf_fields['bc_profile_picture'] ) . "\r\n";
			}

			$vcard .= "END:VCARD\r\n";

			// Output the vCard content
			echo $vcard;

			// Stop further script execution after outputting the vCard
			exit;
		}
	}

	// If we can't return a card, return 404
	global $wp_query;
	$wp_query->set_404();
}
