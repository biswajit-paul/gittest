<?php

function calculateTextBox($text,$fontFile,$fontSize,$fontAngle) {
    /************
    simple function that calculates the *exact* bounding box (single pixel precision).
    The function returns an associative array with these keys:
    left, top:  coordinates you will pass to imagettftext
    width, height: dimension of the image you have to create
    *************/
    $rect = imagettfbbox($fontSize,$fontAngle,$fontFile,$text);
    $minX = min(array($rect[0],$rect[2],$rect[4],$rect[6]));
    $maxX = max(array($rect[0],$rect[2],$rect[4],$rect[6]));
    $minY = min(array($rect[1],$rect[3],$rect[5],$rect[7]));
    $maxY = max(array($rect[1],$rect[3],$rect[5],$rect[7]));
   
    return array(
     "left"   => abs($minX) - 1,
     "top"    => abs($minY) - 1,
     "width"  => $maxX - $minX,
     "height" => $maxY - $minY,
     "box"    => $rect
    );
}

// Example usage - gif image output

$text_string    = "Hello World";
$font_ttf        = "/var/www/html/samples/php/watermark/case2/fonts/arial.ttf";
$font_size        = 22;
$text_angle        = 0;
$text_padding    = 10; // Img padding - around text

$the_box        = calculateTextBox($text_string, $font_ttf, $font_size, $text_angle);

$imgWidth    = $the_box["width"] + $text_padding;
$imgHeight    = $the_box["height"] + $text_padding;

$image = imagecreate($imgWidth,$imgHeight);
imagefill($image, imagecolorallocate($image,200,200,200));

$color = imagecolorallocate($image,0,0,0);
imagettftext($image,
    $font_size,
    $text_angle,
    $the_box["left"] + ($imgWidth / 2) - ($the_box["width"] / 2),
    $the_box["top"] + ($imgHeight / 2) - ($the_box["height"] / 2),
    $color,
    $font_ttf,
    $text_string);

header("Content-Type: image/gif");
imagegif($image);
imagedestroy($image);

?> 