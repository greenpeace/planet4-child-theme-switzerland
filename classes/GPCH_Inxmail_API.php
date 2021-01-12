<?php

/**
 * Class GPCH_Inxmail_API
 */
class GPCH_Inxmail_API implements GPCH_i_REST_API {
	private $user = '';
	private $pass = '';
	private $base_url = '';
	private $error_message = [];

	/**
	 * GPCH_Inxmail_API constructor.
	 */
	public function __construct() {
	}

	/**
	 * @return bool $result : true on success, false on failure
	 */
	protected function set_connection_parameters() {
		$result        = false;
		$child_options = get_option( 'gpch_child_options' );

		if ( $child_options ) {
			if ( array_key_exists( 'gpch_child_field_inxmail_user', $child_options ) && array_key_exists( 'gpch_child_field_inxmail_pass', $child_options ) && array_key_exists( 'gpch_child_field_inxmail_url', $child_options ) ) {
				$user     = $child_options['gpch_child_field_inxmail_user'];
				$pass     = $child_options['gpch_child_field_inxmail_pass'];
				$base_url = $child_options['gpch_child_field_inxmail_url'];

				if ( empty( $user ) || empty( $pass ) || empty( $base_url ) ) {
					$this->error_message['error'] = 'error in set_connection_parameters: one or more required parameter(s) from gpch_child_options are empty';

					return $result;
				} else {
					$this->user     = $user;
					$this->pass     = $pass;
					$this->base_url = $base_url;

					$result = true;

					return $result;
				}
			}
		} else {
			$this->error_message['error'] = 'error in set_connection_parameters: required parameter(s) from gpch_child_options are missing';

			return $result;
		}
	}

	/**
	 * @param string $method
	 * @param string $slug
	 * @param mixed $data : array for default, JSON for POST and PATCH
	 *
	 * @return mixed : JSON on success, false on failure
	 */
	public function call_api( $method, $slug, $data = [] ) {

		$result_scp = $this->set_connection_parameters();

		if ( $result_scp === false ) {
			return $result_scp;
		}

		$url       = $this->base_url . $slug;
		$curl      = curl_init();
		$result_ce = false;

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
			$result_ce = curl_exec( $curl );

			if ( $result_ce === false ) {
				throw new Exception( curl_error( $curl ) );
			}
		} catch ( Exception $exception ) {
			Sentry\captureException( $exception );

			$this->error_message['error'] = 'error in function call_api: check sentry.io for exception details';
		}

		curl_close( $curl );

		return $result_ce;

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
	 * @return mixed : true on success, array with error details on failure
	 */
	public function subscribe( string $email, array $categories, array $attributes = [], string $tracking_permission = '', string $general_category = 'allgemein' ) {

		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			$this->error_message['error'] = 'error in function subscribe: e-mail address is not valid';

			return $this->error_message;
		}

		$recipient = $this->get_recipient( $email );

		if ( $recipient === false ) {
			return $this->error_message;
		}

		$greenpeace_master_id = 8;

		// case 1: recipient exists
		if ( $recipient !== null ) {
			// $recipient_id = $recipient['_embedded']['inx:recipients'][0]['id'];
			$result_istl = $this->is_subscribed_to_list( $greenpeace_master_id, $email );

			if ( $result_istl === false ) {
				return $this->error_message;
			}

			// recipient is subscribed to greenpeace master
			if ( $result_istl === true ) {
				$result_sf = $this->set_flags( $email, $categories );

				if ( $result_sf === true ) {
					return $result_sf;
				} else {
					return $this->error_message;
				}
			} // recipient is not subscribed to greenpeace master
			else {
				$categories[] = $general_category;
				$flags        = $this->prepare_flags( $categories );
				// if the recipient exists, we don't set attributes
				$attributes = $flags;
				$result_stl = $this->subscribe_to_list( $greenpeace_master_id, $email, $attributes, $tracking_permission );
			}
		} // case 2: recipient doesn't exist
		else {
			$categories[] = $general_category;
			$flags        = $this->prepare_flags( $categories );
			// if the recipient doesn't exist, we set attributes
			$attributes = array_merge( $attributes, $flags );
			$result_stl = $this->subscribe_to_list( $greenpeace_master_id, $email, $attributes, $tracking_permission );
		}

