<?php
include_once 'lib/WideImage.php';

$img = WideImage::load('pic.jpg');
$watermark = WideImage::load('logo.png');
$new = $img->merge($watermark, 10, 10, 100);
WideImage::load('pic.jpg')->saveToFile('converted.bmp');
$resized = WideImage::load('pic.jpg')->resize(400, 300);
echo '<pre>'; print_r($resized); echo '</pre>';
?>
<div style="width: 400px;">
<?php //$resized = WideImage::load('pic.jpg')->resize(400, 300)->output('jpg', 90); ?>
</div>