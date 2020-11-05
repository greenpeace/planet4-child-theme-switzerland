<?php

namespace Greenpeace\Planet4GPCHChild;

use TheIconic\Tracking\GoogleAnalytics\Analytics;

class GoogleAnalytics {
	public function __construct() {
	}

	static function generateClientId() {
		// Random 10 digits integer
		$random = mt_rand( pow( 10, 9 ), pow( 10, 10 ) - 1 );

		// Use the same format Google Analytics uses for ClientIds
		return $random . "." . time();
	}

	static function sendFormConversion( $entry, $form ) {
		// Start gathering all of the needed data
		$data          = array();
		$child_options = get_option( 'gpch_child_options' );

		// Custom dimension 2 Internal Traffic Cookie
		if ( key_exists( 'InternalTraffic', $_COOKIE ) && $_COOKIE['InternalTraffic'] == 'true' ) {
			$data['dimension2'] = 'true';
		} else {
			$data['dimension2'] = '';
		}

		// Custom dimension 4 post_tags
		$post_id   = url_to_postid( $entry['source_url'] );
		$post_tags = get_the_tags( $post_id );

		$tags = array();
		foreach ( $post_tags as $tag ) {
			$tags[] = $tag->name;
		}

		$data['dimension4'] = implode( ', ', $tags );

		// Custom dimension 5 gCampaign
		// Custom dimension 6 gBasket
		// Custom dimension 7 gScope
		$page_meta_data = get_post_meta( $post_id );
		$page_meta_data = array_map( 'reset', $page_meta_data );

		$data['dimension5'] = $page_meta_data['p4_campaign_name'];
		$data['dimension6'] = $page_meta_data['p4_basket_name'];
		$data['dimension7'] = self::get_campaign_scope( $page_meta_data['p4_campaign_name'] );

		// Client ID
		foreach ( $form['fields'] as $field ) {
			if ( $field->label == 'analytics_client_id' ) {
				$field_id = $field->id;
				break;
			}
		}

		$client_id = rgar( $entry, $field_id );

		if ( empty( $client_id ) || $client_id == 'clientId' ) {
			// The default value in the form is clientID. Replace it with en empty array.
			$client_id = array();
		} else {
			$client_id = explode( ',', $client_id );
		}

		// IP & Page path
		$data['ip']         = $entry['ip'];
		$data['source_url'] = $entry['source_url'];

		// Event Category
		if ( array_key_exists( 'gpch_child_field_ssa_test_mode', $child_options ) && $child_options['gpch_child_field_ssa_test_mode'] == 1 ) {
			$data['event_category'] = 'TEST: ' . ucfirst( $form['gpch_gf_type'] ) . " Signup";
		} else {
			$data['event_category'] = ucfirst( $form['gpch_gf_type'] ) . " Signup";
		}


		// Get the tracking ID(s)
		if ( key_exists( 'gpch_child_field_ssa_properties', $child_options ) ) {
			$tracking_ids = explode( ',', $child_options['gpch_child_field_ssa_properties'] );

			$tracking_ids = array_map( 'trim', $tracking_ids );
		}

		$i = 0;
		foreach ( $tracking_ids as $tracking_id ) {
			// Instantiate the Analytics object
			// optionally pass TRUE in the constructor if you want to connect using HTTPS
			$analytics = new Analytics( true );

			// Send a page view?
			$send_page_view = false;

			// ClientID
			if ( is_array( $client_id ) && array_key_exists( $i, $client_id ) ) {
				$data['client_id'] = $client_id;
			} else {
				$data['client_id'] = self::generateClientId();
				$send_page_view    = true;
			}

			// Build the GA hit using the Analytics class methods
			$analytics
				->setProtocolVersion( '1' )
				->setTrackingId( $tracking_id )
				->setClientId( $data['client_id'] )
				->setDocumentPath( $data['source_url'] )
				->setIpOverride( $data['ip'] )
				->setCustomDimension( $data['dimension2'], 2 )
				->setCustomDimension( $data['dimension4'], 4 )
				->setCustomDimension( $data['dimension5'], 5 )
				->setCustomDimension( $data['dimension6'], 6 )
				->setCustomDimension( $data['dimension7'], 7 );


			// If the clientID had to be generated, we assume that Analytics also didn't receive a page view and send one
			if ( $send_page_view ) {
				$analytics->sendPageview();
			}

			// Send the event
			$analytics->setEventCategory( $data['event_category'] )
			          ->setEventAction( $data['dimension5'] )
			          ->setEventLabel( $data['dimension7'] )
			          ->sendEvent();

			$i ++;
		}
	}

	/**
	 * Get campaign scope from value selected in the Global Projects dropdown.
	 * Conditions:
	 * - If Global Project equals "Local Campaign" then Scope is Local.
	 * - If Global Project equals none then Scope is not set
	 * - If Global Project matches any other value (apart from "Local Campaign") then Scope is Global
	 *
	 * @param string $global_project The Global Project value.
	 *
	 * @return string The campaign scope.
	 */
	private static function get_campaign_scope( $global_project ) {
		switch ( $global_project ) {
			case 'Local Campaign':
				return 'Local';
			case 'not set':
				return 'not set';
			default:
				return 'Global';
		}
	}
}

add_action( 'gform_after_submission', array(
	'Greenpeace\Planet4GPCHChild\GoogleAnalytics',
	'sendFormConversion'
), 10, 2 );






