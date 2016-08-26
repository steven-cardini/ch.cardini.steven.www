  <h1>Edit Photo Album</h1>

  <?php
  $displayForm = true;

  $albumCatalog = PhotoAlbumCatalog::getInstance();
  $photoAlbums = $albumCatalog->getAlbums();

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

  <table class="table table-hover table-condensed">
    <caption>Current Photo Albums</caption>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
      </tr>
    </thead>
    <tbody>
    <?php
    foreach ($photoAlbums as $photoAlbum) {
      echo "<tr><td>{$photoAlbum->getId()}</td><td>{$photoAlbum->getTitle()}</td></tr>";
    }
    ?>
    </tbody>
  </table>



  <?php
  } // end if display form