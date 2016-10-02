<?php
////////////////////
// PATH CONSTANTS //
////////////////////

define ("PHP_ROOT", substr (__DIR__, 0, strPos(__DIR__, "resources")));
define ("HTML_ROOT", "http://" . $_SERVER["HTTP_HOST"] . substr ($_SERVER["PHP_SELF"], 0, strPos($_SERVER["PHP_SELF"], "pages")));

function getAbsDir ($key, $html=false) {
  $root = $html ? HTML_ROOT : PHP_ROOT;
  $sep = $html ? "/" : DIRECTORY_SEPARATOR;
  
  $res = $root . "resources" . $sep;
  $php = $res . "php" . $sep;

  switch ($key) {
    case "RES":
      return $res;
    case "PAGES":
      return $root . "pages" . $sep;
    // RES subfolders
    case "CSS":
      return $res . "css" . $sep;
    case "IMG":
      return $res . "img" . $sep;
    case "JS":
     return $res . "js" . $sep;
    case "JSON":
     return $res . "json" . $sep;
    case "PHP":
      return $php;
    // PHP subfolders
    case "PHP_API":
      return $php . "api" . $sep;
    case "PHP_FUNC":
      return $php . "functions" . $sep;
  }
}


//////////////////
// CLASS LOADER //
//////////////////

// include non-public configuration (constants etc.)
require_once (getAbsDir("PHP").'config.private.php');

// initialize the class loader for all pages
// and register the autoloader function
require (getAbsDir("PHP").'classloader.php');
spl_autoload_register('loadClass');
