<?php
// include PHP configuration
require_once('../config.php');
// include ReCaptcha PHP
require_once(getAbsDir("PHP_EXT") . "autoload.php");
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
$to = contact_email; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Neue Nachricht über cardini.ch";
$email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nMessage:\n$message";
$headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";   
$done = mail($to,$email_subject,$email_body,$headers);

if (!$done) {
  echo I18n::t("form.send.error");
  return false;
} else {
  echo "OK";
  FileFunctions::log("Successful contact!
                    Name=$name, 
                    EMail=$email_address, 
                    Message=$message");
  return true;   
}

      

