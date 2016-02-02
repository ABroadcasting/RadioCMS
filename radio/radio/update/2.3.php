<?php
    /*
     * RadioCMS update form 2.2 to 2.3
     */

    include 'Update_2.3.class.php';
?>
   <h1>Обновление RadioCMS 2.2 до версии 2.3</h1>
<?php
    $update = new Update_2_3();
    $update->exportDb();
    $update->dbToUtf8();
    $update->dropTables();
    $update->createNewTables();
    $update->importDb();

    echo "<p>Обновление базы: ok</p>";

    include '../set_db.php';
    include '../config.php';

    $update->saveConfig('USER', $user);
    $update->saveConfig('PASSWORD', $password);
    $update->saveConfig('IP', $ip);
    $update->saveConfig('URL', $adres);
    $update->saveConfig('PORT', $port);
    $update->saveConfig('CF_ICECAST', $cf_icecast);
    $update->saveConfig('CF_EZSTREAM', $cf_ezstream);
    $update->saveConfig('PLAYLIST', $play_list_file);
    $update->saveConfig('TEMP_UPLOAD', $temp_upload);
    $update->saveConfig('SSH_USER', $ssh_user);
    $update->saveConfig('SSH_PASS', $ssh_pass);
    $update->saveConfig('ICE_LOGIN', $ice_login);
    $update->saveConfig('ICE_PASS', $ice_pass);
    $update->saveConfig('SYSTEM_SYMVOL', $system_symvol);
    $update->saveConfig('NO_REPEAT', $net_povtorov);
    $update->saveConfig('LIMIT_EVENT', $limit_event);
    $update->saveConfig('LIMIT_ZAKAZOV', $limit_zakazov);
    $update->saveConfig('TRANSLIT', ($rustoen == 1) ? 'on' : 'off');
    $update->saveConfig('PERIOD', $period);

    $update->saveConfig('DIR_SHOW', $dir_show );
    $update->saveConfig('DIR_NAME', $dir_name);
    $update->saveConfig('DIR_URL', $dir_url);
    $update->saveConfig('DIR_STREAM', $dir_stream);
    $update->saveConfig('DIR_DESCRIPTION', $dir_description);
    $update->saveConfig('DIR_GENRE', $dir_genre);
    $update->saveConfig('DIR_BITRATE', $dir_bitrate);

    $update->saveConfig('DB_HOST', $base_host);
    $update->saveConfig('DB_LOGIN', $base_login);
    $update->saveConfig('DB_PASSWORD', $base_password);
    $update->saveConfig('DB_NAME', $base_name);

    echo "<p>Обновление конфига: ok</p>";
    
    echo "<p>Результат: обновлено до RadioCMS 2.3</p>";
    
    include('../Include.php');  
    $ssh = Ssh::create();
    $radioPath = Request::create()->getRadioPath();
      
    @unlink('../set_db.php');
    @unlink('../config.php');
    @unlink('2.3.php');
    $ssh->sshExec('rm '.$radioPath.'set_db.php');
    $ssh->sshExec('rm '.$radioPath.'config.php'); 
    $ssh->sshExec('rm '.$radioPath.'update/2.3.php'); 
     
    if (file_exists('../set_db.php') or file_exists('../config.php')) {
        echo "<p><b>Удалите файлы set_db.php и config.php вручную. Не запускайте данный скрипт повторно!<b></p>";
    }   
     
?>

    <br>
    <input class="button" type="button" value="Перейти в админку" name="B1" onClick="location.href='/radio/index.php'">


