<?php

/**
 * Interface GPCH_i_REST_API
 */
interface GPCH_i_REST_API {
	public function call_api( $method, $slug, $data = false );
}
