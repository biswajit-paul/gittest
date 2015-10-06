<?php
$imagetobewatermark = "logo.png";
$watermarktext = "Muggu";

list($width, $height) = getimagesize($imagetobewatermark);

$image_p = imagecreatetruecolor($width, $height);
$image = imagecreatefrompng($imagetobewatermark);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height);

$font = "arial.ttf";
$font_size = 15;

$white = imagecolorallocate($image_p, 255, 255, 255);
imagettftext($image_p, $font_size, 0, 20, 20, $white, $font, $watermarktext);

header("Content-Type: image/png");
imagepng($image_p, null, 0);
imagedestroy($image);
imagedestroy($image_p);