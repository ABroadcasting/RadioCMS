<?php
    ob_start();
	require_once('Include.php');

	$requestFilter = RequestFilter::create();
	$auth = Autentification::create();
	$request = Request::create();
	$dateTime = Date::create();
	$security = Security::create();
	$filter = Filter::create();
	$ssh = Ssh::create();

	/* --------------------------------------- */

	$auth->handler();
	$user = $auth->getUser();

	if (empty($user)) {
		include('Tpl/login.tpl.html');
		exit;
	}

	/* You've been logged in as */
    if ($user['admin'] == 0) {
    	$prava = "DJ";
    } else {
    	$prava = _("Administrator");
    }

    include('Tpl/header.tpl.html');
?>
What the hell are you doing here?
