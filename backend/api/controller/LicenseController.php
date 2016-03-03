<?php
// license controller
class LicenseController extends BaseController {

	public function register() {
		if(isset($this->params->name) == false) {
			Http::response(array(
				"code" => "LICENSE_REGISTER_EMPTY_FIELD_NAME"
			));
		}
		if(isset($this->params->email) == false) {
			Http::response(array(
				"code" => "LICENSE_REGISTER_EMPTY_FIELD_EMAIL"
			));
		}
		if(filter_var($this->params->email, FILTER_VALIDATE_EMAIL) == false) {
			Http::response(array(
				"code" => "LICENSE_REGISTER_INVALID_FIELD_EMAIL"
			));
		}
		if(isset($this->params->licenseType) == false || (int) $this->params->licenseType < 0 || (int) $this->params->licenseType > 1) {
			Http::response(array(
				"code" => "LICENSE_REGISTER_INVALID_FIELD_LICENSE_TYPE"
			));
		}

		$host = preg_replace("/(www\.|:.*)/i", "", strtolower($_SERVER["HTTP_HOST"]));
		$postFields = array(
			"name" => $this->params->name,
			"email" => $this->params->email,
			"licenseType" => $this->params->licenseType,
			"host" => $host,
			"api" => "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"]
		);

        $curl = new CurlHelper("http://webcm.daldegam.com.br/api/index.php?/license/register");
        $curl->setPost($postFields);
        $curl->createCurl(); 
        $serverResponse = unserialize(base64_decode($curl->__tostring()));

        if($serverResponse["code"] == "LICENSE_REGISTER_SUCCESS") {
	        $aesHelper = new AesHelper();
	        $aesHelper->setKey("DC992D2AA7A4AC3C7F3D629D991617EB1103545B8181B258BFC33C3D417660C3");
	        $licenseDecripted = $aesHelper->decript($serverResponse["license"]);
	        $licenseDecripted = unserialize($licenseDecripted);

	        if(is_array($licenseDecripted) == true) {
	        	$handler = fopen("./license/" . $host . ".license", "w");
	        	fwrite($handler, $serverResponse["license"]);
	        	fclose($handler);

				Http::response(array(
					"code" => "LICENSE_REGISTER_SUCCESS"
				));
	        }
	    }
	    else {
			Http::response(array(
				"code" => $serverResponse["code"]
			));        	
        }
	}
}