<?php
# Playlist generator allows you to generate playlist's formats for the streaming.
#
# @category		i-SHCP
# @copyright	2013 by i-SHCP
# @author		Vilaliy Zhukov <dragonzx@yandex.ru>
# @license		http://www.gnu.org/licenses/gpl-2.0.html GPL v2
# @version		1.1.0
#
# Usage: playlist.php?name=playlist&pltype=m3u&stream=myradio.tld:8000/myradio
ini_set('display_errors', '0');

#define defaults
$type="audio";
$pltype="m3u";
$stream="";
$name="playlist";

#get options
$type=$_GET['type'];
$pltype=$_GET['pltype'];
$stream=$_GET['stream'];
$name=$_GET['name'];

#Undefined varible check
switch ($type){
	case "audio": break;
	case "video": break;
	default: $type="audio"; break;
};
if($name == ""){$name="playlist";};

#Generating output
$smilbody="
	<head>
        <meta name=\"Generator\" content=\"Microsoft Windows Media Player -- 11.0.5721.5145\" />
        <meta name=\"TotalDuration\" content=\"1102\" />
        <meta name=\"ItemCount\" content=\"1\" />
        <author/>
        <title>$name</title>
    </head>
    <body>
        <seq>
            <media src=\"$stream\" />
        </seq>
    </body>
</smil>";
switch ($pltype){
	case "m3u": $output=$stream; break;
	case "ram": $output=$stream; break;
	case "asx": $output="<ASX version = \"3.0\">
<Entry>
<REF HREF=\"$stream\" />
</Entry>
</ASX>"; 
break;
	case "wpl": $output="<?wpl version=\"1.0\"?>
<smil>".$smilbody; break;
	case "smil": $output="<smil xmlns=\"http://www.w3.org/2001/SMIL20/Language\">".$smilbody; break;
	case "zpl": $output="ac=$stream
nm=$stream
dr=-1
br!"; 
	break;
	case "pls": $output="[playlist]
File1=$stream
Title1=$name
NumberOfEntries=1
Version=2";
	break;
	case m3u8: $output="#EXTM3U
#EXTINF:0,$name
#EXTVLCOPT:network-caching=1000
$stream";
break;
	case "xfps": $output="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">
  <trackList>
    <track>
      <title>$name</title>
      <location>$stream</location>
    </track>
  </trackList>
</playlist>";
break;
	case "qtl": 
	$output="<?xml version=\"1.0\"?>
<?quicktime type=\"application/x-quicktime-media-link\"?>
<embed
autoplay=\"true\"
moviename=\"$name\"
src=\"$stream\"
/>";
	break;
	default: $pltype="m3u"; $output=$stream; break;
};

#Making the file
header("Content-Type: application/download; charset=utf-8");
header("Content-Disposition: attachment; filename=$name.$pltype");
print ($output);
?>
