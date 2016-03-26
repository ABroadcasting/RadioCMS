<?php
    /*
     * RadioCMS update form 2.3 to 2.4
     */
     
    class Update_2_4 {
        public $per = "\n";
        public function __construct() {
            
            if (!is_writable('../_config.php')) {
                echo "<p>Файлы _config.php не доступен для записи</p>";
                exit;
            }
                
            $this->filename = "../_config.php";
            $this->file = file($this->filename);
        }               

        // based
        
        public function addConfig($const, $value, $afterConst) {
            $value = htmlspecialchars($value, ENT_QUOTES, "utf-8");
            for ($i=0; $i<count($this->file); $i++) {
                if (strpos($this->file[$i], "define('$afterConst'")) {
                    $this->file[$i] .= "\t"."define('$const', '$value');".$this->per;
                    $h = fopen($this->filename, 'w+');
                    fwrite($h, implode($this->file, ""));
                    fclose($h);
                }
            }
        }
    }
?>