<?
if($_GET['photo']==""||$_GET['text']==""){
   echo "example.php?photo=mypic.jpg[.jpeg .gif .png]&text=www.myownsite.com";
   die();
   exit;
}else{
    include "watermark.class.php";
    // Set
    $path       =   "cache/";
    $file       =   "pics/".$_GET['photo']; // original photo
    $font       =   "fonts/arial.ttf";
    $text       =   $_GET['text'];
    $factor     =   "";
    $browser    =   1;
    $fontsize   =   13.5;
    if($browser){
        $force      =   true;
        $foto       =   new watermark($path,$file,$font,$text,$factor,$fontsize,$force,$browser);
    }else{
        $force      =   false;
        $foto       =   new watermark($path,$file,$font,$text,$factor,$fontsize,$force,$browser);
        ?><img src="<?=$foto->outfile?>"></img><?
    }
} ?>
