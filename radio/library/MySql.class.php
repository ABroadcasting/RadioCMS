<?php
	class MySql {
		
		public static $object;
		
		public static function create() {
			if (self::$object === null) {
				self::$object = new self();
			}
			
			return self::$object;
		}
		
		private function __construct() {
			$this->link = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD)
		  		or die (_("Could not connect to MySQL"));
		
			mysqli_select_db(DB_NAME)
		  		or die (_("Could not select database"));
		
			mysqli_query("SET NAMES 'utf8'", $this->link);
            
            $this->request = Request::create();
		}
		
		public function queryNull($query) {
			mysqli_query($query, $this->link) or die($this->debug());
		}

		public function getLine($query) {
			$result = mysqli_query($query, $this->link) or die($this->debug());
			return mysqli_fetch_array($result, MYSQLI_ASSOC);
		}

		public function getColumn($query, $column) {
			$result = mysqli_query($query, $this->link) or die($this->debug());
			$line =  mysqli_fetch_array($result, MYSQLI_ASSOC);
			return $line[$column];
		}

		public function getLines($query) {
			$result = mysqli_query($query, $this->link) or die($this->debug());
			$lines = array();
			while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    			$lines[] = $line;
			}

			return $lines;
		}

		public function getCountRow($query) {
			$result = mysqli_query($query, $this->link) or die($this->debug());
        	return mysqli_num_rows($result);
		}
		
		public function debug() {
			include($this->request->getRadioPath().'Tpl/debug.tpl.html');
			exit;
		}
	}
?>