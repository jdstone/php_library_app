<?php
/************************************************
* Module: MySQL.inc.php							*
* Author Name: J.D. Stone						*
* 												*
* Purpose: Database interaction helper methods	*
*************************************************/

class MySQL {
	// Declare variables
	public $dblink;
	public $db_username;
	public $db_password;
	public $db_name;

	public function __construct() {
		// "In Main (BaseClass) constructor"
		$this->db_name = "";
		$this->db_username = "";
		$this->db_password = "";
	}

	// 'set' Methods
	function setDatabase($db) {
		$this->db_name = $db;
	}

	function setDbUsername($dbUsername) {
		$this->db_username = $dbUsername;
	}

	function setDbPassword($dbPassword) {
		$this->db_password = $dbPassword;
	}

	// MySQL database connection function
	public function dbConnect() {
		$this->dblink = new mysqli('localhost', $this->db_username, $this->db_password, $this->db_name);
		if ($this->dblink->connect_error) {
			die('Connect Error ('.$this->dblink->connect_errno.')'
				.$this->dblink->connect_error);
		}
	}

	// MySQL database query function
	public function doQuery($query) {
		$result = $this->dblink->query($query, MYSQLI_STORE_RESULT);
		if ($this->dblink->error) {
			die('Query Error ('.$this->dblink->errno.')'
				.$this->dblink->error);
		}
		return $result;
	}
}
?>