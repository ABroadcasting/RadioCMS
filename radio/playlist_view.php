<?php
	include('top.php');

	/* Доступ к модулю */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$playlist = Playlist::create();
	$notices = $playlist->handler();
	$playlistId = $playlist->getPlaylistId();

	$vsego_pesen = $playlist->getCountSongs($playlistId);
    $vsego_time = $playlist->getSongsDuration($playlistId);
    $poryadok = $playlist->isSortShow($playlistId);
    $search = $playlist->getSearch($playlistId);

	$search = $playlist->getSearch();
	$sort = $playlist->getSortArray();
	$start = $playlist->getStart();
	$limit = $playlist->getLimit();
?>
	<div class="body">
		<div class="navi_white"><a href="playlist.php"><?php echo _('Playlists');?></a></div>
		<div class="navi"><a href="playlist_edit.php"><?php echo _('Create playlist');?></a></div>
		<div class="navi"><a href="playlist_zakaz.php"><?php echo _('Orders');?></a></div>
		<div class="navi"><a href="playlist_proverki.php"><?php echo _('Checks');?></a></div>
		<br><br>
		<form method="POST" action="">
			<div class="title">
				Просмотр плейлиста «<?=$playlist->getTitle($playlistId)?>»
			</div>
			<div class="border">
<?php
	if (!empty($notices)) {
		foreach ($notices as $notice) {
?>
			<p><?=$notice?></p>
<?php
		}
	}
?>
				<table border=0 cellspacing="0" cellpadding="0" width="97%" class="table1">
					<tr>
					    <td width="3%">
							<?php echo _('Edit');?>
					    </td>
						<td width="18%">
							<a href="playlist_view.php?playlist_id=<?=$playlistId?>&sort=<?=($sort['string']=='title')?'!title':'title'?>&search=<?=$search?>&start=<?=$start?>">
								<?php echo _('Title');?>
							</a>
						</td>
				        <td width="15%">
				        	<a href="playlist_view.php?playlist_id=<?=$playlistId?>&sort=<?=($sort['string']=='artist')?'!artist':'artist'?>&search=<?=$search?>&start=<?=$start?>">
								<?php echo _('Artist');?>
				        	</a>
				        </td>
				        <td width="12%">
				        	<a href="playlist_view.php?playlist_id=<?=$playlistId?>&sort=<?=$playlistId?>&sort=<?=($sort['string']=='album')?'!album':'album'?>&search=<?=$search?>&start=<?=$start?>">
								<?php echo _('Album');?>
				        	</a>
				        </td>
				        <td width="5%">
				        	<a href="playlist_view.php?playlist_id=<?=$playlistId?>&sort=<?=$playlistId?>&sort=<?=($sort['string']=='zakazano')?'!zakazano':'zakazano'?>&search=<?=$search?>&start=<?=$start?>">
								<?php echo _('Orders');?>
				        	</a>
				        </td>
				        <td width="4%">
							<?php echo _('Time');?>
				        </td>
				        <td width="5%">
							<?php echo _('To the broadcast');?>
				        </td>
				        <td width="35%">
				        	<a href="playlist_view.php?playlist_id=<?=$playlistId?>&sort=<?=$playlistId?>&sort=<?=($sort['string']=='filename')?'!filename':'filename'?>&search=<?=$search?>&start=<?=$start?>">
								<?php echo _('Filename');?>
				        	</a>
				        </td>
<?php
	if ($poryadok) {
?>
				        <td width="3%">
				        	<a href="playlist_view.php?playlist_id=<?=$playlistId?>&sort=<?=$playlistId?>&sort=<?=($sort['string']=='sort')?'!sort':'sort'?>&search=<?=$search?>&start=<?=$start?>">
				        		Сорт.
				        	</a>
				        </td>
<?php
	}
?>
				        <td width="2%"></td>
				        <td width="3%"></td>
				    </tr>
