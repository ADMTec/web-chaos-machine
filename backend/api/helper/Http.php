<?php
// http class
class Http {

	// return response for client
	public static function response(array $response, $httpCode = 200) {
		header("Content-Type: text/json", true, $httpCode); //Return json
		exit(json_encode($response));
	}
}