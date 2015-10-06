<?php
/*
KOIVI GD Image Watermarker for PHP Copyright (C) 2004 Justin Koivisto
Version 2.0
Last Modified: 12/9/2004

    This library is free software; you can redistribute it and/or modify it
    under the terms of the GNU Lesser General Public License as published by
    the Free Software Foundation; either version 2.1 of the License, or (at
    your option) any later version.

    This library is distributed in the hope that it will be useful, but
    WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
    or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public
    License for more details.

    You should have received a copy of the GNU Lesser General Public License
    along with this library; if not, write to the Free Software Foundation,
    Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA 

    Full license agreement notice can be found in the LICENSE file contained
    within this distribution package.

    Justin Koivisto
    justin.koivisto@gmail.com
    http://www.koivi.com
*/

    ob_start();

    $disp_width_max=150;                    // used when displaying watermark choices
    $disp_height_max=80;                    // used when displaying watermark choices
    $edgePadding=15;                        // used when placing the watermark near an edge
    $quality=100;                           // used when generating the final image
    $default_watermark='Sample-trans.png';  // the default image to use if no watermark was chosen
    
    if(isset($_POST['process'])){
        // an image has been posted, let's get to the nitty-gritty
        if(isset($_FILES['watermarkee']) && $_FILES['watermarkee']['error']==0){
        
            // be sure that the other options we need have some kind of value
            if(!isset($_POST['save_as'])) $_POST['save_as']='jpeg';
            if(!isset($_POST['v_position'])) $_POST['v_position']='center';
            if(!isset($_POST['h_position'])) $_POST['h_position']='center';
            if(!isset($_POST['wm_size'])) $_POST['wm_size']='1';
            if(!isset($_POST['watermark'])) $_POST['']=$default_watermark;
        
            // file upload success
            $size=getimagesize($_FILES['watermarkee']['tmp_name']);
            if($size[2]==2 || $size[2]==3){
                // it was a JPEG or PNG image, so we're OK so far
                
                $original=$_FILES['watermarkee']['tmp_name'];
                $target_name=date('YmdHis').'_'.
                    // if you change this regex, be sure to change it in generated-images.php:26
                    preg_replace('`[^a-z0-9-_.]`i','',$_FILES['watermarkee']['name']);
                $target=dirname(__FILE__).'/results/'.$target_name;
                $watermark=dirname(__FILE__).'/watermarks/'.$_POST['watermark'];
                $wmTarget=$watermark.'.tmp';

                $origInfo = getimagesize($original); 
                $origWidth = $origInfo[0]; 
                $origHeight = $origInfo[1]; 

                $waterMarkInfo = getimagesize($watermark);
                $waterMarkWidth = $waterMarkInfo[0];
                $waterMarkHeight = $waterMarkInfo[1];
        
                // watermark sizing info
                if($_POST['wm_size']=='larger'){
                    $placementX=0;
                    $placementY=0;
                    $_POST['h_position']='center';
                    $_POST['v_position']='center';
                	$waterMarkDestWidth=$waterMarkWidth;
                	$waterMarkDestHeight=$waterMarkHeight;
                    
                    // both of the watermark dimensions need to be 5% more than the original image...
                    // adjust width first.
                    if($waterMarkWidth > $origWidth*1.05 && $waterMarkHeight > $origHeight*1.05){
                    	// both are already larger than the original by at least 5%...
                    	// we need to make the watermark *smaller* for this one.
                    	
                    	// where is the largest difference?
                    	$wdiff=$waterMarkDestWidth - $origWidth;
                    	$hdiff=$waterMarkDestHeight - $origHeight;
                    	if($wdiff > $hdiff){
                    		// the width has the largest difference - get percentage
                    		$sizer=($wdiff/$waterMarkDestWidth)-0.05;
                    	}else{
                    		$sizer=($hdiff/$waterMarkDestHeight)-0.05;
                    	}
                    	$waterMarkDestWidth-=$waterMarkDestWidth * $sizer;
                    	$waterMarkDestHeight-=$waterMarkDestHeight * $sizer;
                    }else{
                    	// the watermark will need to be enlarged for this one
                    	
                    	// where is the largest difference?
                    	$wdiff=$origWidth - $waterMarkDestWidth;
                    	$hdiff=$origHeight - $waterMarkDestHeight;
                    	if($wdiff > $hdiff){
                    		// the width has the largest difference - get percentage
                    		$sizer=($wdiff/$waterMarkDestWidth)+0.05;
                    	}else{
                    		$sizer=($hdiff/$waterMarkDestHeight)+0.05;
                    	}
                    	$waterMarkDestWidth+=$waterMarkDestWidth * $sizer;
                    	$waterMarkDestHeight+=$waterMarkDestHeight * $sizer;
                    }
                }else{
	                $waterMarkDestWidth=round($origWidth * floatval($_POST['wm_size']));
	                $waterMarkDestHeight=round($origHeight * floatval($_POST['wm_size']));
	                if($_POST['wm_size']==1){
	                    $waterMarkDestWidth-=2*$edgePadding;
	                    $waterMarkDestHeight-=2*$edgePadding;
	                }
                }

                // OK, we have what size we want the watermark to be, time to scale the watermark image
                resize_png_image($watermark,$waterMarkDestWidth,$waterMarkDestHeight,$wmTarget);
                
                // get the size info for this watermark.
                $wmInfo=getimagesize($wmTarget);
                $waterMarkDestWidth=$wmInfo[0];
                $waterMarkDestHeight=$wmInfo[1];

                $differenceX = $origWidth - $waterMarkDestWidth;
                $differenceY = $origHeight - $waterMarkDestHeight;

                // where to place the watermark?
                switch($_POST['h_position']){
                    // find the X coord for placement
                    case 'left':
                        $placementX = $edgePadding;
                        break;
                    case 'center':
                        $placementX =  round($differenceX / 2);
                        break;
                    case 'right':
                        $placementX = $origWidth - $waterMarkDestWidth - $edgePadding;
                        break;
                }

                switch($_POST['v_position']){
                    // find the Y coord for placement
                    case 'top':
                        $placementY = $edgePadding;
                        break;
                    case 'center':
                        $placementY =  round($differenceY / 2);
                        break;
                    case 'bottom':
                        $placementY = $origHeight - $waterMarkDestHeight - $edgePadding;
                        break;
                }
       
                if($size[2]==3)
                    $resultImage = imagecreatefrompng($original);
                else
                    $resultImage = imagecreatefromjpeg($original);
                imagealphablending($resultImage, TRUE);
        
                $finalWaterMarkImage = imagecreatefrompng($wmTarget);
                $finalWaterMarkWidth = imagesx($finalWaterMarkImage);
                $finalWaterMarkHeight = imagesy($finalWaterMarkImage);
        
                imagecopy($resultImage,
                          $finalWaterMarkImage,
                          $placementX,
                          $placementY,
                          0,
                          0,
                          $finalWaterMarkWidth,
                          $finalWaterMarkHeight
                );
                
                if($size[2]==3){
                    imagealphablending($resultImage,FALSE);
                    imagesavealpha($resultImage,TRUE);
                    imagepng($resultImage,$target,$quality);
                }else{
                    imagejpeg($resultImage,$target,$quality); 
                }

                imagedestroy($resultImage);
                imagedestroy($finalWaterMarkImage);

                // display resulting image for download
?>
   <div>
    <h1>Watermarked Image</h1>
    <p>
     <a href="<?php echo $_SERVER['PHP_SELF'] ?>">Back To Form</a> <a href="generated-images.php">View Previously Watermarked Images</a>
     <a href="/php-gd-image-watermark.zip">Source Code Download</a>
    </p>
    <p>
     Below is the resulting watermarked image based on your input on the submission form. To save the image, right-click
     (control click on a Mac) and select the option similar to "Save Image As..." You can then save the image to your harddrive
     when prompted for a save location.
    </p>
    <p>
     Watermarked images are also saved on this system. You can view and/or delete watermarked images
     <a href="generated-images.php">here</a>.
    </p>
    <p align="center">
     <img src="results/<?php echo $target_name ?>?id=<?php echo md5(time()) ?>" alt="" />
    </p>
    <hr>
   </div>
<?php
                unlink($wmTarget);
            }else{
?>
   <div>
    <h1>Watermarked Image</h1>
    <p class="errorMsg">The image uploaded was not a JPEG or PNG image.</p>
   </div>
<?php
            }
        }else{
?>
   <div>
    <h1>Watermarked Image</h1>
    <p class="errorMsg">Unable to upload the image to watermark.</p>
   </div>
<?php
        }
    }else{
        // no image posted, show form for options
?>
   <div>
    <h1>Watermark A JPEG or PNG Image</h1>
    <p>
     <a href="generated-images.php">View Previously Watermarked Images</a>
     <a href="/php-gd-image-watermark.zip">Source Code Download</a>
    </p>
    <p>
      If you are uploading a PNG-24 image that has alpha transparency and are saving it as a PNG,
      the transparency will be saved with the watermarked version of the image. However, if the original
      image is a PNG-8 with transparency, you may get unexpected results.
    </p>
    <form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>">
     <table cellpadding="2" cellspacing="0" border="0">
      <tr>
       <th width="50%">
        Image to WaterMark:
        <p>(72dpi, RGB JPEG/PNG)</p>
       </th>
       <td width="50%">
        <input type="file" name="watermarkee" />
       </td>
      </tr>
      <tr>
       <th valign="top">
        Choose Watermark:<br />
        <p>
         Watermark images are located in the &quot;watermarks&quot; subdirectory. All images are PNG-24 with alpha
         transparency. These images were created using Adobe Photoshop&reg; by setting the image's opacity to 30% and
         using the "Save For Web" option to save the file.
        </p>
        <p>
         The watermark images shown are not displayed at their true size, they are actually quite a bit larger so that
         the quality of the watermark will not deteriorate as quickly when watermarking larger images.
        </p>
       </th>
       <td valign="top">
        <table cellpadding="2" cellspacing="0" border="0">
<?php
        $dir=dirname(__FILE__).'/watermarks';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $i=0;
                $watermarks=array();
                while (($file = readdir($dh)) !== FALSE) {
                    if(!preg_match('`\.png$`',$file)) continue;
                    $watermarks[]=$file;
                }
                closedir($dh);
                
                // now sort the array according to file name
                asort($watermarks);
                
                foreach($watermarks as $file){
                    // restrain display width
                    $size=getimagesize($dir.'/'.$file);
                    if($size[0] > $disp_width_max && $disp_width_max){
                        $reduction=$disp_width_max / $size[0];
                        $size[0]=$size[0]*$reduction;
                        $size[1]=$size[1]*$reduction;
                        $size[3]=sprintf('width="%d" height="%d"',$size[0],$size[1]);
                        
                        // also restrain the height after a resize
                        if($size[1] > $disp_height_max && $disp_height_max){
                            $reduction=$disp_height_max / $size[1];
                            $size[0]=$size[0]*$reduction;
                            $size[1]=$size[1]*$reduction;
                            $size[3]=sprintf('width="%d" height="%d"',$size[0],$size[1]);
                        }
                    }

                    // also restrain the height
                    if($size[1] > $disp_height_max && $disp_height_max){
                        $reduction=$disp_height_max / $size[1];
                        $size[0]=$size[0]*$reduction;
                        $size[1]=$size[1]*$reduction;
                        $size[3]=sprintf('width="%d" height="%d"',$size[0],$size[1]);
                    }

                    echo '      <tr>',"\n",
                         '       <td>',"\n",
                         '        <input type="radio" value="',$file,'" name="watermark" /> ',
                         '<img src="watermarks/',$file,'" ',$size[3],' alt="" />',"\n",
                         '       </td>',"\n",
                         '      </tr>',"\n";

                    $i++;
                }
                if($i==0){
                    // no images at this time
                    echo '     <tr>',"\n",
                         '      <td>',"\n",
                         '       There are currently no watermark images to use with this the system on this server.',"\n",
                         '      </td>',"\n",
                         '     </tr>',"\n";
                }
            }
        }
