<?php
class BaseDAO {
	// database adapter
	protected $databaseAdapter = null;

	// construct
	public function __construct($databaseAdapter) {
		$this->databaseAdapter = $databaseAdapter;
	}
}