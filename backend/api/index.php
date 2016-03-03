<?php
error_reporting(E_ALL);
session_start();
header("Access-Control-Allow-Origin: *"); //Enable CORS

require("./helper/Autoload.php");
require("./helper/Http.php");
require("./settings.php");
$settings = new Settings();

class App {
	// license
	public static $license = null;

	// app settings
	private $settings = null;

	// database adapter
	private $databaseAdapter = null;

	// constructor
	public function __construct($settings) {
		$this->settings = $settings;

		if(function_exists("mcrypt_encrypt") == false) {
		    Http::response(array("error"=> "mcrypt extension is not installed"), 500);
		}

		if($this->settings->database["driver"] == "mssql") {
			require("./dao/Mssql.driver.php");
		} else if($this->settings->database["driver"] == "sqlsrv") {
			require("./dao/Sqlsrv.driver.php");
		} else {
			Http::response(array("error"=> "invalid database driver"), 500);
		}
		
		$this->databaseAdapter = new pdoLib($this->settings->database);

		ItemDatabaseHelper::setDatabases("./data/", "item.txt", "item.serialize.txt");
		if(ItemDatabaseHelper::checkDatabaseExists() == false)
		{
			ItemDatabaseHelper::createDatabase();
		}
	}

	// check license
	public function checkLicense($exit_if_no_have_license) {
		$host = preg_replace("/(www\.|:.*)/i", "", strtolower($_SERVER["HTTP_HOST"]));
		if(file_exists("./license/" . $host . ".license") == true) {
	    	$handler = fopen("./license/" . $host . ".license", "r");
	    	$licenseEncripted = fread($handler, filesize("./license/" . $host . ".license"));
	    	fclose($handler);

	        $aesHelper = new AesHelper();
	        $aesHelper->setKey("DC992D2AA7A4AC3C7F3D629D991617EB1103545B8181B258BFC33C3D417660C3");
	        $licenseDecripted = $aesHelper->decript($licenseEncripted);
	        $licenseDecripted = unserialize($licenseDecripted);

	        if(is_array($licenseDecripted) == true) {
	        	App::$license = $licenseDecripted;
	        	if($licenseDecripted["host"] == $host) {
	        		return true;
	        	} else {
	        		if($exit_if_no_have_license == true) {
	        			Http::response(array("error" => "license not found"), 401);
	        		} else {
	        			return false;
	        		}
	        	}
	        }
	    }
	    if($exit_if_no_have_license == true) {
			Http::response(array("error" => "license not found"), 401);
		} else {
			return false;
		}
	}

	// execute app
	public function execute() {

		if(isset($_SERVER["QUERY_STRING"]) && !empty($_SERVER["QUERY_STRING"])) {
			switch($_SERVER["QUERY_STRING"]) {
				case "/authentication/signin":
					$this->checkLicense(true);
					$controller = new AuthenticationController($this->databaseAdapter);
					$controller->signInAction();
					break;
				case "/authentication/signout":
					$this->checkLicense(true);
					$controller = new AuthenticationController($this->databaseAdapter);
					$controller->signOutAction();
					break;
				case "/character/getCharacterList":
					$this->checkLicense(true);
					$controller = new CharacterController($this->databaseAdapter);
					$controller->getCharacterList();
					break;
				case "/character/getCharacterItems":
					$this->checkLicense(true);
					$controller = new CharacterController($this->databaseAdapter);
					$controller->getCharacterItems();
					break;
				case "/machine/getMixes":
					$this->checkLicense(true);
					$controller = new MachineController($this->databaseAdapter, $this->settings->machineMixes);
					$controller->getMixes();
					break;
				case "/machine/checkMix":
					$this->checkLicense(true);
					$controller = new MachineController($this->databaseAdapter, $this->settings->machineMixes);
					$controller->checkMix();
					break;
				case "/license/register":
					$controller = new LicenseController($this->databaseAdapter);
					$controller->register();
					break;
				case "/machine/config":
					Http::response(array(
						"api_version" => "1.0.4",
						"have_license" => $this->checkLicense(false),
						"template" => $this->settings->template
					), 201);
					break;
				default:
					Http::response(array("error" => "invalid route"), 500);
			}
		}

	}

};
try {
	$app = new App($settings);
	$app->execute();
}
catch(Exception $exp) {
	Http::response(array("error" => $exp->getMessage()), 500);
}