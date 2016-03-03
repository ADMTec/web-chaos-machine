<?php
// machine controller
class MachineController extends BaseController {

	// mixes settings
	private $mixesSettings = null;

	// check authentication
	public function __construct($databaseAdapter, $mixesSettings) {
		parent::__construct($databaseAdapter);
		$this->mixesSettings = $mixesSettings;
		$this->needAuthentication();
	}

	// get mixes
	public function getMixes() {
		$mixesList = array();
		$mixCount = 0;
		foreach($this->mixesSettings as &$mixes) {
			foreach($mixes["requirements"] as &$requirement) {
				$requirement["haveAmount"] = 0;
				$requirement["itemName"] = ItemDatabaseHelper::$dbItem[$requirement["section"]][$requirement["index"]]["name"];
			}
			$mixesList[] = $mixes;
			$mixCount++;
		}
		Http::response($mixesList);
	}

	// check mix
	public function checkMix() {
		if(isset($this->params->character) == false) {
			Http::response(array(
				"code" => "MACHINE_ERROR_EMPTY_FIELD_CHARACTER"
			));
		}
		if(isset($this->params->currentMix) == false) {
			Http::response(array(
				"code" => "MACHINE_ERROR_EMPTY_FIELD_CURRENT_MIX"
			));
		}
		if(isset($this->params->items) == false) {
			Http::response(array(
				"code" => "MACHINE_ERROR_EMPTY_FIELD_ITEMS"
			));
		}
		if(isset($this->mixesSettings[$this->params->currentMix]) == false) {
			Http::response(array(
				"code" => "MACHINE_ERROR_SELECTED_MIX_NOT_EXISTS"
			));
		}

		$dao = new CharacterDAO($this->databaseAdapter);
		$itemsDump = $dao->getCharacterInventory($this->params->character);

		$inventoryHelper = new InventoryHelper($itemsDump);

		$mix = $this->mixesSettings[$this->params->currentMix];

		foreach($mix["requirements"] as &$requirement) {
			//Enviar o nome para cliente novamente
			$requirement["itemName"] = ItemDatabaseHelper::$dbItem[$requirement["section"]][$requirement["index"]]["name"];
			$requirement["haveAmount"] = 0;

			foreach($this->params->items as &$item) {
				$itemObject = $inventoryHelper->codeGroup[$item->slot];
				if($itemObject["isItem"] == false) {
					Http::response(array(
						"code" => "MACHINE_ERROR_SELECTED_SLOT_NO_HAVE_ITEM"
					));
				}
				if(($itemObject["itemIdSection"] == $requirement["section"]) && 
					($itemObject["itemIdIndex"] == $requirement["index"]) && 
					($itemObject["itemLevel"] >= $requirement["level"]) && 
					($itemObject["itemOption"] >= $requirement["options"]) && 
					($requirement["luck"] ? ($itemObject["itemLuck"] == true) : true)
				) {
					$item->used = true;
					$requirement["haveAmount"]++;
				}
			}
		}

		if($mix["details"]["typeMix"] == 0) {
			$mix["requirements"][-1] = array(
				"amount" => 1, 
				"section" => null, 
				"index" => null, 
				"level" => null, 
				"options" => null, 
				"luck" => null,
				"itemName" => null,
				"translate" => "MACHINE_MIX_STATUS_ITEM_UPGRADE_LEVEL",
				"haveAmount" => 0
			);
		}

		$itemCount = 0;
		// vamos precorrer os itens agora que não foram utilizados
		foreach($this->params->items as &$item) {
			// encontra o item não utilizado
			if(isset($item->used) == false) {
				// seta ele como não utilizado
				$item->used = false;
				$item->upgrade = false;

				$itemObject = $inventoryHelper->codeGroup[$item->slot];
				if($itemObject["isItem"] == false) {
					Http::response(array(
						"code" => "MACHINE_ERROR_SELECTED_SLOT_NO_HAVE_ITEM"
					));
				}

				// caso não seja o primeiro item não utilizado e a combinação seja de level
				if($mix["details"]["typeMix"] == 0 && $itemObject["itemLevel"] == $mix["result"]["oldLevel"]) {
					if($itemCount == 0) {
						$item->used = true;
						$item->upgrade = true;
						$itemCount++;
					}
					$mix["requirements"][-1]["section"] = $itemObject["itemIdSection"];
					$mix["requirements"][-1]["index"] = $itemObject["itemIdIndex"];
					$mix["requirements"][-1]["level"] = $itemObject["itemLevel"];
					$mix["requirements"][-1]["options"] = $itemObject["itemOption"];
					$mix["requirements"][-1]["luck"] = $itemObject["itemLuck"];
					$mix["requirements"][-1]["haveAmount"]++;
				} else {
					$mix["requirements"][-2]["amount"] = 0;
					$mix["requirements"][-2]["section"] = null;
					$mix["requirements"][-2]["index"] = null;
					$mix["requirements"][-2]["level"] = null;
					$mix["requirements"][-2]["options"] = null;
					$mix["requirements"][-2]["luck"] = null;
					$mix["requirements"][-2]["itemName"] = null;
					$mix["requirements"][-2]["translate"] = "MACHINE_MIX_STATUS_ITEM_EXCESS";
					$mix["requirements"][-2]["haveAmount"] = (isset($mix["requirements"][-2]["haveAmount"]) ? $mix["requirements"][-2]["haveAmount"] + 1 : 1);
				}
			}
		}

		// caso receba o parametro de mixar
		if($this->params->executeMix == true) {
			$this->needDisconnect();
			$mixResultSuccess = false;
			if(rand(0, 100) < ($mix["details"]["percentage"] > 100 ? 100 : $mix["details"]["percentage"])) {
				$mixResultSuccess = true;
			}

			// varre novamente os itens da maquina
			foreach($this->params->items as &$item) {
				// verifica se não existe nenhum item sobrando
				if(isset($item->used) == false || $item->used == false) {
					Http::response(array(
						"success" => false,
						"code" => "MACHINE_MIX_STATUS_ITEM_EXCESS"
					));
				}
			}

			// verificar se todos os itens estão atendendo os requisitos
			foreach($mix["requirements"] as &$requirement) {
				// verificando se a quantidade de itens dos requisitos estão corretas
				if($requirement["amount"] != $requirement["haveAmount"]) {
					Http::response(array(
						"success" => false,
						"code" => "MACHINE_MIX_STATUS_NEED_MORE_ITEMS"
					));
				}
			}

			$databaseModel = $this->databaseAdapter->databaseSettings["databaseModel"];
			// apagar os itens inseridos na CM
			// varre novamente os itens da maquina
			foreach($this->params->items as &$item) {
				//encontra um item usado que não sofrerá upgrade e deleta
				if($item->used == true && (isset($item->upgrade) == false || $item->upgrade == false)) {
					$hexTemp = ItemCreateHelper::createEmptyItem($databaseModel);
					$inventoryHelper->insertItemInSlot($hexTemp, $item->slot); // apagando o item
				} 
				// encontra um item que será usado e sofrerá upgrade e atualiza o mesmo
				// importante, apensar de nao ter na condição abaixo, aqui só entra se for mix de upar level...
				// pois o upgrade==true é somente para upgrade de level
				else if($item->used == true && $item->upgrade == true) {
					// caso a maquina calculou sucesso, atualiza o item
					if($mixResultSuccess == true) {
						$itemObject = $inventoryHelper->codeGroup[$item->slot];

						$properties = array(
							"serial" => $itemObject["itemSerial"], 
							"level" => $mix["result"]["newLevel"], //atualiza o novo level
							"option" => $itemObject["itemOption"], 
							"skill" => $itemObject["itemSkill"], 
							"luck" => $itemObject["itemLuck"], 
							"durability" => $itemObject["itemDurability"], 
							"excellent" => array($itemObject["itemExcellents"][0], $itemObject["itemExcellents"][1], $itemObject["itemExcellents"][2], $itemObject["itemExcellents"][3], $itemObject["itemExcellents"][4], $itemObject["itemExcellents"][5]), 
							"ancient" => $itemObject["itemAncient"],
							"refine" => $itemObject["itemRefine"], 
							"harmonyType" => $itemObject["harmonyType"], 
							"harmonyLevel" => $itemObject["harmonyLevel"], 
							"socketOption" => array($itemObject["socket"][0], $itemObject["socket"][1], $itemObject["socket"][2], $itemObject["socket"][3], $itemObject["socket"][4])
	                    );

						$hexTemp = ItemCreateHelper::createItem($databaseModel, $itemObject["itemIdSection"], $itemObject["itemIdIndex"], $properties);
						$inventoryHelper->insertItemInSlot($hexTemp, $item->slot); // atualizando ou apagando o item
					} 
					// no caso de erro, apaga o item
					else {
						$hexTemp = ItemCreateHelper::createEmptyItem($databaseModel);
						$inventoryHelper->insertItemInSlot($hexTemp, $item->slot); // apagando o item
					}
				}

			}

			// agora que o processo está praticamente pronto, devemos criar o novo item CASO o mix seja typeMix == 1
			// e tambem a maquina tenha calculado que o mix é success
			if($mix["details"]["typeMix"] == 1 && $mixResultSuccess == true) {
				//criar o item do resultado
				$properties = array(
					"serial" => 0, //pegar isso no DAO
					"level" => $mix["result"]["level"],
					"option" => $mix["result"]["options"], 
					"skill" => $mix["result"]["skill"], 
					"luck" => $mix["result"]["luck"], 
					"durability" => 255, 
					"excellent" => $mix["result"]["excellents"], 
					"ancient" => 0,
					"refine" => 0, 
					"harmonyType" => 0, 
					"harmonyLevel" => 0, 
					"socketOption" => array(255,255,255,255,255)
                );
				$hexTemp = ItemCreateHelper::createItem($databaseModel, $mix["result"]["section"], $mix["result"]["index"], $properties);
				
				// testa se o inventário possui espaço para acomodar o novo item
				$itemSizeX = ItemDatabaseHelper::$dbItem[$mix["result"]["section"]][$mix["result"]["index"]]["x"];
				$itemSizeY = ItemDatabaseHelper::$dbItem[$mix["result"]["section"]][$mix["result"]["index"]]["y"];
				$putIntoSlot = $inventoryHelper->searchSlotsInInventory($itemSizeX, $itemSizeY);

				// testa se possui espaço para criar o item
				if($putIntoSlot == -1) {
					Http::response(array(
						"success" => false,
						"code" => "MACHINE_MIX_STATUS_NOT_HAVE_SPACE",
						"size" => array("x" => $itemSizeX, "y" => $itemSizeY)
					));
				}
				// insere o novo item no inventário :D
				$inventoryHelper->insertItemInSlot($hexTemp, $putIntoSlot);
			}

			// exportar o inventário pois está tudo correto :)
			$inventoryDump = $inventoryHelper->exportDump();

			// gravar no banco de dados
			$dao->setCharacterInventory($this->params->character, $inventoryDump);

			// envia a resposta se deu certo ou não.
			Http::response(array(
				"success" => $mixResultSuccess,
				"code" => $mixResultSuccess ? "MACHINE_MIX_STATUS_SUCCESS" : "MACHINE_MIX_STATUS_RATE_FAIL"
			));
			// end mix
		}

		Http::response($mix["requirements"]);
	}


}