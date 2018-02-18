<?php
	class Date {
		public $authMinutes = 15;
        
        public static function create() {
            return new self();
        }

		private function __construct() {
			$this->now = date("U");
		}

		public function toLang($text) {
        	$text = str_replace("Monday", _("Monday"), $text);
			$text = str_replace("Tuesday", _("Tuesday"), $text);
			$text = str_replace("Wednesday", _("Wednesday"), $text);
			$text = str_replace("Thursday", _("Thursday"), $text);
			$text = str_replace("Friday", _("Friday"), $text);
			$text = str_replace("Saturday", _("Saturday"), $text);
			$text = str_replace("Sunday", _("Sunday"), $text);
			$text = str_replace("Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday", _("Everyday"), $text);

			return $text;
		}

		public function getNow() {

			return $this->now;
		}

		public function minutes($minutes) {
			return $minutes*60;
		}

		public function getAuthTime() {
			return $this->now+($this->authMinutes*60);
		}

		public function setTime($time) {
			$this->time = $time;
			return $this;
		}

		public function toFormatString($format) {
        	return date($format, $this->time);
		}

		public function toMinSec($second) {
			$return =  floor($second/60).":";
			$dur_minutes= fmod($second, 60);
        	if($dur_minutes < 10) {
				$return .= "0".$dur_minutes;
        	} else {
        		$return .= $dur_minutes;
        	}

        	return $return;
		}
	}
?>