<?php
    /*
     * RadioCMS update form 2.2 to 2.3
     */
     
    class Update_2_3 {
        public $per = "\n";
        public function __construct() {
            
            if (!is_writable('../_config.php') and !is_writable('../_system.php')) {
                echo "<p>Файлы _config.php и\или _system.php не доступны для записи</p>";
                exit;
            }
                
            $this->filename = "../_config.php";
            $this->file = file($this->filename);
                       
            $set_db = '../set_db.php';
            $config = '../config.php';
            
            if (file_exists($set_db) and file_exists($set_db)) {
                include $config;
                include $set_db;
            } else {
                echo "<p>Уже обновлено</p>";
                exit;
            }    
            
            $this->db_name = $base_name;
            
            $this->tables = array(
                'dj',
                'last_zakaz',
                'login',
                'playlist',
                'poisk',
                'settings',
                'songlist',
                'statistic',
                'tracklist',
                'user_ip',
                'zakaz'
            );
            $this->tableData = array();
        }
        
        public function dropTables() {
            $query = "DROP TABLE IF EXISTS `country_ip`";
            $this->queryNull($query);
            foreach ($this->tables as $table) {
                $query = "DROP TABLE `$table`";
                $this->queryNull($query);
            }
        }
        
        public function dbToUtf8() {
            foreach ($this->tableData as $tableName=>$table) {
                foreach ($table as $index=>$line) {
                    foreach ($line as $column=>$value) {
                        $this->tableData[$tableName][$index][$column] = 
                            iconv('cp1251', 'utf-8', $value);
                    }
                }
            }
        }
        
        public function importDb() {
            foreach ($this->tableData as $tableName=>$table) {               
                foreach ($table as $line) {
                    $columns = "";
                    $values = "";
                    foreach ($line as $column=>$value) {                  
                        if (empty($value)) {
                            continue;
                        }
                        
                        if (empty($columns)) {
                            $columns = "`".$column."`";
                        } else {
                            $columns .= ",`".$column."`";
                        }
                        
                        if (empty($values)) {
                            $values = "'".addslashes($value)."'";;
                        } else {
                            $values .= ",'".addslashes($value)."'";;
                        }
                    }  
                    if (!empty($columns) and !empty($values)) {
                        $query = "INSERT INTO `$tableName` ($columns) VALUES ($values);";
                        try {
                            $this->queryNull($query);  
                        } catch(DuplicateException $e) {
                            /* nothing */
                        }     
                    }   
                }
            }
        }
        
        public function exportDb() {
            foreach ($this->tables as $table) {
                $query = "SELECT * FROM `$table`";
                $this->tableData[$table] = $this->getLines($query);
            }
        }      
        
        public function queryNull($query) {
            $result = mysql_query($query);
        }
        
        public function getLines($query) {
            $result = mysql_query($query) or die("Install query failed : " . mysql_error());
            $lines = array();
            while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $lines[] = $line;
            }

            return $lines;
        }
        
        public function createNewTables() {
            mysql_query("SET NAMES 'utf8'") 
                or die("Install query failed : " . mysql_error());
            mysql_query("ALTER DATABASE `".$this->db_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `last_zakaz` (
              `id` varchar(15) NOT NULL,
              `idsong` varchar(15) NOT NULL,
              `track` varchar(100) NOT NULL,
              `time` varchar(25) NOT NULL,
              `skolko` varchar(10) NOT NULL,
              `ip` varchar(25) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `login` (
              `ip` varchar(25) NOT NULL,
              `dj` varchar(50) NOT NULL,
              `raz` tinyint(10) NOT NULL,
              `time` varchar(25) NOT NULL,
              `hash` varchar(25) NOT NULL,
              `admin` int(1) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `playlist` (
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
              `allow_zakaz` int(11) default '1',
              `auto` int(11) default '0',
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `poisk` (
                `title` varchar(50) NOT NULL,
                `artist` varchar(50) NOT NULL,
                `id` int(10) NOT NULL,
                `idsong` int(11) NOT NULL,
                `filename` text NOT NULL,
                `duration` int(11) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `songlist` (
              `idsong` int(11) NOT NULL auto_increment,
              `zakazano` int(10) NOT NULL,
              `id` int(11) default NULL,
              `filename` text,
              `artist` text,
              `title` text,
              `album` text,
              `genre` text,
              `albumyear` int(11) default NULL,
              `duration` int(11) default NULL,
              `sort` int(11) default NULL,
              PRIMARY KEY  (`idsong`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `statistic` (
              `type` varchar(50) NOT NULL,
              `country` varchar(20) NOT NULL,
              `country_name` varchar(25) NOT NULL,
              `ip` varchar(50) NOT NULL,
              `client` varchar(50) NOT NULL,
              `listeners` varchar(15) NOT NULL,
              `time` int(20) NOT NULL,
              `date` varchar(10) NOT NULL,
              KEY `stream` (`listeners`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

            mysql_query("CREATE TABLE `tracklist` (
              `title` text,
              `id` int(20) NOT NULL auto_increment,
              `idsong` int(11) NOT NULL,
              `filename` varchar(200) NOT NULL,
              `time` varchar(25) NOT NULL,
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `user_ip` (
              `id` int(20) NOT NULL auto_increment,
              `ip` varchar(100) NOT NULL,
              `time` varchar(100) NOT NULL,
              `nomer` int(2) NOT NULL,
              PRIMARY KEY  (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `zakaz` (
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
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `settings` (
              `name` varchar(25) NOT NULL,
              `value` text NOT NULL,
              PRIMARY KEY  (`name`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
             or die("Install query failed : " . mysql_error());

            mysql_query("CREATE TABLE `dj` (
              `id` tinyint(50) NOT NULL auto_increment,
              `description` varchar(100) NOT NULL,
              `dj` varchar(50) NOT NULL,
              `password` varchar(50) NOT NULL,
              `admin` int(1) NOT NULL,
              PRIMARY KEY  (`id`),
              UNIQUE KEY `dj` (`dj`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;")
             or die("Install query failed : " . mysql_error());
        }
        
        // based
        
        public function saveConfig($const, $value) {
            $value = iconv('cp1251', 'utf-8', $value);
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
    }
?>