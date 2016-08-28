  <h1>Edit Photo Album</h1>

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

   
   if (!isset($_POST['submitted']) || isset($errorMessage)) { // FORM NOT SUCCESSFULLY SUBMITTED
      if (isset($errorMessage))
        MessageHandler::printError($errorMessage);
      ?>
      <form class="form-horizontal js-feedback-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

        <div class="form-group album-id">
          <label for="album-id" class="control-label col-sm-2">ID</label>
          <div class="col-sm-6">
            <?php echo $album->getId(); ?>
          </div>
        </div>
        
        <div class="form-group album-date">
          <label for="album-date" class="control-label col-sm-2">Date</label>
          <div class="col-sm-6">
            <input type="date" required="required" min="1987-01-01" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $album->getAlbumDate(); ?>" class="form-control" id="album-date" name="album-date" />
          </div>
        </div>

        <div class="form-group album-title">
          <label for="album-title" class="control-label col-sm-2">Title</label>
          <div class="col-sm-6">
            <input type="text" required="required" class="form-control" id="album-title" name="album-title" value="<?php echo $album->getTitle(); ?>" />
          </div>
        </div>

        <div class="form-group album-caption">
          <label for="album-caption" class="control-label col-sm-2">Caption</label>
          <div class="col-sm-6">
            <textarea required="required" class="form-control" id="album-caption" name="album-caption" rows="6" ><?php echo $album->getCaption(); ?></textarea>
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-2"></div>
          <button type="submit" class="btn btn-default" name="submitted">Update Album</button>
        </div>

      </form>
    <?php
  } // END IF FORM NOT SUCCESSFULLY SUBMITTED


