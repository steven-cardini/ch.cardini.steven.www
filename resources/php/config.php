<?php
////////////////////
// PATH CONSTANTS //
////////////////////

// root path - only use with HTML includes, not with PHP require_once!
$root = $_SERVER['REQUEST_URI'];
// remove admin string, if present
$adminPos = strPos($root, '/admin');
if ($adminPos > 0) $root = substr($root, 0, $adminPos);
// remove language string, if present
$langPos = strpos($root, '/de');
if (!$langPos) $langPos = strpos($root, '/en');
if ($langPos > 0) $root = substr($root, 0, $langPos);

define("ROOT_DIR", $root);

// determine prefix to root directory
$subPath = substr($_SERVER['PHP_SELF'], strlen(ROOT_DIR));
$subLevel = substr_count($subPath, "/")-1;
$prefix = "";
for ($i=0; $i<$subLevel; $i++) {
  $prefix .= "../";
}

// subdirectories - important for PHP includes and for HTML includes in combination with ROOT_DIR
define ("RES_DIR", $prefix . "resources/");
define ("PAGE_DIR", $prefix . "pages/");

// RES subfolders
define ("CSS_DIR", RES_DIR."css/");
define ("IMG_DIR", RES_DIR."img/");
define ("JS_DIR", RES_DIR."js/");
define ("JSON_DIR", RES_DIR."json/");
define ("PHP_DIR", RES_DIR."php/");
define ("EXT_DIR", RES_DIR."vendor/");

// PHP subfolders
define ("AUTH_DIR", PHP_DIR."auth/");
define ("FUNCTIONS_DIR", PHP_DIR."functions/");
define ("CONTROLLER_DIR", PHP_DIR."controller/");
define ("MODEL_DIR", PHP_DIR."model/");
define ("VIEW_DIR", PHP_DIR."view/");


//////////////////
// CLASS LOADER //
//////////////////

// initialize the class loader for all pages
// and register the autoloader function
require(PHP_DIR.'classloader.php');
spl_autoload_register('loadClass');
