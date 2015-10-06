<?php
require_once PHPImageWorkshop/ImageWorkshop.php;

$norwayLayer = ImageWorkshop::initFromPath('/var/www/html/samples/php/watermark/case6/logo/csi.jpg');
 
// This is the text layer
$textLayer = ImageWorkshop::initTextLayer('© PHP Image Workshop', '/var/www/html/samples/php/watermark/case6/arial.ttf', 11, 'ffffff', 0);
 
// We add the text layer 12px from the Left and 12px from the Bottom ("LB") of the norway layer:
$norwayLayer->addLayerOnTop($textLayer, 12, 12, "LB");
 
$image = $norwayLayer->getResult();
header('Content-type: image/jpeg');
imagejpeg($image, null, 95); // We chose to show a JPG with a quality of 95%
exit;