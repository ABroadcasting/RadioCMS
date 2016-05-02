<?php
	ob_start();
	$vers = "2.6";
    
    include('Include.php');
    
	$request = Request::create();
	$ins = Install::create();

	$hag_install = _("Installation: Step 1 (Checking files and libraries)");
	$hag = 1;
	if (!empty($_GET['hag'])) {
		if ($_GET['hag'] == 2) { $hag = 2; $hag_install = _("Installation: Step 2 (Database settings)"); }
		if ($_GET['hag'] == 3) { $hag = 3; $hag_install = _("Installation: Step 3 (General settings)"); }
		if ($_GET['hag'] == 4) { $hag = 4; $hag_install = _("Installation: Step 4 (Setting path)"); }
		if ($_GET['hag'] == 5) { $hag = 5; $hag_install = _("Installation: Step 5 (Setting RadioCMS passwords)"); }
		if ($_GET['hag'] == 6) { $hag = 6; $hag_install = _("Installation: Step 6 (Finish installing)"); }
	}

	$action = "install.php?hag=$hag";
?>
<html>
	<head>
		<link rel="stylesheet" href="files/admin_style.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<style> form {margin:0;} </style>
	<title>Установка RadioCMS</title>

	<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td width="2" align="right"><img border="0" src="images/separator.jpg" width="1" height="122"></td>
				<td>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<table border="0" width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td width="324">
								<img border="0" src="images/navi_02.jpg" width="588" height="38"></td>
								<td background="images/navi_03.jpg" valign="top"><div class="navi_text"><?=IP?></a> | <?=date("H:i")?> | <a href="http://radiocms.ru/"><?php echo _('Exit');?></a><br><?php echo _('Installation');?> <?="RadioCMS ".$vers?></div></td>
							</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td background="images/navi_16.jpg"><img border="0" src="images/navi_05.jpg" width="100" height="84"><img border="0" src="images/separator.jpg" width="1" height="84"><img border="0" src="images/navi_07.jpg" width="100" height="84"><img border="0" src="images/separator.jpg" width="1" height="84"><img border="0" src="images/navi_09.jpg" width="100" height="84"><img border="0" src="images/separator.jpg" width="1" height="84"><img border="0" src="images/navi_11.jpg" width="100" height="84"><img border="0" src="images/separator.jpg" width="1" height="84"><img border="0" src="images/navi_17.jpg" width="100" height="84"><img border="0" src="images/separator.jpg" width="1" height="84"><img border="0" src="images/navi_13.jpg" width="100" height="84"><img border="0" src="images/separator.jpg" width="1" height="84"></tr>
				</table>
				</td>
				<td width="2" align="left"><img border="0" src="images/separator.jpg" width="1" height="122"></td>
			</tr>
		</table>

		<div class="body">
		<div class="title"><?=$hag_install?></div>
		<div class="border">
		<form method="POST" action="<?php echo $action; ?>">
<!-- ///////// 3 /////////////////////////////////////////////////////////////////// 3 ////////// -->
<?php
	if ($hag == 3) {
?>
			<table border="0" width="97%" cellpadding="0" class="paddingtable">
				<tr>
					<td width="15%" valign="top"><?php echo _('IP adress:');?><br>
					<div class="podpis"><?php echo _('for SSH connection');?></div></td>
					<td width="75%" valign="top">
						<input type="text" name="ip" size="35" value="<?=$request->hasPostVar('ip') ? $request->getPostVar('ip') : IP ?>">
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('WEB Adress');?><br>
					<div class="podpis"><?php echo _('full site adress witout / at the end');?></div></td>
					<td valign="top">
						<input type="text" name="url" size="35" value="<?=$request->hasPostVar('url') ? $request->getPostVar('url') : URL ?>">
					</td>
				</tr>
					<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('Port');?><br>
					<div class="podpis"><?php echo _('stream port');?></div></td>
					<td valign="top">
						<input type="text" name="port" size="35" value="<?=$request->hasPostVar('port') ? $request->getPostVar('port') : PORT ?>">
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('SSH Login (recommended root):');?></br></td>
					<td valign="top">
						<input type="text" name="ssh_user" size="35" value="<?=$request->hasPostVar('ssh_user') ? $request->getPostVar('ssh_user') : SSH_USER ?>">
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('SSH Password:');?></br></td>
					<td valign="top">
						<input type="password" name="ssh_pass" size="35" value="<?=$request->hasPostVar('ssh_pass') ? $request->getPostVar('ssh_pass') : SSH_PASS ?>">
					</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('SSH Port:');?></br></td>
					<td valign="top">
						<input type="text" name="ssh_port" size="35" value="<?=$request->hasPostVar('ssh_port') ? $request->getPostVar('ssh_port') : SSH_PORT ?>">
					</td>
				</tr>
			</table>
<?php
		if ($request->hasPostVar("hag3")) {
			echo $ins->ifHag3();
		}
?>
			<p>
				<input class="button" type="button" value="<?php echo _('Back');?>" name=B1 onClick="location.href='install.php?hag=2'">
	 			<input class="button" type="submit" value="<?php echo _('Next');?>" name="hag3">
	 		</p>
<?php
	}
