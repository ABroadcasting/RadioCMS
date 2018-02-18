<?php
	include('top.php');
	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$nowplay = Nowplay::create();
	$playlist = Playlist::create();
	$playlist->handler();
?>
	<div class="body">
		<div class="navi_white"><a href="playlist.php"><?php echo _('Playlists');?></a></div>
		<div class="navi"><a href="playlist_edit.php"><?php echo _('Create playlist');?></a></div>
		<div class="navi"><a href="playlist_order.php"><?php echo _('Orders');?></a></div>
		<div class="navi"><a href="playlist_proverki.php"><?php echo _('Checks');?></a></div>
		<br><br>	
		<div class="title"><?php echo _('Visual playlist');?></div>
			<div class="border">
				<?=$nowplay->getVisualPlaylist()?>
			</div>
			<br>
			<div class="title"><?php echo _('List of playlists');?></div>	
<?php 
	if ($playlist->noNowCheck()) {
	
			echo'<p style="padding-left: 5px;"><span class="red"><i>'._('No running playlist. Start playlist in set closest run time').'</i></span></p>';

	}
?>			
			<form method="POST" action="">
				<div class="border">				
<?php
            $vsego_time = $playlist->getAllSongsDuration();
            $vsego_pesen = $playlist->getCountAllSongs();
?>
				<table border=0 cellspacing="0" cellpadding="0" width="97%" class="table1">
<?php
	$i = 0;
?>
<?php
    		foreach ($playlist->getList() as $line) {
    			$color = ($i != 1) ? 'bgcolor=#F5F4F7' : '';
?>
					<tr>
				        <td width="17%" <?=$color?>>
				        	<a href="playlist_view.php?playlist_id=<?=$line['id']?>"><?=$line['name']?></a>
				        	<br>
				        	<?=$playlist->getPlaymode($line['playmode'])?>
							<br>
							<a href="playlist_edit.php?playlist_id=<?php echo $line["id"]?>"><img src="images/edit.gif" width="16" height="16" border="0" title="<?php echo _('Edit playlists');?>"></a>&nbsp;&nbsp;
				        	<a href="manager.php?playlist_id=<?php echo $line['id']?>"><img src="images/plus.gif" width="16" height="16" border="0" title="<?php echo _('Add tracks to playlist');?>"></a>&nbsp;&nbsp;
				        	<a href="playlist.php?delete_playlist=<?php echo $line['id']?>"><img src="images/delete2.gif" width="16" height="16" border="0" title="<?php echo _('Delete playlist');?>"></a>
				        </td>
				        <td width="51%" <?=$color?>>
				        	<?=$playlist->getTimes($line)?>
						</td>
						<td width="10%" <?=$color?>>
							<?=$playlist->getCountSongs($line['id'])." "._("songs");?>
<?php
				if ($line['now'] == '1') {
			echo'<br>'._('broadcasting now');

				}
?>
						</td>
				        <td width="6%" <?=$color?>>
<?php
				if ($line['enable'] == '1') {
?>
						<img src="images/online.gif" width="36" height="29" border="0" title="<?php echo _('Playlist in rotation');?>">
<?php
				} else {
?>
						<img src="images/offline.gif" width="36" height="29" border="0" title="<?php echo _('Playlist is disabled');?>">
<?php
				}
?>
						</td>
				        <td width="6%" <?=$color?>>
<?php
				if ($line['allow_order'] == '1') {
?>
							<img src="images/order.gif" width="29" height="29" border="0" title="<?php echo _('Orders allowed');?>">
<?php
				} else {
?>
							<img src="images/order2.gif" width="29" height="29" border="0" title="<?php echo _('Orders restricted');?>">
<?php
				}
?>
						</td>
				        <td width="5%" <?=$color?>>
<?php
				if ($line['show'] == '1') {
?>
					<img src="images/magnifier.gif" width="29" height="29" border="0" title="<?php echo _('Display on the main page');?>">
<?php
				} else {
?>
					<img src="images/magnifier2.gif" width="29" height="29" border="0" title="<?php echo _('Do not show on main page');?>">
<?php
				}
?>
						</td>
						<td width="7%" <?=$color?>>
							<input title="sort" size="2" type="text" name="playlist_sort[<?=$line['id']?>]" value="<?=$line['sort']?>">
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
				<br>
				<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
					<tr>
						<td width="60%">
							<input class="button" value="<?php echo _('Save');?>" name="submit" type="submit">
						</td>
						<td align="right" valign="top">
							<?php echo _('Tracks on main page:'.'');?><?php echo $vsego_pesen; ?> (<?php echo $vsego_time; ?>)
						</td>
					</tr>
				</table>
			</div>
		</form>
		<br><br>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  