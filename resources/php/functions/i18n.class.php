<?php

  class I18n {

    private static $initialized = false;
    private static $jsonPath; // initialized in initialize-method below

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
      static::$jsonPath = absPath("JSON") . "lang/";

      $lang = $_GET['lang'] ?? $_COOKIE['lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      if (!static::exists($lang)) $lang = static::defaultLang();
      static::$lang = $lang;
      static::$textArray = FileFunctions::jsonToArray(static::$jsonPath . "lang-$lang.json");
      static::$initialized = true;
    }

    static function exists ($lang) {
      return in_array($lang, static::$acceptedLang);
    }

    static function defaultLang () { // English is default language
      return static::$acceptedLang[0];
    }

    static function getLangSwitchInfo () {
      $newLang = (static::$lang === "en") ? "de" : "en";
      $language = ($newLang === "en") ? "English" : "Deutsch";
      $url = HTML_ROOT . "$newLang";
      return array("lang" => $language, "url" => $url);
    }

  }