?>

<!-- ///////// 4 /////////////////////////////////////////////////////////////////// 4 ////////// -->

<?php
	if ($hag == 4) {
?>
			<table border="0" width="97%" cellpadding="0" class="paddingtable">
				<tr>
					<td width="15%" valign="top"><?php echo _('IceCast configuration:');?></td>
					<td width="75%" valign="top">
						<input type="text" name="cf_icecast" size="55" value="<?=$request->hasPostVar('cf_icecast') ? $request->getPostVar('cf_icecast') : CF_ICECAST ?>"><br>
						<div class="podpis"><?php echo _('full path of the configuration file');?></div>
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('ezstream configuration:');?></td>
					<td valign="top">
						<input type="text" name="cf_ezstream" size="55" value="<?=$request->hasPostVar('cf_ezstream') ? $request->getPostVar('cf_ezstream') : CF_EZSTREAM ?>"><br>
						<div class="podpis"><?php echo _('full path of the configuration file');?></div>
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('playlist file');?></td>
					<td valign="top">
						<input type="text" name="playlist" size="55" value="<?=$request->hasPostVar('playlist') ? $request->getPostVar('playlist') : PLAYLIST ?>"><br>
						<div class="podpis"><?php echo _('full path of the configuration file');?></div>
					</td>
				</tr>
			</table>
<?php
		if ($request->hasPostVar("hag4")) {
			echo $ins->ifHag4();
		}
?>
			<p>
				<input class="button" type="button" value="<?php echo _('Back');?>" name="B1" onClick="location.href='?hag=3'">
				<input class="button" type="submit" name="hag4" value="<?php echo _('Next');?>">
			</p>
<?php
	}
?>

<!-- ///////// 5 /////////////////////////////////////////////////////////////////// 5 ////////// -->

<?php
	if ($hag == 5) {
?>
			<table border="0" width="97%" cellpadding="0" class="paddingtable">
				<tr>
					<td width="15%" valign="top"><?php echo _('Login:');?></td>
					<td width="75%" valign="top">
						<input type="text" name="user" size="55" value="<?=USER?>"><br>
						<div class="podpis"><?php echo _('to enter admin panel');?></div>
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('Password:');?></td>
					<td valign="top">
						<input type="text" name="password" size="55" value="<?=PASSWORD?>"><br>
						<div class="podpis"><?php echo _('type the password');?></div>
					</td>
				</tr>
			</table>
<?php
		if ($request->hasPostVar("hag5")) {
			echo $ins->ifHag5();
		}
?>
			<p>
				<input class="button" type="button" value="<?php echo _('Back');?>" name="B1" onClick="location.href='?hag=4'">
				<input class="button" type="submit" name="hag5" value="<?php echo _('Next');?>">
			</p>
<?php
	}
?>

<!-- ///////// 2 /////////////////////////////////////////////////////////////////// 2 ////////// -->

<?php
	if ($hag == 2) {
?>
			<table border="0" width="97%" cellpadding="0" class="paddingtable">
				<tr>
					<td width="150" valign="top"><span lang="en-us"><?php echo _('Server:');?></span><br>
					<div class="podpis"><?php echo _('usually localhost');?></div></td>
					<td valign="top">
						<input type="text" name="db_host" size="35" value="<?=$request->hasPostVar('db_host') ? $request->getPostVar('db_host') : DB_HOST?>">
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><span lang="en-us"><?php echo _('Login:');?></span><br>
					<div class="podpis"><?php echo _('set login');?></div></td>
					<td valign="top">
						<input type="text" name="db_login" size="35" value="<?=$request->hasPostVar('db_login') ? $request->getPostVar('db_login') : DB_LOGIN?>">
					</td>
					</tr>
					<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><span lang="en-us"><?php echo _('Password:');?></span><br>
					<div class="podpis"><?php echo _('Set password');?></div></td>
					<td valign="top">
						<input type="password" name="db_password" size="35" value="<?=$request->hasPostVar('db_password') ? $request->getPostVar('db_password') : DB_PASSWORD?>">
					</td>
				</tr>
				<tr>
					<td valign="top">&nbsp;</td>
					<td valign="top">&nbsp;</td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('Database:');?><br>
					<div class="podpis"><?php echo _('set the database name');?></div></td>
					<td valign="top">
						<input type="text" name="db_name" size="35" value="<?=$request->hasPostVar('db_name') ? $request->getPostVar('db_name') : DB_NAME?>">
					</td>
				</tr>
			</table>
<?php
		if ($request->hasPostVar("hag2")) {
			echo $ins->ifHag2();
		}
?>
			<p>
				<input class="button" type="button" value="<?php echo _('Back');?>" name="B1" onClick="location.href='?hag=1'">
	 			<input class="button" type="submit" value="<?php echo _('Next');?>" name="hag2">
	 		</p>
<?php
	}
