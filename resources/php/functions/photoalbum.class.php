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
      $this->initializeDir();
    }

    // load photos
    $this->loadPhotos();
  }

  public function getTitle() {
    return $this->title;
  }

  public function getCaption() {
    return $this->caption;
  }

  public function addPhoto($path, $caption) {
    
  }



  public function jsonSerialize() {
    $array = [];
    $array['id'] = $this->id;
    $array['date-created'] = $this->creationDate;
    $array['date-album'] = $this->albumDate;
    $array['title'] = $this->title;
    $array['caption'] = $this->caption;
    $array['front-photo'] = $this->frontPhoto;
    
    return $array;
  }


  private function initializeJson() {
    FileFunctions::createFile($this->json, 
      '[
        {
        "file-name": "img-34534.jpg",
        "date-captured": "2016-03-19",
        "caption": "dummy-caption"
        }
       ]');
  }

  private function initializeDir() {
    FileFunctions::createFolder($this->photoFolder);
  }

  private function loadPhotos() {
    $this->photos = [];
    $array = FileFunctions::jsonToArray($this->json);
    foreach ($array as $i => $data) {
      $this->photos[] = new Photo($data);
    }  
  }
    
    
}