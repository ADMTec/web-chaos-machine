<?php
// account dao
class AccountDAO extends BaseDAO {

	// check username and password
	public function checkUsernameAndPassword($username, $password) {
		$stmtQuery = null;
		if($this->databaseAdapter->databaseSettings["md5EncriptyPasswords"] == true) {
			$stmtQuery = "DECLARE @hash BINARY(16); " .
						 "EXEC master..XP_MD5_EncodeKeyVal @memb__pwd, @memb___id, @hash OUT; " .
						 "SELECT [memb___id] FROM [" . $this->databaseAdapter->databaseSettings["database"]["account"] . "].[dbo].[MEMB_INFO] WHERE [memb___id] = @memb___id AND [memb__pwd] = @hash;";
		} else {
			$stmtQuery = "SELECT [memb___id] FROM [" . $this->databaseAdapter->databaseSettings["database"]["account"] . "].[dbo].[MEMB_INFO] WHERE [memb___id] = @memb___id AND [memb__pwd] = @memb__pwd;";
		}
		$stmt = $this->databaseAdapter->prepare($stmtQuery);
		$stmt->bindValue("@memb___id", $username, sqlServerStatement::PARAM_STR);
		$stmt->bindValue("@memb__pwd", $password, sqlServerStatement::PARAM_STR);
		$stmt->execute();

		return $stmt->rowsCount() > 0;
	}

	// check username status
	public function checkUsernameConnected($username) {
		$stmt = $this->databaseAdapter->prepare("SELECT [ConnectStat] FROM [" . $this->databaseAdapter->databaseSettings["database"]["account"] . "].[dbo].[MEMB_STAT] WHERE [memb___id] = @memb___id;");
		$stmt->bindValue("@memb___id", $username, sqlServerStatement::PARAM_STR);
		$stmt->execute();

		if($stmt->rowsCount() > 0) {
			return $stmt->fetchObject()->ConnectStat == 1;
		} else {
			return 0;
		}
	}

}