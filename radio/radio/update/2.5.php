<?php
    /*
     * RadioCMS update form 2.4 to 2.5
     */
    
    include 'Update_2.5.class.php';
    include '../_config.php';
    include('../Include.php'); 
?>
   <h1>Обновление RadioCMS 2.4 до версии 2.5</h1>
<?php  
    $db = MySql::create(); 
    $ssh = Ssh::create();
    $settings = Setting::create();
    $radioPath = Request::create()->getRadioPath();
    $update = new Update_2_5();
     
    if (RADIOCMS_VERSION == "2.5") {
        echo "<p>Уже обновлено</p>";
        exit;
    }
    
    if (RADIOCMS_VERSION != "2.4") {
        echo "<p>Обновление возможно только с версии 2.4</p>";
        exit;
    }
    
    $db->queryNull("alter table statistic change client client varchar(150)");
    $db->queryNull("alter table songlist add fulltext(artist, title)");
    $db->queryNull("alter table songlist add column played int(1) default 0 after duration");
    $settings->saveConfig('RADIOCMS_VERSION', '2.5');
    
    @unlink('2.5.php');
    $ssh->sshExec('rm '.$radioPath.'update/2.5.php'); 
    
    echo "<p>Результат: обновлено до RadioCMS 2.5</p>";
?>

    <br>
    <input class="button" type="button" value="Перейти в админку" name="B1" onClick="location.href='/radio/index.php'">


