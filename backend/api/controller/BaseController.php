<?php
// base controller
class BaseController {
	// database adapter
	protected $databaseAdapter = null;

	// request parameters
	protected $params = null;

	// construct
	public function __construct($databaseAdapter) {
		$this->databaseAdapter = $databaseAdapter;

		$this->params = json_decode(file_get_contents('php://input'));
	}

	public function needAuthentication() {
		if(!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] == false) {
			Http::response(array("code" => "BAD_AUTH"), 401);
		}
	}

	public function needDisconnect() {
		$dao = new AccountDAO($this->databaseAdapter);
		if($dao->checkUsernameConnected($_SESSION["memb___id"])) {
			Http::response(array("code" => "NEED_DISCONNECT_ACCOUNT"), 201);
		}
	}
}