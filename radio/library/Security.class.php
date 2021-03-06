<?php
	class Security {
	    
        public static $object;
        
        public static function create() {
            if (self::$object === null) {
                self::$object = new self();
            }
            
            return self::$object;
        }
        
		private function __construct() {
			$this->request = Request::create();
		}

		public function denied() {
			include("Tpl/securityDenied.tpl.html");
    		exit;
		}

		public function accessCheck($filename) {
			if (!strstr('*'.$filename, '*'.$this->request->getMusicPath())) {
        		die(_('ACCESS DENIED to ').$filename);
    		}
		}
	}
?>