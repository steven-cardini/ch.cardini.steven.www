  <h1>Add Photo Album</h1>

  <?php

  $albumCatalog = PhotoAlbumCatalog::getInstance();
  $photoAlbums = $albumCatalog->getAlbums();

  if (!isset($_GET['id']))
    exit ("ID of photo album is missing!");

  $album = $albumCatalog->getAlbum($_GET['id']);

  if (isset($_POST['submitted'])) {   // FORM WAS SUBMITTED

    // validate user input server-side
    try {

      // ensure that user filled out all compulsory fields
      if (empty($_FILES['photos'])) {
        throw new Exception ('You must select at least one photo!');
      }

    } catch (Exception $e) {
      $errorMessage = $e->getMessage();
    }
    
    // validation is successful -> add photos
    if (!isset($errorMessage)) {
      $total = count($_FILES['photos']['name']);
      $photoPath = IMG_DIR . "albums/{$album->getId()}/";
      $thumbnailPath = $photoPath . "thumbs/";
      
      for ($i=0; $i<$total; $i++) {
                
        $fileName = $_FILES["photos"]["name"][$i];
        $errorCode = $_FILES["photos"]["error"][$i];
        if ($errorCode != UPLOAD_ERR_OK) {
          MessageHandler::printError("Error code $errorCode for file $fileName");
          continue;
        }

        $fileType = $_FILES["photos"]["type"][$i];
        $tmpFilePath = $_FILES['photos']['tmp_name'][$i];
        if ($tmpFilePath == "") {
          continue;
        }
        
        move_uploaded_file($tmpFilePath, $photoPath.$fileName);     
        Photo::createThumbnail ($fileName, $fileType, $photoPath, $thumbnailPath);
        
        $captureDate = "";
        $exif_data = exif_read_data ($photoPath.$fileName);
        if (isset ($exif_data['DateTimeOriginal'])) {
          $dateTimeOriginal = strtotime($exif_data['DateTimeOriginal']);
          $captureDate = date('Y-m-d H:i:s', $dateTimeOriginal);
        }                
        $album->addPhoto($fileName, $captureDate);
      }

      MessageHandler::printSuccess("The photos were added.");
    }

  } // END IF FORM WAS SUBMITTED


  // DISPLAY FORM
  if (!isset($_POST['submitted']) || isset($errorMessage)) {
    if (isset($errorMessage))
      MessageHandler::printError($errorMessage);
  ?>
  
  <form class="form-horizontal js-feedback-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data">

    <div class="form-group album-id">
      <label for="album-id" class="control-label col-sm-2">ID</label>
      <div class="col-sm-6">
        <span id="album-id"><?php echo $album->getId(); ?></span>
      </div>
    </div>

    <div class="form-group album-title">
      <label for="album-title" class="control-label col-sm-2">Title</label>
      <div class="col-sm-6">
        <span id="album-title"><?php echo $album->getTitle(); ?></span>
      </div>
    </div>

    <div class="form-group album-photo">
      <label for="photos" class="control-label col-sm-2">Photos</label>
      <div class="col-sm-6">
        <input id="photos" name="photos[]" class="form-control" type="file" multiple="multiple" required="required" />
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-2"></div>
      <button type="submit" class="btn btn-default" name="submitted">Add Photos</button>
    </div>

  </form>

  <?php
  } // end if display form