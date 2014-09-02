<?php

namespace Modules;

class Image extends Module{

    const jpg = "jpg";
    const gif = "gif";
    const png = "png";

    protected $image;

    public function createImageFromFile($filename){
        $this->image = imagecreatefromstring(file_get_contents($filename));
    }

    public function createImage($width, $height){
        $this->image = imagecreatetruecolor($width, $height);
    }

    public function resize($width = null, $height = null){
        $new         = imagecreatetruecolor($width, $height);
        imagecopyresampled($new, $this->image, 0, 0, 0, 0, $width, $height, imagesx($this->image), imagesy($this->image));
        $this->image = $new;
        imagedestroy($new);
    }

    public function crop($x = 0, $y = 0, $width = null, $height = null){
        $new         = imagecreatetruecolor($width, $height);
        imagecopyresampled($new, $this->image, 0, 0, $x, $y, $width, $height, $width, $height);
        $this->image = $new;
        imagedestroy($new);
    }

    public function display($filetype = Image::jpg, $quality = 100){
        switch($filetype){
            case Image::jpg:
                header("content-type: image/jpeg");
                imagejpeg($this->image, null, $quality);
                break;
            case Image::gif:
                header("content-type: image/gif");
                imagegif($this->image);
                break;
            case Image::png:
                header("content-type: image/png");
                $quality = ceil((9 / 100) * $quality);
                imagepng($this->image, null, $quality);
                break;
        }
    }

    public function save($filename, $filetype = Image::jpg, $quality = 100){
        switch($filetype){
            case Image::jpg:
                imagejpeg($this->image, $filename, $quality);
                break;
            case Image::gif:
                imagegif($this->image, $filename);
                break;
            case Image::png:
                $quality = ceil((9 / 100) * $quality);
                imagepng($this->image, $filename, $quality);
                break;
        }
    }

    /**
     * Creates a layer for the current image
     * @param string $layer_name
     * @return \Modules\Image\Layer
     */
    public function createLayer($layer_name){
        $layer = new Image\Layer();
        $layer->setName($layer_name);
        return $layer;
    }

}
