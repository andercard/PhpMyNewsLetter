<?php

if (!defined("_DB_MYSQL_LAYER")) {

	define("_DB_MYSQL_LAYER", 1);

	class Db {
	
		var $ConnectionID;
		var $DatabaseName;
		var $Result;
		var $Row;

		//
		function DbConnect($host = false, $user = false, $password = "", $database = false) {
		
			$sortie = false;
			
			if ($host && $user) {
				$this->ConnectionID = mysql_connect($host, $user, $password);
				if ($this->ConnectionID) {
					$sortie = true;
					if ($database) {
						$sortie = $this->DbSelectDatabase($database);
					}
				}
			}

			return $sortie;	
		}

		//
		function DbSelectDatabase($database = false) {
			
			$sortie = false;
			
			if ($database) {
				$this->DatabaseName = $database;
				if ($this->ConnectionID) {
					$sortie = mysql_select_db($database, $this->ConnectionID);		
				}
			}
			
			return $sortie;	
		}

		//
		function DbQuery($query = false, $start = false, $limit = false) {
			
			$sortie = false;
			
			if ($this->ConnectionID && $query) {
				if ($start && $limit) {
					$query .= ' LIMIT ' . $start . ',' . $limit;
				}
				$this->Result = mysql_query($query, $this->ConnectionID);
				$sortie = $this->Result;
			}
			
			return $sortie;
		}

		//
		function DbNumRows() {
			
			$sortie = false;
			
			if ($this->Result) {
				$sortie = mysql_num_rows($this->Result);
			}
			
			return $sortie;
		}

		//
		function DbNextRow() {
			
			$sortie = false;
			
			if ($this->Result) {
				$this->Row = @mysql_fetch_array($this->Result);
				$sortie = $this->Row;
			}
			
			return $sortie;
		}

		//
		function DbError() {
			
			$sortie = false;
			
			$sortie = mysql_error();
			
			return $sortie;
		}

		//
		function DbCreate($db_name = false) {
		
			$sortie = 0;
			
			if ($this->ConnectionID && $db_name) {
				if(mysql_query("CREATE DATABASE " . $db_name)) {
					$sortie = 1;
				}
			}
			
			return $sortie;
		}

		//
		function DbAffectedRows() {
			$sortie = false;
			
			$sortie = @mysql_affected_rows($this->ConnectionID);
			
			return $sortie;
		}

	}

	//
	function DbError() {
		$sortie = false;
		
		$sortie = mysql_error();
		
		return $sortie;		
	}

}

?>