<?php
  require __DIR__ . '/vendor/autoload.php';

print_r( $_FILES );

$foo = new Upload($_FILES['image_field']);

if ($foo->uploaded) {
   // save uploaded image with no changes
   $foo->Process('/var/www/html/samples/php/watermark/case12/files/');
   if ($foo->processed) {
     echo 'original image copied';
   } else {
     echo 'error : ' . $foo->error;
   }
   // save uploaded image with a new name
   $foo->file_new_name_body = 'foo';
   $foo->Process('/var/www/html/samples/php/watermark/case12/files/');
   if ($foo->processed) {
     echo 'image renamed "foo" copied';
   } else {
     echo 'error : ' . $foo->error;
   }   
   // save uploaded image with a new name,
   // resized to 100px wide
   $foo->file_new_name_body = 'image_resized';
   $foo->image_resize = true;
   $foo->image_convert = gif;
   $foo->image_x = 100;
   $foo->image_ratio_y = true;
   $foo->Process('/var/www/html/samples/php/watermark/case12/files/');
   if ($foo->processed) {
     echo 'image renamed, resized x=100
           and converted to GIF';
     $foo->Clean();
   } else {
     echo 'error : ' . $foo->error;
   } 
}  