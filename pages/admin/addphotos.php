<?php

if (!isset($_POST['photoDir']) && !isset($_POST['results'])) { // initial GUI -> ask user to select photo folder
  $photoDirectories = PhotoFunctions::getPhotoDirectories();

?>
<p>Fotos zur Seite hinzufügen?</p>
<p>Achtung: die Fotos müssen bereits verkleinert worden sein, um nicht zu viel Speicherplatz aufzubrauchen. Thumbnails werden automatisch generiert. Nicht vergessen, die DB in Prod zu kopieren!</p>
<p>Bitte unten Foto-Ordner wählen:</p>
<form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" id="admin_add_photos_1">
  <select class="form-control" id="photoDir" name="photoDir">
      <?php
        foreach ($photoDirectories as $photoDirectory) {
          ?>
    <option value ="<?php echo $photoDirectory; ?>"><?php echo $photoDirectory; ?></option>
          <?php
        }
          ?>
  </select>

  <input type="submit" class="btn btn-default" value="Weiter" id="submit_button">
</form>

<?php
} else if (!isset($_POST['title'])) { // photo directory was chosen -> display 'unsaved' photos with comment input fields
  $photoDir = $_POST['photoDir'];
  $photos = PhotoFunctions::getPhotos(PHOTO_DIR.$photoDir);
  $photoRepository = new PhotoRepository();
  $results = array();
  $counter = 0;

?>
<p>Bitte den Fotos einen Titel geben!</p>
<form role="form" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="admin_add_photos_2">
  <?php
  foreach ($photos as $photo) {
    // skip already processed photos (check if present in db)
    if ($photoRepository->isAlreadySaved($photo)) {
      continue;
    }
    ?>
  <div class="form-group">
    <img src="<?php echo ROOT_DIR.PHOTO_DIR.$photoDir.'/'.$photo; ?>" style="max-width:300px;max-height:300px;width:auto;height:auto;">
    <input type="hidden" value="<?php echo $photo; ?>" name="photo[<?php echo $counter; ?>]">
    <input type="text" class="form-control" placeholder="Titel" name="title[<?php echo $counter; ?>]">
  </div>
    <?php
    $counter++;
  } // end foreach
  ?>
  <!-- pass on photoDir -->
  <input type="hidden" value="<?php echo $photoDir; ?>" id="photoDir" name="photoDir">
  <input type="submit" class="btn btn-default" value="Speichern" id="submit_button">
</form>
<p>Anzahl ungespeicherte Fotos: <?php echo $counter; ?></p>

<?php
  } else { // save photos to db and as thumbnails
    $photoDir = $_POST['photoDir'];
    $photos = $_POST['photo'];
    $titles = $_POST['title'];
    $totalPhotoCount = count($photos);
    $counter = 0;

    for ($i=0; $i<count($photos); $i++) {
      if (empty($titles[$i])) {
        continue;
      }
      echo '<p>Saving photo '.$photos[$i].' with title: "'.$titles[$i].'"</p>';
      PhotoFunctions::savePhoto ($photoDir.'/', $photos[$i], $titles[$i]);
      $counter++;
    }

    echo '<p>Processed '.$counter.' out of totally '.$totalPhotoCount.' unsaved photos.</p>';
  } //end if-else
?>
