<?php
	include('top.php');
	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$repeat = Repeat::create();
	$repeat->handler();
?>
	<div class="body">
		<div class="navi"><a href="playlist.php"><?php echo _('Playlists');?></a></div>
		<div class="navi"><a href="playlist_edit.php"><?php echo _('Create playlist');?></a></div>
		<div class="navi"><a href="playlist_order.php"><?php echo _('Orders');?></a></div>
		<div class="navi_white"><a href="playlist_proverki.php"><?php echo _('Checks');?></a></div>
		<br><br>
		<div class="title"><?php echo _('Files not found');?></div>
		<div class="border">
<?php
	if ($request->hasGetVar('povtor')) {
		$povtor_yes = 'yes';
	}
echo '
		<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
			<tr>
				<td width="3%">'._('Edit').'</td>
				<td width="20%">'._('Title').'</td>
   				<td width="17%">'._('Artist').'</td>
        		<td width="10%">'._('Playlist').'</td>
        		<td width="47%">'._('Filename').'</td>
				<td width="3%"></td>
			</tr>
';
	$i = 0;
	foreach ($repeat->getNotExisting() as $line) {
		$style = ($i != 1) ? "bgcolor=#F5F4F7" : '';
?>
			<tr>
				<td <?=$style?>>
					<a href="edit_song.php?playlist_id=povtor&edit_song=<?=$line['idsong']?>">
						<img src="images/edit_song.gif" border="0" title="<?php echo _('Edit song');?>">
					</a>
				</td>
				<td <?=$style?>>
					<?=$line['title']?>
				</td>
        		<td <?=$style?>>
        			<?=$line['artist']?>
        		</td>
        		<td <?=$style?>>
        			<?=$line['playlistName']?>
        		</td>
        		<td <?=$style?>>
        			<?=$line['filename']?>
        		</td>
				<td <?=$style?>>
					<a href="playlist_proverki.php?delete_song3=<?=$line['idsong']?>">
						<img src="images/delete2.gif" border="0" title="<?php echo _('Delete song from playlist');?>">
					</a>
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
	<br>
	<div class="title"><?php echo _('Repeating id3');?></div>
	<div class="border">
<?php
	if (empty($povtor_yes)) {
echo('<div>Repeating "Title" and "Artist" will came to partialy system malfunction.<br>Use this function after adding new songs.</div>');
	}
	if (!empty($povtor_yes)) {
echo '
		<table border=0 cellspacing="0" cellpadding="0" width="97%" class="table1">
			<tr>
				<td width="3%">'._('Edit').'</td>
				<td width="20%">'._('Title').'</td>
				<td width="20%">'._('Artist').'</td>
				<td width="10%">'._('Playlist').'</td>
				<td width="10%">'._('Time').'</td>
				<td width="32%">'._('Filename').'</td>
				<td width="3%"></td>
			</tr>';
		$i = 0;
		foreach ($repeat->getRepeat() as $line) {
			$color = ($i!=1) ? 'bgcolor=#F5F4F7' : '';
?>
		<tr>
			<td <?=$color?>>
				<a href="edit_song.php?playlist_id=povtor&edit_song=<?=$line['idsong']?>">
					<img src="images/edit_song.gif" border="0" title="<?php echo _('Edit song');?>">
				</a>
			</td>
			<td <?=$color?>>
				<?=$line['title']?>
			</td>
			<td <?=$color?>>
				<?=$line['artist']?>
			</td>
        	<td <?=$color?>>
        		<?=$line2['name']?>
        	</td>
         	<td <?=$color?>>
         		<?=$line['duration']?>
        	</td>
        	<td <?=$color?>>
        		<?=$line['filename']?>
        	</td>
        	<td <?=$color?>>
        		<a href="playlist_proverki.php?povtor=yes&delete_song=<?=$line['idsong']?>">
        			<img src="images/delete.gif" border="0" title="<?php echo _('Delete song from this playlist');?>">
        		</a>
        	</td>
			<td <?=$color?>>
				<a href="playlist_proverki.php?povtor=yes&delete_song2=<?=$line['idsong']?>">
					<img src="images/delete2.gif" border="0" title="<?php echo _('Delete song from this playlist and the harddisk');?>">
				</a>
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
	<br><br>
	<div class="bborder"><a href="?povtor_start=yes"><?php echo _('Again');?></a></div>
<?php
	} else {
?>
	<br>
 	<div class="bborder"><a href="?povtor_start=yes"><?php echo _('Find dublicates');?></a></div>
<?php
	}
?>
	</div>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  	