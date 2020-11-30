<?php

/**
 * Class GPCH_Inxmail_API
 */
class GPCH_Inxmail_API implements GPCH_i_REST_API {
	private $user = '';
	private $pass = '';
	private $base_url = '';
	private $recipients_slug = 'recipients/';

	/**
	 * GPCH_Inxmail_API constructor.
	 */
	function __construct() {
		// Get child theme options
		$child_options = get_option( 'gpch_child_options' );

		// print_r( '[grownnotmade] -> [__construct()] -> [$child_options]' );
		// var_dump( $child_options );

		if ( $child_options ) {
			if ( array_key_exists( 'gpch_child_field_inxmail_user', $child_options ) && array_key_exists( 'gpch_child_field_inxmail_pass', $child_options ) && array_key_exists( 'gpch_child_field_inxmail_url', $child_options ) ) {
				$user     = $child_options['gpch_child_field_inxmail_user'];
				$pass     = $child_options['gpch_child_field_inxmail_pass'];
				$base_url = $child_options['gpch_child_field_inxmail_url'];

				$this->user     = $user;
				$this->pass     = $pass;
				$this->base_url = $base_url;
			} else {
				// todo
				// Sentry\captureException( $exception );
			}
		}
	}

	/**
	 * @param $method : POST, PUT, GET etc
	 * @param $url
	 * @param bool $data : array("param" => "value") ==> index.php?param=value
	 *
	 * @return bool|string
	 */
	function call_api( $method, $slug, $data = false ) {
		$url = $this->base_url . $slug;
		// print_r( '[grownnotmade] -> [call_api( $method, $slug, $data = false )] -> [$url]' );
		// var_dump( $url );
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

					// print_r( '[grownnotmade] -> [call_api( $method, $slug, $data = false )] -> [$url]' );
					// var_dump( $url );
				}
		}

		// Optional Authentication:
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
	 * Subcribes an recipient to an email list
	 *
	 * @string $email: Primary key for recepient
	 * @array $lists: Array of lists to subscribe to
	 * @array $personalData: additional personal data: first_name, last_name, salutation
	 * @param bool $subscribeToGeneral : Wheter or not to subscribe the recipient to the general list at the same time
	 */
	public function subscribe( $email, $lists, $personal_data, $subscribe_to_general = true ) {
		$recipient = $this->retrieve_recipient_info_by_email( $email );

		// todo: error handling
		/*
		if ( $recipient === null ) {
			$recipient = $this->create_recipient( $email, $personal_data );
		}
		*/

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
	protected function retrieve_recipient_info_by_email( $email ) {
		$method = 'GET';
		$data   = array( 'email' => $email );
		// todo array
		$slug   = $this->recipients_slug;

		$recipient = $this->call_api( $method, $slug, $data );

		// todo: check $recipient
		print_r( '[grownnotmade] -> [retrieve_recipient_info_by_email( $email )] -> [$recipient]' );
		var_dump( $recipient );
		die();

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