?>
        </table>
       </td>
      </tr>
      <tr>
       <th valign="top">
        Watermark Position:
        <p>(Horizontal)</p>
       </th>
       <td>
        <input type="radio" name="h_position" value="left" /> Left<br />
        <input type="radio" name="h_position" value="center" /> Center<br />
        <input type="radio" name="h_position" value="right" /> Right<br />
       </td>
      </tr>
      <tr>
       <th valign="top">
        Watermark Position:
        <p>(Vertical)</p>
       </th>
       <td>
        <input type="radio" name="v_position" value="top" /> Top<br />
        <input type="radio" name="v_position" value="center" /> Center<br />
        <input type="radio" name="v_position" value="bottom" /> Bottom<br />
       </td>
      </tr>
      <tr>
       <th valign="top">
        Watermark Coverage:
        <p>(Size of Originial)</p>
       </th>
       <td>
        <input type="radio" name="wm_size" value="larger" /> Cover Entire Image<br />
        <input type="radio" name="wm_size" value="1" /> 100% (Shows entire watermark)<br />
        <input type="radio" name="wm_size" value=".5" /> 50%<br />
       </td>
      </tr>
      <tr>
       <td align="center" colspan="2">
        <input type="submit" name="process" value="Watermark Image" />
       </td>
      </tr>
     </table>
    </form>
   </div>
