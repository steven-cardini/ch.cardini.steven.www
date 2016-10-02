<?php
// function to automatically load PHP classes
function loadClass ($className) {
  $className = strtolower($className);

  $dirs = [
    absPath("PHP_FUNC"),
  ];

  //try to load class
  foreach ($dirs as $dir) {
    $file = "$dir$className.class.php";
    if (file_exists($file)) {
      require_once($file);
      break;
    }
  }
}
