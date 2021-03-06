<?php
	include('top.php');
    ob_end_flush();
	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$req_pl_id = intval($request->getGetVar('playlist_id'));

	$playlist = Playlist::create();
	$song = Song::create();
	$add = AddTracks::create($song);
	$add->setChmod();
	$add->setPlaylist($req_pl_id);
	$manager = Manager::create();
?>
	<div class="body">
		<div class="title"><?php echo _('Adding songs to'.' '); ?>«<?=$playlist->getTitle($req_pl_id)?>»
		</div>
		<div class="border">
			<br><br><br>
<?php
	$ch = 0;
	$chs = 0;
	if ($request->hasGetVar('add_directory')) {
		$security->accessCheck($request->getGetVar('add_directory'));
    	$folder = $add->getRealPath($request->getGetVar('add_directory'));
        $tracks_array = $add->getAllFilesFromDirectory($folder);

	    foreach ($tracks_array as $filename) {
	    	$add->addTrack($filename);
		    echo    "<div style=\"visibility:hidden; display: none; position:absolute;left:-5000px\">".$filename."</div>";
		    $vsego_liniy = count($tracks_array);
		    $start = (int)((100*$ch)/$vsego_liniy);
		    $end = (int)(100-$start);
		    $skolko = 10;
		    if ($vsego_liniy > 100) $skolko = 15;
		    if ($vsego_liniy > 500) $skolko = 25;
		    if ($vsego_liniy > 1000) $skolko = 50;
		    if ($chs < $skolko) {
		    	$chs = $chs +1;
		    } else {
		    	$chs = 0;
?>
			<div style="position: absolute; top:190px; left: 16px;">
			 	<table border="0" width="400" cellspacing="0" cellpadding="0">
					<tr>
						<td class="graph_g2_2" width="<?php echo $start ?>%" align="center"></td>
						<td width="1"><img src="images/blank.gif" border="0"></td>
						<td class="graph_g2_1" width="<?php echo $end ?>%" align="center"></td>
					</tr>
				</table>
				<table style="position:relative; top:-19px; left:0;" border="0" width="400" cellspacing="0" cellpadding="0">
					<tr>
						<td><div class="minitext"><?php echo _('Completed:'). $start; ?>%</div></td>

					</tr>
				</table>
			</div>
<?php
		}
    	$ch++;
    }
?>
		    <div style="position: absolute; top: 190px; left: 16px;">
			 	<table border="0" width="400" cellspacing="0" cellpadding="0">
					<tr>
						<td class="graph_g2_2" width="10%" align="center"></td>
					</tr>
				</table>
				<table style="position:relative; top:-19px; left:0;" border="0" width="400" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<div class="minitext"><?php echo _("Completed:");?> 100%</div>
						</td>
					</tr>
				</table>
			</div>
<?php
    }

	if ($request->hasGetVar('filename')) {
		$security->accessCheck($request->getGetVar('filename'));
		$add->addTrack($request->getGetVar('filename'));

		$fold = $manager->getFileFolder($request->getGetVar('filename'));
		$location = "manager.php?start=".$manager->getStart()."&search=".$manager->getSearch()."&fold=".$fold."&playlist_id=".$req_pl_id;
        echo _("<b>File has been successfully added to the playlist</b>.");
	}
    
    if ($request->hasGetVar('add_directory')) {
        echo _("<b>Folder has been successfully added to the playlist</b>.");
        $location = "manager.php?start=".$manager->getStart()."&search=".$manager->getSearch()."&fold=".$manager->getFold()."&playlist_id=".$req_pl_id;
    }    
?>
			
			<br><br>
			<input class="button" type="button" value="<?php echo _('Came back'); ?>" name="back" onClick="location.href='<?=$location?>'">
			&nbsp;&nbsp;
			<input class="button" type="button" value="<?php echo _('Finish the addition');?>" name="back" onClick="location.href='playlist_view.php?playlist_id=<?=$req_pl_id?>'">
		</div>
	</div>

