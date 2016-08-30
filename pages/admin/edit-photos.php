  <h1>Edit Photos</h1>

  <?php
  
  $albumCatalog = PhotoAlbumCatalog::getInstance();
  $photoAlbums = $albumCatalog->getAlbums();

  if (!isset($_GET['id']))
    exit ("ID of photo album is missing!");

  $album = $albumCatalog->getAlbum($_GET['id']);
  $photos = $album->getPhotos();

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

   
   if (!isset($_POST['submitted']) || isset($errorMessage)) { // FORM NOT SUCCESSFULLY SUBMITTED
      if (isset($errorMessage))
        MessageHandler::printError($errorMessage);
      ?>

      <form class="form-horizontal js-feedback-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

          <?php 
          $n = 0;
          foreach ($photos as $photo) {  
            if ($n%4 === 0) echo '<div class="row">';
            echo '<div class="col-sm-3">';
              echo "<img src=\"{$album->getThumbnailFolder()}{$photo->getFileName()}\" />";
              echo '<br />';
              echo '<textarea required="required" class="form-control" id="caption-'.$photo->getFileName().'" name="caption-'.$photo->getFileName().'" rows="3">'.$photo->getCaption().'</textarea>';
            echo '</div>'; // end cell
            if ($n%4 === 3) echo '</div><br />'; // end row
            $n++;
          } //end for each loop

          while ($n%4 !== 0) { // fill up last row if necessary
            echo '<div class="col-sm-3">&nbsp;</div>';
            if ($n%4 === 3) echo '</div><br />'; // end row
            $n++;
          }

          ?>
        
          <button type="submit" class="btn btn-default" name="submitted">Update Photos</button>

      </form>
    <?php
  } // END IF FORM NOT SUCCESSFULLY SUBMITTED


