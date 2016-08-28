  <h1>List Photo Albums</h1>

  <?php
  $albumCatalog = PhotoAlbumCatalog::getInstance();
  $photoAlbums = $albumCatalog->getAlbums();
  ?>

  <table class="table table-hover table-condensed">
    <caption>Current Photo Albums</caption>
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Metadata</th>
        <th>Photos</th>
      </tr>
    </thead>
    <tbody>
    <?php
    foreach ($photoAlbums as $photoAlbum) {
      echo "<tr><td>{$photoAlbum->getId()}</td>";
      echo "<td>{$photoAlbum->getTitle()}</td>";
      echo "<td><a href=\"?action=edit-album&id={$photoAlbum->getId()}\">EDIT ALBUM</a></td>";
      echo "<td><a href=\"?action=edit-photos&id={$photoAlbum->getId()}\">EDIT PHOTOS</a></td></tr>";
    }
    ?>
    </tbody>
  </table>
