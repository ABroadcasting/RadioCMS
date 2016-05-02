<?php
	include('top.php');
	$file = FileManager::create();
	$nowplay = Nowplay::create();
	$statistic = Statistic::create();
	$statistic->updateAll();
    
    $tracklist = Tracklist::create();
    $tracklist->update();
?>
	<div class="body">
		<div class="navi_white"><a href="statistic.php"><?php echo _('General');?></a></div>
		<div class="navi"><a href="statistic_client.php"><?php echo _('By listeners');?></a></div>
		<br><br>
		<div class="title"><?php echo _('Station statistics');?></div>
		<div class="border">
			<?php echo _('Listeners now');?>: <?=$statistic->getListeners()?> (<?php echo _('streams');?>: <?=$statistic->getStreamCount()?>), <?php echo _('in last 24 hours');?>:<br><br>
            <?=$nowplay->getDinamika();?>
   			<br><br><br>
<?php
	$disk = $file->getDiskInfo();
?>
			<?php echo _('Hard disk usage');?>:<br><br>
			<table border="0" width="400" cellspacing="0" cellpadding="0">
				<tr>
					<td class="graph_g2_1" width="<?=$disk['zan']['proc']?>%" align="center"></td>
					<td width="1">
						<img src="images/blank.gif" border="0">
					</td>
					<td class="graph_g2_2" width="<?=$disk['free']['proc']?>%" align="center"></td>
				</tr>
			</table>
			<table border="0" width="400" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<div class="minitext" style="position:relative; top:-19px; left:0px;">
							<?php echo _('Used space:');?> <?=$disk['zan']['mb']?> \ <?php echo _('Free space:');?> <?=$disk['free']['mb']?>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<br>
		<div>
		<div class="title"><?php echo _('Latest tracks');?></div>
		<div class="border">
			<table width="97%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="15%"><?php echo _('Time');?></td>
				<td width="85%"><?php echo _('Track');?></td>
			</tr>
<?php
	$i = 0;
?>
<?php
	foreach ($statistic->getLastSongs() as $line) {
		$time = date("H:i:s (d.m)", $line['time']);
?>
			<tr>
        		<td <?=($i!=1) ? 'bgcolor=#F5F4F7' : ''?>>
        			<?php echo $time ?>
        		</td>
				<td <?=($i!=1) ? 'bgcolor=#F5F4F7' : ''?>>
					<?=($line['title']== " - ") ? _("No data") : $line['title']?>
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
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  	