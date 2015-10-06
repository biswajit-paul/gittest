<?php
$imagetobewatermark=imagecreatefrompng("logo.png");
$watermarktext="Muggu";
$font="arial.ttf";
$fontsize="15";
$white = imagecolorallocate($imagetobewatermark, 255, 255, 255);
imagettftext($imagetobewatermark, $fontsize, 0, 20, 10, $white, $font, $watermarktext);
header("Content-type:image/png");
imagepng($imagetobewatermark);
imagedestroy($imagetobewatermark);