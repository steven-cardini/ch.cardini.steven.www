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
        if (empty($_POST['album-date']) || empty($_POST['album-titles']) || empty($_POST['album-captions'])) {
          throw new Exception ('You must fill out all fields!');
        }

        if (!empty($_POST['album-front-photo'])) {
          $frontPhoto = $_POST['album-front-photo'];
        } else {
          $frontPhoto = "";
        }

      } catch (Exception $e) {
        $errorMessage = $e->getMessage();
      }

    
      // validation is successful -> update album
      if (!isset($errorMessage)) {
        $albumCatalog->updateAlbum($album->getId(), $_POST['album-date'], $_POST['album-titles'], $_POST['album-captions'], $frontPhoto);
        MessageHandler::printSuccess("The album was updated.");
      }

   } // END IF FORM SUBMITTED

  $album = $albumCatalog->getAlbum($_GET['id']); // reload album object in order to display new data after form was submitted
  $photos = $album->getPhotos();

   
      if (isset($errorMessage))
        MessageHandler::printError($errorMessage);
      ?>
      <form class="form-horizontal js-feedback-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

        <div class="form-group album-id">
          <label for="album-id" class="control-label col-sm-2">ID</label>
          <div class="col-sm-6">
            <span id="album-id" class="form-control" disabled><?php echo $album->getId(); ?></span>
          </div>
        </div>
        
        <div class="form-group album-date">
          <label for="album-date" class="control-label col-sm-2">Date</label>
          <div class="col-sm-6">
            <input type="date" required="required" min="1987-01-01" max="<?php echo date('Y-m-d'); ?>" value="<?php echo $album->getAlbumDate(); ?>" class="form-control" id="album-date" name="album-date" />
          </div>
        </div>

        <div class="form-group album-title-en">
      <label for="album-titles[en]" class="control-label col-sm-2">Title (English)</label>
      <div class="col-sm-6">
        <input type="text" required="required" class="form-control" id="album-titles[en]" name="album-titles[en]" value="<?php echo $album->getTitle("en"); ?>" />
      </div>
    </div>

    <div class="form-group album-caption-en">
      <label for="album-captions[en]" class="control-label col-sm-2">Caption (English)</label>
      <div class="col-sm-6">
        <textarea required="required" class="form-control" id="album-captions[en]" name="album-captions[en]" rows="6" ><?php echo $album->getCaption("en"); ?></textarea>
      </div>
    </div>

    <div class="form-group album-title-de">
      <label for="album-titles[de]" class="control-label col-sm-2">Title (Deutsch)</label>
      <div class="col-sm-6">
        <input type="text" required="required" class="form-control" id="album-titles[de]" name="album-titles[de]" value="<?php echo $album->getTitle("de"); ?>" />
      </div>
    </div>

    <div class="form-group album-caption-de">
      <label for="album-captions[de]" class="control-label col-sm-2">Caption (Deutsch)</label>
      <div class="col-sm-6">
        <textarea required="required" class="form-control" id="album-captions[de]" name="album-captions[de]" rows="6" ><?php echo $album->getCaption("de"); ?></textarea>
      </div>
    </div>

        <div class="form-group album-front-photo">
          <label for="album-front-photo" class="control-label col-sm-2">Front Photo</label>
          <div class="col-sm-6">
            <select class="form-control image-picker show-html" id="album-front-photo" name="album-front-photo">
              <?php
              echo '<option value=""></option>'; // empty option to avoid default chosing first image
              foreach ($photos as $photo) {
                if (!empty($album->getFrontPhoto()) && $album->getFrontPhoto() === $photo->getFileName()) { // current photo is front photo
                  echo '<option value="'.$photo->getFileName().'" data-img-src="'.$album->getThumbnailFolder().$photo->getFileName().'" selected>'.$photo->getFileName().'</option>';
                } else { // current photo is not front photo
                  echo '<option value="'.$photo->getFileName().'" data-img-src="'.$album->getThumbnailFolder().$photo->getFileName().'">'.$photo->getFileName().'</option>';
                }
              } // end foreach
              ?>
            </select> 
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-2"></div>
          <button type="submit" class="btn btn-default" name="submitted">Update Album</button>
        </div>

      </form>
