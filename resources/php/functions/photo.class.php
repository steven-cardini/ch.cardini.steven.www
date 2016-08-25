<?php
class Photo implements JsonSerializable {

  private $fileName;
  private $captureDate;
  private $caption;

  public function __construct(array $array) {
    $this->fileName = $array['file-name'];
    $this->captureDate = $array['date-captured'];
    $this->caption = $array['caption'];
  }

  public function getDate() {
    return $this->captureDate;
  }

  public function getCaption() {
    return $this->caption;
  }

  public function jsonSerialize() {
    $array = [];
    $array['file-name'] = $this->fileName;
    $array['date-captured'] = $this->captureDate;
    $array['caption'] = $this->caption;
    
    return $array;
  }

}