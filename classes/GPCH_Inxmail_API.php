<?php

/**
 * Class GPCH_Inxmail_API
 */
class GPCH_Inxmail_API implements GPCH_i_REST_API {
	private $user = '';
	private $pass = '';
	private $base_url = '';

	/**
	 * GPCH_Inxmail_API constructor.
	 */
	function __construct() {
		// get child theme options
		$child_options = get_option( 'gpch_child_options' );

		if ( $child_options ) {
			if ( array_key_exists( 'gpch_child_field_inxmail_user', $child_options ) && array_key_exists( 'gpch_child_field_inxmail_pass', $child_options ) && array_key_exists( 'gpch_child_field_inxmail_url', $child_options ) ) {
				$user     = $child_options['gpch_child_field_inxmail_user'];
				$pass     = $child_options['gpch_child_field_inxmail_pass'];
				$base_url = $child_options['gpch_child_field_inxmail_url'];

				$this->user     = $user;
				$this->pass     = $pass;
				$this->base_url = $base_url;
			}
		} else {
			exit( 'required parameter(s) from gpch_child_options are missing' );
		}
	}

	/**
	 * @param string $method
	 * @param string $slug
	 * @param mixed $data : array for default, JSON for POST and PATCH
	 *
	 * @return mixed $result : JSON on success, false on failure
	 */
	function call_api( $method, $slug, $data = [] ) {
		$result = false;
		$url    = $this->base_url . $slug;

		$curl = curl_init();

		switch ( $method ) {
			case "POST":
				if ( $data ) {
					$headers = array( 'Content-Type: application/hal+json' );
					curl_setopt( $curl, CURLOPT_POST, 1 );
					curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
					curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
				}
				break;
			case "PATCH":
				if ( $data ) {
					$headers = array( 'Content-Type: application/merge-patch+json' );
					curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PATCH' );
					curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
					curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
				}
				break;
			default:
				if ( $data ) {
					$url = sprintf( "%s?%s", $url, http_build_query( $data ) );
				}
		}

		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt( $curl, CURLOPT_USERPWD, $this->user . ':' . $this->pass );

		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

		try {
			$result = curl_exec( $curl );

			if ( $result === false ) {
				throw new Exception( curl_error( $curl ) );
			}
		} catch ( Exception $exception ) {
			Sentry\captureException( $exception );
		}

		curl_close( $curl );

		return $result;
	}

