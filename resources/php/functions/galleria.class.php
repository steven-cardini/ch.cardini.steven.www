<?php

class Galleria {

  private static $jsonPath = JSON_DIR . 'photo/galleria/';
  
  private $media;
  private $index;
  private $json;

  public function __construct ($album) {
    $this->json = static::$jsonPath . $album->getId() . '.json';
    $this->index = -1;
    $this->loadMediaFromAlbum ($album->getPhotos(), $album->getPhotoFolder(), $album->getThumbnailFolder());
    
    if (!is_dir(static::$jsonPath)) FileFunctions::createFolder(static::$jsonPath);
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

  public function persist () {
    FileFunctions::arrayToJson($this->media, $this->json);
  }

  public function clear () {
    $this->media = [];
    $this->index = -1;
  }


  private function nextIndex () {
    if ($this->index < 0) {
      $this->index = 0;
      return 0;
    } else {
      $this->index++;
      return $this->index;
    }
  }

  private function loadMediaFromAlbum($photoArray, $photoFolder, $thumbnailFolder) {
    foreach ($photoArray as $photo) {
      $i = $this->nextIndex();
      $this->media[$i]['image'] = $photoFolder . $photo->getFileName();
      $this->media[$i]['thumb'] = $thumbnailFolder . $photo->getFileName();
      // TODO: $this->media[$i]['big'] = ;
      if (!empty($photo->getTitle()))
        $this->media[$i]['title'] = $photo->getTitle();
      if (!empty($photo->getCaption()))
        $this->media[$i]['description'] = $photo->getCaption();
    }
  }

}
