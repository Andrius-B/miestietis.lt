<?php
namespace Miestietis\MainBundle\Services;

/**
 * Resizes, crops and saves the current image
 *
 * @param object $file               file to save
 * @param string $ext                file extension
 * @param int  $width                New width
 * @param int  $height               New height
 * @param string $dir                file save directory
 * @param string $fileName           file name
 * @return boolean
 * @throws InvalidArgumentException
 */
class Image{
    public function handleImage($file, $ext, $width, $height, $dir, $fileName){

        //Making a resource instead of object
        switch($ext){
            case 'jpeg':
                $img = imagecreatefromjpeg($file);
                break;
            case 'png':
                $img = imagecreatefrompng($file);
                break;
            case 'gif'  :
                $img = imagecreatefromgif($file);
                break;
            default  :
                throw new InvalidArgumentException("Image type $file->guessExtension() not supported");
        }

        $x =  imagesx($img);
        $y = imagesy($img);

        $original_aspect = $x / $y;
        $new_aspect = $width / $height;

        if ( $original_aspect >= $new_aspect )
        {
            // If image is wider than thumbnail (in aspect ratio sense)
            $new_height = $height;
            $new_width = $x / ($y / $height);
        }
        else
        {
            // If the thumbnail is wider than the image
            $new_width = $width;
            $new_height = $y / ($x / $width);
        }

        $thumb = imagecreatetruecolor( $width, $height );

        // Resize and crop
        imagecopyresampled($thumb,
            $img,
            0 - ($new_width - $width) / 2, // Center the image horizontally
            0 - ($new_height - $height) / 2, // Center the image vertically
            0, 0,
            $new_width, $new_height,
            $x, $y);
        //Moving the new file
        imagejpeg($thumb, $dir.$fileName, 80);
        return true;
    }
}
