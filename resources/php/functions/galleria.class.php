<?php

class Galleria {

  private static $jsonPath = JSON_DIR . 'photo/galleria/';
  
  private $index;
  private $media = [];
  private $json = [];

  public function __construct ($album) {
    $this->json["en"] = static::$jsonPath . $album->getId() . '-en.json';
    $this->json["de"] = static::$jsonPath . $album->getId() . '-de.json';
    $this->index = -1;
    $this->loadMediaFromAlbum ($album->getPhotos(), $album->getPhotoFolder(), $album->getThumbnailFolder());
    
    if (!is_dir(static::$jsonPath)) FileFunctions::createFolder(static::$jsonPath);
  }

  /* public function addPhoto ($photoPath, $thumbnailPath = null, $bigPhotoPath = null, $title = null, $caption = null) {
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
  } */

  public function persist () {
    FileFunctions::arrayToJson($this->media["en"], $this->json["en"]);
    FileFunctions::arrayToJson($this->media["de"], $this->json["de"]);
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
      $this->media["en"][$i]['image'] = $this->media["de"][$i]['image'] = $photoFolder . $photo->getFileName();
      $this->media["en"][$i]['thumb'] = $this->media["de"][$i]['thumb'] = $thumbnailFolder . $photo->getFileName();
      // TODO: $this->media[$i]['big'] = ;

      // add English titles and captions
      if (!empty($photo->getTitle("en")))
        $this->media["en"][$i]['title'] = $photo->getTitle("en");
      if (!empty($photo->getCaption("en")))
        $this->media["en"][$i]['description'] = $photo->getCaption("en");

      // add German titles and captions
       if (!empty($photo->getTitle("de")))
         $this->media["de"][$i]['title'] = $photo->getTitle("de");
       if (!empty($photo->getCaption("de")))
         $this->media["de"][$i]['description'] = $photo->getCaption("de");
    }
  }

}
