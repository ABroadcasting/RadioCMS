<?php
	ob_start();
	require_once('Include.php');

	$auth = Autentification::create();
	$user = $auth->getUser();
	$security = Security::create();

	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$meneger = Meneger::create();
	$meneger->handler();

	header ("Location: meneger.php?fold=".$meneger->getFolder()."&start=".$meneger->getStart()."&search=".$meneger->getSearch());
?>

