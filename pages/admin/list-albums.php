  <h1>List of Photo Albums</h1>

  <?php
  $albumCatalog = PhotoAlbumCatalog::getInstance();
  $photoAlbums = $albumCatalog->getAlbums();
  $urlPrefix = HTML_ROOT."/admin";
  ?>

  <table class="table table-hover table-condensed">
    <caption>Current Photo Albums</caption>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Edit Album</th>
        <th>Add Photos</th>
        <th>Edit Photos</th>
      </tr>
    </thead>
    <tbody>
    <?php
    foreach ($photoAlbums as $photoAlbum) {
      echo "<tr><td>{$photoAlbum->getId()}</td>";
      echo "<td>{$photoAlbum->getTitle()}</td>";
      echo "<td><a href=\"$urlPrefix/edit-album/{$photoAlbum->getId()}\">EDIT ALBUM</a></td>";
      echo "<td><a href=\"$urlPrefix/add-photos/{$photoAlbum->getId()}\">ADD PHOTOS</a></td>";
      echo "<td><a href=\"$urlPrefix/edit-photos/{$photoAlbum->getId()}\">EDIT PHOTOS</a></td></tr>";
    }
    ?>
    </tbody>
  </table>
