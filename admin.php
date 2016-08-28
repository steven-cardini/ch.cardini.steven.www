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

<div class="container"> 

<ul class="nav nav-pills"> 
  <li role="presentation" class="active">
    <a href="admin.php">Home</a>
  </li> 
  <li role="presentation" class="dropdown"> 
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Photos <span class="caret"></span> </a> 
    <ul class="dropdown-menu"> 
      <li><a href="?action=list-albums">List Albums</a></li> 
      <li><a href="?action=add-album">Add Album</a></li> 
    </ul>
  </li>
</ul>

<?php
if (isset($_GET['action']) && file_exists(PAGE_DIR."admin/{$_GET['action']}.php")) {
  require_once(PAGE_DIR."admin/{$_GET['action']}.php");
} else {
  echo '<h1>Please choose the action</h1><p>Choose the administration action in the navigation above.</p>';
}
?> 

</div> <!-- end container div -->

<!-- jQuery -->
<script src="resources/vendor/jquery/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="resources/vendor/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>
