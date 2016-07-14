<?php
	include('top.php');
	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$statistic = Statistic::create();
	$setting = Setting::create();
	$setting->handler();

	// no caching here
	if ($request->hasPostVar('request')) {
	    if ($request->getPostVar('dir_show') == "on") {
		  $statistic->updateDirectory();
        }    
		header("Location: setting_dir.php");
	}
?>
	<div class="body">
		<div class="navi"><a href="setting.php"><?php echo _('Radio settings');?></a></div>
		<div class="navi"><a href="setting_system.php"><?php echo _('System settings');?></a></div>
		<div class="navi_white"><a href="setting_dir.php"><?php echo _('Catalog');?></a></div>
		<br><br>
		<div class="title"><?php echo _('RadioCMS Catalog');?></div>
			<form method="POST" action="setting_dir.php">
				<div class="border">
					<table border="0" width="97%" cellpadding="0" class="paddingtable">
						<tr>
							<td width="150" valign="top">
								<?php echo _('Station name:');?><br>
							</td>
							<td valign="top">
								<input title="dir_name" maxlength="50" size="35" name="dir_name" type="text" value="<?=DIR_NAME?>"><br>
								<div class="podpis"><?php echo _('two words');?></div>
							</td>
						</tr>
						<tr>
							<td width="150" valign="top">&nbsp;</td>
							<td valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td width="150" valign="top">
								<?php echo _('Website:');?><br>
							</td>
							<td valign="top">
								<input title="dir_url" maxlength="60" size="35" name="dir_url" type="text" value="<?=DIR_URL?>"><br>
								<div class="podpis"><?php echo _('using http://');?></div>
							</td>
						</tr>
						<tr>
							<td width="150" valign="top">&nbsp;</td>
							<td valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td width="150" valign="top">
								<?php echo _('Stream address');?><br>
							</td>
							<td valign="top">
								<input title="dir_url" maxlength="80" size="35" name="dir_stream" type="text" value="<?=DIR_STREAM?>"><br>
								<div class="podpis"><?php echo _('using http://');?></div>
							</td>
						</tr>
						<tr>
							<td width="150" valign="top">&nbsp;</td>
							<td valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td width="150" valign="top">
								<?php echo _('Description:');?><br>
							</td>
							<td valign="top">
								<input title="description" maxlength="80" size="65" name="dir_description" type="text" value="<?=DIR_DESCRIPTION?>"><br>
								<div class="podpis"><?php echo _('two words');?></div>
							</td>
						</tr>
						<tr>
							<td width="150" valign="top">&nbsp;</td>
							<td valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td width="150" valign="top">
								<?php echo _('Genre:');?><br>
							</td>
							<td valign="top">
								<input title="genre" maxlength="10" size="35" name="dir_genre" type="text" value="<?=DIR_GENRE?>"><br>
								<div class="podpis"><?php echo _('one word');?></div>
							</td>
						</tr>
						<tr>
							<td width="150" valign="top">&nbsp;</td>
							<td valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td width="150" valign="top">
								<?php echo _('Bitrate:');?><br>
							</td>
							<td valign="top">
								<select title="bitrate" size="1" name="dir_bitrate" style="width:100px;">
									<option <?=(DIR_BITRATE=='64')? 'selected':''?> value="64"><?php echo ('64 '._('kbit\s'));?></option>
									<option <?=(DIR_BITRATE=='96')? 'selected':''?> value="96"><?php echo ('96 '._('kbit\s'));?></option>
									<option <?=(DIR_BITRATE=='128')? 'selected':''?> value="128"><?php echo ('128 '._('kbit\s'));?></option>
									<option <?=(DIR_BITRATE=='192')? 'selected':''?> value="192"><?php echo ('192 '._('kbit\s'));?></option>
									<option <?=(DIR_BITRATE=='256')? 'selected':''?> value="256"><?php echo _('256 '._('kbit\s'));?></option>
									<option <?=(DIR_BITRATE=='VBR')? 'selected':''?> value="VBR"><?php echo _('VBR');?></option>
								</select>
								<br><div class="podpis"><?php echo _('VBR - Variable bitrate');?></div>
							</td>
						</tr>
						<tr>
							<td width="150" valign="top">&nbsp;</td>
							<td valign="top">&nbsp;</td>
						</tr>
						<tr>
							<td width="150" valign="top"><?php echo _('Display in catalog');?><br></td>
							<td valign="top">
								<select title="dir_show" size="1" name="dir_show" style="width:60px;">
									<option <?=(DIR_SHOW=='off')?'selected':''?> value="off"><?php echo _('No');?></option>
									<option <?=(DIR_SHOW=='on')?'selected':''?> value="on"><?php echo _('Yes');?></option>
								</select>
								<br><div class="podpis"><?php echo _('will be listed ONLY if all fields are folled');?></div>
							</td>
						</tr>
					</table>
					<input title="request" type="text" name="request" size="1" value="request" style="visibility: hidden;"><br>
					<input class="button" type="submit" value="<?php echo _('Save');?>" name="B1">
				</div>
			</form>
		</div>
		<br>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  	