		if ( $result_stl === true ) {
			return $result_stl;
		} else {
			return $this->error_message;
		}
	}

	/**
	 * Retrieves an Inxmail recipient with current subscription data.
	 *
	 * @param $email
	 *
	 * @return mixed $result : array with recipient details on success, false on failure, null if recipient doesn't exist
	 */
	protected function get_recipient( string $email ) {
		$result     = null;
		$result_rrc = $this->retrieve_recipients_collection( [], '', '', (array) $email );

		if ( ( $result_rrc === false ) || ( $result_rrc['_embedded']['inx:recipients'][0] && array_key_exists( 'id', $result_rrc['_embedded']['inx:recipients'][0] ) ) ) {
			$result = $result_rrc;
		}

		return $result;
	}

	/**
	 * @param int $list_id
	 * @param string $email
	 *
	 * @return mixed $result : true if subscribed, false on failure, null if not subscribed
	 */
	protected function is_subscribed_to_list( int $list_id, string $email ) {
		$result     = null;
		$result_rrc = $this->retrieve_recipients_collection( [], (string) $list_id, '', (array) $email );

		if ( $result_rrc === false ) {
			$result = $result_rrc;
		} elseif ( $result_rrc['_embedded']['inx:recipients'][0] && array_key_exists( 'id', $result_rrc['_embedded']['inx:recipients'][0] ) ) {
			$result = true;
		}

		return $result;
	}

	/**
	 * Subscribes a recipient to newsletter categories.
	 *
	 * @param string $email
	 * @param array $categories
	 *
	 * @return mixed $result : true on success, false on failure, null if patch did not work
	 */
	protected function set_flags( string $email, array $categories ) {
		$result    = null;
		$flags     = $this->prepare_flags( $categories );
		$result_pr = $this->patch_recipient( $email, $flags );

		if ( $result_pr === false ) {
			$result = $result_pr;
		} elseif ( $result_pr && array_key_exists( 'id', $result_pr ) ) {
			$result = true;
		} elseif ( $result_pr && array_key_exists( 'type', $result_pr ) ) {
			$error = 'error in function set_flags: ';
			$error = $error . $result_pr['type'];

			if ( array_key_exists( 'detail', $result_pr ) ) {
				$error = $error . ': ' . $result_pr['detail'];

				preg_match( '/(flag_{1})([a-z]*)/', $result_pr['detail'], $flag );
				$category = $flag[2];
				$error2   = 'Newsletter category ' . $category . ' does not exist. Check newsletter form field settings.';

				$error = $error2 . ' (' . $error . ')';
			}

			$this->error_message['error'] = $error;
		} else {
			$this->error_message['error'] = 'error in function set_flags: undefined error in $result_pr';
		}

		return $result;
	}

	/**
	 * @param array $categories
	 *
	 * @return array $flags
	 */
	protected function prepare_flags( array $categories ) {
		$flags = [];

		// for testing only
		// $existing_categories = [ 'allgemein', 'ernaehrung', 'finanzplatz', 'klima', 'meer', 'zerowaste' ];

		foreach ( $categories as $key => $value ) {
			// if ( in_array( $value, $existing_categories ) ) {
			$flags[ 'flag_' . $value ] = true;
			// }
		}

		return $flags;
	}

	/**
	 * Subscribes a recipient to a list.
	 *
	 * @param int $list_id
	 * @param string $email
	 * @param string $tracking_permission
	 * @param array $attributes
	 *
	 * @return mixed $result : true on success, false on failure, null if subscribe did not work
	 */
	protected function subscribe_to_list( int $list_id, string $email, array $attributes = [], string $tracking_permission = '' ) {
		$result      = null;
		$result_srtl = $this->subscribe_recipient_to_list( $list_id, $email, $attributes, $tracking_permission );

		if ( $result_srtl === false ) {
			$result = $result_srtl;
		} elseif ( $result_srtl && array_key_exists( 'result', $result_srtl ) && $result_srtl['result'] == 'PENDING_SUBSCRIPTION' || $result_srtl['result'] == 'PENDING_SUBSCRIPTION_DONE' || $result_srtl['result'] == 'VERIFIED_SUBSCRIPTION' || $result_srtl['result'] == 'MANUAL_SUBSCRIPTION' || $result_srtl['result'] == 'DUPLICATE_SUBSCRIPTION' ) {
			$result = true;
		} elseif ( $result_srtl && array_key_exists( 'type', $result_srtl ) ) {
			$error = 'error in function subscribe_to_list: ';
			$error = $error . $result_srtl['type'];

			if ( array_key_exists( 'title', $result_srtl ) ) {
				$error = $error . ': ' . $result_srtl['title'];
			}

			if ( array_key_exists( 'invalidFields', $result_srtl ) ) {
				foreach ( $result_srtl['invalidFields'] as $key => $value ) {
					$error = $error . ', [field: ' . $value['field'] . ', problem: ' . $value['problem'] . ']';
				}
			}

			$this->error_message['error'] = $error;
		} else {
			$this->error_message['error'] = 'error in function subscribe_to_list: undefined error in $result_srtl';
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
	 * @return mixed $result : array on success, false on failure
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

		$result_ca = $this->call_api( $method, $slug, $data );

		if ( $result_ca === false ) {
			$result = $result_ca;
		} else {
			$result = json_decode( $result_ca, true );
		}

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
	 * @return mixed $result : array on success, false on failure
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

		$result_ca = $this->call_api( $method, $slug, $json_data );

		if ( $result_ca === false ) {
			$result = $result_ca;
		} else {
			$result = json_decode( $result_ca, true );
		}

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
	 * @return mixed $result : array on success, false on failure
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

		$result_ca = $this->call_api( $method, $slug, $json_data );

		if ( $result_ca === false ) {
			$result = $result_ca;
		} else {
			$result = json_decode( $result_ca, true );
		}

		return $result;
	}
}
