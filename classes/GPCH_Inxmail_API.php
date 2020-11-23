<?php

/**
 * Class GPCH_Inxmail_API
 */
class GPCH_Inxmail_API implements GPCH_i_REST_API {
	/**
	 * @param $method : POST, PUT, GET etc
	 * @param $url
	 * @param bool $data : array("param" => "value") ==> index.php?param=value
	 *
	 * @return bool|string
	 */
	function call_api( $method, $url, $data = false ) {
		$curl = curl_init();

		switch ( $method ) {
			case "POST":
				curl_setopt( $curl, CURLOPT_POST, 1 );

				if ( $data ) {
					curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
				}
				break;
			case "PUT":
				curl_setopt( $curl, CURLOPT_PUT, 1 );
				break;
			default:
				if ( $data ) {
					$url = sprintf( "%s?%s", $url, http_build_query( $data ) );
				}
		}

		// Get child theme options
		$child_options = get_option( 'gpch_child_options' );
		$user = $child_options['gpch_child_field_inxmail_user'];
		$pass = $child_options['gpch_child_field_inxmail_pass'];

		// Optional Authentication:
		curl_setopt( $curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		curl_setopt( $curl, CURLOPT_USERPWD, $user . ':' . $pass );

		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

		try {
			$result = curl_exec( $curl );

			if ($result === false) {
				throw new Exception(curl_error($curl));
			}
		} catch (Exception $exception) {
			Sentry\captureException($exception);
		}

		curl_close( $curl );

		return $result;
	}


	/**
	 * Subcribes an recipient to an email list
	 *
	 * @string $email: Primary key for recepient
	 * @array $lists: Array of lists to subscribe to
	 * @array $personalData: additional personal data: first_name, last_name, salutation
	 * @param bool $subscribeToGeneral : Wheter or not to subscribe the recipient to the general list at the same time
	 */
	public function subscribe( $email, $lists, $personal_data, $subscribe_to_general = true ) {
		$recipient = $this->retrieve_recipient_info();

		if ( $recipient === null ) {
			$recipient = $this->create_recipient( $email, $personal_data );
		}

		// IF: Recipient is subscribed to list
		// return
		// ELSE: Subscribe recipient to list
	}


	/**
	 * Retrieve an Inxmail recipient with current subscription data
	 *
	 * @see: https://apidocs.inxmail.com/xpro/rest/v1/#retrieve-recipients-collection
	 *
	 * @param $email
	 *
	 * @return mixed
	 */
	protected function retrieve_recipient_info( $email ) {

		return $recipient;
	}


	protected function create_recipient() {

		return $recipient;
	}

	/**
	 * Subscribe recipient to master list
	 */
	protected function subcribe_to_list() {

	}


	/**
	 *
	 * Set flags. Subscribes an recipient to email lists.
	 *
	 * @array $flags: Array of flags to set
	 */
	protected function set_flags( $flags ) {

	}
}
