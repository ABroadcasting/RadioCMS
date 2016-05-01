<?php
	include('top.php');
	/* Доступ к модулю */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$order = Order::create();
	$order->handler();
?>
	<div class="body">
		<div class="navi"><a href="playlist.php"><?php echo _('Playlists');?></a></div>
		<div class="navi"><a href="playlist_edit.php"><?php echo _('Create playlist');?></a></div>
		<div class="navi_white"><a href="playlist_zakaz.php"><?php echo _('Orders');?></a></div>
		<div class="navi"><a href="playlist_proverki.php"><?php echo _('Checks');?></a></div>
		<br><br>
		<div class="polovina1">
			<div class="title"><?php echo _('Latest orders');?></div>
			<div class="border">
				<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
					<tr>
						<td width="26%"><?php echo _('Time');?></td>
						<td width="54%"><?php echo _('Track');?></td>
						<td width="20%"><?php echo _('Playlist');?></td>
					</tr>
<?php
   $i = 0;
	foreach ($order->getLastOrders(25) as $line) {
		$playlist = $order->getPlaylistBySong($line['id']);
		$dateTime->setTime($line['time']);
?>
					<tr>
						<td <?=($i!=1) ? "bgcolor=#F5F4F7" : ''?>>
							<?=$dateTime->toFormatString("H:i:s (d.m)")?>
						</td>
						<td <?=($i!=1) ? "bgcolor=#F5F4F7" : ''?>>
							<?=$line['track']?>
						</td>
						<td <?=($i!=1) ? "bgcolor=#F5F4F7" : ''?>>
							<?=$playlist['name']?>
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
		</div>
		<div class="polovina2">
			<div class="title"><?php echo _('Orders top');?></div>
			<div class="border">
				<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
					<tr>
						<td width="6%"><?php echo _('Orders');?></td>
						<td width="74%"><?php echo _('Track');?></td>
						<td width="20%"><?php echo _('Playlist');?></td>
					</tr>
<?php
   $i = 0;
	foreach ($order->getTopOrders(25) as $line) {
		$playlist = $order->getPlaylistBySong($line['id']);
?>
					<tr>
						<td <?=($i!=1) ? "bgcolor=#F5F4F7" : ''?>>
							<?=$line['zakazano']?>
						</td>
						<td <?=($i!=1) ? "bgcolor=#F5F4F7" : ''?>>
							<?=$line['artist']." - ".$line['title']?>
						</td>
						<td <?=($i!=1) ? "bgcolor=#F5F4F7" : ''?>>
							<?=$playlist['name']?>
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
		<div class="bborder">
			<a style="color: #333333;" href="?clear_zakaz=yes"><?php echo _('Reset orders counter');?></a></div>
			<br><br>
		</div>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  	