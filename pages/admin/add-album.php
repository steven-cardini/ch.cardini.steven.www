  <h1>Add Photo Album</h1>

  <?php

  if (isset($_POST['submitted'])) {   // FORM WAS SUBMITTED

    // validate user input server-side
    try {

      // ensure that user filled out all compulsory fields
      if (empty($_POST['album-date']) || empty($_POST['album-titles']) || empty($_POST['album-captions'])) {
        throw new Exception ('You must fill out all fields!');
      }

    } catch (Exception $e) {
      $errorMessage = $e->getMessage();
    }
    
    // validation is successful -> add album
    if (!isset($errorMessage)) {
      $albumCatalog = PhotoAlbumCatalog::getInstance();
      $albumCatalog->addAlbum($_POST['album-date'], $_POST['album-titles'], $_POST['album-captions']);
      MessageHandler::printSuccess("The album was created.");
    }

  } // END IF FORM WAS SUBMITTED


  // DISPLAY FORM
  if (!isset($_POST['submitted']) || isset($errorMessage)) {
    if (isset($errorMessage))
      MessageHandler::printError($errorMessage);
  ?>
  
  <form class="form-horizontal js-feedback-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

    <div class="form-group album-date">
      <label for="album-date" class="control-label col-sm-2">Date</label>
      <div class="col-sm-6">
        <input type="date" required="required" min="1987-01-01" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="album-date" name="album-date" />
      </div>
    </div>

    <div class="form-group album-title-en">
      <label for="album-titles[en]" class="control-label col-sm-2">Title (English)</label>
      <div class="col-sm-6">
        <input type="text" required="required" class="form-control" id="album-titles[en]" name="album-titles[en]" />
      </div>
    </div>

    <div class="form-group album-caption-en">
      <label for="album-captions[en]" class="control-label col-sm-2">Caption (English)</label>
      <div class="col-sm-6">
        <textarea required="required" class="form-control" id="album-captions[en]" name="album-captions[en]" rows="6" ></textarea>
      </div>
    </div>

    <div class="form-group album-title-de">
      <label for="album-titles[de]" class="control-label col-sm-2">Title (Deutsch)</label>
      <div class="col-sm-6">
        <input type="text" required="required" class="form-control" id="album-titles[de]" name="album-titles[de]" />
      </div>
    </div>

    <div class="form-group album-caption-de">
      <label for="album-captions[de]" class="control-label col-sm-2">Caption (Deutsch)</label>
      <div class="col-sm-6">
        <textarea required="required" class="form-control" id="album-captions[de]" name="album-captions[de]" rows="6" ></textarea>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-2"></div>
      <button type="submit" class="btn btn-default" name="submitted">Add Album</button>
    </div>

  </form>

  <?php
  } // end if display form