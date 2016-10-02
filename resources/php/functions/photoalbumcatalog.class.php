<?php
class PhotoAlbumCatalog {

  private static $instance;
  private static $json; // initialized in getInstance-method below

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
      static::$json = absPath("JSON") . "photo/albums.json";
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

  public function addAlbum($date, $titles, $captions) {
    if (!$this->albumsAreLoaded) $this->loadAlbums(); // load albums if necessary
    $id = $this->generateId();
    $created = date('Y-m-d H:i:s');
    $this->setArrayElement($id, $created, $date, $titles, $captions, "");
  }

  public function updateAlbum($id, $date, $titles, $captions, $frontPhoto="") {
    if (!$this->albumsAreLoaded) $this->loadAlbums(); // load albums if necessary
    $created = $this->albums[$id]->getCreationDate();
    $this->setArrayElement($id, $created, $date, $titles, $captions, $frontPhoto);
  }




  private function initializeJson() {
    FileFunctions::createFile(static::$json, '{}');
  }

  private function generateId() {
    $bytes = random_bytes(4);
    $id = bin2hex($bytes);
    return $id;
  }

  private function setArrayElement($id, $dateCreated, $dateAlbum, $titles, $captions, $frontPhoto) {
    $albumArray = array ("id" => "$id", "date-created" => "$dateCreated" , "date-album" => "$dateAlbum", "titles" => array("en"=>$titles["en"], "de"=>$titles["de"]), "captions" => array("en"=>$captions["en"], "de"=>$captions["de"]), "front-photo" => "$frontPhoto");
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

}