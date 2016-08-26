  <h1>Add Photo Album</h1>

  <?php
  $displayForm = true;

  // FORM WAS SUBMITTED
  if (isset($_POST['submitted'])) {

    // validate user input server-side
    try {

      // ensure that user filled out all compulsory fields
      if (empty($_POST['album-date']) || empty($_POST['album-title']) || empty($_POST['album-caption'])) {
        throw new Exception ('You must fill out all fields!');
      }

    } catch (Exception $e) {
      $errorMessage = $e->getMessage();
    }

    
    // VALIDATION IS SUCCESSFUL
    if (!isset($errorMessage)) {
      $displayForm = false;
      $albumCatalog = PhotoAlbumCatalog::getInstance();
      $albumCatalog->addAlbum($_POST['album-date'], $_POST['album-title'], $_POST['album-caption']);

    }

  } // end if isset post submitted

  if (isset($errorMessage)) {
    echo '<div class="alert alert-danger" role="alert">'.$errorMessage.'</div>';
  }

  // DISPLAY FORM
  if ($displayForm) {
  ?>
  
  <form class="form-horizontal js-feedback-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">

    <div class="form-group album-date">
      <label for="album-date" class="control-label col-sm-2">Date</label>
      <div class="col-sm-6">
        <input type="date" required="required" min="1987-01-01" max="<?php echo date('Y-m-d'); ?>" value="<?php echo date('Y-m-d'); ?>" class="form-control" id="album-date" name="album-date" />
      </div>
    </div>

    <div class="form-group album-title">
      <label for="album-title" class="control-label col-sm-2">Title</label>
      <div class="col-sm-6">
        <input type="text" required="required" class="form-control" id="album-title" name="album-title" />
      </div>
    </div>

    <div class="form-group album-caption">
      <label for="album-caption" class="control-label col-sm-2">Caption</label>
      <div class="col-sm-6">
        <textarea required="required" class="form-control" id="album-caption" name="album-caption" rows="6" ></textarea>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-2"></div>
      <button type="submit" class="btn btn-default" name="submitted">Add Album</button>
    </div>

  </form>

  <?php
  } // end if display form