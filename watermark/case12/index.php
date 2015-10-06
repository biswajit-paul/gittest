<?php
  require __DIR__ . '/vendor/autoload.php';
?>

<form enctype="multipart/form-data" method="post" action="upload.php">
  <input type="file" size="32" name="image_field" value="">
  <input type="submit" name="Submit" value="upload">
</form>