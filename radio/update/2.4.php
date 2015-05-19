<?php
    /*
     * RadioCMS update form 2.3 to 2.4
     */
    
    include 'Update_2.4.class.php';
    include '../_config.php';
?>
   <h1>Обновление RadioCMS 2.3 до версии 2.4</h1>
<?php  
    if (defined('ID3V1_CHARSET')) {
        echo "<p>Уже обновлено</p>";
        exit;
    }
    
    $update = new Update_2_4();
    $update->addConfig('ID3V1_CHARSET', 'cp1251', 'EXTERNAL_CHARSET');
    $update->addConfig('RADIOCMS_VERSION', '2.4', 'DIR_BITRATE');
    
    include('../Include.php');  
    $ssh = Ssh::create();
    $radioPath = Request::create()->getRadioPath();
      
    @unlink('2.3.php');
    @unlink('2.4.php');
    $ssh->sshExec('rm '.$radioPath.'update/2.3.php'); 
    $ssh->sshExec('rm '.$radioPath.'update/2.4.php'); 
    
    echo "<p>Результат: обновлено до RadioCMS 2.4</p>";
?>

    <br>
    <input class="button" type="button" value="Перейти в админку" name="B1" onClick="location.href='/radio/index.php'">


