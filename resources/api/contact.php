<?php
// include PHP configuration
require_once('../php/config.php');
// include ReCaptcha PHP
require_once(absPath("PHP_EXT") . "autoload.php");
use \ReCaptcha\ReCaptcha;

// Validate user input fields
if (empty($_POST['recaptcha'])) {
  echo I18n::t("form.captcha.required");
  return false;
}

if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
  echo I18n::t("form.fields.required");
  return false;
}

if (!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
  echo I18n::t("form.email.invalid");
  return false;
}

$recaptcha = new ReCaptcha(recaptcha_secret);
$captchaResponse = $recaptcha->verify($_POST['recaptcha'], $_SERVER["REMOTE_ADDR"]);
if (!$captchaResponse->isSuccess()) {
  echo I18n::t("form.captcha.invalid");
  return false;
}

// Securize submitted values
$name = strip_tags(htmlspecialchars($_POST['name']));
$email_address = strip_tags(htmlspecialchars($_POST['email']));
$message = strip_tags(htmlspecialchars($_POST['message']));

// Create the email and send the message
//$MESSAGE_HEADERS = "MIME-Version: 1.0\r\n";
$MESSAGE_HEADERS = "Content-type: text/plain; charset=UTF-8\r\n";
$MESSAGE_HEADERS .= "From: noreply@cardini.ch\r\n";
$MESSAGE_HEADERS .= "Reply-To: $email_address\r\n";
$MESSAGE_TO = contact_email;
$MESSAGE_SUBJECT = "Neue Nachricht über steven.cardini.ch";
$MESSAGE_TEXT = "Nachricht von $name\r\n";
$MESSAGE_TEXT .= "-----------------------------------------------------\r\n";
$MESSAGE_TEXT .= $message;

$done = mail($MESSAGE_TO, $MESSAGE_SUBJECT, $MESSAGE_TEXT, $MESSAGE_HEADERS);

if (!$done) {
  echo I18n::t("form.send.error");
  return false;
} else {
  echo "OK";
  FileFunctions::log("Contact form sent:
                    Name: $name, 
                    EMail: $email_address, 
                    Message: $message");
  return true;   
}

      

