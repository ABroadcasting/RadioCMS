<?php
	include('top.php');
	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$access = false;
	} else {
		$access = true;
	}

	$dj = Dj::create();
	$dj->handler();
echo '
	<div class="body">
	<br>
	<div class="title">DJ List</div>
	<div class="border">
		<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
			<tr>
				<td width="2%"></td>
				<td width="33%">'; echo _("User"); echo '</td>
				<td width="30%">'; echo _("Rights"); echo '</td>
				<td width="30%">'; echo _("Time"); echo '</td>
				<td width="5%">'; echo _("Delete"); echo '</td>
			</tr>
			<tr>
				<td bgcolor=#F5F4F7>
					<img src="images/user_admin.png" width="16" height="16" border="0">
				</td>
				<td bgcolor=#F5F4F7>
					<?=USER?>
				</td>
				<td bgcolor=#F5F4F7>Super admin</td>
				<td bgcolor=#F5F4F7></td>
				<td bgcolor=#F5F4F7>
					<img src="images/delete.gif" width="16" height="16" border="0" title="'; echo _("You can't delete superadmin"); echo '">
				</td>
			</tr>';
		$i = 1;
    	foreach ($dj->getDjList() as $line) {
    		$color = ($i!=1) ? 'bgcolor=#F5F4F7' : '';
?>
			<tr>
       			<td <?=$color?>>
<?php
		if ($line['admin'] == 1) {
?>
       				<img src="images/user_admin.png" width="16" height="16" border="0">
<?php
		} else {
?>
					<img src="images/user.png" width="16" height="16" border="0">
<?php
		}
?>
       			</td>
        		<td <?=$color?>>
        			<?=$line['dj']?>
        		</td>
				<td <?=$color?>>
<?php
		if ($line['admin'] == 1) {
?>
       				Administrator
<?php
		} else {
?>
					DJ
<?php
		}
?>
				</td>
				<td <?=$color?>>
					<?=$line['description']?>
				</td>
				<td <?=$color?>>
<?php
		if ($access) {
?>
					<a href="?del=<?=$line['id']?>">
						<img src="images/delete2.gif" width="16" height="16" border="0" title="Удалить">
					</a>
<?php
		} else {
?>
					<img src="images/delete.gif" width="16" height="16" border="0" title="Нет прав">
<?php
		}
?>
				</td>
			</tr>
<?php
			if ($i == 1) {
				$i = 0;
			} else {
				$i = $i+1;
			}
		}
 ?>
		</table>
	</div>
	<br><br>
	<div class="title">Add new DJ</div>
	<div class="border">
<?php
	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}
?>
		<form method="post" action="">
			<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
				<tr>
					<td width="13%" align="left">Login</td>
					<td width="13%" align="left">Password</td>
					<td width="13%" align="left">Rights</td>
					<td width="13%" align="left">Time</td>
					<td></td>
				</tr>
				<tr>
					<td align="left"><input width="80%" name="dj" type="text" value=""></td>
					<td align="left"><input width="80%" name="djpass" type="password" value=""></td>
					<td align="left"><select width="80%" size="1" name="admin"><option value="0"><?php echo _('DJ');?></option><option value="1"><?php echo _('Administrator');?></option></select></td>
					<td align="left"><input width="80%" name="djdescription" type="text" value=""></td><td></td>
				</tr>
				<tr>
					<td align="left"><input class="button" name="djadd" type="submit" value="add"></td>
					<td colspan="5"><div class="podpis"><?php echo ($dj->getError(). "<font color='red'>".$dj->getError()."</font>&nbsp;&nbsp;&nbsp;&nbsp;: "._("DJ have an access to the \"Stats\" module and partially to \"Your DJs\" (only read the list DJ) and \"Status\" (to switch radioshow and autodj without turn off), Administrator - access to all modules."));?></div></td>
				</tr>
			</table>
		</form>
	</div>
	<br><br>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>	