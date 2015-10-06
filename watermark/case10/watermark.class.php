<?php
// watermark class
// Mario Guarinoni 12/06/2006
 class watermark
{
       var $path;
       var $file;
       var $font;
       var $text;
       var $factor=70;
       var $fontsize=1;
       var $width;
       var $height;
       var $pos;
       var $source;
       var $ext;
       var $photo;
       var $existephoto;
       var $force=false;
   	   var $browser=true;
       var $_FONTSIZE;
       var $outfile;

    function watermark($path,$file,$font,$text,$factor,$_FONTSIZE=8,$force=false,$browser=true){
        $this->path=$path;
        $this->file=$file;
        $this->font=$font;
        $this->text=$text;
        $this->factor=$factor;
        $this->width=$width;
        $this->lenght=$lenght;
        $this->force=$force;
        $this->browser=$browser;
        $this->_FONTSIZE=$_FONTSIZE;
        $this->outfile='';

        list( $this->width, $this->height ) = getimagesize($this->file); // Sizes

        //Calculate relation between photo and watermark
        $this->calculate_rel();

        // load original photo
        $this->load_photo();

        if($this->force||!$this->existephoto){
            // Auxiliar Images
            $this->photo = imagecreatetruecolor( $this->width, $this->height );

            $mark = imagecreatetruecolor( $this->width, $this->height );

            // Create some colors
            $white = imagecolorallocate($this->photo, 255, 255, 255);
            $gray   = imagecolorallocate($this->photo, 40, 40, 40);
            $black  = imagecolorallocate($this->photo, 0, 0, 0);

            // Calculate the text pos on the photo (bottom right)
            $this->calculate_pos();

            // Copy to other free memory
            imagecopy( $this->photo, $this->source, 0, 0, 0, 0, $this->width, $this->height );

            // Copy to other free memory
            imagecopy( $mark, $this->source, 0, 0, 0, 0, $this->width, $this->height );

            // add shadow
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1]+2, $this->pos[y1]+2, $black, $this->font, $this->text);

            // add shadow
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1], $this->pos[y1]-1, $gray, $this->font, $this->text);

            // add shadow
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1]-1, $this->pos[y1], $gray, $this->font, $this->text);

            // add shadow
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1]-1, $this->pos[y1]-1, $gray, $this->font, $this->text);

            // add shadow
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1], $this->pos[y1]+1, $gray, $this->font, $this->text);

            // add shadow
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1]+1, $this->pos[y1]+1, $gray, $this->font, $this->text);

            // add shadow
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1]+1, $this->pos[y1], $gray, $this->font, $this->text);

            // add text
            imagettftext($mark, $this->fontsize, 0, $this->pos[x1], $this->pos[y1], $white, $this->font, $this->text);

            imagecopymerge( $this->photo, $mark, 0, 0, 0, 0, $this->width, $this->height, 80 );

            // save
            $this->save_photo();

           // free memory
            imagedestroy( $this->photo );
            imagedestroy( $mark );

        }else{
            $this->outfile=$this->path.$this->file;
        }

        // free memory
        imagedestroy( $this->source );
    }

    function load_photo(){
        // load original photo
        $this->ext=strrchr($this->file,".");
        switch($this->ext){
            case ".jpeg":
            case ".jpg":
                $this->source = imagecreatefromjpeg( $this->file );
                break;
            case ".gif":
                $this->source = imagecreatefromgif( $this->file );
                break;
            case ".png":
                $this->source = imagecreatefrompng( $this->file );
                break;
        }
        if(strrpos($this->file,"/"))
            $this->file=strrchr($this->file,"/");
        $this->existephoto=(is_file($this->path.$this->file));
    }
    function save_photo(){
       	if($this->browser) {
			header('Content-type: image/'.($this->ext == '.jpg' ? 'jpeg' : substr(1,$this->ext)));
			header('Content-Transfer-Encoding: binary');
			header('Content-Disposition: inline; filename='.basename($this->file));
			header("Cache-control: private");
		}
        imagesetthickness($this->photo,1);
       	switch($this->ext){
		case ".jpeg":
		case ".jpg":
			imagejpeg($this->photo, $this->browser ? '' : "$this->path$this->file", 80 ) or die ( 'You do not have permission to write in this folder!' );
			break;
		case ".gif":
			imagegif($this->photo, $this->browser ? '' : "$this->path$this->file" ) or die ( 'You do not have permission to write in this folder!' );
			break;
		case ".png":
			imagepng($this->photo, $this->browser ? '' : "$this->path$this->file" ) or die ( 'You do not have permission to write in this folder!' );
			break;
		}
	    $this->outfile=$this->path.$this->file;
        $this->file=strrchr("/",$this->file);
    }
    function calculate_pos(){
      $this->pos[x1]=$this->width-abs($this->size[2]+8);
      $this->pos[y1]=$this->height-abs($this->size[3]+8);
      $this->pos[x2]=$this->width-3;
      $this->pos[y2]=$this->height-3;
    }
    function calculate_rel(){
        if ($this->factor!=""){
            $rel=0;
            // Prove size font until the factor
            while($rel < $this->factor){
                $this->size = imageTTFBBox($this->fontsize, 0, $this->font, $this->text);
                $rel=(abs($this->size[2])*100/$this->width);
                $this->fontsize++;
            }
            if ($this->fontsize!=1) $this->fontsize--;
        }else{
            $this->fontsize=$this->_FONTSIZE;
            $this->size = imageTTFBBox($this->fontsize, 0, $this->font, $this->text);
        }
    }
}


