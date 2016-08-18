<?php
class PhotoAlbum implements JsonSerializable {

  private $id;
  private $date;
  private $title;
  private $caption;
  private $frontPhoto;

  public function __construct(array $array) {
    $this->id = $array['id'];
    $this->date = $array['date'];
    $this->title = $array['title'];
    $this->caption = $array['caption'];
    $this->frontPhoto = $array['front-photo'];
  }

  public function getTitle() {
    return $this->title;
  }

  public function getCaption() {
    return $this->caption;
  }

  public function jsonSerialize() {
    $array = [];
    $array['id'] = $this->id;
    $array['date'] = $this->date;
    $array['title'] = $this->title;
    $array['caption'] = $this->caption;
    $array['front-photo'] = $this->frontPhoto;
    
    return $array;
  }
    
    
  }