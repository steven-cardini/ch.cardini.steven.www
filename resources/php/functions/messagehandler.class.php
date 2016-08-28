<?php

class MessageHandler {

  private static $logFile = "output.log";

  static function printError ($message) {
    echo '<div class="alert alert-danger" role="alert">'.$message.'</div>';
  }

  static function printSuccess ($message) {
    echo '<div class="alert alert-success" role="alert">'.$message.'</div>';
  }

}