	/**
	 * Subscribes a recipient to one ore more newsletter categories on the greenpeace_master list.
	 *
	 * @param string $email : primary key for recipient
	 * @param array $categories : array of categories to subscribe to / set flags for
	 * @param array $attributes : additional personal data, at the moment $attributes[Salutation, FirstName, Name]
	 * @param string $tracking_permission : can be 'GRANTED', 'DENIED' or empty (stays unchanged)
	 * @param string $general_category : the name of the general newsletter category
	 *
	 * @return mixed $result : true on success, array with error details on failure
	 */
	public function subscribe( string $email, array $categories, array $attributes = [], string $tracking_permission = '', string $general_category = 'allgemein' ) {
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$recipient            = $this->get_recipient( $email );
			$greenpeace_master_id = 8;

			// $recipient_id = $recipient['_embedded']['inx:recipients'][0]['id'];

			if ( $recipient && $this->is_subscribed_to_list( $greenpeace_master_id, $email ) ) {
				$result_case_1 = $this->set_flags( $email, $categories );

				// print_r( '[grownnotmade] -> [GPCH_Inxmail_API] -> [subscribe] -> [$result_case_1]' );
				// var_dump( $result_case_1 );
				// die();

				return $result_case_1;
			} else {
				$result_case_2 = $this->subscribe_to_list( $greenpeace_master_id, $email, $attributes, $tracking_permission );

				if ( $result_case_2 === true ) {
					$categories[]  = $general_category;
					$result_case_3 = $this->set_flags( $email, $categories );

					// print_r( '[grownnotmade] -> [GPCH_Inxmail_API] -> [subscribe] -> [$result_case_3]' );
					// var_dump( $result_case_3 );
					// die();

					return $result_case_3;
				} else {
					// print_r( '[grownnotmade] -> [GPCH_Inxmail_API] -> [subscribe] -> [$result_case_2]' );
					// var_dump( $result_case_2 );
					// die();

					return $result_case_2;
				}
			}
		} else {
			$result_case_4['error'] = 'e-mail address is not valid';

			// print_r( '[grownnotmade] -> [GPCH_Inxmail_API] -> [subscribe] -> [$result_case_3]' );
			// var_dump( $result_case_4 );
			// die();

			return $result_case_4;
		}
	}

	/**
	 * Retrieves an Inxmail recipient with current subscription data.
	 *
	 * @see: https://apidocs.inxmail.com/xpro/rest/v1/#retrieve-recipients-collection
	 *
	 * @param $email
	 *
	 * @return mixed $result : array with recipient details on success, false on failure
	 */
	protected function get_recipient( string $email ) {
		$recipients_collection = $this->retrieve_recipients_collection( [], '', '', (array) $email );

		if ( $recipients_collection['_embedded']['inx:recipients'][0] && array_key_exists( 'id', $recipients_collection['_embedded']['inx:recipients'][0] ) ) {
			$result = $recipients_collection;
		} else {
			$result = false;
		}

		return $result;
	}

	/**
	 * @param int $list_id
	 * @param string $email
	 *
	 * @return bool $result : true when subscribed, false when not subscribed
	 */
	protected function is_subscribed_to_list( int $list_id, string $email ) {
		$recipient = $this->retrieve_recipients_collection( [], (string) $list_id, '', (array) $email );

		if ( $recipient['_embedded']['inx:recipients'][0] && array_key_exists( 'id', $recipient['_embedded']['inx:recipients'][0] ) ) {
			// $recipient_id = $recipient['_embedded']['inx:recipients'][0]['id'];

			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}


	/**
	 * Subscribes a recipient to newsletter categories.
	 *
	 * @param string $email
	 * @param array $categories
	 *
	 * @return mixed $result : true on success, array with error details on failure
	 */
	protected function set_flags( string $email, array $categories ) {

		$flags = [];

		// for testing only
		// $existing_categories = [ 'allgemein', 'ernaehrung', 'finanzplatz', 'klima', 'meer', 'zerowaste' ];

		foreach ( $categories as $key => $value ) {
			// if ( in_array( $value, $existing_categories ) ) {
			$flags[ 'flag_' . $value ] = true;
			// }
		}

		$patch_result = $this->patch_recipient( $email, $flags );

		if ( $patch_result && array_key_exists( 'id', $patch_result ) ) {
			$result = true;
		} else {
			if ( array_key_exists( 'type', $patch_result ) ) {
				$error_text = $patch_result['type'];

				if ( array_key_exists( 'detail', $patch_result ) ) {
					$error_text = $error_text . ': ' . $patch_result['detail'];
				}

				$result['error'] = $error_text;
			} else {
				$result['error'] = 'Undefined error in the array $patch_result in the set_flags function.';
			}
		}

		return $result;
	}

	/**
	 * Subscribes a recipient to a list.
	 *
	 * @param int $list_id
	 * @param string $email
	 * @param string $tracking_permission
	 * @param array $attributes
	 *
	 * @return mixed  $result : true on success, array with error details on failure
	 */
	protected function subscribe_to_list( int $list_id, string $email, array $attributes = [], string $tracking_permission = '' ) {
		$subscribe_result = $this->subscribe_recipient_to_list( $list_id, $email, $attributes, $tracking_permission );

		if ( array_key_exists( 'result', $subscribe_result ) && $subscribe_result['result'] == 'PENDING_SUBSCRIPTION' || $subscribe_result['result'] == 'PENDING_SUBSCRIPTION_DONE' || $subscribe_result['result'] == 'VERIFIED_SUBSCRIPTION' || $subscribe_result['result'] == 'MANUAL_SUBSCRIPTION' || $subscribe_result['result'] == 'DUPLICATE_SUBSCRIPTION' ) {
			$result = true;
		} else {
			$result['error'] = $subscribe_result['result'];
		}

		return $result;
	}

	/**
	 * 1 to 1 implementation of the Inxmail API request "retrieve recipients collection"
	 * https://apidocs.inxmail.com/xpro/rest/v1/#retrieve-recipients-collection
	 *
	 * Original parameter names are: attributes, subscribedTo, lastModifiedSince, email, attributes.attributeName, trackingPermissionsForLists, subscriptionDatesForLists
	 *
	 * @param array $attributes
	 * @param string $subscribed_to
	 * @param string $last_modified_since - NOT YET IMPLEMENTED
	 * @param array $email
	 * @param array $attributes_attribute_name
	 * @param array $tracking_permissions_for_lists - NOT YET IMPLEMENTED
	 * @param array $subscription_dates_for_lists - NOT YET IMPLEMENTED
	 *
	 * @return array $result : recipient collection on success, details on failure
	 */
	protected function retrieve_recipients_collection( array $attributes = [], string $subscribed_to = '', string $last_modified_since = '', array $email = [], array $attributes_attribute_name = [], array $tracking_permissions_for_lists = [], array $subscription_dates_for_lists = [] ) {
		$method = 'GET';
		$slug   = 'recipients/';
		$data   = [];

		// attributes
		if ( $attributes ) {
			$attributes_string = '';

			foreach ( $attributes as $key => $value ) {
				$attributes_string .= $value . ',';
			}

			$attributes_string  = rtrim( $attributes_string, ',' );
			$data['attributes'] = $attributes_string;
		}

		// subscribed_to
		if ( $subscribed_to ) {
			$data['subscribedTo'] = $subscribed_to;
		}

		// email
		if ( $email ) {
			$email_string = '';

			foreach ( $email as $key => $value ) {
				$email_string .= $value . ',';
			}

			$email_string  = rtrim( $email_string, ',' );
			$data['email'] = $email_string;
		}

		// attributes_attribute_name
		if ( $attributes_attribute_name ) {
			foreach ( $attributes_attribute_name as $key => $value ) {
				$data[ 'attributes.' . $key ] = $value;
			}
		}

		$result = $this->call_api( $method, $slug, $data );
		$result = json_decode( $result, true );

		return $result;
	}

	/**
	 * 1 to 1 implementation of the Inxmail API request "patching a recipient"
	 * https://apidocs.inxmail.com/xpro/rest/v1/#patch-recipient
	 *
	 * Original parameter names are: email, attributes
	 *
	 * @param string $email
	 * @param array $attributes
	 * @param string $new_email
	 *
	 * @return array $result : recipient attributes on success, details on failure
	 */
	protected function patch_recipient( string $email, array $attributes, string $new_email = '' ) {
		$method = 'PATCH';
		$slug   = 'recipients/' . $email . '/';
		$data   = [];

		if ( $new_email ) {
			$data['email'] = $new_email;
		}

		if ( $attributes ) {
			$data['attributes'] = $attributes;
		}

		$json_data = json_encode( $data );

		$result = $this->call_api( $method, $slug, $json_data );
		$result = json_decode( $result, true );

		return $result;
	}


	/**
	 * 1 to 1 implementation of the Inxmail API request "subscribing a recipient to a list"
	 * https://apidocs.inxmail.com/xpro/rest/v1/#_subscribing_a_recipient_to_a_list
	 *
	 * Original parameter names are: listId, email, trackingPermission, attributes
	 *
	 * @param int $list_id
	 * @param string $email
	 * @param string $tracking_permission
	 * @param array $attributes
	 *
	 * @return array $result : recipient attributes on success, details on failure
	 */
	protected function subscribe_recipient_to_list( int $list_id, string $email, array $attributes = [], string $tracking_permission = '' ) {
		$method = 'POST';
		$slug   = 'events/subscriptions/';
		$data   = [];

		if ( $list_id ) {
			$data['listId'] = $list_id;
		}

		if ( $email ) {
			$data['email'] = $email;
		}

		if ( $attributes ) {
			$data['attributes'] = $attributes;
		}

		if ( $tracking_permission ) {
			$data['trackingPermission'] = $tracking_permission;
		}

		$json_data = json_encode( $data );

		$result = $this->call_api( $method, $slug, $json_data );
		$result = json_decode( $result, true );

		return $result;
	}
}
