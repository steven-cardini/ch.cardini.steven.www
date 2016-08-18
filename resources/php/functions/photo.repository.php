<?php

  class PhotoRepository {

    private $dbHandler;
    private $tableName = "photos";
    private $sortAttribute = "capture_date";
    private $getFields = "file";
    private $insertFields = "file, path, capture_date, title";
    private $allFields = "file, path, capture_date, title";

    function __construct () {
      $this->dbHandler = new DBCardiniHandler();
    }

    function saveNewPhoto ($file, $path, $captureDate, $title) {
      $query = "INSERT INTO ".$this->tableName." (".$this->insertFields.") VALUES ('".$file."', '".$path."', '".$captureDate."', '".$title."')";
      $result = $this->dbHandler->save($query);

      // save query to file so it can be introduced to productive db after photo upload
      if ($result) {
        $newFile = !file_exists("queries.log");
        $file = fopen("queries.log", "a");

        if ($newFile) {
          $initialLine = "INSERT INTO `photos` (`file`, `path`, `capture_date`, `title`) VALUES";
          fwrite ($file, $initialLine.PHP_EOL);
        }
        $query = substr($query, 60);
        fwrite ($file, $query.','.PHP_EOL);
        fclose($file);
      }
    }

    function isAlreadySaved ($file) {
      $query = "SELECT ".$this->getFields." FROM ".$this->tableName." WHERE ".$this->getFields."='".$file."'";
      $result = $this->dbHandler->get($query);

      if (!empty($result) && isset($result[0])) {
        return true;
      } else {
        return false;
      }
    }

    function retrieveCategories () {
      $query = "SELECT temp.path AS path, temp.date AS date FROM ( SELECT path, MAX(capture_date) AS date FROM ".$this->tableName." GROUP BY path ) temp ORDER BY temp.date DESC";
      $result = $this->dbHandler->get($query);
      $categories = array();

      foreach ($result as $row) {
        if (empty($row)) {
          continue;
        }
        $category = str_replace('/','',$row['path']);
        $categories[] = $category;
      }

      return $categories;
    }

    function retrievePhotos ($category) {
      $path = $category.'/';
      $query = "SELECT file, title, capture_date FROM ".$this->tableName." WHERE path='".$path."' ORDER BY capture_date ASC";
      $result = $this->dbHandler->get($query);
      $photos = array();

      foreach ($result as $row) {
        if (empty($row)) {
          continue;
        }
        $photos[] = $row;
      }

      return $photos;
    }

  }
?>
