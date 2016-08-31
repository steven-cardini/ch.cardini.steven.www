<?php
class PhotoAlbumCatalog {

  private static $instance;
  private static $json = JSON_DIR."photo-albums.json";

  private $albums;
  private $albumsAreLoaded;

  protected function __construct () {
    // initialize photo-albums.json
    if (!file_exists (static::$json)) {
      $this->initializeJson();
    }

    // load albums only when required
    $this->albumsAreLoaded = false;
  }

  public static function getInstance () {
    if (static::$instance === null) {
      static::$instance = new PhotoAlbumCatalog();
    }
    return static::$instance;
  }

  public function getAlbums() {
    if (!$this->albumsAreLoaded) $this->loadAlbums(); // load albums if necessary
    return $this->albums;
  }

  public function getAlbum($id) {
    if (!$this->albumsAreLoaded) $this->loadAlbums(); // load albums if necessary
    return $this->albums[$id];
  }

  public function addAlbum($date, $title, $caption) {
    if (!$this->albumsAreLoaded) $this->loadAlbums(); // load albums if necessary
    $id = $this->generateId();
    $created = date('Y-m-d H:i:s');
    $this->setArrayElement($id, $created, $date, $title, $caption, "");
  }

  public function updateAlbum($id, $date, $title, $caption, $frontPhoto="") {
    if (!$this->albumsAreLoaded) $this->loadAlbums(); // load albums if necessary
    $created = $this->albums[$id]->getCreationDate();
    $this->setArrayElement($id, $created, $date, $title, $caption, $frontPhoto);
  }




  private function initializeJson() {
    FileFunctions::createFile(static::$json, '{}');
  }

  private function generateId() {
    $bytes = random_bytes(4);
    $id = bin2hex($bytes);
    return $id;
  }

  private function setArrayElement($id, $dateCreated, $dateAlbum, $title, $caption, $frontPhoto) {
    $albumArray = array ("id" => "$id", "date-created" => "$dateCreated" , "date-album" => "$dateAlbum", "title" => "$title", "caption" => "$caption", "front-photo" => "$frontPhoto");
    $album = new PhotoAlbum($albumArray);
    $this->albums[$id] = $album;
    $this->saveAlbums();
  }

  private function loadAlbums() {
    $this->albums = [];
    $array = FileFunctions::jsonToArray(static::$json);
    foreach ((array) $array as $id => $data) {
      $data['id'] = $id;
      $this->albums[$id] = new PhotoAlbum($data);
    }  
  }

  private function saveAlbums() {
    FileFunctions::arrayToJson($this->albums, static::$json);
  }

  // Override methods to ensure class is singleton
  private function __clone() { }
  private function __wakeup() { }








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
