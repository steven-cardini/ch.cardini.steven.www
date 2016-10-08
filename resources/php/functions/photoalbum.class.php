<?php
class PhotoAlbum implements JsonSerializable {

  private $id;
  private $creationDate;
  private $albumDate;
  private $titles = []; //I18n
  private $captions = []; // I18n
  private $frontPhoto;

  private $json;
  private $photoFolder;
  private $thumbnailFolder;
  private $frontFolder; // folder for front photos

  private $photos;
  private $photosAreLoaded;

  public function __construct(array $array) {
    $this->id = $array['id'];
    $this->creationDate = $array['date-created'];
    $this->albumDate = $array['date-album'];
    $this->titles = $array['titles'];
    $this->captions = $array['captions'];
    $this->frontPhoto = $array['front-photo'];

    // initialize JSON file
    $this->json = absPath("JSON") . "photo/backup/$this->id.json";
    if (!file_exists ($this->json)) {
      $this->initializeJson();
    }

    // initialize photo folder
    $this->photoFolder = array (
      "html" => absPath("IMG", true) . "albums/$this->id/",
      "php" => absPath("IMG") . "albums/$this->id/"
    );    
    if (!is_dir($this->photoFolder['php'])) {
      $this->initializeDir($this->photoFolder['php']);
    }

    // initialize thumbnails folder
    $this->thumbnailFolder = array (
      "html" => absPath("IMG", true) . "albums/$this->id/thumbs/",
      "php" => absPath("IMG") . "albums/$this->id/thumbs/"
    );
    if (!is_dir($this->thumbnailFolder['php'])) {
      $this->initializeDir($this->thumbnailFolder['php']);
    }

    // initialize front photos folder
    $this->frontFolder = array (
      "html" => absPath("IMG", true) . "albums/$this->id/front/",
      "php" => absPath("IMG") . "albums/$this->id/front/"
    );
    if (!is_dir($this->frontFolder['php'])) {
      $this->initializeDir($this->frontFolder['php']);
    }

    // load photos only when required
    $this->photosAreLoaded = false;
  }

  public function getId() {
    return $this->id;
  }

  public function getCreationDate() {
    return $this->creationDate;
  }

  public function getAlbumDate() {
    return $this->albumDate;
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

  public function getFrontPhoto() {
    return $this->frontPhoto;
  }

  public function getPhotoFolder($html=false) {
    if ($html) return $this->photoFolder['html'];
    else return $this->photoFolder['php'];
  }

  public function getThumbnailFolder($html=false) {
    if ($html) return $this->thumbnailFolder['html'];
    else return $this->thumbnailFolder['php'];
  }

  public function getFrontFolder($html=false) {
    if ($html) return $this->frontFolder['html'];
    else return $this->frontFolder['php'];
  }

  public function getPhotos () {
    if (!$this->photosAreLoaded) $this->loadPhotos(); // load photos if necessary
    return $this->photos;
  }

  public function getPhoto ($fileName) {
    if (!$this->photosAreLoaded) $this->loadPhotos(); // load photos if necessary
    if (isset($this->photos[$fileName])) {
      return $this->photos[$fileName];
    } else {
      return false;
    }
  }

  public function addPhoto($fileName, $dateCaptured, $titles = null, $captions = null) {
    if (!$this->photosAreLoaded) $this->loadPhotos(); // load photos if necessary
    $dateUploaded = date('Y-m-d H:i:s');
    $this->setArrayElement ($fileName, $dateUploaded, $dateCaptured, $titles, $captions);
  }

  public function updatePhoto ($fileName, $titles, $captions) {
    if (!$this->photosAreLoaded) $this->loadPhotos(); // load photos if necessary
    $dateUploaded = $this->photos[$fileName]->getUploadDate();
    $dateCaptured = $this->photos[$fileName]->getCaptureDate();
    $this->setArrayElement ($fileName, $dateUploaded, $dateCaptured, $titles, $captions);
  }

  public function generateGalleriaJson () {
    $galleria = new Galleria ($this);
    $galleria->persist();
  }

  public function jsonSerialize() {
    $array = [];
    $array['date-created'] = $this->creationDate;
    $array['date-album'] = $this->albumDate;
    $array['titles'] = $this->titles;
    $array['captions'] = $this->captions;
    $array['front-photo'] = $this->frontPhoto;
    
    return $array;
  }




  private function initializeJson() {
    FileFunctions::createFile($this->json, '{}');
  }

  private function initializeDir($dir) {
    FileFunctions::createFolder($dir);
  }

  private function setArrayElement ($fileName, $dateUploaded, $dateCaptured, $titles, $captions) {
    if (!isset($titles))
      $titles = array("en"=>"", "de"=>"");
    if (!isset($captions))
      $captions = array("en"=>"", "de"=>"");
    
    $photoArray = array ("file-name" => "$fileName", "date-uploaded" => "$dateUploaded" , "date-captured" => "$dateCaptured", "titles" => array("en"=>$titles["en"], "de"=>$titles["de"]), "captions" => array("de"=>$captions["de"], "en"=>$captions["en"]));
    $photo = new Photo($photoArray);
    $this->photos[$fileName] = $photo;
    $this->persistPhotos();
  }

  private function loadPhotos() {
    $this->photos = [];
    $array = FileFunctions::jsonToArray($this->json);
    foreach ((array) $array as $fileName => $data) {
      $data['file-name'] = $fileName;
      $this->photos[$fileName] = new Photo($data);
    }  
  }

  private function persistPhotos() {
    FileFunctions::arrayToJson($this->photos, $this->json);
  }
    
}
