<?php
class Photo implements JsonSerializable {

  private $fileName;
  private $addedDate;
  private $captureDate;
  private $caption;

  public function __construct(array $array) {
    $this->fileName = $array['file-name'];
    $this->addedDate = $array['date-added'];
    $this->captureDate = $array['date-captured'];
    $this->caption = $array['caption'];
  }

  public function getDate() {
    return $this->captureDate;
  }

  public function getCaption() {
    return $this->caption;
  }

  public function jsonSerialize() {
    $array = [];
    $array['date-added'] = $this->addedDate;
    $array['date-captured'] = $this->captureDate;
    $array['caption'] = $this->caption;
    
    return $array;
  }



  static function createThumbnail ($fileName, $fileType, $photoPath, $thumbnailPath, $thumbSize = 100) {
    $photoFile = $photoPath . $fileName;
    $thumbFile = $thumbnailPath . $fileName;

    if (!is_dir ($thumbnailPath)) {
      FileFunctions::createFolder($thumbnailPath);
    }

    switch ($fileType) {
      case 'image/jpg' : case 'image/jpeg' :
        $src = imagecreatefromjpeg ($photoFile);
        break;
      case 'image/png' :
        $src = imagecreatefrompng($photoFile);
        break;
      case 'image/gif' :
        $src = imagecreatefromgif($photoFile);
        break;
      default:
        MessageHandler::displayError("File Type of $fileName is not a valid image!");
        return;
    }

    // Determine the Image Dimensions
    $oldW = imagesx($src);
    $oldH = imagesy($src);

    // Calculate the New Image Dimensions
    if ($oldH > $oldW){ // Portrait
      $limitingDim = $oldW;
    } else { // Landscape
      $limitingDim = $oldH;
    }

    // Create the New Image
    $new = imagecreatetruecolor ($thumbSize , $thumbSize);

    // Transcribe the Source Image into the New (Square) Image
    imagecopyresampled ($new, $src, 0, 0, ($oldW-$limitingDim)/2 , ($oldH-$limitingDim)/2 , $thumbSize , $thumbSize , $limitingDim , $limitingDim);
    switch ($fileType) {
      case 'image/jpg' : case 'image/jpeg' :
        $src = imagejpeg ($new, $thumbFile, 100);
        break;
      case 'image/png' :
        $src = imagepng ($new, $thumbFile);
        break;
      case 'image/gif' :
        $src = imagegif ($new, $thumbFile);
        break;
    }
    
    imagedestroy ($new);
  }

}