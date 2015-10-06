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

    // capture output for later
    ob_start();

    $disp_width_max=300;    // used when displaying watermark choices
    $disp_height_max=300;    // used when displaying watermark choices

    if(isset($_GET['delete'])){
        // delete one of these images - use the regex to be sure that people aren't passing file paths to other places
        // in the system (ie. "../../../../../etc/passwd")
        $_GET['delete']=preg_replace('`[^a-z0-9-_.]`i','',$_GET['delete']);
        @unlink(dirname(__FILE__).'/results/'.$_GET['delete']);
    }
?>
   <div>
    <h1>Previously Watermarked Images</h1>
    <p>
     <a href="index.php">Watermark A JPEG or PNG Image With PHP and GD2</a>
     <a href="/php-gd-image-watermark.zip">Source Code Download</a>
    </p>
    <p>
     Below are the images that have been previously watermarked by this system. Images are <b>NOT</b> shown at full size.
     To view the image at full size, simply click on it, and it will pop up in a new window. Images are sorted from oldest
     (top) to newest (bottom). Clicking on the "Delete Image" link will remove the image from this system <u>permanently</u>.
    </p>
<?php
    $dir=dirname(__FILE__).'/results';
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            $i=0;
            echo '    <table cellpadding="3" cellspacing="0" border="0">',"\n";
            $watermarks=array();
            while (($file = readdir($dh)) !== FALSE) {
                if($file=='.' || $file=='..') continue;
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

                echo '     <tr>',"\n",
                     '      <td>',"\n",
                     '       <a href="results/',$file,'" target="_blank"><img src="results/',$file,'?id=',
                     md5(time()),'" ',$size[3],' border="0" alt=""/></a>',"\n",
                     '      </td>',"\n",
                     '      <td>',"\n",
                     '       <a href="',$_SERVER['PHP_SELF'],'?delete=',$file,'">Delete Image</a>',"\n",
                     '      </td>',"\n",
                     '     </tr>',"\n";

                $i++;
            }
            if($i==0){
                // no images at this time
                echo '     <tr>',"\n",
                     '      <td>',"\n",
                     '       There are currently no watermarked images saved on the system.',"\n",
                     '      </td>',"\n",
                     '     </tr>',"\n";
            }
            echo '    </table>',"\n",
                 '   </div>',"\n\n";
        }
    }
    $page_display=ob_get_clean();
?>
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Watermarking JPEG and PNG Images with PHP and GD2: Watermarked Images</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <style type="text/css">
   @import url(http://koivi.com/styles.css);
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
    <h1>Watermarking PNG and JPEG Images with PHP and GD2: Watermarked Images</h1>
    <p>
     This page is a continuation of my
     <a href="/php-gd-image-watermark/">Watermarking PNG and JPEG Images with PHP and GD2</a>.
     This is where you can view the images that have been submitted for watermarking. You can also use this
     page to delete images that had been previously watermarked by the system.
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
