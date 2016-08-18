<?php
class PhotoHandler {

  private static $instance;
  private static $json_albums = JSON_DIR."photo-albums.json";
  private static $photoRepository;

  private $albums;

  protected function __construct () {
    // initialize photo-albums.json
    if (!file_exists (static::$json_albums)) {
      FileFunctions::createFile(static::$json_albums, 
      '[
        {
        "id": "abcdef",
        "date": "2000-01-01",
        "title": "dummy-title",
        "caption": "dummy-caption",
        "front-photo": "front.png"
        }
       ]');
    }
    
    // initialize $this->albums instance variable
    $this->albums = [];
    $array = FileFunctions::jsonToArray(static::$json_albums);
    foreach ($array as $i => $data) {
      $this->albums[] = new PhotoAlbum($data);
    }
        
  }

  private function __clone() { }

  private function __wakeup() { }

  public static function getInstance () {
    if (static::$instance === null) {
      static::$instance = new PhotoHandler();
    }
    return static::$instance;
  }

  public function getAlbums() {
    return $this->albums;
  }




  // returns all distinct folders from photo db --> used for photo selection
  static function getPhotoCategoriesFromDB () {
    if (!isset($photoRepository)) {
      $photoRepository = new PhotoRepository();
    }
    return $photoRepository->retrieveCategories();
  }

  // returns all photos for a given category from db
  static function getPhotosFromDB ($category) {
    if (!isset($photoRepository)) {
      $photoRepository = new PhotoRepository();
    }
    return $photoRepository->retrievePhotos($category);
  }

  // returns all photo files in a directory
  static function getPhotos ($dir) {
    $photos = array();
    $files = scandir ($dir);
    foreach ($files as $file) {
      if (!FileFunctions::isImage($file)) {
        continue;
      }
      $photos[] = $file;
    }
    return $photos;
  }

  static function savePhoto ($dir, $file, $title) {
    if (!isset($photoRepository)) {
      $photoRepository = new PhotoRepository();
    }
    PhotoFunctions::createThumbnail(PHOTO_DIR.$dir, $file, 100);
    $exifData = exif_read_data (PHOTO_DIR.$dir.$file);
    if (isset($exifData['DateTimeOriginal'])) {
      $creationDate = $exifData['DateTimeOriginal'];
    } else {
      $creationDate = date("Y-m-d H:i:s");
    }
    $res = $photoRepository->saveNewPhoto ($file, $dir, $creationDate, $title);
  }

  private static function createThumbnail ($path, $fileName, $thumbSize=100){
    /* Set Filenames, prepare thumb dir */
    $srcFile = $path.$fileName;
    $thumbFile = $path.'thumbs/'.$fileName;
    if (!file_exists($path.'thumbs/')) {
      mkdir($path.'thumbs/');
    }

    /* Determine the File Type */
    $type = FileFunctions::getExtension($fileName);

    /* Create the Source Image */
    switch($type){
      case 'jpg' : case 'jpeg' :
        $src = imagecreatefromjpeg ($srcFile);
        break;
      case 'png' :
        $src = imagecreatefrompng($srcFile);
        break;
      case 'gif' :
        $src = imagecreatefromgif($srcFile);
        break;
      }

      /* Determine the Image Dimensions */
      $oldW = imagesx($src);
      $oldH = imagesy($src);

      /* Calculate the New Image Dimensions */
      if ($oldH > $oldW){
        /* Portrait */
        $limitingDim = $oldW;
      }else{
        /* Landscape */
        $limitingDim = $oldH;
      }

      /* Create the New Image */
      $new = imagecreatetruecolor ($thumbSize , $thumbSize);

      /* Transcribe the Source Image into the New (Square) Image */
      imagecopyresampled ($new, $src, 0, 0, ($oldW-$limitingDim)/2 , ($oldH-$limitingDim)/2 , $thumbSize , $thumbSize , $limitingDim , $limitingDim);
      switch( $type ){
        case 'jpg' : case 'jpeg' :
          $src = imagejpeg ($new, $thumbFile, 100);
          break;
        case 'png' :
          $src = imagepng ($new, $thumbFile);
          break;
        case 'gif' :
          $src = imagegif ($new, $thumbFile);
          break;
        }
        imagedestroy ($new);
      }
    }
