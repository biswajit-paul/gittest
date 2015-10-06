<?php  

#################################################################################
# Watermark Image using Text script usage example
# For updates visit http://www.zubrag.com/scripts/
#################################################################################

// Watermark text
$text = 'zubrag.com';

// Watermark text color, Hex format. Must start from #
$color = '#000000';

// Font name. Case sensitive (i.e. Arial not equals arial)
$font = 'arial.ttf';

// Font size
$font_size = '8';

// Angle for text rotation. For example 0 - horizontal, 90 - vertical
$angle = 90;

// Horizontal offset in pixels, from the right
$offset_x = 0;

// Vertical offset in pixels, from the bottom
$offset_y = 0;

// Shadow? true or false
$drop_shadow = true;

// Shadow color, Hex format. Must start from #
// This may help to make watermark text more distinguishable from image background
$shadow_color = '#909009';

// 1 - save as file on the server, 2 - output to browser, 3 - do both
$mode = 1;

// Images folder, must end with slash.
$images_folder = '/var/www/html/samples/php/watermark/case6/logo/';

// Save watermarked images to this folder, must end with slash.
$destination_folder = '/var/www/html/samples/php/watermark/case6/dest/';

#################################################################################
#     END OF SETTINGS
#################################################################################

// Load functions for image watermarking
include("watermark_text.class.php");

// Watermark all the "jpg" files from images folder
// and save watermarked images into destination folder
foreach (glob($images_folder."*.jpg") as $filename) {

  // Image path
  $imgpath = $filename;
  
  // Where to save watermarked image
  $imgdestpath = $destination_folder . basename($filename);

  // create class instance
  $img = new Zubrag_watermark($imgpath);
  
  // shadow params
  $img->setShadow($drop_shadow, $shadow_color);
  
  // font params
  $img->setFont($font, $font_size);
  
  // Apply watermark
  $img->ApplyWatermark($text, $color, $angle, $offset_x, $offset_y);

  // Save on server
  $img->SaveAsFile($imgdestpath);

  // Output to browser
  //$img->Output();

  // release resources
  $img->Free();

}

?>