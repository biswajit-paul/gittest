<?php
$imagetobewatermark = "logo.png";
list ($width, $height) = getimagesize($imagetobewatermark);
$res = imagecreatetruecolor($width, $height);

$mark = imagecreatefrompng($imagetobewatermark);
//make sure here to use the ressource, not the filepath
imagecopy($res, $mark, 0, 0, 0, 0, $width, $height);

$watermarktext = "Muggu";
//$font = "../font/century gothic.ttf";
//I copied it to my local test folder
$font = "arial.ttf";
$fontsize = "15";
//make sure here to use the ressource, not the filepath
$white = imagecolorallocate($res, 255, 255, 255);
//make sure here to use the ressource, not the filepath
imagettftext($res, $fontsize, 0, 20, 10, $white, $font, $watermarktext);

header("Content-type:image/png");
//make sure here to use the ressource, not the filepath
imagepng($res);
//make sure here to use the ressource, not the filepath
imagedestroy($res);