<?php
	$i = 0;
    foreach ($playlist->getSongs($playlistId) as $line) {
		$color = ($i == 1) ? 'bgcolor=#F5F4F7' : '';
?>
					<tr>
						<td <?=$color?>>
							<a href="edit_song.php?playlist_id=<?=$line['id']?>&edit_song=<?=$line['idsong']?>&start=<?=$start?>&sort=<?=$sort['string']?>&search=<?=$search?>">
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
				        	<?=$line['album']?>
				        </td>
				        <td <?=$color?>>
				        	<?=$line['zakazano']?>
				        </td>
				        <td <?=$color?>>
				        	<?=$playlist->getDuration($line['duration'])?>
				        </td>
				        <td align=center <?=$color?>>
				        	<a href="playlist_view.php?playlist_id=<?=$line['id']?>&start=<?=$start?>&sort=<?=$sort['string']?>&search=<?=$search;?>&play=<?=$line['idsong']?>">
				        		<img src="images/play.gif" border="0" title="<?php echo _('Play');?>">
				        	</a>
				        </td>
				        <td <?=$color?>>
				        	<?=$playlist->getSongLocalPath($line['filename'], 30)?>
				        </td>
<?php
		if ($poryadok) {
?>
				        <td <?=$color?>>
				        	<input size="2" type="text" name="song_sort[<?=$line['idsong']?>]" value="<?=$line['sort']?>">
				        </td>
<?php
        }
?>
				        <td <?=$color?>>
				        	<a href="playlist_view.php?playlist_id=<?=$line['id']?>&delete_song=<?=$line['idsong']?>&start=<?=$start?>&sort=<?=$sort['string']?>&search=<?=$search?>">
				        		<img src="images/delete.gif" border="0" title="<?php echo _('Delete song');?>">
				        	</a>
				        </td>
				        <td <?=$color?>>
				        	<a href="playlist_view.php?playlist_id=<?=$line['id']?>&delete_song_2=<?=$line['idsong']?>&start=<?=$start?>&sort=<?=$sort['string']?>&search=<?=$search?>">
				        		<img src="images/delete2.gif" border="0" title="<?php echo _('Delete song from all playlists');?>">
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
				<br>
				<table border=0 cellspacing="0" cellpadding="0" width="97%" class="table1">
					<tr>
						<td width="60%">
							<a href="playlist_view.php?playlist_id=<?=$playlistId?>"><?php echo _('All songs');?></a>&nbsp;&nbsp;&nbsp;
<?php
	$seychas = $start+$limit;
    $sort_string = ($request->hasGetVar('sort')) ? "&sort=".$sort['string'] : "";
    
	if ($vsego_pesen < $seychas) {
		 echo _('Displayed:');?> <?=$start+1?>-<?=$vsego_pesen?>&nbsp;&nbsp;&nbsp;
<?php
	} else {
		 echo _('Displayed:');?> <?=$start+1?>-<?=$seychas?>&nbsp;&nbsp;&nbsp;
<?php
	}

	if ($limit <= $start) {
		$pokaz = $start-$limit;
?>
							<a href="playlist_view.php?playlist_id=<?=$playlistId?>&start=<?=$pokaz?>&limit=<?=$limit?><?=$sort_string?>&search=<?=$search?>"><?php echo _('Back');?></a>
<?php
	}

	if (($limit <= $start) and ($vsego_pesen > $seychas)) {
		echo " | ";
	}

	$pokaz = $start+$limit;
	if ($vsego_pesen > $seychas) {
?>
							<a href="playlist_view.php?playlist_id=<?=$playlistId?>&start=<?=$pokaz?>&limit=<?=$limit?><?=$sort_string?>&search=<?=$search?>"><?php echo _('Next');?></a>
<?php
	}
?>
						</td>
						<td align="right">
							<?php echo _('Total tracks');?> <?=$vsego_pesen?>&nbsp;(<?=$vsego_time?>)&nbsp;&nbsp;
							<a href="playlist_view.php?del_all=1&playlist_id=<?=$playlistId?>"><?php echo _('Delete all');?></a>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>
				<br>
				<input class="button" type="button" value="<?php echo _('Back');?>" name="back" onClick="location.href='playlist.php'" />
<?php
	if ($poryadok) {?>
				<input class="button" value="<?php echo _('Save');?>" name="submit" type="submit">
<?php
	}
?>
				<input class="button" value="<?php echo _('Add tracks');?>" name="14" type="button"  onClick="location.href='meneger.php?playlist_id=<?=$playlistId?>'" />
			</form>
			<br><br>
			<form method="POST" action="playlist_view.php?playlist_id=<?=$playlistId;?>">
			Поиск <input type="text" name="search" size="20" value="<?=$playlist->getSearchString()?>">
			<input type="submit" value="<?php echo _('Search');?>" name="b1">
			</form>
		</div>
		<br><br>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  	