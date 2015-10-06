<?php
require_once '../lib/WideImage.php';

$image = WideImage::load('sample.jpg');
$canvas = $image->getCanvas();
$canvas->useFont('arial.ttf', 16, $image->allocateColor(0, 0, 0));
$canvas->writeText('right', 'bottom', 'Hello, world!');
$canvas->useFont('arial.ttf', 16, $image->allocateColor(255, 255, 255));
$canvas->writeText('right – 1', 'bottom – 1', 'Hello, world!');
$image->saveToFile('image-with-text.jpg');