?>


<?php
	if ($hag == 6) {
		$ins->addStatistic();
echo _('Greetings! You have successfully installed RadioCMS.
			To finish installing add this command to cron (every 3 minutes):').'<br><br>
			<div class="border">';?>
				<?=$ins->getWgetCron();?>
<?php echo '</div>
			<br>'.-('Other view of command:').'<br><br>
			<div class="border">';?>
				<?=$ins->getPhpCron();?>
<?php echo'	</div>
			<br>'._('In security reasons we recommend to delete install.php file.').'
			<br><br>
			<input class="button" type="button" value="'._('Go to panel').'" name="B1" onClick="location.href='index.php'">';
		
	}
?>

<!-- ///////// 1 /////////////////////////////////////////////////////////////////// 1 ////////// -->

<?php
	if ($hag == 1) {
?>
			<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
				<tr>
					<td width="20%" valign="top"><?php echo _('Description');?></td>
					<td width="15%" valign="top"><?php echo _('Current');?></td>
					<td width="65%" valign="top"><?php echo _('Need to be');?></td>
				</tr>
				<tr>
					<td bgcolor="#F5F4F7" valign="top"><?php echo _('Permissions for <b>music</b> folder');?></td>
					<td bgcolor="#F5F4F7" valign="top"><?=$ins->getPerms(MUSIC_PATH)?></td>
					<td bgcolor="#F5F4F7" valign="top"><span class="green"><?php echo _('is writable');?></span></td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('Permissions for <b>_config.php</b> file');?></td>
					<td valign="top"><?=$ins->getPerms($request->getRadioPath()."_config.php")?></td>
					<td valign="top"><span class="green"><?php echo _('is writable');?></span></td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('Permissions for <b>_system.php</b> file');?></td>
					<td valign="top"><b><?=$ins->getPerms($request->getRadioPath()."_system.php")?></b></td>
					<td valign="top"><span class="green"><?php echo _('is writable');?></span></td>
				</tr>
				<tr>
					<td bgcolor="#F5F4F7" valign="top"><?php echo _('<b>open_basedir</b> param');?></td>
					<td bgcolor="#F5F4F7" valign="top"><?=$ins->getBaseDir()?></td>
					<td bgcolor="#F5F4F7" valign="top"><span class="green"><?php echo _('/ or no_value');?></span></td>
				</tr>
				<tr>
					<td valign="top"><?php echo _('<b>libssh2</b> library');?></td>
					<td valign="top"><?=$ins->getSsh2()?></td>
					<td valign="top"><span class="green"><?php echo _('installed');?></span></td>
				</tr>
				<tr>
					<td bgcolor="#F5F4F7" valign="top"><?php echo _('<b>curl</b> library');?></td>
					<td bgcolor="#F5F4F7" valign="top"><?=$ins->getCurl()?></td>
					<td bgcolor="#F5F4F7" valign="top"><span class="green"><?php echo _('installed');?></span></td>
				</tr>
				<tr>
                    <td valign="top"><?php echo _('<b>SimpleXML</b> library');?></td>
                    <td valign="top"><?=$ins->getXML()?></td>
                    <td valign="top"><span class="green"><?php echo _('installed');?></span></td>
                </tr>
				<tr>
					<td valign="top"><?php echo _('<b>iconv</b> library');?></td>
					<td valign="top"><?=$ins->getIconv()?></b></td>
					<td valign="top"><span class="green"><?php echo _('installed');?></span></td>
				</tr>
				<tr>
					<td bgcolor="#F5F4F7" valign="top"><?php echo _('<b>gd2</b> library');?></td>
					<td bgcolor="#F5F4F7" valign="top"><?=$ins->getGd()?></td>
					<td bgcolor="#F5F4F7" valign="top"><span class="green"><?php echo _('installed');?></span></td>
				</tr>
			</table>
	<br>
<?php
	if ($ins->ifHag1()) {
?>
			<input class="button" type="button" value="<?php echo _('Next');?>" name="B1" onClick="location.href='?hag=2'">
<?php
	} else {
?>
		<?php echo _('Fix all problems to continue');?>
<?php
	}

}
?>
			</div>
		</div>
	</body>
<html>