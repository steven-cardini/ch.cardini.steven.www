<?php

  class I18n {

    private static $initialized = false;
    private static $jsonPath = JSON_DIR . 'lang/';

    private static $acceptedLang = array ("en", "de");
    private static $lang;
    private static $textArray = [];

    static function t ($key) {
      if (!static::$initialized) static::initialize();
      return isset(static::$textArray[$key]) ? static::$textArray[$key] : "Missing translation [$key]";
    }

    static function getLang () {
      if (!static::$initialized) static::initialize();
      return static::$lang;
    }

    static function initialize () {
      $lang = $_GET['lang'] ?? $_COOKIE['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      if (!static::exists($lang)) $lang = static::defaultLang();
      static::$lang = $lang;
      
      static::$textArray = FileFunctions::jsonToArray(static::$jsonPath . "lang-$lang.json");
      setcookie('lang', $lang); // generate or update language cookie
      static::$initialized = true;
    }

    static function exists ($lang) {
      return in_array($lang, static::$acceptedLang);
    }

    static function defaultLang () { // English is default language
      return static::$acceptedLang[0];
    }

    static function getNewQueryString ($queryString, $lang) {
      $newQueryString = "";
      parse_str($_SERVER['QUERY_STRING'], $vars);
      $setAmpersand = false;
      foreach ($vars as $key => $value) {
        if ($key === "lang") continue;
        if ($setAmpersand) $newQueryString .= "&";
        $newQueryString .= "$key=$value";
        $setAmpersand = true;
      }
      if ($setAmpersand) $newQueryString .= "&";
      $newQueryString .= "lang=$lang";
      return $newQueryString;
    }

  }
