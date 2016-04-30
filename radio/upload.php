<?php
	include '_config.php';
	//Get POST
	$namefile = $_FILES['music_file']['name'];
	$mail = $_POST['music_mail'];

	$filename = $namefile."_".$mail;
	//Form new filename
	$filename = str_replace(".mp3", "", $filename);
	$filename = str_replace(".MP3", "", $filename);
	$filename = $filename.".mp3";

	//Delete extra symbols
	$filename = htmlspecialchars($filename, ENT_QUOTES, "utf-8");

	$filename = $_SERVER["DOCUMENT_ROOT"]."/music/".TEMP_UPLOAD."/".$filename;

	//Saving file
	if (move_uploaded_file($_FILES['music_file']['tmp_name'], $filename)) {
		print _("<h1>File has been uploaded</h1><h4>You will be redirected back</h4>");
	} else {
    	print _("<h4>Unable to upload the file</h4>");
	}


	//Redirect back
	$URL = "http://".$_SERVER['HTTP_HOST'];
    if (isset($_GET['back'])) {
        $URL = $_GET['back'];
    }
    if (isset($_POST['back'])) {
        $URL = $_POST['back'];
    }
?>
	<head>
		<link rel="stylesheet" href="/style.css" type="text/css" />
		<link rel="stylesheet" href="/element.css" type="text/css" />
		<meta http-equiv="Refresh" content="2; URL=<?php echo $URL; ?>">
	</head>