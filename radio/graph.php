<?php
	ob_start();
	include('Include.php');

	$stat = Statistic::create();

	$gr_val = array();

	$query = "SELECT * FROM `statistic` WHERE `type` = 'graph' ORDER BY `time` DESC";
	$result = mysqli_query($query) or die("Query failed : " . mysqli_error());

	$s = mysql_num_rows($result);


	while ($line = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		if ($_GET['type'] == "client") {
			$client = $stat->getClient($line['client']);
			if (empty($client)) {
				$client = "neizvestno";
			}

			$client = trim(preg_replace("/[^a-zA-Z0-9\s]+/", "", $client));

			if (!isset($gr_val[$client])) {
				$gr_val[$client] = 1;
			} else {
    			$gr_val[$client]++;;
    		}
		} else {
			$time = $line['time'];
			$name = "< 1 min";
			if ( $time < 60 ){
				if (!isset($gr_val[$name])) {
					$gr_val[$name] = 1;
				} else {
					$gr_val[$name]++;
				}
			}
			$name = "1-10 min";
			if ( ($time >= 60) and ($time < 600) ){
				if (!isset($gr_val[$name])) {
					$gr_val[$name] = 1;
				} else {
					$gr_val[$name]++;
				}
			}
			$name = "10-60 min";
			if ( ($time >= 600) and ($time < 3600) ){
				if (!isset($gr_val[$name])) {
					$gr_val[$name] = 1;
				} else {
					$gr_val[$name]++;
				}
			}
			$name = "1-7 hour";
			if ( ($time >= 3600) and ($time < 25200) ) {
				if (!isset($gr_val[$name])) {
					$gr_val[$name] = 1;
				} else {
					$gr_val[$name]++;
				}
			}
			$name = "> 7 hour";
			if ( $time >= 25200){
				if (!isset($gr_val[$name])) {
					$gr_val[$name] = 1;
				} else {
					$gr_val[$name]++;
				}
			}

		}
	}

	// Build diagram
	GraphPie($gr_val);


	function GraphPie($ar) {
    	global $s;
    	// Diagram size
    	$diagramWidth = 450;
    	$diagramHeight = 250;
    	$legendOffset = 50;

    	// Sort decend? save keys
    	if ($_GET['type'] == "client") {arsort($ar);}

    	// Will sum all sectors less then 1%
    	$sum = 0;
    	foreach ($ar as $name => $val) {
    	    $sum += $val;
    	}

        if ($sum == 0) {
            $sum = 1;
        }

    	//chech if less 1%
    	$sumless1 = 0; // and their sum
    	$countless1=$countgreater1=0;
    	foreach ($ar as $name => $val) {
    	    if ($val/$sum<0.01) {
    	        $sumless1 += $val;
                $countless1++;
    	    } else {
    	        $countgreater1++;
            }
    	}

    	 // making image
    	$image = imageCreate($diagramWidth, $diagramHeight);

    	// bg and text colours
    	$colorBackgr = imageColorAllocate($image, 255,255,255);
    	$colorText = imageColorAllocate($image, 76, 76, 76);
    	$colorWhite = imageColorAllocate($image, 255,255,255);
    	// sectors colours
    	$colors[0] = imagecolorallocate($image, 171, 203, 203);
    	$colors[1] = imagecolorallocate($image, 214, 179, 140);
    	$colors[2] = imagecolorallocate($image, 221, 221, 153);
    	$colors[3] = imagecolorallocate($image, 153, 174, 177);
    	$colors[4] = imagecolorallocate($image, 212, 199, 199);
    	$colors[5] = imagecolorallocate($image, 158, 151, 138);
    	$colors[6] = imagecolorallocate($image, 143, 179, 187);
    	$colors[7] = imagecolorallocate($image, 199, 184, 183);
    	$colors[8] = imagecolorallocate($image, 192, 205, 220);
    	$colors[9] = imagecolorallocate($image, 197, 164, 170);
    	$colors[10] = imagecolorallocate($image, 198, 120, 201);
    	$colors[11] = imagecolorallocate($image, 188, 130, 201);
    	$colors[12] = imagecolorallocate($image, 178, 140, 201);
    	$colors[13] = imagecolorallocate($image, 168, 150, 201);
    	$colors[14] = imagecolorallocate($image, 158, 160, 201);
    	$colors[15] = imagecolorallocate($image, 148, 170, 201);
    	$colors[16] = imagecolorallocate($image, 194,255,255);
    	$colors[17] = imagecolorallocate($image, 90,9,255);
    	$colors[18] = imagecolorallocate($image, 109,255,110);
    	$colors[19] = imagecolorallocate($image, 255,133,22);


    	// fill the image from background colour
    	imageFilledRectangle($image, 0, 0, $diagramWidth - 1, $diagramHeight - 1, $colorBackgr);

    	// start angle for sector
    	$startAngle = 0;
    	$perc =360/$sum; // Associate degree with one persent
    	$i=0; // to echo element order in legend
    	foreach ($ar as $name => $val) {
    	// if current element more then 1%
    	  if ($val/$sum<0.01) // cycle exit
    	    break;

    	$font = "files/arial.ttf";

    	// final sector angle
    	  $endAngle=$startAngle+$val*$perc;
    	  // % of current element
    	  $percents=round(100*($val/$sum),2);

    	  // color square in legend
    	  imagefilledrectangle($image,250,$legendOffset+$i*15-9,260,$legendOffset+$i*15,$colors[$i]);
    	  // legend text
    	  ImageString($image , 2, 268, $legendOffset+$i*15-11, ($i+1).". ".$name." (".$percents."%)", $colorText);
    	  //imagettftext ($image, 10, 0, 265, $legendOffset+$i*15, $colorText, $font, ($i+1).". ".$name." (".$percents."%)");
    	  // sector
    	  imagefilledarc($image, $diagramWidth/2-110, $diagramHeight/2, 200, 200, $startAngle, $endAngle, $colors[$i++], IMG_ARC_PIE);


    	// Count subscription coordinates
    	  $tochka  = $endAngle-4;
    	  if ($percents > 6) {
    	  	$name = substr($name,0,13);
    	    $pr = 360-$tochka; $tochka = $tochka+$pr*2;
    	    if ($_GET['type']=="client") {
    	    	//ImageString($image , 2, 9, $tochka, "      ".$name, $colorText);
    	    	imagettftext($image, 9, $tochka, 115, 125, $colorText, $font, "      ".$name);
    	  	} else {
    	  		//ImageString($image , 2, 9, $tochka, "            ".$name, $colorText);
    	    	imagettftext($image, 9, $tochka, 115, 125, $colorText, $font, "            ".$name);
    	    }
    	  }

    	  // next sector will prolong last corner of current sector
    	  $startAngle=$endAngle;
    	}

    	// if we have less then 1%
    	if ($countless1) {
    	 $endAngle=360;
    	  $percents=round(100*($sumless1/$sum),2);
    	  // square in legend
    	  imagefilledrectangle($image,250,$legendOffset+$i*15-9,260,$legendOffset+$i*15,$colors[$i]);
    	 // text in legend
    	  ImageString($image , 2, 268, $legendOffset+$i*15-11, ($i+1).". "."Other"." (".$percents."%)", $colorText);
    	  //imagettftext ($image, 10, 0, 265, $legendOffset+$i*15, $colorText, $font, ($i+1).". "."Other"." (".$percents."%)");
    	  // "Other" sector
    	  imagefilledarc($image, $diagramWidth/2-110, $diagramHeight/2, 200, 200, $startAngle, $endAngle, $colors[$i++], IMG_ARC_PIE);
    	}

    	ImageString($image , 2, 268, $diagramHeight-20, "Vsego: ".$s, $colorText);
    	//imagettftext ($image, 10, 0, 250, $diagramHeight-10, $colorText, $font, "Vsego: ".$s);

    	// Printing image
    	header("Content-type:  image/png");
    	imagepng($image);
    	imageInterlace($image, 1);
    	imageColorTransparent($image, $colorBackgr);
    	return;
	}
?>