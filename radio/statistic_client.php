<?php
	include 'top.php';
	$stat = Statistic::create();
    $stat->updateAll();
?>
	<div class="body">
		<div class="polovina1">
			<div class="navi"><a href="statistic.php"><?php echo _('General');?></a></div>
			<div class="navi_white"><a href="statistic_client.php"><?php echo _('By listeners');?></a></div>
			<br><br>
			<div class="title"><?php echo _('Listeners online');?></div>
			<div class="border">
				<table border="0" width="97%" cellspacing="0" cellpadding="0" class="table1">
					<tr>
						<td width="5%"></td><td width="25%"><?php echo _('IP address');?></td><td width="20%"><?php echo _('Time');?></td><td width="45%"><?php echo _('Client');?></td>
					</tr>
<?php
	$k = 0;
	$clients = $stat->getClients();
	foreach ($clients as $line) {
	    if ($k == 1) {
	    	$bg = "";
	    } else {
	    	$bg = "#F5F4F7";
	    }
?>
					<tr>
						<td bgcolor="<?=$bg?>">
							<img src="<?=$stat->getIcon($line['client'])?>" width="16" height="16">
						</td>
						<td bgcolor="<?=$bg?>">
							<?=$line['ip']?>
						</td>
						<td bgcolor="<?=$bg?>">
							<?=$stat->getTime($line['time'])?>
						</td>
						<td bgcolor="<?=$bg?>">
							<?=$stat->getClient($line['client'])?>
						</td>
					</tr>
<?php
	    if ($k == 1) {
	    	$k=0;
	    } else {
	    	$k++;
	    }
	}
?>
				</table>
			</div>
			<br><br>
		</div>
		<div class="polovina2">
		<br><br>
		<div class="title"><?php echo _('Graphs');?></div>
		<div class="border">
<?php
echo "<img src=graph.php?type=client>";
echo "<br><img src=graph.php?type=time>";
?>
			<br>
			<?php echo _('Total listeners');?>: <?=count($clients)?>.
		</div>
		<br><br>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  