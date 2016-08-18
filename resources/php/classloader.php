<?php
// function to automatically load PHP classes
function loadClass ($className) {
  $className = strtolower($className);

  $dirs = [
    AUTH_DIR,
    FUNCTIONS_DIR,
    CONTROLLER_DIR,
    MODEL_DIR,
    VIEW_DIR
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
