<?php
	class PlaylistAll {
	    
        public static function create() {
            return new self();
        }
        
		private function __construct() {
			$this->request = Request::create();
			$this->filter = Filter::create();
			$this->playlist = Playlist::create();
			$this->db = MySql::create();
			$this->allowTime = $allow_time;
		}

		public function handler() {
			$notice = array();
			$this->clean();
			$order = $this->getorder();
			if ($order) {
				$notice['order'] = $this->order($order);
			}
			return $notice;
		}

		public function getSongList() {
			$search = $this->getSearch();
			$letter = $this->getLetter();
			if (!empty($search)) {
				return $this->getSongListWitchSearch();
			}
			if (!empty($letter)) {
				return $this->getSongListWitchLetter();
			}

			return $this->getSongListWitchNoFilter();
		}

		private function getSongListWitchNoFilter() {
			$ne_pokazivat = $this->playlist->getNePokazivat();
			$sortArray = $this->getSortArray();
			$query = "SELECT * FROM `songlist` WHERE $ne_pokazivat ORDER BY `".$sortArray['value']."` ".$sortArray['obr'];
			$this->vsegoPesen = $this->db->getCountRow($query);
			return $this->db->getLines($query." LIMIT ".$this->getStart().",".$this->getLimit());
		}

		private function getSongListWitchLetter() {
			$ne_pokazivat = $this->playlist->getNePokazivat();
        	$letter = $this->getLetter();
        	$sortArray = $this->getSortArray();
        	if ($letter == "0-9") {
        		$query = "SELECT * FROM `songlist` WHERE ($ne_pokazivat) and `artist` LIKE '0%' or `artist` LIKE '1%' or `artist` LIKE '2%' or `artist` LIKE '3%' or `artist` LIKE '4%' or `artist` LIKE '5%' or `artist` LIKE '6%' or `artist` LIKE '7%' or `artist` LIKE '8%' or `artist` LIKE '9%' ORDER BY `".$sortArray['value']."` ".$sortArray['obr'];
        		$this->vsegoPesen = $this->db->getCountRow($query);
        		return $this->db->getLines($query." LIMIT ".$this->getStart().",".$this->getLimit());
        	} else {
        		$query = "SELECT * FROM `songlist` WHERE ($ne_pokazivat) and `artist` LIKE '".$letter."%' ORDER BY `".$sortArray['value']."` ".$sortArray['obr'];
                $this->vsegoPesen = $this->db->getCountRow($query);
                return $this->db->getLines($query." LIMIT ".$this->getStart().",".$this->getLimit());
        	}
		}

		private function getSongListWitchSearch() {
			$search = $this->getSearch();
			$sortArray = $this->getSortArray();
			$ne_pokazivat = $this->playlist->getNePokazivat();
			
			if ($this->request->hasGetVar('sort')) {
                $sort = "ORDER BY `".$sortArray['value']."` ".$sortArray['obr'];
            } else {
                $sort = "";
            }
			
			$query = "SELECT * FROM `songlist` WHERE ($ne_pokazivat) and MATCH (`artist`, `title`) AGAINST ('$search') $sort";
			$this->vsegoPesen = $this->db->getCountRow($query);
			
			return $this->db->getLines($query." LIMIT ".$this->getStart().",".$this->getLimit());
		}

		public function getSortArray() {
			if ($this->request->hasGetVar('sort')) {
				$sort = array();
				$sortString = $this->request->getGetVar('sort');
				$sortString = str_replace('%21', '!', $sortString);
				if ($sortString[0] == "!"){
					$sort['obr'] = "DESC";
					$sort['value'] = substr($sortString, 1);
					$sort['string'] = $sortString;
				} else {
					$sort['obr'] = "ASC";
					$sort['value'] = $sortString;
					$sort['string'] = $sortString;
				}
			} else {
				$sort['obr'] = "DESC";
				$sort['value'] = "orderano";
				$sort['string'] = "!orderano";
			}
			
			$sort['value'] = addslashes($sort['value']);
			
			return $sort;
		}

		public function getVsegoPesen() {
			return $this->vsegoPesen;
		}

		public function order($order) {
			$return = array();
        	$query = "SELECT * FROM `playlist` WHERE `now` = 1 ";
			$now_play = $this->db->getColumn($query, 'now');
			$allow_order = $this->db->getColumn($query, 'allow_order');
			$on_air = $this->getStatus();

			if ( $allow_order != 1 or $on_air == "2" or $on_air == "0" ) {
				if ($allow_order != 1) {
					$return[] = _("Orders under maintain. Try again later.");
				}
				if ($on_air == "2") {
					$return[] = _("Can not be ordered during radioshow.");
				}
				if ($on_air == "0") {
					$return[] = _("Unfortunately it does not work.");
				}
			} else {
				$proverka_realip = $this->request->getServerVar('REMOTE_ADDR');
				$proverka_gettime_now = date('U');
				$proverka_gettime = date('U')+900;

				$query = " SELECT * FROM `user_ip` WHERE `ip` = '$proverka_realip' and `nomer` >= 1 ";
				if ($this->db->getColumn($query, 'ip') == $proverka_realip) {
					$return[] = _("You may order only one track in 15 minues, please wait.");
				}

				// Запрос на проверку одинаковых песен
				$query = " SELECT * FROM `order` WHERE `idsong` = $order ";
				$odinakovie_pesni = $this->db->getColumn($query, 'idsong');
				if (($odinakovie_pesni != "") and ($odinakovie_pesni == $order)) {
					$return[] = _('This track already has been ordered');
				}

				// Считаем количество заказов
				$query = " SELECT * FROM `order`";
			    if ($this->db->getCountRow($query) >= LIMIT_orderOV) {
			        if ($this->getAllowTime() > date("U")) {
			    		$return[] = _("Too late for orders, please try after ").$this->getPosle()._("of local time.");
			    	}

			    }

			    // Getting artist - title
			    $query = " SELECT * FROM `songlist` WHERE `idsong` = $order ";
				$proverka_full = $this->db->getColumn($query, 'artist')." - ".$this->db->getColumn($query, 'title');

			 	// Check if recently played
			 	$query = " SELECT * FROM `tracklist` WHERE `title` = '".addslashes($proverka_full)."'";

				if ($this->db->getColumn($query, 'title')) {
					$return[] = _("This track is recently played. Not for orders.");
				}

				if (empty($return)) {
					// Adding orders to tre array songlist
					$query = " SELECT * FROM `songlist` WHERE `idsong` = $order ";
					$line = $this->db->getLine($query);

				    $order_track = $line['artist']." - ".$line['title'];
				    $query = "SELECT * FROM `order`";
					$status_orderov_imeetsa = $this->db->getCountRow($query)+1;

					// adding order to order
					$query = "INSERT INTO `last_order` (`track` , `time` , `skolko`  , `ip` , `idsong`, `id` )
						VALUES (
							'".addslashes($order_track)."',
							'$proverka_gettime_now',
							'$status_orderov_imeetsa',
							'$proverka_realip',
							'".$line['idsong']."',
							'".$line['id']."'
						)";
					$this->db->queryNull($query);	
				
					$query = "SELECT * FROM `last_order`";
					$status_zapisei = $this->db->getCountRow($query);
					$query = "DELETE FROM `last_order` WHERE $status_zapisei>100 ORDER BY `time` LIMIT 2;";
					$this->db->queryNull($query);

					$query = "INSERT INTO `order` (`idsong` ,`filename` , `artist` , `title` , `album` , `duration` )
						VALUES (
							'".$line['idsong']."',
							'".addslashes($line['filename'])."',
							'".addslashes($line['artist'])."',
							'".addslashes($line['title'])."',
							'".addslashes($line['album'])."',
							'".$line['duration']."'
						)";
					$this->db->queryNull($query);
					$return[] =  _("Order confirmed an will be set 20 minutes after ").$this->getPosle()._("of local time.");

					$query = " UPDATE `songlist` SET `orderano` = `orderano`+1 WHERE `filename` = '".addslashes($line['filename'])."' ";
					$this->db->queryNull($query);

					$query = " SELECT * FROM `user_ip` WHERE `ip` = '$proverka_realip' ";
					if (!$this->db->getLine($query)) {
						$query = "INSERT INTO `user_ip` (`ip` , `time` , `nomer` ) VALUES ( '$proverka_realip', '$proverka_gettime', '1' )";
						$this->db->queryNull($query);
					}
				}
			}

			return $return;
		}

		public function getPosle() {
			$posle =  date("H:i", $this->getAllowTime()+120);
			if (date("U") > $this->getAllowTime()) {
				$posle =  date("H:i", date("U")+120);
			}
			return $posle;
		}

		public function getAllowTime() {
			return $this->allowTime;
		}

		public function getStatus() {
			$query = "SELECT * FROM `settings` WHERE `name` = 'online' LIMIT 1 ";
			return $this->db->getColumn($query, 'value');
		}

		private function getorder() {
			$order = false;
			for ($k=0; $k<$this->getLimit(); $k++) {
				$order_proverka = "order_".$k."_x";
				$order_nomer = "order_".$k;
				if (!empty($_POST[$order_proverka])) {
					$order = intval($_POST[$order_nomer]);
				}
			}
			return $order;
		}

		private function clean() {
			$query = "SELECT * FROM `user_ip`";
			foreach ($this->db->getLines($query) as $line) {
				if ($line['time'] < date('U')) {
					$query = " DELETE FROM `user_ip` WHERE `user_ip`.`id` = '".$line['id']."' LIMIT 1 ";
					$this->db->queryNull($query);
				}
			}
		}

		public function setUrlStart($url) {
        	$this->urlStart = $url;
		}

		public function getUrlStart() {
        	return "http://".$this->request->getServerVar('HTTP_HOST').$this->request->getServerVar('PHP_SELF');
		}

		public function getStart() {
			if ($this->request->hasGetVar('start')) {
				return (int) $this->request->getGetVar('start');
			} else {
				return 0;
			}
		}

		public function getLimit() {
			if ($this->request->hasGetVar('limit')) {
				return (int) $this->request->getGetVar('limit');
			} else {
				return 25;
			}
		}

		public function getSort() {
			if ($this->request->hasGetVar('sort')) {
			    $sortString = $this->request->getGetVar('sort');
			    $sortString = str_replace('%21', '!', $sortString);
				return $sortString;
			} else {
				return "!orderano";
			}
		}

		public function getSearch() {
			if ($this->request->hasGetVar('search')) {
				$search = $this->request->getGetVar('search');
				$search = htmlspecialchars($search, ENT_QUOTES, "utf-8");

				if (TRANSLIT == "on") {
					$search = $this->filter->translit($search);
				}

				return $search;
			} else {
				return "";
			}
		}
        
        public function getSearchString() {
            if ($this->request->hasGetVar('search')) {
                return str_replace('"', '', $this->request->getGetVar('search'));
            } else {
                return "";
            } 
        }       

		public function getLetter() {
			if ($this->request->hasGetVar('letter')) {
				return addslashes($this->request->getGetVar('letter'));
			} else {
				return "";
			}
		}
	}
?>