
<?php
// Create a 300x150 image
$im = imagecreatetruecolor(300, 150);
$black = imagecolorallocate($im, 0, 0, 0);
$white = imagecolorallocate($im, 255, 255, 255);

// Set the background to be white
imagefilledrectangle($im, 0, 0, 299, 299, $white);

// Path to our font file
$font = 'fonts/arial.ttf';

// First we create our bounding box for the first text
$bbox = imagettfbbox(10, 45, $font, 'Powered by PHP ' . phpversion());

// This is our cordinates for X and Y
$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2) - 25;
$y = $bbox[1] + (imagesy($im) / 2) - ($bbox[5] / 2) - 5;

// Write it
imagettftext($im, 10, 45, $x, $y, $black, $font, 'Powered by PHP ' . phpversion());

// Create the next bounding box for the second text
$bbox = imagettfbbox(10, 45, $font, 'and Zend Engine ' . zend_version());

// Set the cordinates so its next to the first text
$x = $bbox[0] + (imagesx($im) / 2) - ($bbox[4] / 2) + 10;
$y = $bbox[1] + (imagesy($im) / 2) - ($bbox[5] / 2) - 5;

// Write it
imagettftext($im, 10, 45, $x, $y, $black, $font, 'and Zend Engine ' . zend_version());

// Output to browser
header('Content-Type: image/png');

imagepng($im);
imagedestroy($im);
?>
