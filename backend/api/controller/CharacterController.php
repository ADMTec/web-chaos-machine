<?php
// character controller
class CharacterController extends BaseController {
	
	// check authentication
	public function __construct($databaseAdapter) {
		parent::__construct($databaseAdapter);
		$this->needAuthentication();
	}

	// get character list
	public function getCharacterList() {
		$dao = new CharacterDAO($this->databaseAdapter);
		$list = $dao->getCharacterList();
		Http::response($list);
	}

	// get character list
	public function getCharacterItems() {
		if(isset($this->params->character) == false) {
			Http::response(array(
				"code" => "GENERIC_ERROR_EMPTY_FIELD_CHARACTER"
			));
		}

		$dao = new CharacterDAO($this->databaseAdapter);
		$itemsDump = $dao->getCharacterInventory($this->params->character);

		$inventoryHelper = new InventoryHelper($itemsDump);

		$listItems = array();
		foreach($inventoryHelper->codeGroup as $slot => $item) {
			if ($item["isItem"] && $item["slot"]["i"] < 76) { // não é necessário enviar para o cliente slots que não serão utilizados
				unset($item["code"]);
				unset($item["error"]);
				//unset($item["isItem"]);
				//unset($item["isFree"]);
				unset($item["itemIdUnique"]);
				unset($item["itemSerial"]);
				$listItems[] = $item;
			}
		}

		Http::response($listItems);
	}
}