<?php  

#################################################################################
# Watermark Image script usage example
# For updates visit http://www.zubrag.com/scripts/
#################################################################################

// Images folder, must end with slash.
$images_folder = '/img/';

// Save watermarked images to this folder, must end with slash.
$destination_folder = '/img/dest/';

// Path to the watermark image (apply this image as watermark)
$watermark_path = '/watermark.png';

// MOST LIKELY YOU WILL NOT NEED TO CHANGE CODE BELOW

// Load functions for image watermarking
include("watermark_image.class.php");

// Watermark all the "jpg" files from images folder
// and save watermarked images into destination folder
foreach (glob($images_folder."*.jpg") as $filename) {

  // Image path
  $image_path = $filename;

  // Where to save watermarked image
  $imgdestpath = $destination_folder . basename($filename);

  // Watermark image
  $img = new Zubrag_watermark($image_path);
  $img->ApplyWatermark($watermark_path);
  $img->SaveAsFile($imgdestpath);
  $img->Free();

}

?>