<?php
    }
    $page_display=ob_get_clean();

function resize_png_image($img,$newWidth,$newHeight,$target){
    $srcImage=imagecreatefrompng($img);
    if($srcImage==''){
        return FALSE;
    }
    $srcWidth=imagesx($srcImage);
    $srcHeight=imagesy($srcImage);
    $percentage=(double)$newWidth/$srcWidth;
    $destHeight=round($srcHeight*$percentage)+1;
    $destWidth=round($srcWidth*$percentage)+1;
    if($destHeight > $newHeight){
        // if the width produces a height bigger than we want, calculate based on height
        $percentage=(double)$newHeight/$srcHeight;
        $destHeight=round($srcHeight*$percentage)+1;
        $destWidth=round($srcWidth*$percentage)+1;
    }
    $destImage=imagecreatetruecolor($destWidth-1,$destHeight-1);
    if(!imagealphablending($destImage,FALSE)){
        return FALSE;
    }
    if(!imagesavealpha($destImage,TRUE)){
        return FALSE;
    }
    if(!imagecopyresampled($destImage,$srcImage,0,0,0,0,$destWidth,$destHeight,$srcWidth,$srcHeight)){
        return FALSE;
    }
    if(!imagepng($destImage,$target)){
        return FALSE;
    }
    imagedestroy($destImage);
    imagedestroy($srcImage);
    return TRUE;
}

	echo '<?xml version="1.0" encoding="iso-8859-1"?>',"\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Watermarking JPEG and PNG Images with PHP and GD2</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="description" content="A PHP implementation to watermark JPEG or PNG images with a PNG-24 image with alpha transparency." />
  <style type="text/css">
   @import url(http://koivi.com/styles.css);
    th{
        text-align: right;
        font-weight: bold;
    }
    th p{
        font-weight: normal;
        font-size: 75%;
        color: #c00;
        background-color: transparent;
    }
  </style>
  <!--[if lt IE 7]><script src="/ie7/ie7-standard.js" type="text/javascript"></script><![endif]-->
 
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-207722-1";
urchinTracker();
</script>
 </head>


 <body>
  <div id="container">
   <div id="intro">
    <h1>Watermarking PNG and JPEG Images with PHP and GD2</h1>
    <p>
     Have you ever wanted to add a transparent watermark to an image that you post on your website?
     If so, then this may be exactly what you are looking for!
    </p>
    <p>
     Choose the image to watermark, the watermark you want to use (pulled from a directory of images),
     how you want it positioned on the resulting image and its size. Watermarked images are stored on
     the server so you can use them later. Management screen also lets you delete a watermarked image
     from the server to reduce clutter.
    </p>
    <p>
     A <big>BIG</big> &quot;Thank-you&quot; goes out to J Wynia for his excellent article entitled
     &quot;<a href="http://www.phpgeek.com/articles.php?content_id=6">Watermarking Photos with PHP/GD2</a>&quot;
     from June 3, 2003 that covers the basics that were used to get this system together!
    </p>
   </div>

   <div>
    <?php echo $page_display ?>
   </div>

<script type="text/javascript"><!--
google_ad_client = "pub-6879264339756189";
google_alternate_ad_url = "http://koivi.com/google_adsense_script.html";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text_image";
google_ad_channel ="7653137181";
google_color_border = "6E6057";
google_color_bg = "DFE0D0";
google_color_link = "313040";
google_color_url = "0000CC";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

  </div>

<?php include_once 'site_menu.php'; ?>

 </body>
</html>
