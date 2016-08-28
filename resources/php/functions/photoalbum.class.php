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

  public function __construct(array $array) {
    $this->id = $array['id'];
    $this->creationDate = $array['date-created'];
    $this->albumDate = $array['date-album'];
    $this->title = $array['title'];
    $this->caption = $array['caption'];
    $this->frontPhoto = $array['front-photo'];

    // initialize JSON file
    $this->json = JSON_DIR."photo-albums/$this->id.json";
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

    // load photos
    $this->loadPhotos();
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

  public function addPhoto($fileName, $dateCaptured, $caption = "") {
    $dateAdded = date('Y-m-d H:i:s');
    $this->setArrayElement ($fileName, $dateAdded, $dateCaptured, $caption);
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
    FileFunctions::createFile($this->json, 
      '{
        "dummy.jpg" {
          "date-added": "2016-08-28 18:58:39",
          "date-captured": "2015-03-11 11:12:13",
          "caption": "dummy-caption"
        }
       }');
  }

  private function initializeDir($dir) {
    FileFunctions::createFolder($dir);
  }

  private function setArrayElement ($fileName, $dateAdded, $dateCaptured, $caption = "") {
    $photoArray = array ("file-name" => "$fileName", "date-added" => "$dateAdded" , "date-captured" => "$dateCaptured", "caption" => "$caption");
    $photo = new Photo($photoArray);
    $this->photos[$fileName] = $photo;
    $this->savePhotos();
  }

  private function loadPhotos() {
    $this->photos = [];
    $array = FileFunctions::jsonToArray($this->json);
    foreach ((array) $array as $i => $data) {
      $this->photos[] = new Photo($data);
    }  
  }

  private function savePhotos() {
    FileFunctions::arrayToJson($this->photos, $this->json);
  }
    
    
}