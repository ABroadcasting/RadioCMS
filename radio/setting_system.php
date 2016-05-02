<?php
	include('top.php');
	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}
	$setting = Setting::create();
	$setting->handler();

	// no caching here
	if ($request->hasPostVar('request')) {
		Header("Location: setting_system.php");
	}
?>
	<div class="body">
		<div class="navi"><a href="setting.php"><?php echo _('Radio settings');?></a></div>
		<div class="navi_white"><a href="setting_system.php"><?php echo _('System settings');?></a></div>
		<div class="navi"><a href="setting_dir.php"><?php echo _('Catalog');?></a></div>
		<br><br>
		<div class="title"><?php echo _('System settings');?></div>
		<form method="POST" action="setting_system.php">
			<div class="border">
				<table border="0" width="97%" cellpadding="0" class="paddingtable">
					<tr>
						<td width="104" valign="top">
							<?php echo _('IP address:');?>
						</td>
						<td valign="top">
							<input type="text" name="ip" size="35" value="<?=IP?>"><br>
							<div class="podpis"><?php echo _('for SSH connection');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td width="104" valign="top">
							<?php echo _('WEB address');?>
						</td>
						<td valign="top">
							<input type="text" name="url" size="35" value="<?=URL?>"><br>
							<div class="podpis"><?php echo _('full site adress witout / at the end');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td width="104" valign="top">
							<?php echo _('Port');?>
						</td>
						<td valign="top">
							<input type="text" name="port" size="35" value="<?=PORT?>"><br>
							<div class="podpis"><?php echo _('stream port');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
<?php
	if ($user['dj'] == USER)  {
?>
					<tr>
						<td width="104" valign="top">
							<?php echo _('Login:');?>
						</td>
						<td valign="top">
							<input type="text" name="setting_user" size="35" value="<?=USER?>"><br>
							<div class="podpis"><?php echo _('to enter admin panel');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td width="104" valign="top">
							<?php echo _('Password:');?>
						</td>
						<td valign="top">
							<input type="password" name="setting_password" size="35" value="<?=PASSWORD?>"><br>
							<div class="podpis"><?php echo _('type the password');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
<?php
	}
?>
					<tr>
						<td width="104" valign="top"><?php echo _('IceCast configuration:');?></td>
						<td valign="top">
							<input type="text" name="cf_icecast" size="55" value="<?=CF_ICECAST?>"><br>
							<div class="podpis"><?php echo _('full path of the configuration file');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td width="104" valign="top">
							<?php echo _('ezstream configuration:');?>
						</td>
						<td valign="top">
							<input type="text" name="cf_ezstream" size="55" value="<?=CF_EZSTREAM?>"><br>
							<div class="podpis"><?php echo _('full path of the configuration file');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td width="104" valign="top">
							<?php echo _('playlist file');?>
						</td>
						<td valign="top">
							<input type="text" name="playlist" size="55" value="<?=PLAYLIST?>"><br>
							<div class="podpis"><?php echo _('full path of the configuration file');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
					<tr>
						<td width="104" valign="top"><?php echo _('Upload directory');?></td>
						<td valign="top">
							<input type="text" name="temp_upload" size="55" value="<?=TEMP_UPLOAD?>"><br>
							<div class="podpis"><?php echo _('in music directory without full path');?></div>
						</td>
					</tr>
					<tr>
						<td width="104" valign="top">&nbsp;</td>
						<td valign="top">&nbsp;</td>
					</tr>
				</table>
				<?php echo _('Be careful in filling!');?>
				<br><br>
				<input class="button" type="submit" value="<?php echo _('Save');?>" name="request">
			</div>
		</form>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  	