<?php
// character dap
class CharacterDAO extends BaseDAO {

	// get character list
	public function getCharacterList() {
		$stmt = $this->databaseAdapter->prepare("SELECT [Name] FROM [" . $this->databaseAdapter->databaseSettings["database"]["character"] . "].[dbo].[Character] WHERE [AccountID] = @AccountID ORDER BY [Name] ASC;");
		$stmt->bindValue("@AccountID", $_SESSION["memb___id"], sqlServerStatement::PARAM_STR);
		$stmt->execute();

		$list = array();
		foreach($stmt->fetchAll(sqlServerStatement::FETCH_OBJ) as $character) {
			$list[] = array("name" => $character->Name);
		}
		return $list;
	}

	// get character inventory
	public function getCharacterInventory($character) {
		$length = 0;
		switch($this->databaseAdapter->databaseSettings["databaseModel"]) {
			case 1: $length = 10 * 76; break;
			case 2: $length = 16 * 108; break;
			case 3: $length = 16 * (108 + 128); break;
		}
		$stmt = $this->databaseAdapter->prepare("SELECT CONVERT(TEXT, CONVERT(VARCHAR(" . $length . "), Inventory)) [Inventory] FROM [" . $this->databaseAdapter->databaseSettings["database"]["character"] . "].[dbo].[Character] WHERE [AccountID] = @AccountId AND [Name] = @Name;");
		$stmt->bindValue("@AccountId", $_SESSION["memb___id"], sqlServerStatement::PARAM_STR);
		$stmt->bindValue("@Name", $character, sqlServerStatement::PARAM_STR);
		$stmt->execute();
		return strtoupper(bin2hex($stmt->fetchObject()->Inventory));
	}

	public function setCharacterInventory($character, $hexDump) {
		$stmt = $this->databaseAdapter->prepare("UPDATE [" . $this->databaseAdapter->databaseSettings["database"]["character"] . "].[dbo].[Character] SET [Inventory] = 0x" . $hexDump . " WHERE [AccountID] = @AccountId AND [Name] = @Name;");
		$stmt->bindValue("@AccountId", $_SESSION["memb___id"], sqlServerStatement::PARAM_STR);
		$stmt->bindValue("@Name", $character, sqlServerStatement::PARAM_STR);
		$stmt->execute();
	}

}