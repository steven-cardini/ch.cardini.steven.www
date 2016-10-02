<?php

class Galleria {

  private static $jsonPath; // initialized upon first construction of object
  
  private $index;
  private $media = [];
  private $jsonId = [];

  public function __construct ($album) {
    if (empty(static::$jsonPath)) {
      static::$jsonPath = array (
        "html" => absPath("JSON", true) . "photo/galleria/",
        "php" => absPath("JSON") . "photo/galleria/"
      );
    }
    $this->jsonId = array (
      "en" => $album->getId() . '-en.json',
      "de" => $album->getId() . '-de.json'
    );
    $this->index = -1;
    $this->loadMediaFromAlbum ($album->getPhotos(), $album->getPhotoFolder(true), $album->getThumbnailFolder(true));
  }

  public function persist () {
    FileFunctions::arrayToJson($this->media["en"], static::$jsonPath['php'].$this->jsonId["en"]);
    FileFunctions::arrayToJson($this->media["de"], static::$jsonPath['php'].$this->jsonId["de"]);
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
