<?php
	include('top.php');
	/* Доступ к модулю */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$song = Song::create();
	$song->handler();
echo '
	<div class="body">
		<div class="navi_white"><a href="playlist.php">'. _('Playlists').'</a></div>
		<div class="navi"><a href="playlist_edit.php">'. _('Create playlists').'</a></div>
		<div class="navi"><a href="playlist_zakaz.php">'. _('Orders').'</a></div>
		<div class="navi"><a href="playlist_proverki.php">'. _('Checks').'</a></div>
		<br><br>
		<form method="POST" action="">
			<div class="title">'. _('Edit songs').'</div>
			<div class="border">';

	$line = $song->getSong($request->getGetVar('edit_song'));
	$player_filename = $song->getPlayerPath($line['filename']);
echo '
				<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
					<tr>
					    <td width="15%">
					    	'._('Player').'<br>
					    	<div class="podpis">Dewplayer Classic 1.9</div>
					    </td>
				        <td width="85%">
				        	<object type="application/x-shockwave-flash" data="files/dewplayer.swf?mp3=<?=$player_filename?>&amp;showtime=1" width="200" height="20">
				        		<param name="wmode" value="transparent" />
				        		<param name="movie" value="files/dewplayer.swf?mp3=<?=$player_filename?>&amp;showtime=1" />
				        	</object>
				        </td>
				    </tr>
					<tr>
					    <td>
					    	'._('Title').'<br>
					    	<div class="podpis">'._('ID3 of MP3-file').'</div>
					    </td>
				        <td>
				        	<input maxlength="90" size="60" type="text" name="title" value="'.htmlspecialchars($line['title']).'">
				        </td>
				    </tr>
					<tr>
					    <td>
					    	'._('Artist').'<br>
					    	<div class="podpis">'._('ID3 of MP3-file').'</div>
					    </td>
				        <td>
				        	<input maxlength="90" size="60" type="text" name="artist" value="'.htmlspecialchars($line['artist']).'">
				        </td>
				    </tr>
					<tr>
					    <td>
					    	'._('Album').'<br>
					    	<div class="podpis">'._('ID3 of MP3-file').'</div>
					    </td>
				        <td>
				        	<input maxlength="90" size="60" type="text" name="album" value="'.htmlspecialchars($line['album']).'">
				        </td>
				    </tr>
				    <tr>
					    <td>
					    	'._('Orders').'<br>
					    	<div class="podpis">'._('Orders number').'</div>
					    </td>
				        <td>
				        	<input size="40" type="text" name="zakazano" value="'.$line['zakazano'].'">
				        </td>
				    </tr>
				    <tr>
					    <td>
					    	'._('Sort').'<br>
					    	<div class="podpis">'._('Sort order').'</div>
					    </td>
				        <td>
				        	<input size="40" type="text" name="sort" value="'.$line['sort'].'">
				        </td>
				    </tr>
				    <tr>
					    <td>
					    	'._('Move to').'<br>
					    	<div class="podpis">'._('Move to other folder/playlist').'</div>
					    </td>
				        <td>
				        	<select size="1" name="position">';

		 foreach ($song->getPlaylistList() as $playlist) {
?>
								<option <?=$playlist['id']==$line['id']? 'selected':''?> value="<?=$playlist['id']?>">
			 						<?=$playlist['name']?>
								</option>
<?php
		 }
?>
							</select>
							&nbsp;&nbsp;&nbsp;
        					<select size="1" name="folder">
<?php

		foreach ($song->getFolderList() as $folder) {

?>
								<option value="<?=$folder?>" <?=$song->getFolder($line['filename'])==$folder?'selected':''?>>
									<?=$folder?>
								</option>
                            </select>
<?php
		}
echo '    					
						</td>
					</tr>
		    		<tr>
				   		<td>
				   			'._('Filename').'<br>
				   			<div class="podpis">'._('Change').'</div>
				   		</td>
			        	<td>
			        		<input size="40" type="text" name="filename" value="'.$song->getFilename($line['filename']).'">
						</td>
				    </tr>
				    <tr>
					    <td>
					    	'._('ID').'<br>
					    	<div class="podpis">'._('Read only').'</div>
					    </td>
				        <td>
				        	<input readonly  size="40" type="text" name="idsong" value="'.$line['idsong'].'">
				        </td>
				    </tr>
				</table>
				<br><br>
';
	if ($request->getGetVar('playlist_id') == "povtor") {
?>
				<input class="button" type="button" value="<?php echo _('Back'); ?>" name="back" onClick="location.href='playlist_proverki.php?povtor=yes'" />
<?php
	} else {
?>
				<input class="button" type="button" value="<?php echo _('Back'); ?>" name="back" onClick="location.href='playlist_view.php?playlist_id=<?=$request->getGetVar('playlist_id')?>&sort=<?=$request->getGetVar('sort')?>&start=<?=$request->getGetVar('start')?>&search=<?=$request->getGetVar('search')?>'" />
<?php
	}
?>
				<input class="button" value="<?php echo _('Save'); ?>" name="submit" type="submit"> <input class="button" value="<?php echo _('Save and back'); ?>" name="submit_and_save" type="submit">
			</div>
		</form>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  	