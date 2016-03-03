<?php
// authentication controller
class AuthenticationController extends BaseController {

	// constructor
	public function __construct($databaseAdapter) {
		parent::__construct($databaseAdapter);
	}

	// signin action
	public function signInAction() {

		if(isset($this->params->username) == false) {
			Http::response(array(
				"code" => "GENERIC_ERROR_EMPTY_FIELD_USERNAME"
			));
		}
		if(isset($this->params->password) == false) {
			Http::response(array(
				"code" => "GENERIC_ERROR_EMPTY_FIELD_PASSWORD"
			));
		}


		$dao = new AccountDAO($this->databaseAdapter);
		if($dao->checkUsernameAndPassword($this->params->username, $this->params->password) == true) {
			if($dao->checkUsernameConnected($this->params->username) == false) {
			
				$_SESSION["authenticated"] = true;
				$_SESSION["memb___id"] = $this->params->username;

				Http::response(array(
					"code" => "AUTHENTICATION_SUCCESS", "auth" => true
				));
			}
			else
			{
				Http::response(array(
					"code" => "AUTHENTICATION_NEED_DISCONNECT_ACCOUNT", "auth" => false
				));
			}
		} else {
			Http::response(array(
				"code" => "AUTHENTICATION_ERROR_INVALID_CREDENTIALS", "auth" => false
			));
		}
	}

	// signout action
	public function signOutAction() {
		session_destroy();
	}
}