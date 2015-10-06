<?php
error_reporting(1);

$image = new Imagick();

echo '<pre>'; print_r( $image ); echo '</pre>'; die;

$image->readImage("img/url.jpeg");
 
$watermark = new Imagick();
$watermark->readImage("img/watermark.png");
 
// how big are the images?
$iWidth = $image->getImageWidth();
$iHeight = $image->getImageHeight();
$wWidth = $watermark->getImageWidth();
$wHeight = $watermark->getImageHeight();
 
if ($iHeight < $wHeight || $iWidth < $wWidth) {
    // resize the watermark
    $watermark->scaleImage($iWidth, $iHeight);
 
    // get new size
    $wWidth = $watermark->getImageWidth();
    $wHeight = $watermark->getImageHeight();
}
 
// calculate the position
$x = ($iWidth - $wWidth) / 2;
$y = ($iHeight - $wHeight) / 2;
 
$image->compositeImage($watermark, imagick::COMPOSITE_OVER, $x, $y);
 
header("Content-Type: image/" . $image->getImageFormat());
echo $image;
?>


<?php
// Load the stamp and the photo to apply the watermark to
$stamp = imagecreatefrompng('img/watermark.png');
$im = imagecreatefrompng('img/url.jpeg');

// Set the margins for the stamp and get the height/width of the stamp image
$marge_right = 10;
$marge_bottom = 10;
$sx = imagesx($stamp);
$sy = imagesy($stamp);

$imgx = imagesx($im);
$imgy = imagesy($im);
$centerX=round($imgx/2);
$centerY=round($imgy/2);

// Copy the stamp image onto our photo using the margin offsets and the photo 
// width to calculate positioning of the stamp. 
imagecopy($im, $stamp, $centerX, $centerY, 0, 0, imagesx($stamp), imagesy($stamp));

// Output and free memory
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>