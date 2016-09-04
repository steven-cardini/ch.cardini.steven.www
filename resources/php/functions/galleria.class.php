<?php

class Galleria {

  private static $jsonPath = JSON_DIR . 'galleria/';
  
  private $media;
  private $index;
  private $json;

  public function __construct ($albumId) {
    $this->json = static::$jsonPath . $albumId . '.json';
    if (is_file($this->json)) { // file already exists --> load it
      $this->media = FileFunctions::jsonToArray($this->json);
      $this->index = count($this->media)-1;
    } else {
      $this->media = [];
      $this->index = -1;
      if (!is_dir(static::$jsonPath)) FileFunctions::createFolder(static::$jsonPath);
    }

  }

  public function addPhoto ($photoPath, $thumbnailPath = null, $bigPhotoPath = null, $title = null, $caption = null) {
    $i = $this->nextIndex();
    $this->media[$i]['image'] = $photoPath;
    if (isset($thumbnailPath))
      $this->media[$i]['thumb'] = $thumbnailPath;
    if (isset($bigPhotoPath))
      $this->media[$i]['big'] = $bigPhotoPath;
    if (isset($title)) {
      $this->media[$i]['title'] = $title;
    if (isset($caption))
      $this->media[$i]['description'] = $caption;
    }
  }

  public function save () {
    FileFunctions::arrayToJson($this->media, $this->json);
  }

  public function clear () {
    $this->media = [];
    $this->index = -1;
  }


  private function nextIndex() {
    if ($this->index < 0) {
      $this->index = 0;
      return 0;
    } else {
      $this->index++;
      return $this->index;
    }
  }

}
