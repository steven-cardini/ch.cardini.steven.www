  <h1>Edit Photos</h1>

  <?php
  
  $albumCatalog = PhotoAlbumCatalog::getInstance();
  $photoAlbums = $albumCatalog->getAlbums();

  if (!isset($_GET['id']))
    exit ("ID of photo album is missing!");

  $album = $albumCatalog->getAlbum($_GET['id']);

  if (isset ($_POST['submitted'])) { // FORM SUBMITTED
      // validate user input server-side
      try {
        // ensure that user filled out all compulsory fields
        if (empty($_POST['album-date']) || empty($_POST['album-title']) || empty($_POST['album-caption'])) {
          throw new Exception ('You must fill out all fields!');
        }

      } catch (Exception $e) {
        $errorMessage = $e->getMessage();
      }

    
      // validation is successful -> update album
      if (!isset($errorMessage)) {
        $displayTable = false;
        $albumCatalog->updateAlbum($album->getId(), $_POST['album-date'], $_POST['album-title'], $_POST['album-caption']);
        MessageHandler::printSuccess("The album was updated.");
      }

   } // END IF FORM SUBMITTED

  $album = $albumCatalog->getAlbum($_GET['id']); // reload album object in order to display new data after form was submitted
  $photos = $album->getPhotos();

   
      if (isset($errorMessage))
        MessageHandler::printError($errorMessage);
      ?>

      <form class="form-horizontal js-feedback-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
        <div class="thumbnail-table">
          <?php
          foreach ($photos as $photo) {
            echo "<div class=\"col-md-3 col-sm-4 col-xs-6\">";
              echo "<img src=\"{$album->getThumbnailFolder()}{$photo->getFileName()}\" />";
              echo "<br /> <label for=\"photos[{$photo->getFileName()}][caption]\" class=\"control-label col-sm-2\">Caption</label>";
              echo "<textarea class=\"form-control\" id=\"photos[{$photo->getFileName()}][caption]\" name=\"photos[{$photo->getFileName()}][caption]\" rows=\"3\">{$photo->getCaption()}</textarea>'";
            echo "</div>";
          }
          ?>
         </div> <!-- end div.thumbnail-table --> 
        
          <button type="submit" class="btn btn-default" name="submitted">Update Photos</button>

      </form>
