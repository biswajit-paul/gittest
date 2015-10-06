<?php
// include composer autoload
 require 'vendor/autoload.php';

// import the Intervention Image Manager Class
 use Intervention\Image\ImageManager;


// create Image from file
$img = Image::make('sample.jpg');

//echo '<pre>'; print_r($img); echo '</pre>'; die;

// write text
$img->text('The quick brown fox jumps over the lazy dog.');

// write text at position
$img->text('The quick brown fox jumps over the lazy dog.', 120, 100);

// use callback to define details
$img->text('foo', 0, 0, function($font) {
    $font->file('arial.ttf');
    $font->size(24);
    $font->color('#fdf6e3');
    $font->align('center');
    $font->valign('top');
    $font->angle(45);
});

// draw transparent text
$img->text('foo', 0, 0, function($font) { $font->color(array(255, 255, 255, 0.5)); });