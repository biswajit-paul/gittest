<?php
$warter = 'images/csi.jpg';
$image = imagecreatefromjpeg($warter);
$font_size = 14;
$black = imagecolorallocate($image, 0,0,0);
ImageTTFText ($image, $font_size, 0, 56, 36, $black, 'fonts/arial.ttf','Test Text');
header('Content-type: image/jpeg');
imagejpeg($image);
imagedestroy($image);