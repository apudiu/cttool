<?php
/**
 * Handles images Upload, delete
 *
 * Supports 2 level 2 thumbnail generation
 */

namespace App\Traits;


use File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait ImageHandleTrait {

    /***** Thumbnail generation *******************************/
    /**
     * Require generating thumbnails if true
     * @var bool
     */
    private $generateThumb;
    /**
     * Require generating big thumbnails if true
     * @var bool
     */
    private $generateBigThumb;


    /***** Upload Paths **************************************/
    /**
     * Image upload path
     * @var string
     */
    private $imagePath;
    /**
     * Image Thumbnail path
     * @var string
     */
    private $imageThumbPath;


    /***** Name prefix ******************************/
    /**
     * Image name prefix
     * @var string
     */
    private $prefix;
    /**
     * Small thumbnail name prefix
     * @var string
     */
    private $thumbPrefix;
    /**
     * Big thumbnail name prefix
     * @var string
     */
    private $thumbBigPrefix;


    /***** Thumbnail Size *************************************/
    /**
     * Small thumbnail size
     * @var array  like: [1280,720] (width , height)
     */
    private $thumbSize;
    /**
     * Big thumbnail size
     * @var array  like: [1280,720] (width , height)
     */
    private $thumbBigSize;


    /***** Thumbnail Generation Constraint **********************/
    /**
     * Methods to be used to generate thumbnails
     * Options are: 'aspectRatio', 'upsize'
     * @var string
     */
    private $constraint = 'upsize';


    /**
     * Sets Image & thumbnail (if $generateThumb is true) save path
     *
     * @param string	$path
     * @param bool  	$generateThumb
     * @param string 	$thumbPath
     * @param bool 		$generateBigThumb
     * @return $this
     */
    public function setUploadPath($path, $generateThumb=false, $thumbPath=null, $generateBigThumb=false) {

        $this->imagePath = $path;

        $this->generateThumb = $generateThumb;
        $this->imageThumbPath= $thumbPath;
        $this->generateBigThumb= $generateBigThumb;

        return $this;
    }

    /**
     * Sets prefix for thumbnail names
     *
     * @param string  $prefix
     * @param string  $thumbPrefix
     * @param string  $thumbBigPrefix
     * @return $this
     */
    public function setPrefix($prefix=null, $thumbPrefix=null, $thumbBigPrefix=null) {

        $this->prefix = $prefix;

        $this->thumbPrefix = $thumbPrefix;
        $this->thumbBigPrefix = $thumbBigPrefix;

        return $this;
    }

    /**
     * Sets thumbnail sizes
     *
     * @param array		$thumbSize		like: [600,300] (width, height)
     * @param array		$thumbBigSize
     * @return $this
     */
    public function setThumbnailSize($thumbSize, $thumbBigSize=null) {

        $this->thumbSize = $thumbSize;
        $this->thumbBigSize = $thumbBigSize;

        return $this;
    }

    /**
     * Sets constraints to use when resizing
     * Options: 'upsize' & 'aspectRatio' (default: aspectRation)
     *
     * @param string $constraint
     * @return $this
     */
    public function setConstraint($constraint='aspectRatio') {

        $this->constraint = $constraint;

        return $this;
    }


    /**
     * Uploads array of images
     *
     * @param array $images		array of images
     * @param string $imageName	Add only for single image upload,
     *                          if set for multiple images, it will append number to image name
     * @param array $resizeDimension	if set then images will be resize, pass value as [300(w), 250(h)]
     * @return array|mixed
     */
    public function uploadImages(array $images, $imageName=null, $resizeDimension=null) {

        $imgs = [];
        $imageCountIndex = 1;

        foreach ($images as $image) {

            //new file name to rename

            if ($imageName) {  // if custom name set

                if ($imageCountIndex > 1) {
                    $rename= $imageName. $imageCountIndex . '.' . $image->getClientOriginalExtension();
                } else {
                    $rename= $imageName . '.' . $image->getClientOriginalExtension();
                }

            } else {

                if ($this->prefix) { // if prefix given
                    $rename= $this->prefix.time() . '-'.Str::random().'.' . $image->getClientOriginalExtension();
                } else {
                    $rename= time() . '-'.Str::random().'.' . $image->getClientOriginalExtension();
                }
            }


            //location to save the image
            $save_path = $this->imagePath.$rename;


            // generating thumbnail

            //small
            if ($this->generateThumb) {

                //location to save the image thumbnail
                $save_path_thumb_small = $this->imageThumbPath.$this->thumbPrefix.$rename;

                //saving small thumbnail & maintain aspect ratio
                Image::make($image)->fit($this->thumbSize[0], $this->thumbSize[1], function($constraint) {
                    $constraint->{$this->constraint}();
                })->save($save_path_thumb_small);
            }

            //big
            if ($this->generateBigThumb) {

                $save_path_thumb_big = $this->imageThumbPath.$this->thumbBigPrefix.$rename;

                //saving big thumbnail & maintain aspect ratio
                Image::make($image)->fit($this->generateBigThumb[0], $this->generateBigThumb[1], function($constraint) {
                    $constraint->{$this->constraint}();
                })->save($save_path_thumb_big);
            }



            //saving the actual image to the location / save path

            if ($resizeDimension) {

                Image::make($image)->fit($resizeDimension[0], $resizeDimension[1], function($constraint) {
                    $constraint->{$this->constraint}();
                })->save($save_path);

            } else {

                Image::make($image)->save($save_path);
            }


            $imgs[] = $rename;

            $imageCountIndex++;
        }

        return (count($imgs) > 1) ? $imgs : $imgs[0];
    }


    /**
     * Deletes all associated images of same name including thumbnails
     * from storage
     * @param string $imageName	base image name
     * @return bool
     */
    public function deleteImage($imageName) {

        // Delete main Image
        File::delete($this->imagePath.$imageName);

        // Delete big thumb Image
        File::delete($this->imageThumbPath.$this->thumbBigPrefix.$imageName);

        // Delete thumb Image
        File::delete($this->imageThumbPath.$this->thumbPrefix.$imageName);

        return true;
    }
}
