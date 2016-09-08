<?php

  class I18n {

    private static $jsonPath = JSON_DIR . 'lang/';

    private static $acceptedLang = array ("en", "de");
    private static $lang;
    private static $textArray = [];

    static function t ($key) {
      return isset(static::$textArray[$key]) ? static::$textArray[$key] : "Missing translation [$key]";
    }

    static function getLang () {
      return static::$lang;
    }

    static function initialize () {
      $lang = $_GET['lang'] ?? $_COOKIE['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      if (!in_array($lang, static::$acceptedLang)) $lang = $acceptedLang[0]; // English is default language
      static::$lang = $lang;
      
      static::$textArray = FileFunctions::jsonToArray(static::$jsonPath . "lang-$lang.json");
      setcookie('lang', $lang); // generate or update language cookie
    }

  }
