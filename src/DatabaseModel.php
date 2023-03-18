<?php namespace Mamok\TestParser;

use \MeekroDB;
use \MeekroDBException;

/**
 * Database model class
 */
class DatabaseModel 
{
	/**
	 * Database connection instance
	 */
	private $dbInstance = null;


	public function __construct($host, $port, $database, $username, $password, $charset) {

		// Init db instance
		$this->dbInstance = new MeekroDB(
			$host,  $username, $password, $database, $port, $charset
		);

		$this->connectionCheck();
	}

	public function connectionCheck() {
		// Simply getting table list
		try {
			$tableList = $this->getTableList();
		} catch(MeekroDBException $ex) {
			throw new Error("Database connection not avaible");
		}
	}

	public function getTableList() {
		return $this->dbInstance->tableList();
	}

	public function instertToTendersParse($insertData) {
		$this->dbInstance->insert('tenders_parse', $insertData);
	}
}