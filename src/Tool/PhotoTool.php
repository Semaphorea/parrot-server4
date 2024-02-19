<?php

namespace App\Tool;

use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;

  
trait PhotoTool
{


    /**
     * getMimeTypeFinfo 
     * Arg : Path de l'image
     * Return : Type de l'image 
     **/
    function getMimeTypeFinfo($file)
    {

        $fi = finfo_open(FILEINFO_MIME_TYPE);
        $type = $fi->buffer($file);
        $mime_type = substr($type, strpos($type, '/') + 1, strlen($type));

        finfo_close($fi);
        return $mime_type;
    }


    /**
     * displayPhoto
     * Arg : Photo
     * Return : Array [file en base64,titre]
     */
    function displayPhoto($photo)
    {
        $tabphoto = null;
        $file = stream_get_contents($photo->getBinaryFile());
        if ($file != null) {
            $tabphoto = array('base64' => "data:image/" . $this->getMimeTypeFinfo($file) . ";base64," . base64_encode($file), 'titre' => $photo->getTitre());
        }
        return  $tabphoto;
    }

    /**
     * displayPhoto
     * Arg : Photo
     * Return : Array [file en base64,titre]
     */
    function displayPhoto2($photobinary, $entityphoto)
    {
        $tabphoto = null;
        //$file=stream_get_contents($entityphoto->getBinaryFile());  
        $file = $photobinary;
        if ($photobinary != null && $file != null) {
            $tabphoto = array('base64' => "data:image/" . $this->getMimeTypeFinfo($file) . ";base64," . base64_encode($photobinary), 'titre' => $entityphoto->getTitre());
        }
        return  $tabphoto;
    }

    /**
     * displayFile
     * Arg : BinaryFile
     * Return : Array [file en base64,titre]
     */
    function displayFile($photo, $titre)
    {
        $file = stream_get_contents($photo);
        if ($file != null) {
            $tabphoto['data'] = ['base64' => "data:image/" . $this->getMimeTypeFinfo($file) . ";base64," . base64_encode($file), 'titre' => $titre];
        }
        return  $tabphoto;
    }


    /**
     * Function photocenter
     * Arg String $image (path+titre)
     * Arg String $titre d'image retourne
     * Arg Int $width en pixel
     * Arg Int $height en pixel
     * Return une photo aux dimensions souhaitées
     *  */
    function photocenter(string $image, string $imagecentered, $width, $height)
    {
        $mimetype = $this->getMimeTypeFinfo($image);
        if ($mimetype == 'png') {
            $im = imagecreatefrompng($image);
        }
        if ($mimetype == 'jpg' || $mimetype == 'jpeg') {
            $im = imagecreatefromjpeg($image);
        }
        // $size = min(imagesx($im), imagesy($im));
        $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $height]);
        if ($im2 !== FALSE) {
            imagepng($im2, $imagecentered);
            imagedestroy($im2);
        }
        imagedestroy($im);
    }




    /** 
     * Function photocenter2
     * Arg String $image (path+titre)
     * Arg String $titre d'image retourne
     * Arg Int $width en pixel
     * Arg Int $height en pixel
     * Return une photo aux dimensions souhaitées
     **/
    function photocenter2($image, $imagecentered, $width, $height)
    {
        $res = null;
        $image = stream_get_contents($image, -1);
        $mimetype = $this->getMimeTypeFinfo($image);
        $im = imagecreatefromstring($image);
        $im = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $height]);
        if ($im !== false) {
            $res = $this->saveImageToVariable($im, $mimetype);
        }
        return $res;
    }



    /**
     * Function reduceImageHomothecy
     * Param: Ressource $image           
     * Param: String $mimetype
     * Param: int $max_width
     * Param: int $max_height
     * Return une image attribuable à une variable ayant le typemime déterminé
     * */
    function reduceImageHomothecy($image, $mimetype, $widthmax, $heightmax)
    {
        // Load the image
        $image = stream_get_contents($image, -1);
        $mimetype = $this->getMimeTypeFinfo($image);
        $im = imagecreatefromstring($image);

        // Determine the center of homothety
        $cx = imagesx($im) / 2;
        $cy = imagesy($im) / 2;

        // Determine the ratio of homothety
        $ratio = min($widthmax / imagesx($im), $heightmax / imagesy($im));

        // Apply homothecy
        $new_width = imagesx($im) * $ratio;
        $new_height = imagesy($im) * $ratio;
        $new_im = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_im, $im, 0, 0, $cx, $cy, $new_width, $new_height, $new_width / $ratio, $new_height / $ratio);

        $res = $this->saveImageToVariable($new_im, $mimetype);

        return $res;
    }

    /**
     * Function reduceImageProportional
     * Param: Ressource $image           
     * Param: String $mimetype
     * Param: int $max_width
     * Param: int $max_height
     * Return une image attribuable à une variable ayant le typemime déterminé
     * */
    function reduceImageProportional($image, $mimetype, $max_width, $max_height)
    {
        $image = stream_get_contents($image, -1);
        $mimetype = $this->getMimeTypeFinfo($image);
        $im = imagecreatefromstring($image);

        var_dump("xsize" . imagesx($im));
        var_dump("ysize" . imagesy($im));
        // Get the original dimensions
        $orig_width = imagesx($im);
        $orig_height = imagesy($im);

        // Calculate the aspect ratio
        $aspect_ratio = $orig_width / $orig_height;

        // Calculate the new dimensions
        if ($aspect_ratio > 1) {
            // Landscape image
            $new_width = $max_width;
            $new_height = $max_width / $aspect_ratio;
        } else {
            // Portrait image
            $new_height = $max_height;
            $new_width = $max_height * $aspect_ratio;
        }

        // Resize the image
        $new_im = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_im, $im, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
        var_dump("xsize resized" . imagesx($new_im));
        var_dump("ysize resized" . imagesy($new_im));


        $res = $this->saveImageToVariable($new_im, $mimetype);

        return $res;
    }

    /**
     * Function reduceImageScale
     * Param: Ressource $image 
     * Param: String $mimetype
     * Param: int $max_width
     * Param: int $max_height
     * Return une image attribuable à une variable ayant le typemime déterminé
     * */
    function reduceImageScale($image, $mimetype, $max_width, $max_height)
    {
        $image = stream_get_contents($image, -1);
        $mimetype = $this->getMimeTypeFinfo($image);
        $im = imagecreatefromstring($image);

        //modes imagescale : IMG_NEAREST_NEIGHBOUR, IMG_BILINEAR_FIXED, IMG_BICUBIC, IMG_BICUBIC_FIXED
        $imgResized = imagescale($im, $max_width, $max_height, IMG_BICUBIC);

        //Renforcement de la netteté d'image
        $sharpen = array([0, -1, 0], [-1, 5, -1], [0, -1, 0]);
        imageconvolution($imgResized, $sharpen, 1, 0);


        $res = $this->saveImageToVariable($imgResized, $mimetype);


        return $res;
    }


    /**
     * Function saveImageToVariable
     * Param $img au format GdImage
     * Param $mimetype (png par défaut)
     * Return une image attribuable à une variable ayant le typemime déterminé
     */
    function saveImageToVariable($img, $mimetype = 'png')
    {
        ob_start();

        switch ($mimetype) {
            case 'png':
                imagepng($img, null, 0, PNG_NO_FILTER);;
                break;
            case 'jpeg' || 'jpg':
                imagejpeg($img, null, 100);;
                break;
        }

        $res = ob_get_clean();
        return $res;
    }

   
}
