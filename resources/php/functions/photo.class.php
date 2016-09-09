<?php
class Photo implements JsonSerializable {

  private $fileName;
  private $uploadDate;
  private $captureDate;
  private $titles = []; // I18n
  private $captions = []; // I18n

  public function __construct(array $array) {
    $this->fileName = $array['file-name'];
    $this->uploadDate = $array['date-uploaded'];
    $this->captureDate = $array['date-captured'];
    $this->titles = $array['titles'];
    $this->captions = $array['captions'];
  }

  public function getFileName() {
    return $this->fileName;
  }

  public function getUploadDate() {
    return $this->uploadDate;
  }

  public function getCaptureDate() {
    return $this->captureDate;
  }

  public function getTitle($lang=null) {
    if (!isset($lang)) {
      return $this->titles[I18n::getLang()];
    } else {
      return $this->titles[ (I18n::exists($lang) ? $lang : I18n::defaultLang()) ];
    }
  }

  public function getCaption($lang=null) {
    if (!isset($lang)) {
      return $this->captions[I18n::getLang()];
    } else {
      return $this->captions[ (I18n::exists($lang) ? $lang : I18n::defaultLang()) ];
    }
  }

  public function jsonSerialize() {
    $array = [];
    $array['date-uploaded'] = $this->uploadDate;
    $array['date-captured'] = $this->captureDate;
    $array['titles'] = $this->titles;
    $array['captions'] = $this->captions;
    
    return $array;
  }



  static function copyResized ($sourcePath, $destinationPath, $newWidth = 100, $newHeight = 100) {
    $splitArray = preg_split('/\//', $sourcePath);
    $sourceFileName = end($splitArray);

    // Create image resource from source path
    switch (exif_imagetype($sourcePath)) {
      case IMAGETYPE_JPEG :
        $sourceImage = imagecreatefromjpeg ($sourcePath);
        break;
      case IMAGETYPE_PNG :
        $sourceImage = imagecreatefrompng($sourcePath);
        break;
      case IMAGETYPE_GIF :
        $sourceImage = imagecreatefromgif($sourcePath);
        break;
      default:
        MessageHandler::printError("File Type of $sourceFileName is not a valid image!");
        return false;
    }

    // Create the new image (still blank/empty)
    $newImage = imagecreatetruecolor ($newWidth , $newHeight);

    // Determine the source image Dimensions
    $sourceImageWidth = imagesx($sourceImage);
    $sourceImageHeight = imagesy($sourceImage);
    $sourceImageAspectRatio = $sourceImageWidth / $sourceImageHeight;
    $newImageAspectRatio = $newWidth / $newHeight;

    // Determine parameters and copy part of the source image into the new image
    if ($newImageAspectRatio >= $sourceImageAspectRatio) { // width is the limiting factor for the source image
      $src_x = 0;
      $src_w = $sourceImageWidth;
      $src_h = $src_w / $newImageAspectRatio;
      $src_y = ($sourceImageHeight - $src_h) / 2;
    } else { // height of source image is limiting factor
      $src_y = 0;
      $src_h = $sourceImageHeight;
      $src_w = $src_h * $newImageAspectRatio;
      $src_x = ($sourceImageWidth - $src_w) / 2;
    }
    imagecopyresampled ($newImage, $sourceImage, 0, 0, $src_x, $src_y, $newWidth, $newHeight, $src_w, $src_h);

    // Save new image to destination path
    switch (exif_imagetype($sourcePath)) {
      case IMAGETYPE_JPEG :
        $success = imagejpeg ($newImage, $destinationPath, 100);
        break;
      case IMAGETYPE_PNG :
        $success = imagepng ($newImage, $destinationPath);
        break;
      case IMAGETYPE_GIF :
        $success = imagegif ($newImage, $destinationPath);
        break;
    }
    
    // Remove image resources to reallocate space
    imagedestroy ($sourceImage);
    imagedestroy ($newImage);

    return $success;
  }

}