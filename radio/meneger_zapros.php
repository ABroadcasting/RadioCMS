<?php
	ob_start();
	include('top.php');

	/* Module access */
    if (!empty($user) and $user['admin'] != 1) {
    	$security->denied();
	}

	$meneger = Manager::create();
	$meneger->zaprosHandler();

    if ($meneger->request->hasPostVar('fl')) {
	   $fl = $meneger->request->getPostVar('fl');
    } else {
        $fl = array();
    }    
	$fold = $meneger->getFold();
	$folder = $meneger->getFolder();
	$start = $meneger->getStart();
	$search = $meneger->getSearch();
	$root_path = $request->getMusicPath();
?>

	<div class="body">
		<br>
		<div class="title"><?php echo _('File action');?></div>
		<div class="border">
			<form action="meneger_do.php?folder=<?=$folder?>&start=<?=$start?>&search=<?=$search?>" method="post">
				<table border="0" cellspacing="0" cellpadding="0" width="97%" class="table1">
<?php
	if ($meneger->isUdal()) {
?>
					<tr>
						<td width="95%"><?php echo _('Delete files?');?><br></td>
					</tr>
					<tr>
						<td>
<?php
		foreach ($fl as $k=>$i) {
			if (is_dir($folder."/".$i)) {
	  			$ds = " (папка)";
	  		} else {
	  			$ds = "";
	  		}
?>
							<input type="hidden" name="fl[]" value="<?=$i?>"><b><?=urldecode($i)?></b> <?=$ds?><?php echo _('From folder');?><?=$folder?><br>
<?php
		}
?>
						</td>
					</tr>
					<tr>
						<td>
							<input class="button" type="submit" value="<?php echo _('Cancel');?>" name="ot"> <input class="button" type="submit" value="<?php echo _('Delete');?>" name="udal_x"><br>
						</td>
<?php
	}

	if ($meneger->isCopy()) {
		$begin = $root_path;
		$begin = substr($begin, 0, -1);
?>
					<tr>
						<td width=95%><b><?php echo _('Where do you want to copy files?');?></b></td>
					</tr>
					<tr>
						<td width=95%>
<?php
		foreach ($fl as $i) {
			if (is_dir($folder."/".urldecode($i))) {
				$ds = " (папка)";
			} else {
				$ds = "";
			}
?>
							<input type="hidden" name="fl[]" value="<?=$i?>">&nbsp;<?=urldecode($i)?> <?=$ds?><br>
<?php
		}
?>
						</td>
					</tr>
					<tr>
						<td width="95%"><b><?php echo _('Choose folder to copy');?></b></td>
					</tr>
					<tr>
						<td width="95%">
<?php
		foreach ($meneger->getTree($begin) as $fllnm2=>$fllnm) {
?>
							<input id="<?=$fllnm2?>" name="rd" type="radio" value="<?=$fllnm?>">&nbsp;
							<label for="<?=$fllnm2?>"><?=$fllnm2?></label><br>
<?php
		}
?>
<?php
		if ($begin != $folder) {
			$begin2 = "/";
?>
							<input id="<?=$begin2?>" name="rd" type="radio" value="<?=$begin?>">&nbsp;
							<label for="<?=$begin2?>"><?=$begin2?></label><br>
<?php
		}
?>
						</td>
					</tr>
					<tr>
						<td width="95%">
							<input class="button" type="submit" value="<?php echo _('Cancel');?>" name="ot">
							<input class="button" type="submit" value="<?php echo _('Copy');?>" name="copy_x">
						</td>
					</tr>
<?php
	}

	if ($meneger->isMove()) {
		$begin = $root_path;
		$begin = substr($begin, 0, -1);
?>
					<tr>
						<td width=95%><b><?php echo _('Where do you want to move files?');?></b></td>
					</tr>
					<tr>
						<td width=95%>
<?php
		foreach ($fl as $i)	{
			if (is_dir($folder."/".$i)) {
				$ds = " (папка)";
			} else {
				$ds = "";
			}
?>
			<input type="hidden" name="fl[]" value="<?=$i?>">&nbsp;<?=urldecode($i)?> <?=$ds?><br>
<?php
		}
?>
						</td>
					</tr>
					<tr>
						<td width=95%><b><?php echo _('Choose folder to move');?></b></td>
					</tr>
					<tr>
						<td width=95%>
<?php
		foreach ($meneger->getTree($begin) as $fllnm2=>$fllnm) {
?>
							<input id="<?=$fllnm2?>" name="rd" type="radio" value="<?=$fllnm?>">&nbsp;
							<label for="<?=$fllnm2?>"><?=$fllnm2?></label><br>
<?php
		}
?>
<?php
		if ($begin!=$folder) {
			$begin2 = "/";
?>
							<input id="<?=$begin2?>" name="rd" type="radio" value="<?=$begin?>">&nbsp;
							<label for="<?=$begin2?>"><?=$begin2?></label><br>
<?php
		}
?>
						</td>
					</tr>
					<tr>
						<td width="95%">
							<input class="button" type="submit" value="<?php echo _('Cancel');?>" name="ot">
							<input class="button" type="submit" value="<?php echo _('Move');?>" name="move_x">
						</td>
					</tr>
<?php
	}

	if ($meneger->isRename()) {
		foreach ($fl as $i) {
?>
					<tr>
						<td width="200">
							Старое имя:<br><div class="podpis"><?php echo _('Current filename');?></div>
						</td>
						<td width="80%" valign=top>
							<input readonly type="hidden" size="30" name="afl[]" value="<?=$i?>" style='color:#888888'>
							<?=urldecode($i)?>
						</td>
					</tr>
					<tr>
						<td>
							Новое имя:<br><div class=podpis><?php echo _('Filename to save');?></div>
						</td>
						<td valign="top">
							<input type="text" size="30" name="rfl[]" value="<?=urldecode($i)?>">
						</td>
					</tr>
<?php
		}
?>
					<tr>
						<td>
							<input class="button" type="submit" value="<?php echo _('Cancel');?>" name="ot">
							<input class="button" type="submit" value="Save" name="ren_x">
						</td>
						<td>
							<!-- nothing -->
						</td>
					</tr>
<?php
	}

	if ($meneger->isMakeDir()) {
?>
					<tr>
						<td width="200">
							<?php echo _('Type the folder name:');?><br>
							<div class="podpis"><?php echo _('Name to save');?></div><br><br>
							<input class="button" type="submit" value="<?php echo _('Cancel');?>" name="ot">
							<input class="button" type="submit" value="<?php echo _('Create');?>" name="md_x">
						</td>
						<td width="80%" valign="top">
							<input type="text" size="30" name="newname">
						</td>
					</tr>
<?php
	 }
?>
				</table>
			</form>
		</div>
	</div>
<?php
    include('Tpl/footer.tpl.html');
?>  