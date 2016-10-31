<?php
	include('top.php') ;

	$file = FileManager::create();
	$file->handler();
	$ssh = Ssh::create();
	$setting = Setting::create();
	$setting->handler();

echo '  <div class="body">
		<div class="title">'._('Welcome').'</div>
		<div class="border">';

	if ($user['admin'] == 0) {

echo (_("You have been logged in as")._("<i>DJ</i>")._("You can access")." "._(" the statistics module and partially 'your DJs' and 'Status'."));

    } else {
echo (_("You have been logged in as")._("<i>Administrator</i>")._("You can access")." "._("all modules."));
    }
echo _("Please, use main menu to work with accessable services.").
	"<br><br>".
	_("System:")." <b>RadioCMS</b><br>".
	_("Version:")."<b>";?><?=RADIOCMS_VERSION ?>.<?php echo("</b><br>");

	$count = $file->getCountTempFiles();
	$pokazat = "";
	if ($count >= 1 and TEMP_UPLOAD != "") {
		$pokazat = " — <a href='/radio/meneger.php?fold=".$request->getMusicPath().TEMP_UPLOAD."'>"._("View")."</a>";
	} else {
		$count = 0;
	}

echo _('Number of files in temporary Upload directory:');?> <b><?=$count?></b><?=$pokazat?>
	<br>
<?php
    if (!$ssh->checkEzstreamCompatibility()) {
echo ('<div><span class="red">'._('Installed version ezstream have no LibTag support, having some restrictions of id3 characters number').'</span></div>');   }

	if (
		DIR_SHOW == "on" and
		DIR_NAME != "" and
		DIR_URL != "" and
		DIR_STREAM != "" and
		DIR_DESCRIPTION != "" and
		DIR_GENRE != ""
	) {
	echo ('<div>'._('Your station').'<span class="green">'._('listed').'</span>'. _('in the RadioCMS catalog').'</div>');
	} else {
	echo ('<div>'._('Your station').'<span class="red">'._('not listed').'</span>'._('in the RadioCMS catalog').'- <a href="setting_dir.php">'._('Fix it').'</a></div>)');
	}

	if ( file_exists("install.php")) {
	echo ('<div><span class="red">'._('install.php has not be deleted').'</span> — <a href="?del_install=1">'._('Delete').'</a></div>');
	}
			include('Tpl/error.tpl.html');
echo'
			<br><br>
			<img style="position: absolute; margin-top: -1px;" src="images/go.png" border="0">'._('<a style="position: absolute; margin-left: 17px;" href="http://radiocms.ru" target="_blank">Official site</a>').
    '		<br>
			<br>
			<form method="POST" action="">
				<textarea name="main_text" style="width: 500px; height: 100px;">'.$setting->getDescription().'</textarea>
				<p>
					<input class="button" type="submit" value="'._('Save').'">
				</p>
			</form>
		</div>
	</div>';

    include('Tpl/footer.tpl.html');
?>  	