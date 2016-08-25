<!DOCTYPE html>
<?php
// include constants
require_once('resources/php/constants.php');
// include classloader and register it to autoloading
require_once('resources/php/classloader.php');
spl_autoload_register('loadClass');
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Steven Cardini - Administration</title>

    <!-- Bootstrap Core CSS -->
    <link href="resources/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="resources/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Theme CSS -->
    <link href="resources/css/style.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

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
  ?>
    
    <!-- jQuery -->
    <script src="resources/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="resources/vendor/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>
