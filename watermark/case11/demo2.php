<?php
$imagetobewatermark=imagecreatefrompng("logo.png");
$font="arial.ttf";
$fontsize="15";
$watermarktext="Muggu"; // text to be printed on image
$white = imagecolorallocate($imagetobewatermark, 255, 255, 255);
imagettftext($imagetobewatermark, $fontsize, 0, 20, 10, $white, $font, $watermarktext);
header("Content-type:image/png");
imagepng($imagetobewatermark);
imagedestroy($imagetobewatermark);