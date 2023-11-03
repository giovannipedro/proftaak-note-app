<?php

	class rPDO extends PDO
	{

		const hostname = "localhost";
		const username = 'alexauou';
		const password = '0409-DerpCpanel!';
		const database = 'alexauou_notes';
		public $_pdoConn = null;
		public function __construct()
		{
		
			try {
				$this->_pdoConn = new parent("mysql:host=" . self::hostname . ";dbname=" . self::database, self::username, self::password);
				$this->_pdoConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->_pdoConn->query("SET NAMES utf8");
			} catch(PDOException $e) {
				die("SQL Error: " . $e->getMessage());
			}
					
		}
		public function __destruct(){
			$this->_pdoConn = null;
		}


		public function error(){
			return $this->_pdoConn->errorInfo();
		}
		
		public function Req($Query, $Params){
			$this->_pdoConn->query("SET NAMES utf8");
			$pdoQuery = $this->_pdoConn->prepare($Query);
			foreach($Params as $key => &$value) {
				$pdoQuery->bindParam($key+1, $value);
			}
			$pdoQuery->execute();
			return $pdoQuery;
		}

		public function QueryArray($Query, $Args){
			$pdoQuery = $this->_pdoConn->prepare($Query);
			$pdoQuery->execute($Args);
			return $pdoQuery;
		}

		public function getLastInsertedID()
		{
			return $this->_pdoConn->lastInsertId();
		}
	}
	$db = new rPDO();
?>