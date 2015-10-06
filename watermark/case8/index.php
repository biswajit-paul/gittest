<?php
require_once __DIR__ . '/vendor/autoload.php';
use PHPImageWorkshop\ImageWorkshop;
 
$norwayLayer = ImageWorkshop::initFromPath('/img/sample.jpg');
 
// This is the text layer
$textLayer = ImageWorkshop::initTextLayer('© PHP Image Workshop', '/fonts/arial.ttf', 11, 'ffffff', 0);
 
// We add the text layer 12px from the Left and 12px from the Bottom ("LB") of the norway layer:
$norwayLayer->addLayerOnTop($textLayer, 12, 12, "LB");
 
$image = $norwayLayer->getResult();
header('Content-type: image/jpeg');
imagejpeg($image, null, 95); // We chose to show a JPG with a quality of 95%
exit;


echo 'one';