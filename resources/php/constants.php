<?php

// PATH CONSTANTS
// constants to access root directory, only use with HTML includes, not with PHP require_once!
// define ("ROOT_DIR", "/");              // PROD
// define ("ROOT_DIR", "/beta/");          // PRE-PROD
define ("ROOT_DIR", "/cardini.ch/steven");    // DEV

// constants to access subdirectories, important for PHP includes and for HTML includes in combination with ROOT_DIR
// differentiate between API call and normal page calls
$res_dir = "resources/";
$page_dir = "pages/";
if (isset($api_call)) { // TODO
  define ("RES_DIR", "../$res_dir");
  define ("PAGE_DIR", "../$page_dir");
} else {
  define ("RES_DIR", "$res_dir");
  define ("PAGE_DIR", "$page_dir");
}

// RES subfolders
define ("IMG_DIR", RES_DIR."img/");
define ("CSS_DIR", RES_DIR."css/");
define ("JS_DIR", RES_DIR."js/");
define ("PHP_DIR", RES_DIR."php/");
define ("EXT_DIR", RES_DIR."vendor/");

// PHP subfolders
define ("AUTH_DIR", PHP_DIR."auth/");
define ("FUNCTIONS_DIR", PHP_DIR."functions/");
define ("CONTROLLER_DIR", PHP_DIR."controller/");
define ("MODEL_DIR", PHP_DIR."model/");
define ("VIEW_DIR", PHP_DIR."view/");


/*
// TODO: is this necessary ??
-------------------------------------
// PAGE_SOURCE in order to prepare content file for mainContent
// default page, loaded upon first site call
if (!isset($_GET['page']) || empty($_GET['page'])) {
  define("PAGE_SOURCE", "pages/home.php");
//page does not exist -> error page
} elseif (!file_exists('pages/'.$_GET['page'].'.php') || $_GET['page']=='error') {
  define("PAGE_SOURCE", "pages/error.php");
// page exists -> define appropriate page file
} else {
  define("PAGE_SOURCE", 'pages/'.$_GET['page'].'.php');
}

*/