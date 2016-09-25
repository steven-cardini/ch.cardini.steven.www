<?php  
  // include PHP configuration
  require_once('resources/php/config.php');

  // initialize the web page language
  I18n::initialize();
  $path = $_SERVER['REQUEST_URI'];
  $lang = I18n::getLang();

  // redirect to I18n URL
  header("location: $path$lang");