<?php
	class Install {
	    
        public $per = "\n";
	    
        public static function create() {
            return new self();
        }
        
		private function __construct() {
			$this->request = Request::create();
            $this->filename = $this->request->getRadioPath()."_config.php";
            $this->file = file($this->filename);
		}

		public function getWgetCron() {
			return Ssh::create()->getWgetCommand()." http://".$this->request->getServerVar('HTTP_HOST')."/radio/"."event.php -O event.php";
		}

		public function getPhpCron() {
  			$file_adres = _("full/path/to/php ").$this->request->getServerVar('DOCUMENT_ROOT')."/radio/"."event.php";
			$file_adres = str_replace("//","/",$file_adres);
			return $file_adres;
		}

		public function ifstep5() {
			$user = $this->request->getPostVar('user');
			$password = $this->request->getPostVar('password');

			if (empty($user) or empty($password)) {
				return _("<p>Fields cannot be empty</p>");
			}

			$this->saveConfig('USER', $user);
            $this->saveConfig('PASSWORD', $password);

            header("Location: install.php?step=6");
		}

		public function ifstep4() {
			$play_list_file = $this->request->getPostVar('playlist');
			$cf_ezstream = $this->request->getPostVar('cf_ezstream');
			$cf_icecast = $this->request->getPostVar('cf_icecast');
			if (empty($play_list_file) or empty($cf_ezstream) or empty($cf_icecast)) {
				return _("<p>Please fill all fields.</p>");
			}
            
            if (!file_exists($cf_icecast)){
                return _("<p>Icecast configuration file not found.</p>");
            }
            
            if (!file_exists($cf_ezstream)) {
                return _("<p>Ezstream configuration file not found.</p>");
            }
            
			if (!file_exists($play_list_file)) {
				return _("<p>Playlist file not found.</p>");
			}

            $pos_vhoh = strrpos($play_list_file, "/");
            $folder_chmod = substr($play_list_file, 0, $pos_vhoh);
            Ssh::create()->sshExec("chmod 777 $folder_chmod && chmod 777 $play_list_file");
            
            $pos_vhoh = strrpos($cf_ezstream, "/");
            $folder_chmod = substr($cf_ezstream, 0, $pos_vhoh);
            Ssh::create()->sshExec("chmod 777 $folder_chmod && chmod 644 $cf_ezstream");
            
            $pos_vhoh = strrpos($cf_icecast, "/");
            $folder_chmod = substr($cf_icecast, 0, $pos_vhoh);
            Ssh::create()->sshExec("chmod 777 $folder_chmod && chmod 644 $cf_icecast");

            $this->saveConfig('PLAYLIST', $play_list_file);
            $this->saveConfig('CF_EZSTREAM', $cf_ezstream);
            $this->saveConfig('CF_ICECAST', $cf_icecast);

            $xml = simplexml_load_file($cf_icecast);
			$this->saveConfig('ICE_LOGIN', $xml->authentication->{'admin-user'});
			$this->saveConfig('ICE_PASS', $xml->authentication->{'admin-password'});

			header("Location: install.php?step=5");
		}

		public function ifstep3() {
			$con = @ssh2_connect($this->request->getPostVar('ip'), $this->request->getPostVar('ssh_port'));
			if(!@ssh2_auth_password($con, $this->request->getPostVar('ssh_user'), $this->request->getPostVar('ssh_pass'))) {
				return _("<p>Wrong login or password.</p>");
    		}
    		$this->saveConfig('IP', $this->request->getPostVar('ip'));
    		$this->saveConfig('URL', $this->request->getPostVar('url'));
    		$this->saveConfig('PORT', $this->request->getPostVar('port'));
    		$this->saveConfig('SSH_USER', $this->request->getPostVar('ssh_user'));
    		$this->saveConfig('SSH_PASS', $this->request->getPostVar('ssh_pass'));
			$this->saveConfig('SSH_PORT', $this->request->getPostVar('ssh_port'));
    		header("Location: install.php?step=4");
		}

		public function ifstep2() {
			$link = @mysqli_connect(
				$this->request->getPostVar('db_host'),
				$this->request->getPostVar('db_login'),
				$this->request->getPostVar('db_password')
			);
			$link_db = @mysqli_select_db($this->request->getPostVar('db_name'));

			if ($link and $link_db) {
				$this->saveConfig('DB_HOST', $this->request->getPostVar('db_host'));
				$this->saveConfig('DB_LOGIN', $this->request->getPostVar('db_login'));
				$this->saveConfig('DB_PASSWORD', $this->request->getPostVar('db_password'));
				$this->saveConfig('DB_NAME', $this->request->getPostVar('db_name'));
				$this->createTable($this->request->getPostVar('db_name'));
				header("Location: install.php?step=3");
			} else {
				return _("<p>Connection could not be established.</p>");
			}
		}

		public function createTable($db_name) {
			mysqli_query("SET NAMES 'utf8'") 
				or die(_("Install query failed : ") . mysqli_error());
			mysqli_query("ALTER DATABASE `".$db_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `last_order` (
			  `id` varchar(15) NOT NULL,
			  `idsong` varchar(15) NOT NULL,
			  `track` varchar(100) NOT NULL,
			  `time` varchar(25) NOT NULL,
			  `skolko` varchar(10) NOT NULL,
			  `ip` varchar(25) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `login` (
			  `ip` varchar(25) NOT NULL,
			  `dj` varchar(50) NOT NULL,
			  `raz` tinyint(10) NOT NULL,
			  `time` varchar(25) NOT NULL,
			  `hash` varchar(25) NOT NULL,
			  `admin` int(1) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `playlist` (
			  `id` int(11) NOT NULL auto_increment,
			  `name` text,
			  `playmode` tinyint(4) default NULL,
			  `enable` tinyint(4) default NULL,
			  `event1` text,
			  `event2` text,
			  `now` tinyint(4) default NULL,
			  `show` tinyint(4) default NULL,
			  `sort` int(11) default NULL,
			  `last_time` bigint(20) default NULL,
			  `allow_order` int(11) default '1',
			  `auto` int(11) default '0',
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `poisk` (
				`title` varchar(50) NOT NULL,
				`artist` varchar(50) NOT NULL,
				`id` int(10) NOT NULL,
				`idsong` int(11) NOT NULL,
				`filename` text NOT NULL,
				`duration` int(11) NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `songlist` (
			  `idsong` int(11) NOT NULL auto_increment,
			  `orderano` int(10) NOT NULL,
			  `id` int(11) default NULL,
			  `filename` text,
			  `artist` text,
			  `title` text,
			  `album` text,
			  `genre` text,
			  `albumyear` int(11) default NULL,
			  `duration` int(11) default NULL,
			  `played` int(1) default '0',
			  `sort` int(11) default NULL,
              PRIMARY KEY  (`idsong`),
              FULLTEXT KEY `artist` (`artist`,`title`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `statistic` (
			  `type` varchar(50) NOT NULL,
			  `country` varchar(20) NOT NULL,
			  `country_name` varchar(25) NOT NULL,
			  `ip` varchar(50) NOT NULL,
			  `client` varchar(150) NOT NULL,
			  `listeners` varchar(15) NOT NULL,
			  `time` int(20) NOT NULL,
			  `date` varchar(10) NOT NULL,
			  KEY `stream` (`listeners`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			mysqli_query("CREATE TABLE IF NOT EXISTS `tracklist` (
			  `title` text,
			  `id` int(20) NOT NULL auto_increment,
			  `idsong` int(11) NOT NULL,
			  `filename` varchar(200) NOT NULL,
			  `time` varchar(25) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `user_ip` (
			  `id` int(20) NOT NULL auto_increment,
			  `ip` varchar(100) NOT NULL,
			  `time` varchar(100) NOT NULL,
			  `nomer` int(2) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `order` (
			  `id` int(11) NOT NULL auto_increment,
			  `idsong` int(10) NOT NULL,
			  `filename` text,
			  `artist` text,
			  `title` text,
			  `album` text,
			  `duration` int(11) default NULL,
			  `admin` int(1) NOT NULL,
			  PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
			 or die(_("Install query failed : "). mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `settings` (
			  `name` varchar(25) NOT NULL,
			  `value` text NOT NULL,
			  PRIMARY KEY  (`name`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
			 or die(_("Install query failed : ") . mysqli_error());

			mysqli_query("CREATE TABLE IF NOT EXISTS `dj` (
			  `id` tinyint(50) NOT NULL auto_increment,
			  `description` varchar(100) NOT NULL,
			  `dj` varchar(50) NOT NULL,
			  `password` varchar(50) NOT NULL,
			  `admin` int(1) NOT NULL,
			  PRIMARY KEY  (`id`),
			  UNIQUE KEY `dj` (`dj`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;")
			 or die(_("Install query failed : ") . mysqli_error());
			 			 
             $this->saveSetting('main_text', _('Here you can keep some shared writings'));
             $this->saveSetting('online', '0');
		}

		public function getPerms($file) {
        	if (is_writable($file)) {
        		return _('<span class="green"><b>is writable</b></span>');
        	} else {
        		return _('<span class="red"><b>is not writable</b></span>');
        	}
		}

		public function ifPerms($file) {
        	if (is_writable($file)) {
        		return true;
        	} else {
        		return false;
        	}
		}

		public function getBaseDir() {
			$base_dir = ini_get("open_basedir");
   			if ($base_dir == "" or $base_dir == "/") {
   			    $base_dir = (empty($base_dir)) ? 'no_value' : $base_dir;
   				return '<span class="green"><b>'.$base_dir.'</b></span>';
   			} else {
   				return '<span class="red"><b>'.$base_dir.'</b></span>';
   			}
		}

		public function getSsh2() {
			if (function_exists("ssh2_connect")) {
				return _('<span class="green"><b>installed</b></span>');
			} else {
				return '<span class="red"><b>not installed</b></span>';
			}
		}
        
        public function getXML() {
            if (function_exists("simplexml_load_file")) {
                return _('<span class="green"><b>installed</b></span>');
            } else {
                return ('<span class="red"><b>not installed</b></span>');
            }
        }

		public function getCurl() {
			if (function_exists("curl_init")) {
				return _('<span class="green"><b>installed</b></span>');
			} else {
				return _('<span class="red"><b>not installed</b></span>');
			}
		}

		public function getIconv() {
			if (function_exists("iconv")) {
				return _('<span class="green"><b>installed</b></span>');
			} else {
				return _('<span class="red"><b>not installed</b></span>');
			}
		}

		public function getGd() {
			if (function_exists("imageCreate")) {
				return _('<span class="green"><b>installed</b></span>');
			} else {
				return _('<span class="red"><b>not installed</b></span>');
			}
		}

		public function isGreen($string) {
			if (strpos($string, 'green') !== false) {
				return true;
			} else {
				return false;
			}
		}

		public function addStatistic() {
			$add_site = "http://stat.soclan.ru/radio/stations.php?i_url=".URL."&i_ip=".IP;
			$this->request->get($add_site);
		}

		public function ifstep1() {
            if (
            	$this->isGreen(
            		$this->getPerms(MUSIC_PATH)
            	)	 and
            	$this->isGreen(
            		$this->getPerms($this->request->getRadioPath()."_config.php")
            	)	 and
            	$this->isGreen(
            		$this->getBaseDir()
            	)	 and
            	$this->isGreen(
            		$this->getSsh2()
            	)	 and
            	$this->isGreen(
            		$this->getCurl()
            	)	 and
            	$this->isGreen(
            		$this->getIconv()
            	)   and
                $this->isGreen(
                    $this->getGd()
                )    and
                $this->isGreen(
                    $this->getXML()
                )
            ) {
            	return true;
            } else {
            	return false;
            }
		}
		
        public function saveConfig($const, $value) {     
            $value = htmlspecialchars($value, ENT_QUOTES, "utf-8");
            for ($i=0; $i<count($this->file); $i++) {
                if (strpos($this->file[$i], "define('$const'")) {
                    $this->file[$i] = "\t"."define('$const', '$value');".$this->per;
                    $h = fopen($this->filename, 'w+');
                    fwrite($h, implode($this->file, ""));
                    fclose($h);
                }
            }
        }
        
        public function saveSetting($name, $value) {
            $query = "SELECT * FROM  `settings` WHERE `name`='$name' LIMIT 1";
            $line = $this->getLine($query);
            if (!empty($line)) {
                $query = "UPDATE `settings` SET `value` = '".addslashes($value)."' WHERE `name`= '$name';";
                 $this->queryNull($query);
            } else {
                $query = "INSERT INTO `settings` ( `name` , `value` ) VALUES ('$name', '".addslashes($value)."');";
                $this->queryNull($query);;
            }
        }
        
        public function getLine($query) {
            $result = mysqli_query($query) or die($this->debug());
            return mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        
        public function queryNull($query) {
            mysqli_query($query) or die($this->debug());
        }
	}
?>
