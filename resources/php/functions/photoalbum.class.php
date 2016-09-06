<?php
class PhotoAlbum implements JsonSerializable {

  private $id;
  private $creationDate;
  private $albumDate;
  private $title;
  private $caption;
  private $frontPhoto;

  private $json;
  private $photoFolder;
  private $thumbnailFolder;

  private $photos;
  private $photosAreLoaded;

  public function __construct(array $array) {
    $this->id = $array['id'];
    $this->creationDate = $array['date-created'];
    $this->albumDate = $array['date-album'];
    $this->title = $array['title'];
    $this->caption = $array['caption'];
    $this->frontPhoto = $array['front-photo'];

    // initialize JSON file
    $this->json = JSON_DIR."photo/backup/$this->id.json";
    if (!file_exists ($this->json)) {
      $this->initializeJson();
    }

    // initialize photo folder
    $this->photoFolder = IMG_DIR."albums/$this->id/";
    if (!is_dir($this->photoFolder)) {
      $this->initializeDir($this->photoFolder);
    }

    // initialize thumbnails folder
    $this->thumbnailFolder = IMG_DIR."albums/$this->id/thumbs/";
    if (!is_dir($this->thumbnailFolder)) {
      $this->initializeDir($this->thumbnailFolder);
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

  public function getTitle() {
    return $this->title;
  }

  public function getCaption() {
    return $this->caption;
  }

  public function getFrontPhoto() {
    return $this->frontPhoto;
  }

  public function getPhotoFolder() {
    return $this->photoFolder;
  }

  public function getThumbnailFolder() {
    return $this->thumbnailFolder;
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

  public function addPhoto($fileName, $dateCaptured, $caption = "") {
    if (!$this->photosAreLoaded) $this->loadPhotos(); // load photos if necessary
    $dateUploaded = date('Y-m-d H:i:s');
    $this->setArrayElement ($fileName, $dateUploaded, $dateCaptured, $caption);
  }

  public function updatePhoto ($fileName, $caption) {
    if (!$this->photosAreLoaded) $this->loadPhotos(); // load photos if necessary
    $dateUploaded = $this->photos[$fileName]->getUploadDate();
    $dateCaptured = $this->photos[$fileName]->getCaptureDate();
    $this->setArrayElement ($fileName, $dateUploaded, $dateCaptured, $caption);
  }

  public function jsonSerialize() {
    $array = [];
    $array['date-created'] = $this->creationDate;
    $array['date-album'] = $this->albumDate;
    $array['title'] = $this->title;
    $array['caption'] = $this->caption;
    $array['front-photo'] = $this->frontPhoto;
    
    return $array;
  }




  private function initializeJson() {
    FileFunctions::createFile($this->json, '{}');
  }

  private function initializeDir($dir) {
    FileFunctions::createFolder($dir);
  }

  private function setArrayElement ($fileName, $dateUploaded, $dateCaptured, $caption) {
    $photoArray = array ("file-name" => "$fileName", "date-uploaded" => "$dateUploaded" , "date-captured" => "$dateCaptured", "caption" => "$caption");
    $photo = new Photo($photoArray);
    $this->photos[$fileName] = $photo;
    $this->savePhotos();
  }

  private function loadPhotos() {
    $this->photos = [];
    $array = FileFunctions::jsonToArray($this->json);
    foreach ((array) $array as $fileName => $data) {
      $data['file-name'] = $fileName;
      $this->photos[$fileName] = new Photo($data);
    }  
  }

  private function savePhotos() {
    FileFunctions::arrayToJson($this->photos, $this->json);
  }
    
    
}