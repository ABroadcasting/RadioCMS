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

	$manager = Manager::create();
	$manager->handler();

	header ("Location: manager.php?fold=".$manager->getFolder()."&start=".$manager->getStart()."&search=".$manager->getSearch());
?>

